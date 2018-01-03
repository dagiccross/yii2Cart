function ws_blur(t, i, e) {
    function n(t, i, e) {
        wowAnimate(t.css({
            visibility: "visible"
        }), {
            opacity: 0
        },
        {
            opacity: 1
        },
        i, e)
    }
    function a(t, i, e) {
        wowAnimate(t, {
            opacity: 1
        },
        {
            opacity: 0
        },
        i, e)
    }
    function r(t, i, e, n) {
        var a = (parseInt(t.parent().css("z-index")) || 0) + 1;
        if (g) {
            var r = n.getContext("2d");
            return r.drawImage(t.get(0), 0, 0, i.width, i.height),
            o(r, 0, 0, n.width, n.height, e) ? d(n) : 0
        }
        for (var s = d("<div></div>").css({
            position: "absolute",
            "z-index": a,
            left: 0,
            top: 0,
            display: "none"
        }).css(i).appendTo(h), c = (Math.sqrt(5) + 1) / 2, f = 1 - c / 2, u = 0; e > f * u; u++) {
            var l = Math.PI * c * u,
            p = f * u + 1,
            v = p * Math.cos(l),
            b = p * Math.sin(l);
            d(document.createElement("img")).attr("src", t.attr("src")).css({
                opacity: 1 / (u / 1.8 + 1),
                position: "absolute",
                "z-index": a,
                left: Math.round(v) + "px",
                top: Math.round(b) + "px",
                width: "100%",
                height: "100%"
            }).appendTo(s)
        }
        return s
    }
    function o(t, i, e, n, a, r) {
        if (! (isNaN(r) || 1 > r)) {
            r |= 0;
            var o;
            try {
                o = t.getImageData(i, e, n, a)
            } catch(d) {
                return console.log("error:unable to access image data: " + d),
                !1
            }
            var c, h, g, f, u, l, b, w, x, m, y, I, M, T, C, E, _, z, D, A, L = o.data,
            N = r + r + 1,
            j = n - 1,
            q = a - 1,
            O = r + 1,
            P = O * (O + 1) / 2,
            Q = new s,
            k = Q;
            for (g = 1; N > g; g++) if (k = k.next = new s, g == O) var B = k;
            k.next = Q;
            var F = null,
            G = null;
            b = l = 0;
            var H = p[r],
            J = v[r];
            for (h = 0; a > h; h++) {
                for (T = C = E = w = x = m = 0, y = O * (_ = L[l]), I = O * (z = L[l + 1]), M = O * (D = L[l + 2]), w += P * _, x += P * z, m += P * D, k = Q, g = 0; O > g; g++) k.r = _,
                k.g = z,
                k.b = D,
                k = k.next;
                for (g = 1; O > g; g++) f = l + ((g > j ? j: g) << 2),
                w += (k.r = _ = L[f]) * (A = O - g),
                x += (k.g = z = L[f + 1]) * A,
                m += (k.b = D = L[f + 2]) * A,
                T += _,
                C += z,
                E += D,
                k = k.next;
                for (F = Q, G = B, c = 0; n > c; c++) L[l] = w * H >> J,
                L[l + 1] = x * H >> J,
                L[l + 2] = m * H >> J,
                w -= y,
                x -= I,
                m -= M,
                y -= F.r,
                I -= F.g,
                M -= F.b,
                f = b + ((f = c + r + 1) < j ? f: j) << 2,
                T += F.r = L[f],
                C += F.g = L[f + 1],
                E += F.b = L[f + 2],
                w += T,
                x += C,
                m += E,
                F = F.next,
                y += _ = G.r,
                I += z = G.g,
                M += D = G.b,
                T -= _,
                C -= z,
                E -= D,
                G = G.next,
                l += 4;
                b += n
            }
            for (c = 0; n > c; c++) {
                for (C = E = T = x = m = w = 0, l = c << 2, y = O * (_ = L[l]), I = O * (z = L[l + 1]), M = O * (D = L[l + 2]), w += P * _, x += P * z, m += P * D, k = Q, g = 0; O > g; g++) k.r = _,
                k.g = z,
                k.b = D,
                k = k.next;
                for (u = n, g = 1; r >= g; g++) l = u + c << 2,
                w += (k.r = _ = L[l]) * (A = O - g),
                x += (k.g = z = L[l + 1]) * A,
                m += (k.b = D = L[l + 2]) * A,
                T += _,
                C += z,
                E += D,
                k = k.next,
                q > g && (u += n);
                for (l = c, F = Q, G = B, h = 0; a > h; h++) f = l << 2,
                L[f] = w * H >> J,
                L[f + 1] = x * H >> J,
                L[f + 2] = m * H >> J,
                w -= y,
                x -= I,
                m -= M,
                y -= F.r,
                I -= F.g,
                M -= F.b,
                f = c + ((f = h + O) < q ? f: q) * n << 2,
                w += T += F.r = L[f],
                x += C += F.g = L[f + 1],
                m += E += F.b = L[f + 2],
                F = F.next,
                y += _ = G.r,
                I += z = G.g,
                M += D = G.b,
                T -= _,
                C -= z,
                E -= D,
                G = G.next,
                l += n
            }
            return t.putImageData(o, i, e),
            !0
        }
    }
    function s() {
        this.r = 0,
        this.g = 0,
        this.b = 0,
        this.a = 0,
        this.next = null
    }
    var d = jQuery,
    c = d(this),
    h = d("<div>").addClass("ws_effect ws_blur").css({
        position: "absolute",
        overflow: "hidden",
        top: 0,
        left: 0,
        width: "100%",
        height: "100%"
    }).appendTo(e),
    g = !t.noCanvas && !window.opera && !!document.createElement("canvas").getContext;
    if (g) try {
        document.createElement("canvas").getContext("2d").getImageData(0, 0, 1, 1)
    } catch(f) {
        g = 0
    }
    var u, l;
    this.go = function(o, s) {
        if (l) return - 1;
        l = 1;
        var f, p = d(i.get(s)),
        v = d(i.get(o)),
        b = {
            width: p.width(),
            height: p.height(),
            marginTop: p.css("marginTop"),
            marginLeft: p.css("marginLeft")
        };
        if (g && (u || (u = '<canvas width="' + b.width + '" height="' + b.height + '"/>', u = d(u + u).css({
            "z-index": 8,
            position: "absolute",
            left: 0,
            top: 0,
            visibility: "hidden"
        }).appendTo(h)), u.css(b).attr({
            width: b.width,
            height: b.height
        }), f = r(p, b, 30, u.get(0))), g && f) {
            var w = r(v, b, 30, u.get(1));
            n(f, t.duration / 3,
            function() {
                e.find(".ws_list").css({
                    visibility: "hidden"
                }),
                a(f, t.duration / 6),
                n(w, t.duration / 6,
                function() {
                    f.css({
                        visibility: "hidden"
                    }),
                    e.find(".ws_list").css({
                        visibility: "visible"
                    }),
                    c.trigger("effectEnd", {
                        delay: t.duration / 2
                    }),
                    a(w, t.duration / 2,
                    function() {
                        l = 0
                    })
                })
            })
        } else g = 0,
        f = r(p, b, 8),
        f.fadeIn(t.duration / 3, "linear",
        function() {
            c.trigger("effectEnd", {
                delay: t.duration / 3
            }),
            f.fadeOut(t.duration / 3, "linear",
            function() {
                f.remove(),
                l = 0
            })
        })
    };
    var p = [512, 512, 456, 512, 328, 456, 335, 512, 405, 328, 271, 456, 388, 335, 292, 512, 454, 405, 364, 328, 298, 271, 496, 456, 420, 388, 360, 335, 312, 292, 273, 512, 482, 454, 428, 405, 383, 364, 345, 328, 312, 298, 284, 271, 259, 496, 475, 456, 437, 420, 404, 388, 374, 360, 347, 335, 323, 312, 302, 292, 282, 273, 265, 512, 497, 482, 468, 454, 441, 428, 417, 405, 394, 383, 373, 364, 354, 345, 337, 328, 320, 312, 305, 298, 291, 284, 278, 271, 265, 259, 507, 496, 485, 475, 465, 456, 446, 437, 428, 420, 412, 404, 396, 388, 381, 374, 367, 360, 354, 347, 341, 335, 329, 323, 318, 312, 307, 302, 297, 292, 287, 282, 278, 273, 269, 265, 261, 512, 505, 497, 489, 482, 475, 468, 461, 454, 447, 441, 435, 428, 422, 417, 411, 405, 399, 394, 389, 383, 378, 373, 368, 364, 359, 354, 350, 345, 341, 337, 332, 328, 324, 320, 316, 312, 309, 305, 301, 298, 294, 291, 287, 284, 281, 278, 274, 271, 268, 265, 262, 259, 257, 507, 501, 496, 491, 485, 480, 475, 470, 465, 460, 456, 451, 446, 442, 437, 433, 428, 424, 420, 416, 412, 408, 404, 400, 396, 392, 388, 385, 381, 377, 374, 370, 367, 363, 360, 357, 354, 350, 347, 344, 341, 338, 335, 332, 329, 326, 323, 320, 318, 315, 312, 310, 307, 304, 302, 299, 297, 294, 292, 289, 287, 285, 282, 280, 278, 275, 273, 271, 269, 267, 265, 263, 261, 259],
    v = [9, 11, 12, 13, 13, 14, 14, 15, 15, 15, 15, 16, 16, 16, 16, 17, 17, 17, 17, 17, 17, 17, 18, 18, 18, 18, 18, 18, 18, 18, 18, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24]
}