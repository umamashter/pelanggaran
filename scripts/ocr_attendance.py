#!/usr/bin/env python3
"""
OCR Attendance Reader — MIS Nurul Ulum
=======================================
Reads a photo of a printed attendance book table and outputs JSON
compatible with AbsensiImportService::matchStudentsWithAi().

Uses OpenCV for grid detection + Tesseract for text recognition.
Strategy: per-cell ink analysis + targeted Tesseract OCR for status detection.

Usage:
    python ocr_attendance.py <image_path> <tesseract_path> [--debug]

Output (stdout): JSON
{
    "success": true,
    "siswa": [
        {
            "no": 1,
            "nisn": "318245974",
            "nama_ocr": "AFIQOTUL ULYA",
            "ketidakhadiran": [
                {"tanggal": 3, "status": "I", "confidence": 0.8}
            ],
            "warnings": []
        }
    ],
    "metadata": {
        "bulan": 7,
        "tahun": 2026,
        "kelas": "4A",
        "date_mapping": {"4": 1, "5": 2, ...},
        "libur_cols": [9, 14, ...],
        "recap_cols": [34, 35, 36],
        "recap_headers": {"34": "A", "35": "I", "36": "S"}
    }
}
"""

import sys
import os
import json
import re
import calendar
from datetime import date

try:
    import cv2
    import numpy as np
except ImportError as e:
    print(json.dumps({"success": False, "error": f"Missing dependency: {e}"}))
    sys.exit(1)

try:
    import pytesseract
except ImportError as e:
    print(json.dumps({"success": False, "error": f"Missing pytesseract: {e}"}))
    sys.exit(1)


DEBUG = False


def debug_log(msg):
    if DEBUG:
        print(f"[DEBUG] {msg}", file=sys.stderr)


MONTH_NAMES_ID = {
    'JANUARI': 1, 'FEBRUARI': 2, 'MARET': 3, 'APRIL': 4,
    'MEI': 5, 'JUNI': 6, 'JULI': 7, 'AGUSTUS': 8,
    'SEPTEMBER': 9, 'OKTOBER': 10, 'NOVEMBER': 11, 'DESEMBER': 12,
}


def configure_tesseract(tesseract_path=None):
    if tesseract_path:
        pytesseract.pytesseract.tesseract_cmd = tesseract_path
    else:
        for p in [
            r"C:\Program Files\Tesseract-OCR\tesseract.exe",
            "/usr/bin/tesseract",
            "/usr/local/bin/tesseract",
        ]:
            if os.path.isfile(p):
                pytesseract.pytesseract.tesseract_cmd = p
                break


def preprocess_image(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    gray = cv2.fastNlMeansDenoising(gray, None, 10, 7, 21)
    gray = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8)).apply(gray)

    blur = cv2.GaussianBlur(gray, (5, 5), 0)
    binary = cv2.adaptiveThreshold(
        blur, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
        cv2.THRESH_BINARY_INV, 21, 7
    )

    kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (3, 3))
    binary = cv2.morphologyEx(binary, cv2.MORPH_CLOSE, kernel, iterations=1)
    binary = cv2.medianBlur(binary, 3)
    return gray, binary


