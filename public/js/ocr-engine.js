/**
 * OCR Engine — MIS Nurul Ulum
 * Client-side OCR for attendance book photos using Tesseract.js
 * Runs entirely in the browser. No server-side processing needed.
 */
window.OCREngine = (function () {
    var worker = null;
    var initialized = false;

    function loadScript(src) {
        return new Promise(function (resolve, reject) {
            if (document.querySelector('script[src="' + src + '"]')) {
                resolve();
                return;
            }
            var s = document.createElement('script');
            s.src = src;
            s.onload = resolve;
            s.onerror = function () { reject(new Error('Gagal memuat ' + src)); };
            document.head.appendChild(s);
        });
    }

    async function init(progressCb) {
        if (initialized) return;
        if (progressCb) progressCb(5, 'Memuat Tesseract.js...');

        await loadScript('https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js');

        if (progressCb) progressCb(15, 'Memuat OCR engine...');
        worker = await Tesseract.createWorker('eng', 1, {
            logger: function (m) {
                if (progressCb && m.status === 'recognizing text') {
                    var pct = 15 + Math.round((m.progress || 0) * 35);
                    progressCb(pct, 'Membaca teks dari gambar...');
                }
            }
        });

        await worker.setParameters({
            tessedit_char_whitelist: '.HISAhibaBlsSsaAai1l?'
        });

        initialized = true;
        if (progressCb) progressCb(50, 'OCR engine siap.');
    }

    /* ====================== IMAGE PREPROCESSING ====================== */

    function preprocessImage(imgSrc) {
        return new Promise(function (resolve) {
            var img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = function () {
                var canvas = document.createElement('canvas');
                var maxDim = 3000;
                var w = img.naturalWidth;
                var h = img.naturalHeight;
                if (Math.max(w, h) > maxDim) {
                    var scale = maxDim / Math.max(w, h);
                    w = Math.round(w * scale);
                    h = Math.round(h * scale);
                }
                canvas.width = w;
                canvas.height = h;
                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, w, h);

                var imageData = ctx.getImageData(0, 0, w, h);
                var data = imageData.data;

                // Grayscale
                for (var i = 0; i < data.length; i += 4) {
                    var gray = 0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2];
                    data[i] = data[i + 1] = data[i + 2] = gray;
                }

                // Adaptive-like threshold using local mean
                var threshold = 140;
                var localSize = 15;
                for (var y = 0; y < h; y++) {
                    for (var x = 0; x < w; x++) {
                        var idx = (y * w + x) * 4;
                        var sum = 0, count = 0;
                        for (var dy = -localSize; dy <= localSize; dy++) {
                            for (var dx = -localSize; dx <= localSize; dx++) {
                                var ny = y + dy, nx = x + dx;
                                if (ny >= 0 && ny < h && nx >= 0 && nx < w) {
                                    sum += data[(ny * w + nx) * 4];
                                    count++;
                                }
                            }
                        }
                        var localMean = sum / count;
                        var val = data[idx] < (localMean - 10) ? 0 : 255;
                        data[idx] = data[idx + 1] = data[idx + 2] = val;
                    }
                }

                ctx.putImageData(imageData, 0, 0);
                resolve(canvas);
            };
            img.src = imgSrc;
        });
    }

    /* ====================== GRID DETECTION ====================== */

    function getPixelGrid(canvas) {
        var ctx = canvas.getContext('2d');
        var w = canvas.width, h = canvas.height;
        var data = ctx.getImageData(0, 0, w, h).data;
        var grid = [];
        for (var y = 0; y < h; y++) {
            var row = [];
            for (var x = 0; x < w; x++) {
                row.push(data[(y * w + x) * 4] < 128 ? 1 : 0);
            }
            grid.push(row);
        }
        return grid;
    }

    function detectGridLines(grid, imgW, imgH) {
        var minLineRatio = 0.15;

        // Horizontal projection
        var hProj = [];
        for (var y = 0; y < imgH; y++) {
            var sum = 0;
            for (var x = 0; x < imgW; x++) sum += grid[y][x];
            hProj.push(sum);
        }
        var hThreshold = imgW * minLineRatio;
        var hPositions = findLinePositions(hProj, imgH, hThreshold);

        // Vertical projection
        var vProj = [];
        for (var x = 0; x < imgW; x++) {
            var sum = 0;
            for (var y = 0; y < imgH; y++) sum += grid[y][x];
            vProj.push(sum);
        }
        var vThreshold = imgH * minLineRatio;
        var vPositions = findLinePositions(vProj, imgW, vThreshold);

        return { h: hPositions, v: vPositions };
    }

    function findLinePositions(proj, length, threshold) {
        var positions = [];
        var inLine = false, start = 0;
        for (var i = 0; i < length; i++) {
            if (proj[i] > threshold && !inLine) {
                inLine = true;
                start = i;
            } else if (proj[i] <= threshold && inLine) {
                inLine = false;
                positions.push(Math.round((start + i) / 2));
            }
        }
        if (inLine) positions.push(Math.round((start + length) / 2));
        return clusterPositions(positions, 20);
    }

    function clusterPositions(positions, threshold) {
        if (!positions.length) return [];
        var sorted = positions.slice().sort(function (a, b) { return a - b; });
        var clusters = [[sorted[0]]];
        for (var i = 1; i < sorted.length; i++) {
            var lastCluster = clusters[clusters.length - 1];
            if (sorted[i] - lastCluster[lastCluster.length - 1] <= threshold) {
                lastCluster.push(sorted[i]);
            } else {
                clusters.push([sorted[i]]);
            }
        }
        return clusters.map(function (c) {
            return Math.round(c.reduce(function (s, v) { return s + v; }, 0) / c.length);
        });
    }

    function extractCell(grid, y1, y2, x1, x2) {
        var rows = [];
        for (var y = y1; y < y2 && y < grid.length; y++) {
            var row = [];
            for (var x = x1; x < x2 && x < grid[0].length; x++) {
                row.push(grid[y][x]);
            }
            rows.push(row);
        }
        return rows;
    }

    function cellIsEmpty(cellGrid) {
        if (!cellGrid.length) return true;
        var total = 0, ink = 0;
        for (var y = 0; y < cellGrid.length; y++) {
            for (var x = 0; x < cellGrid[y].length; x++) {
                total++;
                if (cellGrid[y][x]) ink++;
            }
        }
        return total === 0 || (ink / total) < 0.02;
    }

    /* ====================== CELL IMAGE FOR OCR ====================== */

    function extractCellImage(canvas, y1, y2, x1, x2) {
        var cw = x2 - x1, ch = y2 - y1;
        if (cw <= 0 || ch <= 0) return null;
        var cellCanvas = document.createElement('canvas');
        cellCanvas.width = cw;
        cellCanvas.height = ch;
        var ctx = cellCanvas.getContext('2d');
        ctx.drawImage(canvas, x1, y1, cw, ch, 0, 0, cw, ch);

        // Scale up for better OCR
        var scale = Math.max(1, Math.ceil(60 / Math.max(cw, ch)));
        if (scale > 1) {
            var big = document.createElement('canvas');
            big.width = cw * scale;
            big.height = ch * scale;
            var bCtx = big.getContext('2d');
            bCtx.imageSmoothingEnabled = false;
            bCtx.drawImage(cellCanvas, 0, 0, cw * scale, ch * scale);
            cellCanvas = big;
        }

        // Sharpen: invert for Tesseract (dark text on white bg)
        var ctx2 = cellCanvas.getContext('2d');
        var imgData = ctx2.getImageData(0, 0, cellCanvas.width, cellCanvas.height);
        var d = imgData.data;
        for (var i = 0; i < d.length; i += 4) {
            var v = d[i] > 128 ? 255 : 0;
            d[i] = d[i + 1] = d[i + 2] = v;
        }
        ctx2.putImageData(imgData, 0, 0);

        return cellCanvas;
    }

    /* ====================== SYMBOL MAPPING ====================== */

    function mapSymbol(text) {
        if (!text) return '?';
        var t = text.trim();
        if (!t) return '?';
        var c = t[0];
        if (c === '.') return 'H';
        if (c === 'H' || c === 'h') return 'H';
        if (c === 'I' || c === 'i' || c === 'l' || c === '1') return 'I';
        if (c === 'S' || c === 's') return 'S';
        if (c === 'A' || c === 'a') return 'A';
        if (c === 'B' || c === 'b') return 'I'; // "Ijin" sometimes OCR'd as B
        return '?';
    }

    function inkDensity(cellGrid) {
        var total = 0, ink = 0;
        for (var y = 0; y < cellGrid.length; y++) {
            for (var x = 0; x < cellGrid[y].length; x++) {
                total++;
                if (cellGrid[y][x]) ink++;
            }
        }
        return total > 0 ? ink / total : 0;
    }

    /* ====================== MAIN PROCESS ====================== */

    /**
     * Process an attendance book photo.
     * @param {string} imageSrc - data URL or URL of the image
     * @param {function} progressCb - callback(percent, message)
     * @returns {Promise<{success: boolean, students: Array, total_rows: number, total_cols: number, error?: string}>}
     */
    async function processImage(imageSrc, progressCb) {
        if (!progressCb) progressCb = function () {};

        try {
            // Step 1: Initialize Tesseract
            progressCb(2, 'Menyiapkan OCR engine...');
            await init(progressCb);
            progressCb(50, 'Memproses gambar...');

            // Step 2: Preprocess image
            var canvas = await preprocessImage(imageSrc);
            var imgW = canvas.width, imgH = canvas.height;
            progressCb(55, 'Gambar diproses (' + imgW + 'x' + imgH + '). Mendeteksi tabel...');

            // Step 3: Detect grid
            var grid = getPixelGrid(canvas);
            var lines = detectGridLines(grid, imgW, imgH);

            progressCb(60, 'Ditemukan ' + lines.h.length + ' garis horizontal, ' + lines.v.length + ' garis vertikal.');

            // Need at least header line and a few students
            if (lines.h.length < 2 || lines.v.length < 2) {
                // Fallback: try using Tesseract on full image
                progressCb(62, 'Grid tidak terdeteksi. Mencoba pembacaan langsung...');
                return await fallbackRead(imageSrc, progressCb);
            }

            // Step 4: Extract cells
            var numRows = lines.h.length - 1;
            var numCols = lines.v.length - 1;

            // Find name column width (typically first 2-3 columns: no, nisn, name)
            // We assume the grid has: |No|NISN|Nama|1|2|3|...|31|
            // The date columns start after the name column.
            // Heuristic: date columns should have 28-31 entries.
            // If numCols > 34, first 3 cols are non-date.
            // If numCols > 31, first (numCols - 31) cols are non-date.
            var nameCols = 0;
            if (numCols > 34) {
                nameCols = 3; // No, NISN, Nama
            } else if (numCols > 31) {
                nameCols = numCols - 31;
            } else {
                nameCols = Math.max(1, numCols - 28); // At least skip name col
            }

            var dateColStart = nameCols;
            var totalDateCols = numCols - nameCols;
            if (totalDateCols > 31) totalDateCols = 31;

            progressCb(65, 'Membaca ' + numRows + ' baris x ' + totalDateCols + ' kolom tanggal...');

            // Step 5: OCR each cell
            var students = [];
            var totalCells = numRows * totalDateCols;
            var processed = 0;

            for (var r = 0; r < numRows; r++) {
                var y1 = lines.h[r] + 2;
                var y2 = lines.h[r + 1] - 2;
                var statuses = {};

                for (var c = 0; c < totalDateCols; c++) {
                    var colIdx = dateColStart + c;
                    var dayNum = c + 1;

                    if (colIdx + 1 >= lines.v.length) break;

                    var x1 = lines.v[colIdx] + 2;
                    var x2 = lines.v[colIdx + 1] - 2;

                    var cellGrid = extractCell(grid, y1, y2, x1, x2);

                    if (cellIsEmpty(cellGrid)) {
                        statuses[String(dayNum)] = '?';
                    } else {
                        var density = inkDensity(cellGrid);
                        var cellCanvas = extractCellImage(canvas, y1, y2, x1, x2);

                        if (cellCanvas && density > 0.02) {
                            try {
                                var result = await worker.recognize(cellCanvas);
                                var text = (result.data.text || '').trim();
                                statuses[String(dayNum)] = mapSymbol(text);

                                // If Tesseract returned something but density is very low,
                                // it might be noise
                                if (density < 0.03 && text.length > 2) {
                                    statuses[String(dayNum)] = '?';
                                }
                            } catch (e) {
                                statuses[String(dayNum)] = '?';
                            }
                        } else {
                            statuses[String(dayNum)] = '?';
                        }
                    }

                    processed++;
                    if (processed % 10 === 0) {
                        var pct = 65 + Math.round((processed / totalCells) * 30);
                        progressCb(pct, 'Membaca sel ' + processed + '/' + totalCells + '...');
                        // Yield to browser
                        await new Promise(function (r) { setTimeout(r, 0); });
                    }
                }

                students.push({
                    row_index: r,
                    statuses: statuses
                });
            }

            progressCb(98, 'Selesai membaca. Memproses hasil...');

            var result = {
                success: true,
                students: students,
                total_rows: numRows,
                total_cols: totalDateCols,
                name_cols: nameCols,
                grid_rows: lines.h.length,
                grid_cols: lines.v.length
            };

            progressCb(100, 'OCR selesai!');
            return result;

        } catch (err) {
            return {
                success: false,
                error: 'Error OCR: ' + (err.message || err),
                students: [],
                total_rows: 0,
                total_cols: 0
            };
        }
    }

    /**
     * Fallback: use Tesseract on the full image (no grid detection).
     */
    async function fallbackRead(imageSrc, progressCb) {
        try {
            progressCb(65, 'Membaca seluruh gambar tanpa deteksi grid...');

            var result = await worker.recognize(imageSrc);
            var lines = (result.data.text || '').split('\n').filter(function (l) {
                return l.trim().length > 0;
            });

            // Try to extract attendance symbols from each line
            var students = [];
            var r = 0;
            for (var i = 0; i < lines.length; i++) {
                var line = lines[i].trim();
                // Skip header lines
                if (/^(No|NISN|Nama|Nama\s+Siswa|Kelas)/i.test(line)) continue;
                if (/^[\d\s.]+$/.test(line)) continue; // Numbers only

                var statuses = {};
                // Try to find H/I/S/A/. symbols in the line
                var chars = line.split('');
                var dayNum = 1;
                for (var j = 0; j < chars.length && dayNum <= 31; j++) {
                    var c = chars[j];
                    if (c === '.' || c === 'H' || c === 'h') {
                        statuses[String(dayNum)] = c === '.' ? 'H' : 'H';
                        dayNum++;
                    } else if (c === 'I' || c === 'i' || c === 'l' || c === '1') {
                        statuses[String(dayNum)] = 'I';
                        dayNum++;
                    } else if (c === 'S' || c === 's') {
                        statuses[String(dayNum)] = 'S';
                        dayNum++;
                    } else if (c === 'A' || c === 'a') {
                        statuses[String(dayNum)] = 'A';
                        dayNum++;
                    }
                }

                if (Object.keys(statuses).length > 0) {
                    students.push({ row_index: r, statuses: statuses });
                    r++;
                }
            }

            if (students.length === 0) {
                return {
                    success: false,
                    error: 'Tidak dapat membaca data dari foto. Pastikan foto jelas dan menampilkan tabel absensi.',
                    students: [],
                    total_rows: 0,
                    total_cols: 0,
                    fallback: true
                };
            }

            progressCb(100, 'Selesai (mode fallback)!');

            return {
                success: true,
                students: students,
                total_rows: students.length,
                total_cols: 31,
                name_cols: 0,
                fallback: true
            };
        } catch (err) {
            return {
                success: false,
                error: 'Error membaca foto: ' + (err.message || err),
                students: [],
                total_rows: 0,
                total_cols: 0
            };
        }
    }

    /**
     * Terminate worker. Call when leaving the page.
     */
    async function terminate() {
        if (worker) {
            await worker.terminate();
            worker = null;
            initialized = false;
        }
    }

    return {
        init: init,
        processImage: processImage,
        terminate: terminate
    };
})();
