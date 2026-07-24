#!/usr/bin/env python3
"""
OCR Attendance Reader — MIS Nurul Ulum
=======================================
Reads a photo of a printed attendance book table and outputs JSON
with student attendance data per date.

Usage:
    python ocr_attendance.py <image_path> <tesseract_path>

Output (stdout): JSON
{
    "success": true/false,
    "error": "...",
    "students": [
        {
            "row_index": 0,
            "statuses": { "1": "H", "2": "?", "3": "I", ... }
        }
    ],
    "dates": [1, 2, 3, ...],
    "total_rows": 25,
    "total_cols": 31
}

Mapping:
    "." -> H (Hadir)
    "I", "i", "l", "1" -> I (Izin)
    "S", "s" -> S (Sakit)
    "A", "a" -> A (Alpha)
    empty -> ? (Perlu Diperiksa)
    unknown -> ? (Perlu Diperiksa)
"""

import sys
import os
import json
import re

try:
    import cv2
    import numpy as np
    from PIL import Image
except ImportError as e:
    print(json.dumps({
        "success": False,
        "error": f"Missing Python dependency: {e}. Install with: pip install opencv-python-headless Pillow numpy"
    }))
    sys.exit(1)

try:
    import pytesseract
except ImportError as e:
    print(json.dumps({
        "success": False,
        "error": f"Missing pytesseract: {e}. Install with: pip install pytesseract"
    }))
    sys.exit(1)


def configure_tesseract(tesseract_path=None):
    """Set tesseract executable path."""
    if tesseract_path:
        pytesseract.pytesseract.tesseract_cmd = tesseract_path
    else:
        # Try common paths
        for p in [
            r"C:\Program Files\Tesseract-OCR\tesseract.exe",
            "/usr/bin/tesseract",
            "/usr/local/bin/tesseract",
        ]:
            if os.path.isfile(p):
                pytesseract.pytesseract.tesseract_cmd = p
                break


def preprocess_image(img):
    """Convert to grayscale and apply adaptive threshold."""
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    # Adaptive threshold for varying lighting
    binary = cv2.adaptiveThreshold(
        gray, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
        cv2.THRESH_BINARY_INV, 15, 5
    )
    return gray, binary


