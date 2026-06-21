/**
 * qr-local.js — QR Code Version 3-M generator
 * Pure vanilla JS, zero dependencies, runs entirely in browser.
 * Usage: QRLocal.render('text', 'container-id', size_px, dark_color)
 */
var QRLocal = (function () {
    'use strict';

    /* ── Galois Field GF(256) ── */
    var EXP = new Uint8Array(512);
    var LOG  = new Uint8Array(256);
    (function () {
        var x = 1;
        for (var i = 0; i < 255; i++) {
            EXP[i] = x; LOG[x] = i;
            x <<= 1; if (x & 256) x ^= 285;
        }
        for (var i = 255; i < 512; i++) EXP[i] = EXP[i - 255];
    }());

    function gmul(a, b) {
        if (a === 0 || b === 0) return 0;
        return EXP[(LOG[a] + LOG[b]) % 255];
    }

    function genpoly(n) {           /* RS generator polynomial of degree n */
        var p = [1];
        for (var i = 0; i < n; i++) {
            var q = [1, EXP[i]], r = new Uint8Array(p.length + 1);
            for (var a = 0; a < p.length; a++)
                for (var b = 0; b < q.length; b++)
                    r[a + b] ^= gmul(p[a], q[b]);
            p = Array.from(r);
        }
        return p;
    }

    function rsec(data, n) {        /* Reed-Solomon error correction */
        var gen = genpoly(n);
        var out = Array.from(data).concat(new Array(n).fill(0));
        for (var i = 0; i < data.length; i++) {
            var c = out[i];
            if (c !== 0)
                for (var j = 0; j < gen.length; j++)
                    out[i + j] ^= gmul(gen[j], c);
        }
        return out.slice(data.length);
    }

    /* ── UTF-8 encode ── */
    function utf8(str) {
        var b = [];
        for (var i = 0; i < str.length; i++) {
            var c = str.charCodeAt(i);
            if (c < 0x80)       b.push(c);
            else if (c < 0x800) { b.push(0xC0 | c >> 6); b.push(0x80 | c & 63); }
            else                { b.push(0xE0 | c >> 12); b.push(0x80 | (c >> 6) & 63); b.push(0x80 | c & 63); }
        }
        return b;
    }

    /* ── Build bit-stream (Version 3, EC-M, mode byte) ── */
    /* V3-M: 26 data bytes, 26 EC bytes */
    var DC = 26, EC_N = 26;
    function bitstream(text) {
        var bytes = utf8(text);
        var bits  = [];
        function push(v, n) { for (var i = n - 1; i >= 0; i--) bits.push((v >> i) & 1); }
        push(0b0100, 4);            /* byte mode */
        push(bytes.length, 8);
        for (var i = 0; i < bytes.length; i++) push(bytes[i], 8);
        /* terminator + padding */
        for (var i = 0; i < 4 && bits.length < DC * 8; i++) bits.push(0);
        while (bits.length % 8) bits.push(0);
        var padBytes = [0xEC, 0x11], pi = 0;
        while (bits.length < DC * 8) { push(padBytes[pi++ % 2], 8); }
        return bits;
    }

    function makeCodewords(text) {
        var bits = bitstream(text);
        var data = new Uint8Array(DC);
        for (var i = 0; i < DC; i++) {
            var v = 0;
            for (var j = 0; j < 8; j++) v = (v << 1) | bits[i * 8 + j];
            data[i] = v;
        }
        var ec   = rsec(data, EC_N);
        var all  = new Uint8Array(DC + EC_N);
        all.set(data); all.set(ec, DC);
        return all;
    }

    /* ── Build 29×29 matrix (Version 3) ── */
    var N = 29;

    function emptyGrid() {
        var g = [];
        for (var i = 0; i < N; i++) {
            var row = new Int8Array(N);
            for (var j = 0; j < N; j++) row[j] = -1;
            g.push(row);
        }
        return g;
    }

    function placeFinder(g, r, c) {
        for (var dr = -1; dr <= 7; dr++) for (var dc = -1; dc <= 7; dc++) {
            var rr = r + dr, cc = c + dc;
            if (rr < 0 || rr >= N || cc < 0 || cc >= N) continue;
            var border = dr === 0 || dr === 6 || dc === 0 || dc === 6;
            var inner  = dr >= 2 && dr <= 4 && dc >= 2 && dc <= 4;
            g[rr][cc] = (dr >= 0 && dr <= 6 && dc >= 0 && dc <= 6 && (border || inner)) ? 1 : 0;
        }
    }

    function placeAlignment(g) {   /* V3 alignment at row=20, col=20 */
        for (var dr = -2; dr <= 2; dr++) for (var dc = -2; dc <= 2; dc++) {
            if (g[20 + dr][20 + dc] !== -1) continue;
            var onBorder = Math.abs(dr) === 2 || Math.abs(dc) === 2;
            g[20 + dr][20 + dc] = (dr === 0 && dc === 0) ? 1 : onBorder ? 1 : 0;
        }
    }

    /* Format string for EC-M, mask pattern 2 — pre-computed */
    var FMT = [1,0,1,1,0,0,0,0,1,0,1,1,0,0,0];

    function placeFormat(g) {
        /* around top-left finder */
        var seq = [0,1,2,3,4,5,7,8];
        for (var i = 0; i < 8; i++) {
            g[8][seq[i]] = FMT[i];
            g[seq[i]][8] = FMT[14 - i];
        }
        /* beside top-right and bottom-left finders */
        for (var i = 0; i < 7; i++) {
            g[8][N - 1 - i] = FMT[i];
            g[N - 7 + i][8] = FMT[6 - i + 8];  /* rough mirror */
        }
        g[N - 8][8] = 1;   /* dark module */
    }

    function dataBits(codewords) {
        var bits = [];
        for (var i = 0; i < codewords.length; i++)
            for (var j = 7; j >= 0; j--) bits.push((codewords[i] >> j) & 1);
        return bits;
    }

    function placeData(g, bits) {
        var bi = 0, up = true;
        for (var col = N - 1; col > 0; col -= 2) {
            if (col === 6) col--;
            for (var ri = 0; ri < N; ri++) {
                var row = up ? N - 1 - ri : ri;
                for (var dx = 0; dx < 2; dx++) {
                    var c = col - dx;
                    if (g[row][c] !== -1) continue;
                    var bit = bi < bits.length ? bits[bi++] : 0;
                    /* mask pattern 2: col % 3 === 0 */
                    g[row][c] = (c % 3 === 0) ? bit ^ 1 : bit;
                }
            }
            up = !up;
        }
    }

    function buildMatrix(text) {
        var g  = emptyGrid();
        placeFinder(g, 0, 0);
        placeFinder(g, 0, N - 7);
        placeFinder(g, N - 7, 0);
        placeAlignment(g);
        /* timing strips */
        for (var i = 8; i < N - 8; i++) {
            if (g[6][i] === -1) g[6][i] = i % 2 === 0 ? 1 : 0;
            if (g[i][6] === -1) g[i][6] = i % 2 === 0 ? 1 : 0;
        }
        placeFormat(g);
        var cw   = makeCodewords(text);
        var bits = dataBits(cw);
        placeData(g, bits);
        return g;
    }

    /* ── Public API ── */
    return {
        render: function (text, containerId, sizePx, darkColor) {
            var container = document.getElementById(containerId);
            if (!container) return;
            sizePx    = sizePx    || 200;
            darkColor = darkColor || '#1A3D2B';
            try {
                var mat  = buildMatrix(text);
                var cell = Math.floor(sizePx / N);
                var dim  = cell * N;
                var svg  = '<svg xmlns="http://www.w3.org/2000/svg" width="' + dim + '" height="' + dim
                         + '" viewBox="0 0 ' + dim + ' ' + dim + '" shape-rendering="crispEdges">'
                         + '<rect width="' + dim + '" height="' + dim + '" fill="#fff"/>';
                for (var r = 0; r < N; r++)
                    for (var c = 0; c < N; c++)
                        if (mat[r][c] === 1)
                            svg += '<rect x="' + (c * cell) + '" y="' + (r * cell)
                                 + '" width="' + cell + '" height="' + cell
                                 + '" fill="' + darkColor + '"/>';
                svg += '</svg>';
                container.innerHTML = svg;
            } catch (e) {
                container.innerHTML = '<div style="width:' + sizePx + 'px;height:' + sizePx + 'px;'
                    + 'display:flex;align-items:center;justify-content:center;background:#F3F4F6;'
                    + 'border-radius:8px;color:#6B7280;font-size:13px;text-align:center;padding:16px;">'
                    + '<span>QR gagal dimuat.<br>' + (e.message || '') + '</span></div>';
            }
        }
    };
}());