def detect_lines(binary):
    h, w = binary.shape
    h_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (max(w // 15, 40), 1))
    h_lines = cv2.morphologyEx(binary, cv2.MORPH_OPEN, h_kernel)
    v_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, max(h // 15, 40)))
    v_lines = cv2.morphologyEx(binary, cv2.MORPH_OPEN, v_kernel)
    return h_lines, v_lines


def cluster_points(points, axis, threshold=15):
    if not points:
        return []
    sorted_pts = sorted(points, key=lambda p: p[axis])
    clusters = []
    current_cluster = [sorted_pts[0]]
    for pt in sorted_pts[1:]:
        if pt[axis] - current_cluster[-1][axis] <= threshold:
            current_cluster.append(pt)
        else:
            clusters.append(current_cluster)
            current_cluster = [pt]
    clusters.append(current_cluster)
    return [int(sum(p[axis] for p in c) / len(c)) for c in clusters]


def detect_grid_structure(h_lines, v_lines, img_shape):
    h, w = img_shape[:2]
    h_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (max(w // 8, 60), 1))
    h_mask = cv2.morphologyEx(h_lines, cv2.MORPH_OPEN, h_kernel)
    h_proj = np.sum(h_mask, axis=1)
    h_threshold = w * 50
    h_line_positions = []
    in_line = False
    line_start = 0
    for y in range(h):
        if h_proj[y] > h_threshold and not in_line:
            in_line = True
            line_start = y
        elif h_proj[y] <= h_threshold and in_line:
            in_line = False
            h_line_positions.append((line_start + y) // 2)
    if in_line:
        h_line_positions.append((line_start + h) // 2)

    v_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, max(h // 8, 60)))
    v_mask = cv2.morphologyEx(v_lines, cv2.MORPH_OPEN, v_kernel)
    v_proj = np.sum(v_mask, axis=0)
    v_threshold = h * 50
    v_line_positions = []
    in_line = False
    for x in range(w):
        if v_proj[x] > v_threshold and not in_line:
            in_line = True
            line_start = x
        elif v_proj[x] <= v_threshold and in_line:
            in_line = False
            v_line_positions.append((line_start + x) // 2)
    if in_line:
        v_line_positions.append((line_start + w) // 2)

    return h_line_positions, v_line_positions


def extract_cells(img, h_lines_pos, v_lines_pos):
    cells = []
    for i in range(len(h_lines_pos) - 1):
        row = []
        for j in range(len(v_lines_pos) - 1):
            y1 = h_lines_pos[i] + 2
            y2 = h_lines_pos[i + 1] - 2
            x1 = v_lines_pos[j] + 2
            x2 = v_lines_pos[j + 1] - 2
            if y2 > y1 and x2 > x1:
                row.append(img[y1:y2, x1:x2])
            else:
                row.append(None)
        cells.append(row)
    return cells


def ocr_cell_text(cell, config='--psm 7 --oem 3', whitelist=None):
    if cell is None or cell.size == 0:
        return ''
    gray = cv2.cvtColor(cell, cv2.COLOR_BGR2GRAY) if len(cell.shape) == 3 else cell
    scale = max(1, 80 // max(gray.shape[0], 1))
    if scale > 1:
        gray = cv2.resize(gray, None, fx=scale, fy=scale, interpolation=cv2.INTER_CUBIC)
    _, thresh = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)
    inv = cv2.bitwise_not(thresh)
    pad = 15
    padded = cv2.copyMakeBorder(inv, pad, pad, pad, pad, cv2.BORDER_CONSTANT, value=255)
    cfg = config
    if whitelist:
        cfg += f' -c tessedit_char_whitelist={whitelist}'
    try:
        text = pytesseract.image_to_string(padded, config=cfg).strip()
    except Exception:
        text = ''
    return text


def compute_cell_ink(cell):
    if cell is None or cell.size == 0:
        return 0.0
    gray = cv2.cvtColor(cell, cv2.COLOR_BGR2GRAY) if len(cell.shape) == 3 else cell
    _, binary = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
    total = binary.shape[0] * binary.shape[1]
    if total == 0:
        return 0.0
    return cv2.countNonZero(binary) / total


def compute_cell_ink_no_border(cell, border_px=2):
    if cell is None or cell.size == 0:
        return 0.0
    gray = cv2.cvtColor(cell, cv2.COLOR_BGR2GRAY) if len(cell.shape) == 3 else cell
    h, w = gray.shape[:2]
    inner = gray[border_px:h - border_px, border_px:w - border_px]
    if inner.size == 0:
        return 0.0
    _, binary = cv2.threshold(inner, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
    total = binary.shape[0] * binary.shape[1]
    if total == 0:
        return 0.0
    return cv2.countNonZero(binary) / total


def classify_status_cell(cell, is_libur=False):
    if is_libur:
        return 'LIBUR', 1.0

    ink = compute_cell_ink_no_border(cell)

    if ink < 0.005:
        return 'H', 0.95
    if ink < 0.06:
        return 'H', 0.85

    text = ocr_cell_text(cell, config='--psm 10 --oem 3', whitelist='ISAisa.')
    if text:
        ch = text[0].upper()
        if ch == 'I':
            return 'I', 0.7
        elif ch == 'S':
            return 'S', 0.7
        elif ch == 'A':
            return 'A', 0.7

    gray = cv2.cvtColor(cell, cv2.COLOR_BGR2GRAY) if len(cell.shape) == 3 else cell
    _, binary = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
    contours, _ = cv2.findContours(binary, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    real = [c for c in contours if cv2.contourArea(c) >= 5]

    if not real:
        return 'H', 0.5

    all_pts = np.vstack(real)
    bx, by, bw, bh = cv2.boundingRect(all_pts)
    aspect = max(bw, bh) / max(1, min(bw, bh))
    area_ratio = sum(cv2.contourArea(c) for c in real) / (bw * bh) if bw * bh > 0 else 0

    if aspect > 2.5 and bh > bw:
        return 'I', 0.6
    elif area_ratio > 0.4 and aspect < 2.0:
        return 'A', 0.5
    elif ink > 0.3:
        return 'I', 0.4

    return 'UNKNOWN', 0.3


def detect_column_roles(cells, num_cols, v_lines_pos, h_lines_pos, img):
    roles = {}
    col_widths = [0] * num_cols
    count_per_col = [0] * num_cols
    sample_rows = min(5, len(cells))
    for i in range(1, sample_rows):
        for j, cell in enumerate(cells[i]):
            if cell is not None and cell.size > 0:
                col_widths[j] += cell.shape[1]
                count_per_col[j] += 1
    avg_widths = [w / max(count_per_col[i], 1) for i, w in enumerate(col_widths)]

    non_zero = [w for w in avg_widths if w > 0]
    if not non_zero:
        return {j: 'status' for j in range(num_cols)}
    sorted_w = sorted(non_zero)
    median_w = sorted_w[len(sorted_w) // 2]
    status_low = median_w * 0.6
    status_high = median_w * 1.6

    for j in range(num_cols):
        w = avg_widths[j]
        if status_low <= w <= status_high:
            roles[j] = 'status'
        elif w < status_low:
            roles[j] = 'narrow'
        else:
            roles[j] = 'wide'

    best_start = None
    best_len = 0
    run_start = None
    run_len = 0
    for j in range(num_cols):
        if roles.get(j) == 'status':
            if run_start is None:
                run_start = j
            run_len += 1
        else:
            if run_len > best_len:
                best_len = run_len
                best_start = run_start
            run_start = None
            run_len = 0
    if run_len > best_len:
        best_len = run_len
        best_start = run_start

    status_start = best_start if best_start is not None else num_cols

    pre_cols = list(range(status_start))
    if not pre_cols:
        return roles

    nisn_scores = {}
    probe_rows = list(range(2, min(6, len(cells))))
    for j in pre_cols:
        digit_hits = 0
        for i in probe_rows:
            if i >= len(cells) or j >= len(cells[i]):
                continue
            cell = cells[i][j]
            if cell is None or cell.size == 0:
                continue
            text = ocr_cell_text(cell, config='--psm 7 --oem 3', whitelist='0123456789')
            digits = re.sub(r'[^\d]', '', text)
            if len(digits) >= 6:
                digit_hits += 1
        nisn_scores[j] = digit_hits

    if nisn_scores:
        best_nisn = max(nisn_scores, key=nisn_scores.get)
        if nisn_scores[best_nisn] > 0:
            roles[best_nisn] = 'nisn'

    has_probe_nisn = any(roles.get(j) == 'nisn' for j in pre_cols)
    remaining = [j for j in pre_cols if roles.get(j) in ('narrow', 'wide', None)]
    if remaining:
        remaining_sorted = sorted(remaining, key=lambda j: avg_widths[j])
        roles[remaining_sorted[0]] = 'no'
        if len(remaining_sorted) >= 2:
            roles[remaining_sorted[-1]] = 'nama'
        if not has_probe_nisn:
            for j in remaining_sorted[1:-1]:
                roles[j] = 'nisn'

    for j in range(num_cols):
        if j not in roles:
            roles[j] = 'status'

    return roles


def detect_recap_columns(roles, avg_widths, status_cols):
    """Detect recap columns (TIDAK MASUK: A/I/S) by width + position analysis."""
    if not status_cols:
        return [], []

    status_widths = [avg_widths[j] for j in status_cols]
    if not status_widths:
        return [], []
    sorted_sw = sorted(status_widths)
    median_sw = sorted_sw[len(sorted_sw) // 2]

    recap_cols = []
    date_cols = []
    for j in status_cols:
        w = avg_widths[j]
        if w > median_sw * 1.2 and j >= max(status_cols) - 3:
            recap_cols.append(j)
        else:
            date_cols.append(j)

    return date_cols, recap_cols


def extract_title_metadata(cells, h_lines_pos, v_lines_pos, img):
    """Extract month, year, class from title row (row 0)."""
    metadata = {'bulan': None, 'tahun': None, 'kelas': None}

    if not cells or len(cells) < 1:
        return metadata

    title_row = cells[0]
    if not title_row:
        return metadata

    title_img = img[h_lines_pos[0]:h_lines_pos[1], :]
    g = cv2.cvtColor(title_img, cv2.COLOR_BGR2GRAY)
    gs = cv2.resize(g, None, fx=3, fy=3, interpolation=cv2.INTER_CUBIC)
    _, th = cv2.threshold(gs, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)
    inv = cv2.bitwise_not(th)
    pad = 15
    padded = cv2.copyMakeBorder(inv, pad, pad, pad, pad, cv2.BORDER_CONSTANT, value=255)

    try:
        text = pytesseract.image_to_string(padded, config='--psm 6 --oem 3')
    except Exception:
        text = ''

    debug_log(f"Title OCR: {text}")

    text_upper = text.upper()
    for name, num in MONTH_NAMES_ID.items():
        if name in text_upper:
            metadata['bulan'] = num
            break

    year_match = re.search(r'20\d{2}', text)
    if year_match:
        metadata['tahun'] = int(year_match.group())

    kelas_match = re.search(r'KELAS[:\s]*([A-Z0-9]+)', text_upper)
    if kelas_match:
        metadata['kelas'] = kelas_match.group(1)

    debug_log(f"Metadata: {metadata}")
    return metadata


def build_sequential_date_mapping(date_cols):
    """Map date columns sequentially: col 4 -> tanggal 1, col 5 -> tanggal 2, etc."""
    sorted_cols = sorted(date_cols)
    mapping = {}
    for idx, j in enumerate(sorted_cols):
        mapping[j] = idx + 1
    return mapping


def read_header_for_validation(cells, h_lines_pos, v_lines_pos, date_cols, img):
    """Read header row 2 to extract date numbers for validation warnings only.

    Returns list of detected numbers in order, to be compared against sequential mapping.
    """
    if len(cells) < 3:
        return []

    header_row_idx = 2
    if header_row_idx >= len(cells):
        return []

    header_img = img[h_lines_pos[header_row_idx]:h_lines_pos[header_row_idx + 1], :]
    g = cv2.cvtColor(header_img, cv2.COLOR_BGR2GRAY)
    gs = cv2.resize(g, None, fx=4, fy=4, interpolation=cv2.INTER_CUBIC)
    _, th = cv2.threshold(gs, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)
    inv = cv2.bitwise_not(th)
    pad = 15
    padded = cv2.copyMakeBorder(inv, pad, pad, pad, pad, cv2.BORDER_CONSTANT, value=255)

    try:
        data = pytesseract.image_to_data(padded, config='--psm 6 --oem 3', output_type=pytesseract.Output.DICT)
    except Exception:
        return []

    numbers = []
    for i in range(len(data['text'])):
        txt = data['text'][i].strip()
        if not txt:
            continue
        for n in re.findall(r'\d+', txt):
            val = int(n)
            if 1 <= val <= 31:
                numbers.append(val)

    debug_log(f"Header detected numbers: {numbers}")
    return numbers


def compute_libur_dates(bulan, tahun):
    """Compute which dates are Fridays (LIBUR) for the given month/year."""
    if not bulan or not tahun:
        return set()
    libur = set()
    days_in_month = calendar.monthrange(tahun, bulan)[1]
    for d in range(1, days_in_month + 1):
        try:
            dt = date(tahun, bulan, d)
            if dt.weekday() == 4:
                libur.add(d)
        except ValueError:
            continue
    debug_log(f"Computed LIBUR dates (Fridays): {sorted(libur)}")
    return libur


def read_recap_values(cells, recap_cols, v_lines_pos, h_lines_pos, data_start_row, data_end_row):
    """Read TIDAK MASUK recap values (A, I, S counts) from recap columns."""
    recap_data = {}
    if not recap_cols:
        return recap_data

    recap_labels = ['A', 'I', 'S']
    for idx, col_j in enumerate(recap_cols):
        label = recap_labels[idx] if idx < len(recap_labels) else f'RECAP_{idx}'
        col_counts = []
        for i in range(data_start_row, min(data_end_row + 1, len(cells))):
            if col_j >= len(cells[i]):
                continue
            cell = cells[i][col_j]
            if cell is None or cell.size == 0:
                col_counts.append(0)
                continue
            text = ocr_cell_text(cell, config='--psm 7 --oem 3', whitelist='0123456789')
            digits = re.sub(r'[^\d]', '', text)
            try:
                col_counts.append(int(digits) if digits else 0)
            except ValueError:
                col_counts.append(0)
        recap_data[label] = col_counts
        debug_log(f"Recap {label} (col {col_j}): {col_counts}")

    return recap_data


def detect_table_grid_with_fallback(img, gray, binary):
    h_lines, v_lines = detect_lines(binary)
    h_pos, v_pos = detect_grid_structure(h_lines, v_lines, img.shape)
    if len(h_pos) >= 2 and len(v_pos) >= 2:
        return h_pos, v_pos, 'morphology'
    for scale_div in [10, 20, 30]:
        h_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (img.shape[1] // scale_div, 1))
        v_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, img.shape[0] // scale_div))
        h_l = cv2.morphologyEx(binary, cv2.MORPH_OPEN, h_kernel)
        v_l = cv2.morphologyEx(binary, cv2.MORPH_OPEN, v_kernel)
        h_p, v_p = detect_grid_structure(h_l, v_l, img.shape)
        if len(h_p) >= 2 and len(v_p) >= 2:
            return h_p, v_p, f'morphology_scale_{scale_div}'
    edges = cv2.Canny(gray, 50, 150)
    lines = cv2.HoughLinesP(edges, 1, np.pi / 180, 100, minLineLength=img.shape[1] // 10, maxLineGap=10)
    if lines is not None:
        h_lines_list = []
        v_lines_list = []
        for line in lines:
            x1, y1, x2, y2 = line[0]
            angle = abs(np.arctan2(y2 - y1, x2 - x1) * 180 / np.pi)
            if angle < 5:
                h_lines_list.append((y1 + y2) // 2)
            elif angle > 85:
                v_lines_list.append((x1 + x2) // 2)
        h_pos = cluster_points([(0, y) for y in h_lines_list], 1, threshold=20)
        v_pos = cluster_points([(x, 0) for x in v_lines_list], 0, threshold=20)
        if len(h_pos) >= 2 and len(v_pos) >= 2:
            return h_pos, v_pos, 'hough'
    raise ValueError("Tidak dapat mendeteksi struktur tabel pada gambar.")


def parse_status_text(text, expected_days):
    results = []
    chars = list(text.replace(' ', '').replace('\n', ''))
    for i, ch in enumerate(chars):
        if i >= expected_days:
            break
        upper = ch.upper()
        if upper == 'I':
            results.append(('I', 0.7))
        elif upper == 'S':
            results.append(('S', 0.7))
        elif upper == 'A':
            results.append(('A', 0.7))
        elif ch == '.':
            results.append(('H', 0.8))
        else:
            results.append(('H', 0.4))
    return results


def run_full_mode(image_path, tesseract_path=None):
    configure_tesseract(tesseract_path)

    img = cv2.imread(image_path)
    if img is None:
        print(json.dumps({"success": False, "error": f"Cannot read image: {image_path}"}))
        sys.exit(1)

    max_dim = max(img.shape[:2])
    if max_dim > 4000:
        scale = 4000 / max_dim
        img = cv2.resize(img, None, fx=scale, fy=scale, interpolation=cv2.INTER_AREA)

    gray, binary = preprocess_image(img)

    try:
        h_lines_pos, v_lines_pos, grid_method = detect_table_grid_with_fallback(img, gray, binary)
    except ValueError as e:
        print(json.dumps({"success": False, "error": str(e), "fallback": True}))
        sys.exit(1)

    cells = extract_cells(img, h_lines_pos, v_lines_pos)

    if not cells or len(cells) < 2:
        print(json.dumps({"success": False, "error": "Tabel tidak memiliki cukup baris data.", "fallback": True}))
        sys.exit(1)

    num_cols = len(cells[0]) if cells[0] else 0
    roles = detect_column_roles(cells, num_cols, v_lines_pos, h_lines_pos, img)

    nisn_col = next((j for j, r in roles.items() if r == 'nisn'), None)
    nama_col = next((j for j, r in roles.items() if r == 'nama'), None)

    all_status_cols = sorted([j for j, r in roles.items() if r == 'status'])

    avg_widths = [0.0] * num_cols
    count_per_col = [0] * num_cols
    for i in range(1, min(5, len(cells))):
        for j, cell in enumerate(cells[i]):
            if cell is not None and cell.size > 0:
                avg_widths[j] += cell.shape[1]
                count_per_col[j] += 1
    avg_widths = [w / max(count_per_col[i], 1) for i, w in enumerate(avg_widths)]

    date_cols, recap_cols = detect_recap_columns(roles, avg_widths, all_status_cols)

    if not date_cols:
        date_cols = all_status_cols
        recap_cols = []

    metadata = extract_title_metadata(cells, h_lines_pos, v_lines_pos, img)
    libur_dates_computed = compute_libur_dates(metadata['bulan'], metadata['tahun'])
    date_mapping = build_sequential_date_mapping(date_cols)
    libur_cols = set()
    for j in date_cols:
        tanggal = date_mapping.get(j, 0)
        if tanggal in libur_dates_computed:
            libur_cols.add(j)

    data_start_row = 3
    data_end_row = len(cells) - 2
    recap_values = read_recap_values(cells, recap_cols, v_lines_pos, h_lines_pos, data_start_row, data_end_row)

    siswa_list = []
    row_counter = 0
    row_heights = [(i, h_lines_pos[i + 1] - h_lines_pos[i]) for i in range(len(h_lines_pos) - 1)]
    data_heights = [h for _, h in row_heights[1:] if h > 20]
    if data_heights:
        sorted_heights = sorted(data_heights)
        median_h = sorted_heights[len(sorted_heights) // 2]
        min_row_height = max(median_h * 0.85, 35)
        max_row_height = median_h * 2.5
    else:
        min_row_height = 35
        max_row_height = 500

    for i in range(data_start_row, len(cells)):
        row = cells[i]
        if not row:
            continue

        row_h = h_lines_pos[i + 1] - h_lines_pos[i]
        if row_h < min_row_height or row_h > max_row_height:
            continue

        nisn = ''
        nama = ''

        if nisn_col is not None and nisn_col < len(row):
            raw = ocr_cell_text(row[nisn_col], whitelist='0123456789')
            digits = re.sub(r'[^\d]', '', raw)
            if len(digits) >= 6:
                nisn = digits

        if nama_col is not None and nama_col < len(row):
            raw = ocr_cell_text(row[nama_col])
            raw = re.sub(r'[^\w\s]', '', raw).strip()
            raw = re.sub(r'\s+', ' ', raw)
            nama = raw

        if not nisn:
            nama_upper = nama.upper()
            header_keywords = ['NAMA', 'SISWA', 'NO', 'NOMOR', 'NISN', 'KELAS', 'JURUSAN', 'ABSENSI']
            if any(kw in nama_upper for kw in header_keywords):
                continue
            if not nama:
                continue

        ketidakhadiran = []
        warnings = []
        ocr_counts = {'I': 0, 'S': 0, 'A': 0}

        for col_idx in date_cols:
            if col_idx >= len(row):
                continue
            tanggal = date_mapping.get(col_idx, 0)
            if tanggal == 0:
                continue
            is_libur = col_idx in libur_cols
            cell = row[col_idx]
            if is_libur:
                ketidakhadiran.append({"tanggal": tanggal, "status": "LIBUR", "confidence": 1.0})
                continue
            if cell is None or cell.size == 0:
                warnings.append(f"Tanggal {tanggal} sel tidak berhasil diekstrak")
                ketidakhadiran.append({"tanggal": tanggal, "status": "UNKNOWN", "confidence": 0.0})
                continue
            status, conf = classify_status_cell(cell, is_libur=False)
            if status == 'UNKNOWN':
                warnings.append(f"Tanggal {tanggal} status tidak terdeteksi dengan pasti")
                ketidakhadiran.append({"tanggal": tanggal, "status": "UNKNOWN", "confidence": round(conf, 2)})
            elif status in ('I', 'S', 'A'):
                ocr_counts[status] += 1
                ketidakhadiran.append({"tanggal": tanggal, "status": status, "confidence": round(conf, 2)})
            else:
                ketidakhadiran.append({"tanggal": tanggal, "status": status, "confidence": round(conf, 2)})

        if recap_values:
            recap_a = recap_values.get('A', [])
            recap_i = recap_values.get('I', [])
            recap_s = recap_values.get('S', [])
            data_idx = row_counter
            if data_idx < len(recap_a):
                recap_a_val = recap_a[data_idx]
                if recap_a_val > 0 and ocr_counts['A'] != recap_a_val:
                    warnings.append(f"Rekap A={recap_a_val} tapi OCR mendeteksi {ocr_counts['A']}")
            if data_idx < len(recap_i):
                recap_i_val = recap_i[data_idx]
                if recap_i_val > 0 and ocr_counts['I'] != recap_i_val:
                    warnings.append(f"Rekap I={recap_i_val} tapi OCR mendeteksi {ocr_counts['I']}")
            if data_idx < len(recap_s):
                recap_s_val = recap_s[data_idx]
                if recap_s_val > 0 and ocr_counts['S'] != recap_s_val:
                    warnings.append(f"Rekap S={recap_s_val} tapi OCR mendeteksi {ocr_counts['S']}")

        row_counter += 1
        siswa_list.append({
            "no": row_counter,
            "nisn": nisn if nisn else None,
            "nama_ocr": nama,
            "ketidakhadiran": ketidakhadiran,
            "warnings": warnings
        })

    result = {
        "success": True,
        "siswa": siswa_list,
        "meta": {
            "total_rows": len(siswa_list),
            "total_cols": num_cols,
            "grid_rows": len(h_lines_pos),
            "grid_cols": len(v_lines_pos),
            "date_cols": len(date_cols),
            "recap_cols": recap_cols,
            "nisn_col": nisn_col,
            "nama_col": nama_col,
            "grid_method": grid_method,
            "preprocess_steps": ["denoise", "clahe", "adaptive_threshold", "morph_close", "median_blur"],
        },
        "metadata": {
            "bulan": metadata['bulan'],
            "tahun": metadata['tahun'],
            "kelas": metadata['kelas'],
            "date_mapping": {str(k): v for k, v in date_mapping.items()},
            "libur_cols": sorted(libur_cols),
            "libur_dates": sorted(libur_dates_computed),
            "recap_cols": recap_cols,
        }
    }

    print(json.dumps(result, ensure_ascii=False))


def run_cell_mode(image_path, tesseract_path=None, payload=None):
    configure_tesseract(tesseract_path)
    img = cv2.imread(image_path)
    if img is None:
        print(json.dumps({"success": False, "error": "Cannot read image"}))
        sys.exit(1)

    bbox = (payload or {}).get('bbox') or {}
    x = max(0, int(bbox.get('x', 0)))
    y = max(0, int(bbox.get('y', 0)))
    w = max(1, int(bbox.get('width', 1)))
    h = max(1, int(bbox.get('height', 1)))
    crop = img[y:y+h, x:x+w]
    if crop is None or crop.size == 0:
        print(json.dumps({"success": False, "error": "Empty crop"}))
        sys.exit(0)

    raw = ocr_cell_text(crop, config='--psm 10 --oem 3', whitelist='HISA.,/-')
    text = raw.strip() if raw else ''
    normalized = text[:1].upper() if text else None
    conf = 0 if not text else 85

    print(json.dumps({
        "success": True,
        "raw": raw,
        "text": text,
        "normalized": normalized,
        "confidence": conf,
        "bbox": {"x": x, "y": y, "width": w, "height": h},
        "provider": "tesseract",
        "warnings": [] if text else ["unreadable_cell"]
    }, ensure_ascii=False))


def run_batch_mode(image_path, tesseract_path=None, payload=None):
    configure_tesseract(tesseract_path)
    img = cv2.imread(image_path)
    if img is None:
        print(json.dumps({"success": False, "error": "Cannot read image"}))
        sys.exit(1)

    queue = (payload or {}).get('queue') or []
    cells = []
    success = 0
    failed = 0
    retry_count = 0
    confidences = []
    start = cv2.getTickCount()

    for item in queue:
        coordinate = item.get('coordinate') or {}
        bbox = {
            'x': max(0, int(coordinate.get('x', 0))),
            'y': max(0, int(coordinate.get('y', 0))),
            'width': max(1, int(coordinate.get('width', 1))),
            'height': max(1, int(coordinate.get('height', 1))),
        }
        crop = img[bbox['y']:bbox['y']+bbox['height'], bbox['x']:bbox['x']+bbox['width']]
        duration_start = cv2.getTickCount()
        if crop is None or crop.size == 0:
            cells.append({
                'cell_id': item.get('cell_id'),
                'logical_type': item.get('logical_type', 'unknown'),
                'raw': None,
                'text': None,
                'normalized': None,
                'confidence': 0,
                'bbox': bbox,
                'provider': 'tesseract',
                'warnings': ['unreadable_cell'],
                'geometry_source': 'fallback',
                'duration_ms': 0,
                'retried': False,
            })
            failed += 1
            continue

        raw = ocr_cell_text(crop, config='--psm 10 --oem 3', whitelist='HISA.,/-')
        text = raw.strip() if raw else ''
        conf = 0 if not text else 85
        retried = False
        if conf < 25:
            retried = True
            retry_count += 1
            raw_retry = ocr_cell_text(crop, config='--psm 10 --oem 3', whitelist='HISA.,/-')
            text_retry = raw_retry.strip() if raw_retry else ''
            conf_retry = 0 if not text_retry else 85
            if conf_retry > conf:
                raw = raw_retry
                text = text_retry
                conf = conf_retry

        duration_ms = round((cv2.getTickCount() - duration_start) / cv2.getTickFrequency() * 1000)
        normalized = text[:1].upper() if text else None
        cells.append({
            'cell_id': item.get('cell_id'),
            'logical_type': item.get('logical_type', 'unknown'),
            'raw': raw,
            'text': text,
            'normalized': normalized,
            'confidence': conf,
            'bbox': bbox,
            'provider': 'tesseract',
            'warnings': [] if text else ['unreadable_cell'],
            'geometry_source': 'ocr' if text else 'fallback',
            'duration_ms': duration_ms,
            'retried': retried,
        })
        if text:
            success += 1
            confidences.append(conf)
        else:
            failed += 1

    total_duration = round((cv2.getTickCount() - start) / cv2.getTickFrequency() * 1000)
    average_confidence = round(sum(confidences) / len(confidences), 2) if confidences else 0

    print(json.dumps({
        'success': True,
        'provider': 'local',
        'cells_processed': len(queue),
        'cells_success': success,
        'cells_failed': failed,
        'average_confidence': average_confidence,
        'duration_ms': total_duration,
        'fallback_used': failed > 0,
        'retry_count': retry_count,
        'cells': cells,
    }, ensure_ascii=False))


def main():
    global DEBUG

    if len(sys.argv) < 2:
        print(json.dumps({"success": False, "error": "Usage: python ocr_attendance.py <image_path> [tesseract_path] [--debug] [--mode full|cell|batch] [--payload json] [--payload-file file]"}))
        sys.exit(1)

    image_path = sys.argv[1]
    tesseract_path = None
    mode = 'full'
    payload = None
    payload_file = None
    i = 2
    while i < len(sys.argv):
        arg = sys.argv[i]
        if arg == '--debug':
            DEBUG = True
        elif arg == '--mode' and i + 1 < len(sys.argv):
            mode = sys.argv[i + 1]
            i += 1
        elif arg == '--payload' and i + 1 < len(sys.argv):
            try:
                payload = json.loads(sys.argv[i + 1])
            except Exception:
                payload = None
            i += 1
        elif arg == '--payload-file' and i + 1 < len(sys.argv):
            payload_file = sys.argv[i + 1]
            i += 1
        elif not tesseract_path:
            tesseract_path = arg
        i += 1

    if not os.path.isfile(image_path):
        print(json.dumps({"success": False, "error": f"Image file not found: {image_path}"}))
        sys.exit(1)

    if payload_file:
        try:
            with open(payload_file, 'r', encoding='utf-8') as f:
                payload = json.load(f)
        except Exception as e:
            print(json.dumps({"success": False, "error": f"Invalid payload file: {e}"}))
            sys.exit(0)

    if mode == 'cell':
        run_cell_mode(image_path, tesseract_path, payload)
    elif mode == 'batch':
        run_batch_mode(image_path, tesseract_path, payload)
    else:
        run_full_mode(image_path, tesseract_path)


if __name__ == "__main__":
    main()