def detect_lines(binary):
    """Detect horizontal and vertical lines to find grid structure."""
    h, w = binary.shape

    # Detect horizontal lines
    h_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (max(w // 15, 40), 1))
    h_lines = cv2.morphologyEx(binary, cv2.MORPH_OPEN, h_kernel)

    # Detect vertical lines
    v_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, max(h // 15, 40)))
    v_lines = cv2.morphologyEx(binary, cv2.MORPH_OPEN, v_kernel)

    return h_lines, v_lines


def find_grid_intersections(h_lines, v_lines):
    """Find grid intersection points."""
    h, w = h_lines.shape
    intersections = cv2.bitwise_and(h_lines, v_lines)

    # Dilate to connect nearby points
    kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (5, 5))
    intersections = cv2.dilate(intersections, kernel, iterations=2)

    # Find contours of intersection regions
    contours, _ = cv2.findContours(intersections, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    points = []
    for c in contours:
        M = cv2.moments(c)
        if M["m00"] > 0:
            cx = int(M["m10"] / M["m00"])
            cy = int(M["m01"] / M["m00"])
            points.append((cx, cy))

    # Sort: first by row (y), then by column (x)
    points.sort(key=lambda p: (p[1], p[0]))
    return points


def cluster_points(points, axis, threshold=15):
    """Cluster points along an axis by proximity."""
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

    # Return average position of each cluster
    result = []
    for cluster in clusters:
        avg_pos = sum(p[axis] for p in cluster) / len(cluster)
        result.append(int(avg_pos))
    return result


def detect_grid_structure(h_lines, v_lines, img_shape):
    """Detect grid rows and columns from line images."""
    h, w = img_shape[:2]

    # Find horizontal line positions (y coordinates)
    h_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (max(w // 8, 60), 1))
    h_mask = cv2.morphologyEx(h_lines, cv2.MORPH_OPEN, h_kernel)

    # Project horizontally to find line y-positions
    h_proj = np.sum(h_mask, axis=1)
    h_threshold = w * 50  # minimum pixel intensity to count as a line
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

    # Find vertical line positions (x coordinates)
    v_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, max(h // 8, 60)))
    v_mask = cv2.morphologyEx(v_lines, cv2.MORPH_OPEN, v_kernel)

    # Project vertically to find line x-positions
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
    """Extract individual cell images from the grid."""
    cells = []
    for i in range(len(h_lines_pos) - 1):
        row = []
        for j in range(len(v_lines_pos) - 1):
            y1 = h_lines_pos[i] + 2
            y2 = h_lines_pos[i + 1] - 2
            x1 = v_lines_pos[j] + 2
            x2 = v_lines_pos[j + 1] - 2

            if y2 > y1 and x2 > x1:
                cell_img = img[y1:y2, x1:x2]
                row.append(cell_img)
            else:
                row.append(None)
        cells.append(row)
    return cells


def is_cell_empty(cell_img, threshold=0.03):
    """Check if a cell image is effectively empty (less than threshold% ink pixels)."""
    if cell_img is None or cell_img.size == 0:
        return True

    gray = cv2.cvtColor(cell_img, cv2.COLOR_BGR2GRAY) if len(cell_img.shape) == 3 else cell_img
    _, binary = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)

    total_pixels = binary.shape[0] * binary.shape[1]
    ink_pixels = cv2.countNonZero(binary)
    ratio = ink_pixels / total_pixels if total_pixels > 0 else 0

    return ratio < threshold


def read_cell_symbol(cell_img, tesseract_path=None):
    """
    Read the symbol in a single cell image.
    Returns one of: 'H', 'I', 'S', 'A', '?'
    """
    if cell_img is None or cell_img.size == 0:
        return '?'

    gray = cv2.cvtColor(cell_img, cv2.COLOR_BGR2GRAY) if len(cell_img.shape) == 3 else cell_img

    # Check if cell is empty
    _, binary_check = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
    total_pixels = binary_check.shape[0] * binary_check.shape[1]
    ink_pixels = cv2.countNonZero(binary_check)
    ink_ratio = ink_pixels / total_pixels if total_pixels > 0 else 0

    if ink_ratio < 0.02:
        return '?'

    # Preprocess for OCR
    # Resize for better OCR accuracy
    scale = max(1, 60 // max(cell_img.shape[0], 1))
    resized = cv2.resize(cell_img, None, fx=scale, fy=scale, interpolation=cv2.INTER_CUBIC)

    # Convert to grayscale if needed
    if len(resized.shape) == 3:
        gray_resized = cv2.cvtColor(resized, cv2.COLOR_BGR2GRAY)
    else:
        gray_resized = resized

    # Sharpen
    kernel = np.array([[-1, -1, -1], [-1, 9, -1], [-1, -1, -1]])
    sharpened = cv2.filter2D(gray_resized, -1, kernel)

    # Adaptive threshold
    thresh = cv2.adaptiveThreshold(
        sharpened, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
        cv2.THRESH_BINARY, 11, 2
    )

    # Invert for Tesseract (dark text on light bg)
    # Tesseract expects dark text on light bg
    # Our binary is white-on-black (ink=white), so invert
    final = cv2.bitwise_not(thresh)

    # Add padding
    pad = 20
    padded = cv2.copyMakeBorder(
        final, pad, pad, pad, pad,
        cv2.BORDER_CONSTANT, value=255
    )

    # OCR with Tesseract — single character mode
    try:
        custom_config = r'--psm 10 --oem 3 -c tessedit_char_whitelist=.ISAisa1l'
        text = pytesseract.image_to_string(padded, config=custom_config).strip()
    except Exception:
        return '?'

    # Map result
    if not text:
        return '?'

    # Take first character
    char = text[0]

    # Mapping
    if char == '.':
        return 'H'
    elif char in ('I', 'i', 'l', '1'):
        return 'I'
    elif char in ('S', 's'):
        return 'S'
    elif char in ('A', 'a'):
        return 'A'
    else:
        return '?'


def analyze_cell_with_fallback(cell_img, tesseract_path=None):
    """
    Analyze a cell using multiple methods:
    1. Check if empty -> '?'
    2. OCR single char
    3. If confidence low, return '?'
    """
    if cell_img is None or cell_img.size == 0:
        return '?'

    return read_cell_symbol(cell_img, tesseract_path)


def detect_table_grid_with_fallback(img, gray, binary):
    """
    Try multiple grid detection approaches.
    Returns (h_lines_pos, v_lines_pos) or raises if no grid found.
    """
    # Approach 1: Line detection with morphological operations
    h_lines, v_lines = detect_lines(binary)
    h_pos, v_pos = detect_grid_structure(h_lines, v_lines, img.shape)

    # Need at least 2 horizontal and 2 vertical lines for a table
    if len(h_pos) >= 2 and len(v_pos) >= 2:
        return h_pos, v_pos

    # Approach 2: Try different kernel sizes
    for scale_div in [10, 20, 30]:
        h_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (img.shape[1] // scale_div, 1))
        v_kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (1, img.shape[0] // scale_div))

        h_l = cv2.morphologyEx(binary, cv2.MORPH_OPEN, h_kernel)
        v_l = cv2.morphologyEx(binary, cv2.MORPH_OPEN, v_kernel)

        h_p, v_p = detect_grid_structure(h_l, v_l, img.shape)
        if len(h_p) >= 2 and len(v_p) >= 2:
            return h_p, v_p

    # Approach 3: Use Hough lines
    edges = cv2.Canny(gray, 50, 150)
    lines = cv2.HoughLinesP(edges, 1, np.pi / 180, 100, minLineLength=img.shape[1] // 10, maxLineGap=10)

    if lines is not None:
        h_lines_list = []
        v_lines_list = []
        for line in lines:
            x1, y1, x2, y2 = line[0]
            angle = abs(np.arctan2(y2 - y1, x2 - x1) * 180 / np.pi)
            if angle < 5:  # Horizontal
                h_lines_list.append((y1 + y2) // 2)
            elif angle > 85:  # Vertical
                v_lines_list.append((x1 + x2) // 2)

        h_pos = cluster_points([(0, y) for y in h_lines_list], 1, threshold=20)
        v_pos = cluster_points([(x, 0) for x in v_lines_list], 0, threshold=20)

        if len(h_pos) >= 2 and len(v_pos) >= 2:
            return h_pos, v_pos

    raise ValueError("Tidak dapat mendeteksi struktur tabel pada gambar.")


def main():
    if len(sys.argv) < 2:
        print(json.dumps({
            "success": False,
            "error": "Usage: python ocr_attendance.py <image_path> [tesseract_path]"
        }))
        sys.exit(1)

    image_path = sys.argv[1]
    tesseract_path = sys.argv[2] if len(sys.argv) > 2 and sys.argv[2] else None

    if not os.path.isfile(image_path):
        print(json.dumps({
            "success": False,
            "error": f"Image file not found: {image_path}"
        }))
        sys.exit(1)

    configure_tesseract(tesseract_path)

    # Load image
    img = cv2.imread(image_path)
    if img is None:
        print(json.dumps({
            "success": False,
            "error": f"Cannot read image: {image_path}"
        }))
        sys.exit(1)

    # Resize if too large (max 4000px on longest side)
    max_dim = max(img.shape[:2])
    if max_dim > 4000:
        scale = 4000 / max_dim
        img = cv2.resize(img, None, fx=scale, fy=scale, interpolation=cv2.INTER_AREA)

    gray, binary = preprocess_image(img)

    # Detect grid
    try:
        h_lines_pos, v_lines_pos = detect_table_grid_with_fallback(img, gray, binary)
    except ValueError as e:
        print(json.dumps({
            "success": False,
            "error": str(e),
            "fallback": True
        }))
        sys.exit(1)

    # Extract cells
    cells = extract_cells(img, h_lines_pos, v_lines_pos)

    if not cells or len(cells) < 2:
        print(json.dumps({
            "success": False,
            "error": "Tabel tidak memiliki cukup baris data.",
            "fallback": True
        }))
        sys.exit(1)

    # Parse attendance data
    students = []
    dates = []

    for row_idx, row in enumerate(cells):
        if not row:
            continue

        student_data = {
            "row_index": row_idx,
            "statuses": {}
        }

        for col_idx, cell in enumerate(row):
            # Skip first column (usually row number or name)
            if col_idx == 0:
                continue

            day_num = col_idx  # column 1 = day 1, column 2 = day 2, etc.
            symbol = analyze_cell_with_fallback(cell, tesseract_path)
            student_data["statuses"][str(day_num)] = symbol

            if day_num not in dates and col_idx > 0:
                dates.append(day_num)

        students.append(student_data)

    dates.sort()

    # Validate structure
    expected_max_dates = 31
    if len(dates) > expected_max_dates + 5:
        print(json.dumps({
            "success": False,
            "error": "Jumlah kolom tanggal tidak wajar. Pastikan foto menampilkan satu bulan absensi.",
            "fallback": True,
            "detected_cols": len(dates),
            "detected_rows": len(students)
        }))
        sys.exit(1)

    result = {
        "success": True,
        "students": students,
        "dates": dates,
        "total_rows": len(students),
        "total_cols": len(dates),
        "grid_rows": len(h_lines_pos),
        "grid_cols": len(v_lines_pos)
    }

    print(json.dumps(result))


if __name__ == "__main__":
    main()
