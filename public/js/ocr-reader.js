/**
 * OCR Reader v1 — MIS Nurul Ulum
 * Simple client-side OCR using Tesseract.js v5
 *
 * NO grid detection, NO cell extraction, NO 37-column constraint.
 * Just reads raw text from the entire image.
 * Parser (server-side) handles extracting I/S/A from the raw text.
 */
window.OCRReader = (function () {
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
            s.onerror = function () {
                reject(new Error('Gagal memuat ' + src));
            };
            document.head.appendChild(s);
        });
    }

    async function init(progressCb) {
        if (initialized) return;
        if (progressCb) progressCb(5, 'Memuat Tesseract.js...');

        await loadScript(
            'https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js'
        );

        if (progressCb) progressCb(15, 'Memuat OCR engine...');
        worker = await Tesseract.createWorker('eng', 1, {
            logger: function (m) {
                if (progressCb && m.status === 'recognizing text') {
                    var pct = 15 + Math.round((m.progress || 0) * 75);
                    progressCb(pct, 'Membaca teks dari gambar...');
                }
            },
        });

        initialized = true;
        if (progressCb) progressCb(90, 'OCR engine siap.');
    }

    function clamp(v, lo, hi) {
        return v < lo ? lo : v > hi ? hi : v;
    }

    /**
     * Preprocess: grayscale, simple contrast, deskew attempt.
     * Returns a canvas with enhanced image for better OCR.
     */
    function preprocessImage(imgSrc) {
        return new Promise(function (resolve) {
            var img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = function () {
                var w = img.naturalWidth;
                var h = img.naturalHeight;

                var minDim = Math.min(w, h);
                if (minDim < 1500) {
                    var scale = Math.min(3, 2000 / minDim);
                    w = Math.round(w * scale);
                    h = Math.round(h * scale);
                }

                var maxDim = 4000;
                if (Math.max(w, h) > maxDim) {
                    var s2 = maxDim / Math.max(w, h);
                    w = Math.round(w * s2);
                    h = Math.round(h * s2);
                }

                var canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;
                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, w, h);

                var imageData = ctx.getImageData(0, 0, w, h);
                var data = imageData.data;

                for (var i = 0; i < data.length; i += 4) {
                    var gray = Math.round(0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2]);
                    var enhanced = clamp(Math.round((gray - 128) * 1.3 + 128), 0, 255);
                    data[i] = data[i + 1] = data[i + 2] = enhanced;
                }

                ctx.putImageData(imageData, 0, 0);
                resolve(canvas);
            };
            img.src = imgSrc;
        });
    }

    /**
     * Main function: read the full image and return raw OCR text.
     *
     * @param {string} imageSrc - Data URL or URL of the image
     * @param {function} progressCb - Progress callback(pct, msg)
     * @returns {Promise<{success: boolean, rawText: string, error?: string}>}
     */
    async function readRawText(imageSrc, progressCb) {
        if (!progressCb) progressCb = function () {};

        try {
            progressCb(2, 'Menyiapkan OCR engine...');
            await init(progressCb);

            progressCb(50, 'Memproses gambar...');
            var canvas = await preprocessImage(imageSrc);

            progressCb(55, 'Membaca teks dari gambar...');
            var result = await worker.recognize(canvas);
            var rawText = (result.data.text || '').trim();

            progressCb(95, 'OCR selesai.');

            if (rawText.length < 5) {
                return {
                    success: false,
                    rawText: '',
                    error: 'Teks yang terbaca terlalu sedikit. Pastikan foto jelas dan menampilkan tabel absensi.',
                };
            }

            return {
                success: true,
                rawText: rawText,
            };
        } catch (err) {
            return {
                success: false,
                rawText: '',
                error: 'Error OCR: ' + (err.message || err),
            };
        }
    }

    async function terminate() {
        if (worker) {
            await worker.terminate();
            worker = null;
            initialized = false;
        }
    }

    return {
        init: init,
        readRawText: readRawText,
        terminate: terminate,
    };
})();
