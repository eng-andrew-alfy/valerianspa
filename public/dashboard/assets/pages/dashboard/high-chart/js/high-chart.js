/*
 Highcharts JS v5.0.5 (2024-11-29)

 (c) 2009-2024 Torstein Honsi

 License: www.highcharts.com/license
*/
(function (M, a) {
    "object" === typeof module && module.exports
        ? (module.exports = M.document ? a(M) : a)
        : (M.Highcharts = a(M));
})("undefined" !== typeof window ? window : this, function (M) {
    M = (function () {
        var a = window,
            E = a.document,
            A = (a.navigator && a.navigator.userAgent) || "",
            F =
                E &&
                E.createElementNS &&
                !!E.createElementNS("http://www.w3.org/2000/svg", "svg")
                    .createSVGRect,
            H = /(edge|msie|trident)/i.test(A) && !window.opera,
            p = !F,
            d = /Firefox/.test(A),
            g = d && 4 > parseInt(A.split("Firefox/")[1], 10);
        return a.Highcharts
            ? a.Highcharts.error(16, !0)
            : {
                  product: "Highcharts",
                  version: "5.0.5",
                  deg2rad: (2 * Math.PI) / 360,
                  doc: E,
                  hasBidiBug: g,
                  hasTouch: E && void 0 !== E.documentElement.ontouchstart,
                  isMS: H,
                  isWebKit: /AppleWebKit/.test(A),
                  isFirefox: d,
                  isTouchDevice: /(Mobile|Android|Windows Phone)/.test(A),
                  SVG_NS: "http://www.w3.org/2000/svg",
                  chartCount: 0,
                  seriesTypes: {},
                  symbolSizes: {},
                  svg: F,
                  vml: p,
                  win: a,
                  charts: [],
                  marginNames: [
                      "plotTop",
                      "marginRight",
                      "marginBottom",
                      "plotLeft",
                  ],
                  noop: function () {},
              };
    })();
    (function (a) {
        var E = [],
            A = a.charts,
            F = a.doc,
            H = a.win;
        a.error = function (a, d) {
            a = "Highcharts error #" + a + ": www.highcharts.com/errors/" + a;
            if (d) throw Error(a);
            H.console && console.log(a);
        };
        a.Fx = function (a, d, g) {
            this.options = d;
            this.elem = a;
            this.prop = g;
        };
        a.Fx.prototype = {
            dSetter: function () {
                var a = this.paths[0],
                    d = this.paths[1],
                    g = [],
                    v = this.now,
                    l = a.length,
                    r;
                if (1 === v) g = this.toD;
                else if (l === d.length && 1 > v)
                    for (; l--; )
                        (r = parseFloat(a[l])),
                            (g[l] = isNaN(r)
                                ? a[l]
                                : v * parseFloat(d[l] - r) + r);
                else g = d;
                this.elem.attr("d", g, null, !0);
            },
            update: function () {
                var a = this.elem,
                    d = this.prop,
                    g = this.now,
                    v = this.options.step;
                if (this[d + "Setter"]) this[d + "Setter"]();
                else
                    a.attr
                        ? a.element && a.attr(d, g, null, !0)
                        : (a.style[d] = g + this.unit);
                v && v.call(a, g, this);
            },
            run: function (a, d, g) {
                var p = this,
                    l = function (a) {
                        return l.stopped ? !1 : p.step(a);
                    },
                    r;
                this.startTime = +new Date();
                this.start = a;
                this.end = d;
                this.unit = g;
                this.now = this.start;
                this.pos = 0;
                l.elem = this.elem;
                l.prop = this.prop;
                l() &&
                    1 === E.push(l) &&
                    (l.timerId = setInterval(function () {
                        for (r = 0; r < E.length; r++)
                            E[r]() || E.splice(r--, 1);
                        E.length || clearInterval(l.timerId);
                    }, 13));
            },
            step: function (a) {
                var d = +new Date(),
                    g,
                    p = this.options;
                g = this.elem;
                var l = p.complete,
                    r = p.duration,
                    f = p.curAnim,
                    b;
                if (g.attr && !g.element) g = !1;
                else if (a || d >= r + this.startTime) {
                    this.now = this.end;
                    this.pos = 1;
                    this.update();
                    a = f[this.prop] = !0;
                    for (b in f) !0 !== f[b] && (a = !1);
                    a && l && l.call(g);
                    g = !1;
                } else
                    (this.pos = p.easing((d - this.startTime) / r)),
                        (this.now =
                            this.start + (this.end - this.start) * this.pos),
                        this.update(),
                        (g = !0);
                return g;
            },
            initPath: function (a, d, g) {
                function p(a) {
                    var c, e;
                    for (h = a.length; h--; )
                        (c = "M" === a[h] || "L" === a[h]),
                            (e = /[a-zA-Z]/.test(a[h + 3])),
                            c &&
                                e &&
                                a.splice(
                                    h + 1,
                                    0,
                                    a[h + 1],
                                    a[h + 2],
                                    a[h + 1],
                                    a[h + 2]
                                );
                }
                function l(a, c) {
                    for (; a.length < k; ) {
                        a[0] = c[k - a.length];
                        var e = a.slice(0, t);
                        [].splice.apply(a, [0, 0].concat(e));
                        C &&
                            ((e = a.slice(a.length - t)),
                            [].splice.apply(a, [a.length, 0].concat(e)),
                            h--);
                    }
                    a[0] = "M";
                }
                function r(a, c) {
                    for (var b = (k - a.length) / t; 0 < b && b--; )
                        (e = a.slice().splice(a.length / u - t, t * u)),
                            (e[0] = c[k - t - b * t]),
                            w && ((e[t - 6] = e[t - 2]), (e[t - 5] = e[t - 1])),
                            [].splice.apply(a, [a.length / u, 0].concat(e)),
                            C && b--;
                }
                d = d || "";
                var f,
                    b = a.startX,
                    n = a.endX,
                    w = -1 < d.indexOf("C"),
                    t = w ? 7 : 3,
                    k,
                    e,
                    h;
                d = d.split(" ");
                g = g.slice();
                var C = a.isArea,
                    u = C ? 2 : 1,
                    c;
                w && (p(d), p(g));
                if (b && n) {
                    for (h = 0; h < b.length; h++)
                        if (b[h] === n[0]) {
                            f = h;
                            break;
                        } else if (b[0] === n[n.length - b.length + h]) {
                            f = h;
                            c = !0;
                            break;
                        }
                    void 0 === f && (d = []);
                }
                d.length &&
                    ((k = g.length + (f || 0) * u * t),
                    c ? (l(d, g), r(g, d)) : (l(g, d), r(d, g)));
                return [d, g];
            },
        };
        a.extend = function (a, d) {
            var g;
            a || (a = {});
            for (g in d) a[g] = d[g];
            return a;
        };
        a.merge = function () {
            var p,
                d = arguments,
                g,
                v = {},
                l = function (d, f) {
                    var b, n;
                    "object" !== typeof d && (d = {});
                    for (n in f)
                        f.hasOwnProperty(n) &&
                            ((b = f[n]),
                            a.isObject(b, !0) &&
                            "renderTo" !== n &&
                            "number" !== typeof b.nodeType
                                ? (d[n] = l(d[n] || {}, b))
                                : (d[n] = f[n]));
                    return d;
                };
            !0 === d[0] && ((v = d[1]), (d = Array.prototype.slice.call(d, 2)));
            g = d.length;
            for (p = 0; p < g; p++) v = l(v, d[p]);
            return v;
        };
        a.pInt = function (a, d) {
            return parseInt(a, d || 10);
        };
        a.isString = function (a) {
            return "string" === typeof a;
        };
        a.isArray = function (a) {
            a = Object.prototype.toString.call(a);
            return "[object Array]" === a || "[object Array Iterator]" === a;
        };
        a.isObject = function (p, d) {
            return p && "object" === typeof p && (!d || !a.isArray(p));
        };
        a.isNumber = function (a) {
            return "number" === typeof a && !isNaN(a);
        };
        a.erase = function (a, d) {
            for (var g = a.length; g--; )
                if (a[g] === d) {
                    a.splice(g, 1);
                    break;
                }
        };
        a.defined = function (a) {
            return void 0 !== a && null !== a;
        };
        a.attr = function (p, d, g) {
            var v, l;
            if (a.isString(d))
                a.defined(g)
                    ? p.setAttribute(d, g)
                    : p && p.getAttribute && (l = p.getAttribute(d));
            else if (a.defined(d) && a.isObject(d))
                for (v in d) p.setAttribute(v, d[v]);
            return l;
        };
        a.splat = function (p) {
            return a.isArray(p) ? p : [p];
        };
        a.syncTimeout = function (a, d, g) {
            if (d) return setTimeout(a, d, g);
            a.call(0, g);
        };
        a.pick = function () {
            var a = arguments,
                d,
                g,
                v = a.length;
            for (d = 0; d < v; d++)
                if (((g = a[d]), void 0 !== g && null !== g)) return g;
        };
        a.css = function (p, d) {
            a.isMS &&
                !a.svg &&
                d &&
                void 0 !== d.opacity &&
                (d.filter = "alpha(opacity\x3d" + 100 * d.opacity + ")");
            a.extend(p.style, d);
        };
        a.createElement = function (p, d, g, v, l) {
            p = F.createElement(p);
            var r = a.css;
            d && a.extend(p, d);
            l && r(p, { padding: 0, border: "none", margin: 0 });
            g && r(p, g);
            v && v.appendChild(p);
            return p;
        };
        a.extendClass = function (p, d) {
            var g = function () {};
            g.prototype = new p();
            a.extend(g.prototype, d);
            return g;
        };
        a.pad = function (a, d, g) {
            return Array((d || 2) + 1 - String(a).length).join(g || 0) + a;
        };
        a.relativeLength = function (a, d) {
            return /%$/.test(a) ? (d * parseFloat(a)) / 100 : parseFloat(a);
        };
        a.wrap = function (a, d, g) {
            var p = a[d];
            a[d] = function () {
                var a = Array.prototype.slice.call(arguments),
                    d = arguments,
                    f = this;
                f.proceed = function () {
                    p.apply(f, arguments.length ? arguments : d);
                };
                a.unshift(p);
                a = g.apply(this, a);
                f.proceed = null;
                return a;
            };
        };
        a.getTZOffset = function (p) {
            var d = a.Date;
            return (
                6e4 *
                ((d.hcGetTimezoneOffset && d.hcGetTimezoneOffset(p)) ||
                    d.hcTimezoneOffset ||
                    0)
            );
        };
        a.dateFormat = function (p, d, g) {
            if (!a.defined(d) || isNaN(d))
                return a.defaultOptions.lang.invalidDate || "";
            p = a.pick(p, "%Y-%m-%d %H:%M:%S");
            var v = a.Date,
                l = new v(d - a.getTZOffset(d)),
                r,
                f = l[v.hcGetHours](),
                b = l[v.hcGetDay](),
                n = l[v.hcGetDate](),
                w = l[v.hcGetMonth](),
                t = l[v.hcGetFullYear](),
                k = a.defaultOptions.lang,
                e = k.weekdays,
                h = k.shortWeekdays,
                C = a.pad,
                v = a.extend(
                    {
                        a: h ? h[b] : e[b].substr(0, 3),
                        A: e[b],
                        d: C(n),
                        e: C(n, 2, " "),
                        w: b,
                        b: k.shortMonths[w],
                        B: k.months[w],
                        m: C(w + 1),
                        y: t.toString().substr(2, 2),
                        Y: t,
                        H: C(f),
                        k: f,
                        I: C(f % 12 || 12),
                        l: f % 12 || 12,
                        M: C(l[v.hcGetMinutes]()),
                        p: 12 > f ? "AM" : "PM",
                        P: 12 > f ? "am" : "pm",
                        S: C(l.getSeconds()),
                        L: C(Math.round(d % 1e3), 3),
                    },
                    a.dateFormats
                );
            for (r in v)
                for (; -1 !== p.indexOf("%" + r); )
                    p = p.replace(
                        "%" + r,
                        "function" === typeof v[r] ? v[r](d) : v[r]
                    );
            return g ? p.substr(0, 1).toUpperCase() + p.substr(1) : p;
        };
        a.formatSingle = function (p, d) {
            var g = /\.([0-9])/,
                v = a.defaultOptions.lang;
            /f$/.test(p)
                ? ((g = (g = p.match(g)) ? g[1] : -1),
                  null !== d &&
                      (d = a.numberFormat(
                          d,
                          g,
                          v.decimalPoint,
                          -1 < p.indexOf(",") ? v.thousandsSep : ""
                      )))
                : (d = a.dateFormat(p, d));
            return d;
        };
        a.format = function (p, d) {
            for (var g = "{", v = !1, l, r, f, b, n = [], w; p; ) {
                g = p.indexOf(g);
                if (-1 === g) break;
                l = p.slice(0, g);
                if (v) {
                    l = l.split(":");
                    r = l.shift().split(".");
                    b = r.length;
                    w = d;
                    for (f = 0; f < b; f++) w = w[r[f]];
                    l.length && (w = a.formatSingle(l.join(":"), w));
                    n.push(w);
                } else n.push(l);
                p = p.slice(g + 1);
                g = (v = !v) ? "}" : "{";
            }
            n.push(p);
            return n.join("");
        };
        a.getMagnitude = function (a) {
            return Math.pow(10, Math.floor(Math.log(a) / Math.LN10));
        };
        a.normalizeTickInterval = function (p, d, g, v, l) {
            var r,
                f = p;
            g = a.pick(g, 1);
            r = p / g;
            d ||
                ((d = l
                    ? [1, 1.2, 1.5, 2, 2.5, 3, 4, 5, 6, 8, 10]
                    : [1, 2, 2.5, 5, 10]),
                !1 === v &&
                    (1 === g
                        ? (d = a.grep(d, function (a) {
                              return 0 === a % 1;
                          }))
                        : 0.1 >= g && (d = [1 / g])));
            for (
                v = 0;
                v < d.length &&
                !((f = d[v]),
                (l && f * g >= p) ||
                    (!l && r <= (d[v] + (d[v + 1] || d[v])) / 2));
                v++
            );
            return f * g;
        };
        a.stableSort = function (a, d) {
            var g = a.length,
                p,
                l;
            for (l = 0; l < g; l++) a[l].safeI = l;
            a.sort(function (a, f) {
                p = d(a, f);
                return 0 === p ? a.safeI - f.safeI : p;
            });
            for (l = 0; l < g; l++) delete a[l].safeI;
        };
        a.arrayMin = function (a) {
            for (var d = a.length, g = a[0]; d--; ) a[d] < g && (g = a[d]);
            return g;
        };
        a.arrayMax = function (a) {
            for (var d = a.length, g = a[0]; d--; ) a[d] > g && (g = a[d]);
            return g;
        };
        a.destroyObjectProperties = function (a, d) {
            for (var g in a)
                a[g] && a[g] !== d && a[g].destroy && a[g].destroy(),
                    delete a[g];
        };
        a.discardElement = function (p) {
            var d = a.garbageBin;
            d || (d = a.createElement("div"));
            p && d.appendChild(p);
            d.innerHTML = "";
        };
        a.correctFloat = function (a, d) {
            return parseFloat(a.toPrecision(d || 14));
        };
        a.setAnimation = function (p, d) {
            d.renderer.globalAnimation = a.pick(
                p,
                d.options.chart.animation,
                !0
            );
        };
        a.animObject = function (p) {
            return a.isObject(p) ? a.merge(p) : { duration: p ? 500 : 0 };
        };
        a.timeUnits = {
            millisecond: 1,
            second: 1e3,
            minute: 6e4,
            hour: 36e5,
            day: 864e5,
            week: 6048e5,
            month: 24192e5,
            year: 314496e5,
        };
        a.numberFormat = function (p, d, g, v) {
            p = +p || 0;
            d = +d;
            var l = a.defaultOptions.lang,
                r = (p.toString().split(".")[1] || "").length,
                f,
                b,
                n = Math.abs(p);
            -1 === d ? (d = Math.min(r, 20)) : a.isNumber(d) || (d = 2);
            f = String(a.pInt(n.toFixed(d)));
            b = 3 < f.length ? f.length % 3 : 0;
            g = a.pick(g, l.decimalPoint);
            v = a.pick(v, l.thousandsSep);
            p = (0 > p ? "-" : "") + (b ? f.substr(0, b) + v : "");
            p += f.substr(b).replace(/(\d{3})(?=\d)/g, "$1" + v);
            d &&
                ((v = Math.abs(n - f + Math.pow(10, -Math.max(d, r) - 1))),
                (p += g + v.toFixed(d).slice(2)));
            return p;
        };
        Math.easeInOutSine = function (a) {
            return -0.5 * (Math.cos(Math.PI * a) - 1);
        };
        a.getStyle = function (p, d) {
            return "width" === d
                ? Math.min(p.offsetWidth, p.scrollWidth) -
                      a.getStyle(p, "padding-left") -
                      a.getStyle(p, "padding-right")
                : "height" === d
                ? Math.min(p.offsetHeight, p.scrollHeight) -
                  a.getStyle(p, "padding-top") -
                  a.getStyle(p, "padding-bottom")
                : (p = H.getComputedStyle(p, void 0)) &&
                  a.pInt(p.getPropertyValue(d));
        };
        a.inArray = function (a, d) {
            return d.indexOf ? d.indexOf(a) : [].indexOf.call(d, a);
        };
        a.grep = function (a, d) {
            return [].filter.call(a, d);
        };
        a.map = function (a, d) {
            for (var g = [], v = 0, l = a.length; v < l; v++)
                g[v] = d.call(a[v], a[v], v, a);
            return g;
        };
        a.offset = function (a) {
            var d = F.documentElement;
            a = a.getBoundingClientRect();
            return {
                top:
                    a.top + (H.pageYOffset || d.scrollTop) - (d.clientTop || 0),
                left:
                    a.left +
                    (H.pageXOffset || d.scrollLeft) -
                    (d.clientLeft || 0),
            };
        };
        a.stop = function (a, d) {
            for (var g = E.length; g--; )
                E[g].elem !== a ||
                    (d && d !== E[g].prop) ||
                    (E[g].stopped = !0);
        };
        a.each = function (a, d, g) {
            return Array.prototype.forEach.call(a, d, g);
        };
        a.addEvent = function (p, d, g) {
            function v(a) {
                a.target = a.srcElement || H;
                g.call(p, a);
            }
            var l = (p.hcEvents = p.hcEvents || {});
            p.addEventListener
                ? p.addEventListener(d, g, !1)
                : p.attachEvent &&
                  (p.hcEventsIE || (p.hcEventsIE = {}),
                  (p.hcEventsIE[g.toString()] = v),
                  p.attachEvent("on" + d, v));
            l[d] || (l[d] = []);
            l[d].push(g);
            return function () {
                a.removeEvent(p, d, g);
            };
        };
        a.removeEvent = function (p, d, g) {
            function v(a, b) {
                p.removeEventListener
                    ? p.removeEventListener(a, b, !1)
                    : p.attachEvent &&
                      ((b = p.hcEventsIE[b.toString()]),
                      p.detachEvent("on" + a, b));
            }
            function l() {
                var a, b;
                if (p.nodeName)
                    for (b in (d ? ((a = {}), (a[d] = !0)) : (a = f), a))
                        if (f[b]) for (a = f[b].length; a--; ) v(b, f[b][a]);
            }
            var r,
                f = p.hcEvents,
                b;
            f &&
                (d
                    ? ((r = f[d] || []),
                      g
                          ? ((b = a.inArray(g, r)),
                            -1 < b && (r.splice(b, 1), (f[d] = r)),
                            v(d, g))
                          : (l(), (f[d] = [])))
                    : (l(), (p.hcEvents = {})));
        };
        a.fireEvent = function (p, d, g, v) {
            var l;
            l = p.hcEvents;
            var r, f;
            g = g || {};
            if (F.createEvent && (p.dispatchEvent || p.fireEvent))
                (l = F.createEvent("Events")),
                    l.initEvent(d, !0, !0),
                    a.extend(l, g),
                    p.dispatchEvent ? p.dispatchEvent(l) : p.fireEvent(d, l);
            else if (l)
                for (
                    l = l[d] || [],
                        r = l.length,
                        g.target ||
                            a.extend(g, {
                                preventDefault: function () {
                                    g.defaultPrevented = !0;
                                },
                                target: p,
                                type: d,
                            }),
                        d = 0;
                    d < r;
                    d++
                )
                    (f = l[d]) && !1 === f.call(p, g) && g.preventDefault();
            v && !g.defaultPrevented && v(g);
        };
        a.animate = function (p, d, g) {
            var v,
                l = "",
                r,
                f,
                b;
            a.isObject(g) ||
                ((v = arguments),
                (g = { duration: v[2], easing: v[3], complete: v[4] }));
            a.isNumber(g.duration) || (g.duration = 400);
            g.easing =
                "function" === typeof g.easing
                    ? g.easing
                    : Math[g.easing] || Math.easeInOutSine;
            g.curAnim = a.merge(d);
            for (b in d)
                a.stop(p, b),
                    (f = new a.Fx(p, g, b)),
                    (r = null),
                    "d" === b
                        ? ((f.paths = f.initPath(p, p.d, d.d)),
                          (f.toD = d.d),
                          (v = 0),
                          (r = 1))
                        : p.attr
                        ? (v = p.attr(b))
                        : ((v = parseFloat(a.getStyle(p, b)) || 0),
                          "opacity" !== b && (l = "px")),
                    r || (r = d[b]),
                    r.match && r.match("px") && (r = r.replace(/px/g, "")),
                    f.run(v, r, l);
        };
        a.seriesType = function (p, d, g, v, l) {
            var r = a.getOptions(),
                f = a.seriesTypes;
            r.plotOptions[p] = a.merge(r.plotOptions[d], g);
            f[p] = a.extendClass(f[d] || function () {}, v);
            f[p].prototype.type = p;
            l && (f[p].prototype.pointClass = a.extendClass(a.Point, l));
            return f[p];
        };
        a.uniqueKey = (function () {
            var a = Math.random().toString(36).substring(2, 9),
                d = 0;
            return function () {
                return "highcharts-" + a + "-" + d++;
            };
        })();
        H.jQuery &&
            (H.jQuery.fn.highcharts = function () {
                var p = [].slice.call(arguments);
                if (this[0])
                    return p[0]
                        ? (new a[a.isString(p[0]) ? p.shift() : "Chart"](
                              this[0],
                              p[0],
                              p[1]
                          ),
                          this)
                        : A[a.attr(this[0], "data-highcharts-chart")];
            });
        F &&
            !F.defaultView &&
            (a.getStyle = function (p, d) {
                var g = { width: "clientWidth", height: "clientHeight" }[d];
                if (p.style[d]) return a.pInt(p.style[d]);
                "opacity" === d && (d = "filter");
                if (g)
                    return (
                        (p.style.zoom = 1),
                        Math.max(p[g] - 2 * a.getStyle(p, "padding"), 0)
                    );
                p =
                    p.currentStyle[
                        d.replace(/\-(\w)/g, function (a, l) {
                            return l.toUpperCase();
                        })
                    ];
                "filter" === d &&
                    (p = p.replace(
                        /alpha\(opacity=([0-9]+)\)/,
                        function (a, l) {
                            return l / 100;
                        }
                    ));
                return "" === p ? 1 : a.pInt(p);
            });
        Array.prototype.forEach ||
            (a.each = function (a, d, g) {
                for (var v = 0, l = a.length; v < l; v++)
                    if (!1 === d.call(g, a[v], v, a)) return v;
            });
        Array.prototype.indexOf ||
            (a.inArray = function (a, d) {
                var g,
                    v = 0;
                if (d) for (g = d.length; v < g; v++) if (d[v] === a) return v;
                return -1;
            });
        Array.prototype.filter ||
            (a.grep = function (a, d) {
                for (var g = [], v = 0, l = a.length; v < l; v++)
                    d(a[v], v) && g.push(a[v]);
                return g;
            });
    })(M);
    (function (a) {
        var E = a.each,
            A = a.isNumber,
            F = a.map,
            H = a.merge,
            p = a.pInt;
        a.Color = function (d) {
            if (!(this instanceof a.Color)) return new a.Color(d);
            this.init(d);
        };
        a.Color.prototype = {
            parsers: [
                {
                    regex: /rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]?(?:\.[0-9]+)?)\s*\)/,
                    parse: function (a) {
                        return [
                            p(a[1]),
                            p(a[2]),
                            p(a[3]),
                            parseFloat(a[4], 10),
                        ];
                    },
                },
                {
                    regex: /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/,
                    parse: function (a) {
                        return [p(a[1], 16), p(a[2], 16), p(a[3], 16), 1];
                    },
                },
                {
                    regex: /rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/,
                    parse: function (a) {
                        return [p(a[1]), p(a[2]), p(a[3]), 1];
                    },
                },
            ],
            names: { white: "#ffffff", black: "#000000" },
            init: function (d) {
                var g, v, l, r;
                if ((this.input = d = this.names[d] || d) && d.stops)
                    this.stops = F(d.stops, function (f) {
                        return new a.Color(f[1]);
                    });
                else
                    for (l = this.parsers.length; l-- && !v; )
                        (r = this.parsers[l]),
                            (g = r.regex.exec(d)) && (v = r.parse(g));
                this.rgba = v || [];
            },
            get: function (a) {
                var g = this.input,
                    d = this.rgba,
                    l;
                this.stops
                    ? ((l = H(g)),
                      (l.stops = [].concat(l.stops)),
                      E(this.stops, function (d, f) {
                          l.stops[f] = [l.stops[f][0], d.get(a)];
                      }))
                    : (l =
                          d && A(d[0])
                              ? "rgb" === a || (!a && 1 === d[3])
                                  ? "rgb(" +
                                    d[0] +
                                    "," +
                                    d[1] +
                                    "," +
                                    d[2] +
                                    ")"
                                  : "a" === a
                                  ? d[3]
                                  : "rgba(" + d.join(",") + ")"
                              : g);
                return l;
            },
            brighten: function (a) {
                var d,
                    v = this.rgba;
                if (this.stops)
                    E(this.stops, function (l) {
                        l.brighten(a);
                    });
                else if (A(a) && 0 !== a)
                    for (d = 0; 3 > d; d++)
                        (v[d] += p(255 * a)),
                            0 > v[d] && (v[d] = 0),
                            255 < v[d] && (v[d] = 255);
                return this;
            },
            setOpacity: function (a) {
                this.rgba[3] = a;
                return this;
            },
        };
        a.color = function (d) {
            return new a.Color(d);
        };
    })(M);
    (function (a) {
        var E,
            A,
            F = a.addEvent,
            H = a.animate,
            p = a.attr,
            d = a.charts,
            g = a.color,
            v = a.css,
            l = a.createElement,
            r = a.defined,
            f = a.deg2rad,
            b = a.destroyObjectProperties,
            n = a.doc,
            w = a.each,
            t = a.extend,
            k = a.erase,
            e = a.grep,
            h = a.hasTouch,
            C = a.isArray,
            u = a.isFirefox,
            c = a.isMS,
            q = a.isObject,
            x = a.isString,
            K = a.isWebKit,
            I = a.merge,
            J = a.noop,
            D = a.pick,
            G = a.pInt,
            L = a.removeEvent,
            N = a.stop,
            m = a.svg,
            z = a.SVG_NS,
            O = a.symbolSizes,
            P = a.win;
        E = a.SVGElement = function () {
            return this;
        };
        E.prototype = {
            opacity: 1,
            SVG_NS: z,
            textProps:
                "direction fontSize fontWeight fontFamily fontStyle color lineHeight width textDecoration textOverflow textOutline".split(
                    " "
                ),
            init: function (a, B) {
                this.element =
                    "span" === B ? l(B) : n.createElementNS(this.SVG_NS, B);
                this.renderer = a;
            },
            animate: function (a, B, c) {
                (B = D(B, this.renderer.globalAnimation, !0))
                    ? (c && (B.complete = c), H(this, a, B))
                    : this.attr(a, null, c);
                return this;
            },
            colorGradient: function (y, B, c) {
                var m = this.renderer,
                    b,
                    e,
                    z,
                    q,
                    k,
                    Q,
                    h,
                    f,
                    x,
                    n,
                    t,
                    u = [],
                    D;
                y.linearGradient
                    ? (e = "linearGradient")
                    : y.radialGradient && (e = "radialGradient");
                if (e) {
                    z = y[e];
                    k = m.gradients;
                    h = y.stops;
                    n = c.radialReference;
                    C(z) &&
                        (y[e] = z =
                            {
                                x1: z[0],
                                y1: z[1],
                                x2: z[2],
                                y2: z[3],
                                gradientUnits: "userSpaceOnUse",
                            });
                    "radialGradient" === e &&
                        n &&
                        !r(z.gradientUnits) &&
                        ((q = z),
                        (z = I(z, m.getRadialAttr(n, q), {
                            gradientUnits: "userSpaceOnUse",
                        })));
                    for (t in z) "id" !== t && u.push(t, z[t]);
                    for (t in h) u.push(h[t]);
                    u = u.join(",");
                    k[u]
                        ? (n = k[u].attr("id"))
                        : ((z.id = n = a.uniqueKey()),
                          (k[u] = Q = m.createElement(e).attr(z).add(m.defs)),
                          (Q.radAttr = q),
                          (Q.stops = []),
                          w(h, function (y) {
                              0 === y[1].indexOf("rgba")
                                  ? ((b = a.color(y[1])),
                                    (f = b.get("rgb")),
                                    (x = b.get("a")))
                                  : ((f = y[1]), (x = 1));
                              y = m
                                  .createElement("stop")
                                  .attr({
                                      offset: y[0],
                                      "stop-color": f,
                                      "stop-opacity": x,
                                  })
                                  .add(Q);
                              Q.stops.push(y);
                          }));
                    D = "url(" + m.url + "#" + n + ")";
                    c.setAttribute(B, D);
                    c.gradient = u;
                    y.toString = function () {
                        return D;
                    };
                }
            },
            applyTextOutline: function (a) {
                var y = this.element,
                    c,
                    m,
                    b;
                -1 !== a.indexOf("contrast") &&
                    (a = a.replace(
                        /contrast/g,
                        this.renderer.getContrast(y.style.fill)
                    ));
                this.fakeTS = !0;
                this.ySetter = this.xSetter;
                c = [].slice.call(y.getElementsByTagName("tspan"));
                a = a.split(" ");
                m = a[a.length - 1];
                (b = a[0]) &&
                    "none" !== b &&
                    ((b = b.replace(/(^[\d\.]+)(.*?)$/g, function (a, y, B) {
                        return 2 * y + B;
                    })),
                    w(c, function (a) {
                        "highcharts-text-outline" === a.getAttribute("class") &&
                            k(c, y.removeChild(a));
                    }),
                    w(c, function (a, B) {
                        0 === B &&
                            (a.setAttribute("x", y.getAttribute("x")),
                            (B = y.getAttribute("y")),
                            a.setAttribute("y", B || 0),
                            null === B && y.setAttribute("y", 0));
                        a = a.cloneNode(1);
                        p(a, {
                            class: "highcharts-text-outline",
                            fill: m,
                            stroke: m,
                            "stroke-width": b,
                            "stroke-linejoin": "round",
                        });
                        y.insertBefore(a, y.firstChild);
                    }));
            },
            attr: function (a, B, c, m) {
                var y,
                    b = this.element,
                    e,
                    z = this,
                    q;
                "string" === typeof a &&
                    void 0 !== B &&
                    ((y = a), (a = {}), (a[y] = B));
                if ("string" === typeof a)
                    z = (this[a + "Getter"] || this._defaultGetter).call(
                        this,
                        a,
                        b
                    );
                else {
                    for (y in a)
                        (B = a[y]),
                            (q = !1),
                            m || N(this, y),
                            this.symbolName &&
                                /^(x|y|width|height|r|start|end|innerR|anchorX|anchorY)/.test(
                                    y
                                ) &&
                                (e || (this.symbolAttr(a), (e = !0)), (q = !0)),
                            !this.rotation ||
                                ("x" !== y && "y" !== y) ||
                                (this.doTransform = !0),
                            q ||
                                ((q =
                                    this[y + "Setter"] || this._defaultSetter),
                                q.call(this, B, y, b),
                                this.shadows &&
                                    /^(width|height|visibility|x|y|d|transform|cx|cy|r)$/.test(
                                        y
                                    ) &&
                                    this.updateShadows(y, B, q));
                    this.doTransform &&
                        (this.updateTransform(), (this.doTransform = !1));
                }
                c && c();
                return z;
            },
            updateShadows: function (a, B, c) {
                for (var y = this.shadows, m = y.length; m--; )
                    c.call(
                        y[m],
                        "height" === a
                            ? Math.max(B - (y[m].cutHeight || 0), 0)
                            : "d" === a
                            ? this.d
                            : B,
                        a,
                        y[m]
                    );
            },
            addClass: function (a, B) {
                var y = this.attr("class") || "";
                -1 === y.indexOf(a) &&
                    (B || (a = (y + (y ? " " : "") + a).replace("  ", " ")),
                    this.attr("class", a));
                return this;
            },
            hasClass: function (a) {
                return -1 !== p(this.element, "class").indexOf(a);
            },
            removeClass: function (a) {
                p(
                    this.element,
                    "class",
                    (p(this.element, "class") || "").replace(a, "")
                );
                return this;
            },
            symbolAttr: function (a) {
                var y = this;
                w(
                    "x y r start end width height innerR anchorX anchorY".split(
                        " "
                    ),
                    function (B) {
                        y[B] = D(a[B], y[B]);
                    }
                );
                y.attr({
                    d: y.renderer.symbols[y.symbolName](
                        y.x,
                        y.y,
                        y.width,
                        y.height,
                        y
                    ),
                });
            },
            clip: function (a) {
                return this.attr(
                    "clip-path",
                    a ? "url(" + this.renderer.url + "#" + a.id + ")" : "none"
                );
            },
            crisp: function (a, B) {
                var y,
                    c = {},
                    m;
                B = B || a.strokeWidth || 0;
                m = (Math.round(B) % 2) / 2;
                a.x = Math.floor(a.x || this.x || 0) + m;
                a.y = Math.floor(a.y || this.y || 0) + m;
                a.width = Math.floor((a.width || this.width || 0) - 2 * m);
                a.height = Math.floor((a.height || this.height || 0) - 2 * m);
                r(a.strokeWidth) && (a.strokeWidth = B);
                for (y in a) this[y] !== a[y] && (this[y] = c[y] = a[y]);
                return c;
            },
            css: function (a) {
                var y = this.styles,
                    b = {},
                    e = this.element,
                    z,
                    q,
                    k = "";
                z = !y;
                a && a.color && (a.fill = a.color);
                if (y) for (q in a) a[q] !== y[q] && ((b[q] = a[q]), (z = !0));
                if (z) {
                    z = this.textWidth =
                        (a &&
                            a.width &&
                            "text" === e.nodeName.toLowerCase() &&
                            G(a.width)) ||
                        this.textWidth;
                    y && (a = t(y, b));
                    this.styles = a;
                    z && !m && this.renderer.forExport && delete a.width;
                    if (c && !m) v(this.element, a);
                    else {
                        y = function (a, y) {
                            return "-" + y.toLowerCase();
                        };
                        for (q in a)
                            k += q.replace(/([A-Z])/g, y) + ":" + a[q] + ";";
                        p(e, "style", k);
                    }
                    this.added &&
                        (z && this.renderer.buildText(this),
                        a &&
                            a.textOutline &&
                            this.applyTextOutline(a.textOutline));
                }
                return this;
            },
            strokeWidth: function () {
                return this["stroke-width"] || 0;
            },
            on: function (a, B) {
                var y = this,
                    c = y.element;
                h && "click" === a
                    ? ((c.ontouchstart = function (a) {
                          y.touchEventFired = Date.now();
                          a.preventDefault();
                          B.call(c, a);
                      }),
                      (c.onclick = function (a) {
                          (-1 === P.navigator.userAgent.indexOf("Android") ||
                              1100 < Date.now() - (y.touchEventFired || 0)) &&
                              B.call(c, a);
                      }))
                    : (c["on" + a] = B);
                return this;
            },
            setRadialReference: function (a) {
                var y = this.renderer.gradients[this.element.gradient];
                this.element.radialReference = a;
                y &&
                    y.radAttr &&
                    y.animate(this.renderer.getRadialAttr(a, y.radAttr));
                return this;
            },
            translate: function (a, B) {
                return this.attr({ translateX: a, translateY: B });
            },
            invert: function (a) {
                this.inverted = a;
                this.updateTransform();
                return this;
            },
            updateTransform: function () {
                var a = this.translateX || 0,
                    B = this.translateY || 0,
                    c = this.scaleX,
                    m = this.scaleY,
                    b = this.inverted,
                    e = this.rotation,
                    z = this.element;
                b && ((a += this.attr("width")), (B += this.attr("height")));
                a = ["translate(" + a + "," + B + ")"];
                b
                    ? a.push("rotate(90) scale(-1,1)")
                    : e &&
                      a.push(
                          "rotate(" +
                              e +
                              " " +
                              (z.getAttribute("x") || 0) +
                              " " +
                              (z.getAttribute("y") || 0) +
                              ")"
                      );
                (r(c) || r(m)) &&
                    a.push("scale(" + D(c, 1) + " " + D(m, 1) + ")");
                a.length && z.setAttribute("transform", a.join(" "));
            },
            toFront: function () {
                var a = this.element;
                a.parentNode.appendChild(a);
                return this;
            },
            align: function (a, B, c) {
                var y,
                    m,
                    b,
                    e,
                    z = {};
                m = this.renderer;
                b = m.alignedObjects;
                var q, h;
                if (a) {
                    if (
                        ((this.alignOptions = a),
                        (this.alignByTranslate = B),
                        !c || x(c))
                    )
                        (this.alignTo = y = c || "renderer"),
                            k(b, this),
                            b.push(this),
                            (c = null);
                } else
                    (a = this.alignOptions),
                        (B = this.alignByTranslate),
                        (y = this.alignTo);
                c = D(c, m[y], m);
                y = a.align;
                m = a.verticalAlign;
                b = (c.x || 0) + (a.x || 0);
                e = (c.y || 0) + (a.y || 0);
                "right" === y ? (q = 1) : "center" === y && (q = 2);
                q && (b += (c.width - (a.width || 0)) / q);
                z[B ? "translateX" : "x"] = Math.round(b);
                "bottom" === m ? (h = 1) : "middle" === m && (h = 2);
                h && (e += (c.height - (a.height || 0)) / h);
                z[B ? "translateY" : "y"] = Math.round(e);
                this[this.placed ? "animate" : "attr"](z);
                this.placed = !0;
                this.alignAttr = z;
                return this;
            },
            getBBox: function (a, B) {
                var y,
                    m = this.renderer,
                    b,
                    e = this.element,
                    z = this.styles,
                    q,
                    k = this.textStr,
                    h,
                    x = m.cache,
                    n = m.cacheKeys,
                    u;
                B = D(B, this.rotation);
                b = B * f;
                q = z && z.fontSize;
                void 0 !== k &&
                    ((u = k.toString()),
                    -1 === u.indexOf("\x3c") && (u = u.replace(/[0-9]/g, "0")),
                    (u += [
                        "",
                        B || 0,
                        q,
                        e.style.width,
                        e.style["text-overflow"],
                    ].join()));
                u && !a && (y = x[u]);
                if (!y) {
                    if (e.namespaceURI === this.SVG_NS || m.forExport) {
                        try {
                            (h =
                                this.fakeTS &&
                                function (a) {
                                    w(
                                        e.querySelectorAll(
                                            ".highcharts-text-outline"
                                        ),
                                        function (y) {
                                            y.style.display = a;
                                        }
                                    );
                                }) && h("none"),
                                (y = e.getBBox
                                    ? t({}, e.getBBox())
                                    : {
                                          width: e.offsetWidth,
                                          height: e.offsetHeight,
                                      }),
                                h && h("");
                        } catch (T) {}
                        if (!y || 0 > y.width) y = { width: 0, height: 0 };
                    } else y = this.htmlGetBBox();
                    m.isSVG &&
                        ((a = y.width),
                        (m = y.height),
                        c &&
                            z &&
                            "11px" === z.fontSize &&
                            "16.9" === m.toPrecision(3) &&
                            (y.height = m = 14),
                        B &&
                            ((y.width =
                                Math.abs(m * Math.sin(b)) +
                                Math.abs(a * Math.cos(b))),
                            (y.height =
                                Math.abs(m * Math.cos(b)) +
                                Math.abs(a * Math.sin(b)))));
                    if (u && 0 < y.height) {
                        for (; 250 < n.length; ) delete x[n.shift()];
                        x[u] || n.push(u);
                        x[u] = y;
                    }
                }
                return y;
            },
            show: function (a) {
                return this.attr({ visibility: a ? "inherit" : "visible" });
            },
            hide: function () {
                return this.attr({ visibility: "hidden" });
            },
            fadeOut: function (a) {
                var y = this;
                y.animate(
                    { opacity: 0 },
                    {
                        duration: a || 150,
                        complete: function () {
                            y.attr({ y: -9999 });
                        },
                    }
                );
            },
            add: function (a) {
                var y = this.renderer,
                    c = this.element,
                    m;
                a && (this.parentGroup = a);
                this.parentInverted = a && a.inverted;
                void 0 !== this.textStr && y.buildText(this);
                this.added = !0;
                if (!a || a.handleZ || this.zIndex) m = this.zIndexSetter();
                m || (a ? a.element : y.box).appendChild(c);
                if (this.onAdd) this.onAdd();
                return this;
            },
            safeRemoveChild: function (a) {
                var y = a.parentNode;
                y && y.removeChild(a);
            },
            destroy: function () {
                var a = this.element || {},
                    c =
                        this.renderer.isSVG &&
                        "SPAN" === a.nodeName &&
                        this.parentGroup,
                    m,
                    b;
                a.onclick =
                    a.onmouseout =
                    a.onmouseover =
                    a.onmousemove =
                    a.point =
                        null;
                N(this);
                this.clipPath && (this.clipPath = this.clipPath.destroy());
                if (this.stops) {
                    for (b = 0; b < this.stops.length; b++)
                        this.stops[b] = this.stops[b].destroy();
                    this.stops = null;
                }
                this.safeRemoveChild(a);
                for (
                    this.destroyShadows();
                    c && c.div && 0 === c.div.childNodes.length;

                )
                    (a = c.parentGroup),
                        this.safeRemoveChild(c.div),
                        delete c.div,
                        (c = a);
                this.alignTo && k(this.renderer.alignedObjects, this);
                for (m in this) delete this[m];
                return null;
            },
            shadow: function (a, c, m) {
                var y = [],
                    B,
                    b,
                    e = this.element,
                    z,
                    q,
                    k,
                    h;
                if (!a) this.destroyShadows();
                else if (!this.shadows) {
                    q = D(a.width, 3);
                    k = (a.opacity || 0.15) / q;
                    h = this.parentInverted
                        ? "(-1,-1)"
                        : "(" + D(a.offsetX, 1) + ", " + D(a.offsetY, 1) + ")";
                    for (B = 1; B <= q; B++)
                        (b = e.cloneNode(0)),
                            (z = 2 * q + 1 - 2 * B),
                            p(b, {
                                isShadow: "true",
                                stroke: a.color || "#000000",
                                "stroke-opacity": k * B,
                                "stroke-width": z,
                                transform: "translate" + h,
                                fill: "none",
                            }),
                            m &&
                                (p(
                                    b,
                                    "height",
                                    Math.max(p(b, "height") - z, 0)
                                ),
                                (b.cutHeight = z)),
                            c
                                ? c.element.appendChild(b)
                                : e.parentNode.insertBefore(b, e),
                            y.push(b);
                    this.shadows = y;
                }
                return this;
            },
            destroyShadows: function () {
                w(
                    this.shadows || [],
                    function (a) {
                        this.safeRemoveChild(a);
                    },
                    this
                );
                this.shadows = void 0;
            },
            xGetter: function (a) {
                "circle" === this.element.nodeName &&
                    ("x" === a ? (a = "cx") : "y" === a && (a = "cy"));
                return this._defaultGetter(a);
            },
            _defaultGetter: function (a) {
                a = D(
                    this[a],
                    this.element ? this.element.getAttribute(a) : null,
                    0
                );
                /^[\-0-9\.]+$/.test(a) && (a = parseFloat(a));
                return a;
            },
            dSetter: function (a, c, m) {
                a && a.join && (a = a.join(" "));
                /(NaN| {2}|^$)/.test(a) && (a = "M 0 0");
                m.setAttribute(c, a);
                this[c] = a;
            },
            dashstyleSetter: function (a) {
                var c,
                    y = this["stroke-width"];
                "inherit" === y && (y = 1);
                if ((a = a && a.toLowerCase())) {
                    a = a
                        .replace("shortdashdotdot", "3,1,1,1,1,1,")
                        .replace("shortdashdot", "3,1,1,1")
                        .replace("shortdot", "1,1,")
                        .replace("shortdash", "3,1,")
                        .replace("longdash", "8,3,")
                        .replace(/dot/g, "1,3,")
                        .replace("dash", "4,3,")
                        .replace(/,$/, "")
                        .split(",");
                    for (c = a.length; c--; ) a[c] = G(a[c]) * y;
                    a = a.join(",").replace(/NaN/g, "none");
                    this.element.setAttribute("stroke-dasharray", a);
                }
            },
            alignSetter: function (a) {
                this.element.setAttribute(
                    "text-anchor",
                    { left: "start", center: "middle", right: "end" }[a]
                );
            },
            opacitySetter: function (a, c, m) {
                this[c] = a;
                m.setAttribute(c, a);
            },
            titleSetter: function (a) {
                var c = this.element.getElementsByTagName("title")[0];
                c ||
                    ((c = n.createElementNS(this.SVG_NS, "title")),
                    this.element.appendChild(c));
                c.firstChild && c.removeChild(c.firstChild);
                c.appendChild(
                    n.createTextNode(String(D(a), "").replace(/<[^>]*>/g, ""))
                );
            },
            textSetter: function (a) {
                a !== this.textStr &&
                    (delete this.bBox,
                    (this.textStr = a),
                    this.added && this.renderer.buildText(this));
            },
            fillSetter: function (a, c, m) {
                "string" === typeof a
                    ? m.setAttribute(c, a)
                    : a && this.colorGradient(a, c, m);
            },
            visibilitySetter: function (a, c, m) {
                "inherit" === a ? m.removeAttribute(c) : m.setAttribute(c, a);
            },
            zIndexSetter: function (a, c) {
                var m = this.renderer,
                    y = this.parentGroup,
                    b = (y || m).element || m.box,
                    B,
                    e = this.element,
                    z;
                B = this.added;
                var q;
                r(a) &&
                    ((e.zIndex = a),
                    (a = +a),
                    this[c] === a && (B = !1),
                    (this[c] = a));
                if (B) {
                    (a = this.zIndex) && y && (y.handleZ = !0);
                    c = b.childNodes;
                    for (q = 0; q < c.length && !z; q++)
                        (y = c[q]),
                            (B = y.zIndex),
                            y !== e &&
                                (G(B) > a ||
                                    (!r(a) && r(B)) ||
                                    (0 > a && !r(B) && b !== m.box)) &&
                                (b.insertBefore(e, y), (z = !0));
                    z || b.appendChild(e);
                }
                return z;
            },
            _defaultSetter: function (a, c, m) {
                m.setAttribute(c, a);
            },
        };
        E.prototype.yGetter = E.prototype.xGetter;
        E.prototype.translateXSetter =
            E.prototype.translateYSetter =
            E.prototype.rotationSetter =
            E.prototype.verticalAlignSetter =
            E.prototype.scaleXSetter =
            E.prototype.scaleYSetter =
                function (a, c) {
                    this[c] = a;
                    this.doTransform = !0;
                };
        E.prototype["stroke-widthSetter"] = E.prototype.strokeSetter =
            function (a, c, m) {
                this[c] = a;
                this.stroke && this["stroke-width"]
                    ? (E.prototype.fillSetter.call(
                          this,
                          this.stroke,
                          "stroke",
                          m
                      ),
                      m.setAttribute("stroke-width", this["stroke-width"]),
                      (this.hasStroke = !0))
                    : "stroke-width" === c &&
                      0 === a &&
                      this.hasStroke &&
                      (m.removeAttribute("stroke"), (this.hasStroke = !1));
            };
        A = a.SVGRenderer = function () {
            this.init.apply(this, arguments);
        };
        A.prototype = {
            Element: E,
            SVG_NS: z,
            init: function (a, c, m, b, e, z) {
                var y;
                b = this.createElement("svg")
                    .attr({ version: "1.1", class: "highcharts-root" })
                    .css(this.getStyle(b));
                y = b.element;
                a.appendChild(y);
                -1 === a.innerHTML.indexOf("xmlns") &&
                    p(y, "xmlns", this.SVG_NS);
                this.isSVG = !0;
                this.box = y;
                this.boxWrapper = b;
                this.alignedObjects = [];
                this.url =
                    (u || K) && n.getElementsByTagName("base").length
                        ? P.location.href
                              .replace(/#.*?$/, "")
                              .replace(/([\('\)])/g, "\\$1")
                              .replace(/ /g, "%20")
                        : "";
                this.createElement("desc")
                    .add()
                    .element.appendChild(
                        n.createTextNode("Created with Highcharts 5.0.5")
                    );
                this.defs = this.createElement("defs").add();
                this.allowHTML = z;
                this.forExport = e;
                this.gradients = {};
                this.cache = {};
                this.cacheKeys = [];
                this.imgCount = 0;
                this.setSize(c, m, !1);
                var B;
                u &&
                    a.getBoundingClientRect &&
                    ((c = function () {
                        v(a, { left: 0, top: 0 });
                        B = a.getBoundingClientRect();
                        v(a, {
                            left: Math.ceil(B.left) - B.left + "px",
                            top: Math.ceil(B.top) - B.top + "px",
                        });
                    }),
                    c(),
                    (this.unSubPixelFix = F(P, "resize", c)));
            },
            getStyle: function (a) {
                return (this.style = t(
                    {
                        fontFamily:
                            '"Lucida Grande", "Lucida Sans Unicode", Arial, Helvetica, sans-serif',
                        fontSize: "12px",
                    },
                    a
                ));
            },
            setStyle: function (a) {
                this.boxWrapper.css(this.getStyle(a));
            },
            isHidden: function () {
                return !this.boxWrapper.getBBox().width;
            },
            destroy: function () {
                var a = this.defs;
                this.box = null;
                this.boxWrapper = this.boxWrapper.destroy();
                b(this.gradients || {});
                this.gradients = null;
                a && (this.defs = a.destroy());
                this.unSubPixelFix && this.unSubPixelFix();
                return (this.alignedObjects = null);
            },
            createElement: function (a) {
                var c = new this.Element();
                c.init(this, a);
                return c;
            },
            draw: J,
            getRadialAttr: function (a, c) {
                return {
                    cx: a[0] - a[2] / 2 + c.cx * a[2],
                    cy: a[1] - a[2] / 2 + c.cy * a[2],
                    r: c.r * a[2],
                };
            },
            buildText: function (a) {
                for (
                    var c = a.element,
                        b = this,
                        y = b.forExport,
                        q = D(a.textStr, "").toString(),
                        k = -1 !== q.indexOf("\x3c"),
                        h = c.childNodes,
                        x,
                        f,
                        t,
                        u,
                        l = p(c, "x"),
                        d = a.styles,
                        C = a.textWidth,
                        g = d && d.lineHeight,
                        r = d && d.textOutline,
                        K = d && "ellipsis" === d.textOverflow,
                        I = h.length,
                        L = C && !a.added && this.box,
                        P = function (a) {
                            var m;
                            m = /(px|em)$/.test(a && a.style.fontSize)
                                ? a.style.fontSize
                                : (d && d.fontSize) || b.style.fontSize || 12;
                            return g
                                ? G(g)
                                : b.fontMetrics(
                                      m,
                                      a.getAttribute("style") ? a : c
                                  ).h;
                        };
                    I--;

                )
                    c.removeChild(h[I]);
                k || r || K || C || -1 !== q.indexOf(" ")
                    ? ((x = /<.*class="([^"]+)".*>/),
                      (f = /<.*style="([^"]+)".*>/),
                      (t = /<.*href="(http[^"]+)".*>/),
                      L && L.appendChild(c),
                      (q = k
                          ? q
                                .replace(
                                    /<(b|strong)>/g,
                                    '\x3cspan style\x3d"font-weight:bold"\x3e'
                                )
                                .replace(
                                    /<(i|em)>/g,
                                    '\x3cspan style\x3d"font-style:italic"\x3e'
                                )
                                .replace(/<a/g, "\x3cspan")
                                .replace(
                                    /<\/(b|strong|i|em|a)>/g,
                                    "\x3c/span\x3e"
                                )
                                .split(/<br.*?>/g)
                          : [q]),
                      (q = e(q, function (a) {
                          return "" !== a;
                      })),
                      w(q, function (e, B) {
                          var q,
                              k = 0;
                          e = e
                              .replace(/^\s+|\s+$/g, "")
                              .replace(/<span/g, "|||\x3cspan")
                              .replace(/<\/span>/g, "\x3c/span\x3e|||");
                          q = e.split("|||");
                          w(q, function (e) {
                              if ("" !== e || 1 === q.length) {
                                  var h = {},
                                      D = n.createElementNS(b.SVG_NS, "tspan"),
                                      G,
                                      g;
                                  x.test(e) &&
                                      ((G = e.match(x)[1]), p(D, "class", G));
                                  f.test(e) &&
                                      ((g = e
                                          .match(f)[1]
                                          .replace(
                                              /(;| |^)color([ :])/,
                                              "$1fill$2"
                                          )),
                                      p(D, "style", g));
                                  t.test(e) &&
                                      !y &&
                                      (p(
                                          D,
                                          "onclick",
                                          'location.href\x3d"' +
                                              e.match(t)[1] +
                                              '"'
                                      ),
                                      v(D, { cursor: "pointer" }));
                                  e = (e.replace(/<(.|\n)*?>/g, "") || " ")
                                      .replace(/&lt;/g, "\x3c")
                                      .replace(/&gt;/g, "\x3e");
                                  if (" " !== e) {
                                      D.appendChild(n.createTextNode(e));
                                      k
                                          ? (h.dx = 0)
                                          : B && null !== l && (h.x = l);
                                      p(D, h);
                                      c.appendChild(D);
                                      !k &&
                                          B &&
                                          (!m &&
                                              y &&
                                              v(D, { display: "block" }),
                                          p(D, "dy", P(D)));
                                      if (C) {
                                          h = e
                                              .replace(/([^\^])-/g, "$1- ")
                                              .split(" ");
                                          G = "nowrap" === d.whiteSpace;
                                          for (
                                              var Q =
                                                      1 < q.length ||
                                                      B ||
                                                      (1 < h.length && !G),
                                                  r,
                                                  I,
                                                  w = [],
                                                  L = P(D),
                                                  S = a.rotation,
                                                  O = e,
                                                  R = O.length;
                                              (Q || K) &&
                                              (h.length || w.length);

                                          )
                                              (a.rotation = 0),
                                                  (r = a.getBBox(!0)),
                                                  (I = r.width),
                                                  !m &&
                                                      b.forExport &&
                                                      (I = b.measureSpanWidth(
                                                          D.firstChild.data,
                                                          a.styles
                                                      )),
                                                  (r = I > C),
                                                  void 0 === u && (u = r),
                                                  K && u
                                                      ? ((R /= 2),
                                                        "" === O ||
                                                        (!r && 0.5 > R)
                                                            ? (h = [])
                                                            : ((O = e.substring(
                                                                  0,
                                                                  O.length +
                                                                      (r
                                                                          ? -1
                                                                          : 1) *
                                                                          Math.ceil(
                                                                              R
                                                                          )
                                                              )),
                                                              (h = [
                                                                  O +
                                                                      (3 < C
                                                                          ? "\u2026"
                                                                          : ""),
                                                              ]),
                                                              D.removeChild(
                                                                  D.firstChild
                                                              )))
                                                      : r && 1 !== h.length
                                                      ? (D.removeChild(
                                                            D.firstChild
                                                        ),
                                                        w.unshift(h.pop()))
                                                      : ((h = w),
                                                        (w = []),
                                                        h.length &&
                                                            !G &&
                                                            ((D =
                                                                n.createElementNS(
                                                                    z,
                                                                    "tspan"
                                                                )),
                                                            p(D, {
                                                                dy: L,
                                                                x: l,
                                                            }),
                                                            g &&
                                                                p(
                                                                    D,
                                                                    "style",
                                                                    g
                                                                ),
                                                            c.appendChild(D)),
                                                        I > C && (C = I)),
                                                  h.length &&
                                                      D.appendChild(
                                                          n.createTextNode(
                                                              h
                                                                  .join(" ")
                                                                  .replace(
                                                                      /- /g,
                                                                      "-"
                                                                  )
                                                          )
                                                      );
                                          a.rotation = S;
                                      }
                                      k++;
                                  }
                              }
                          });
                      }),
                      u && a.attr("title", a.textStr),
                      L && L.removeChild(c),
                      r && a.applyTextOutline && a.applyTextOutline(r))
                    : c.appendChild(
                          n.createTextNode(
                              q
                                  .replace(/&lt;/g, "\x3c")
                                  .replace(/&gt;/g, "\x3e")
                          )
                      );
            },
            getContrast: function (a) {
                a = g(a).rgba;
                return 510 < a[0] + a[1] + a[2] ? "#000000" : "#FFFFFF";
            },
            button: function (a, m, b, e, z, q, h, k, x) {
                var B = this.label(
                        a,
                        m,
                        b,
                        x,
                        null,
                        null,
                        null,
                        null,
                        "button"
                    ),
                    y = 0;
                B.attr(I({ padding: 8, r: 2 }, z));
                var f, n, u, D;
                z = I(
                    {
                        fill: "#f7f7f7",
                        stroke: "#cccccc",
                        "stroke-width": 1,
                        style: {
                            color: "#333333",
                            cursor: "pointer",
                            fontWeight: "normal",
                        },
                    },
                    z
                );
                f = z.style;
                delete z.style;
                q = I(z, { fill: "#e6e6e6" }, q);
                n = q.style;
                delete q.style;
                h = I(
                    z,
                    {
                        fill: "#e6ebf5",
                        style: { color: "#000000", fontWeight: "bold" },
                    },
                    h
                );
                u = h.style;
                delete h.style;
                k = I(z, { style: { color: "#cccccc" } }, k);
                D = k.style;
                delete k.style;
                F(B.element, c ? "mouseover" : "mouseenter", function () {
                    3 !== y && B.setState(1);
                });
                F(B.element, c ? "mouseout" : "mouseleave", function () {
                    3 !== y && B.setState(y);
                });
                B.setState = function (a) {
                    1 !== a && (B.state = y = a);
                    B.removeClass(
                        /highcharts-button-(normal|hover|pressed|disabled)/
                    ).addClass(
                        "highcharts-button-" +
                            ["normal", "hover", "pressed", "disabled"][a || 0]
                    );
                    B.attr([z, q, h, k][a || 0]).css([f, n, u, D][a || 0]);
                };
                B.attr(z).css(t({ cursor: "default" }, f));
                return B.on("click", function (a) {
                    3 !== y && e.call(B, a);
                });
            },
            crispLine: function (a, c) {
                a[1] === a[4] && (a[1] = a[4] = Math.round(a[1]) - (c % 2) / 2);
                a[2] === a[5] && (a[2] = a[5] = Math.round(a[2]) + (c % 2) / 2);
                return a;
            },
            path: function (a) {
                var c = { fill: "none" };
                C(a) ? (c.d = a) : q(a) && t(c, a);
                return this.createElement("path").attr(c);
            },
            circle: function (a, c, m) {
                a = q(a) ? a : { x: a, y: c, r: m };
                c = this.createElement("circle");
                c.xSetter = c.ySetter = function (a, c, m) {
                    m.setAttribute("c" + c, a);
                };
                return c.attr(a);
            },
            arc: function (a, c, m, b, e, z) {
                q(a) &&
                    ((c = a.y),
                    (m = a.r),
                    (b = a.innerR),
                    (e = a.start),
                    (z = a.end),
                    (a = a.x));
                a = this.symbol("arc", a || 0, c || 0, m || 0, m || 0, {
                    innerR: b || 0,
                    start: e || 0,
                    end: z || 0,
                });
                a.r = m;
                return a;
            },
            rect: function (a, c, m, b, e, z) {
                e = q(a) ? a.r : e;
                var B = this.createElement("rect");
                a = q(a)
                    ? a
                    : void 0 === a
                    ? {}
                    : {
                          x: a,
                          y: c,
                          width: Math.max(m, 0),
                          height: Math.max(b, 0),
                      };
                void 0 !== z && ((a.strokeWidth = z), (a = B.crisp(a)));
                a.fill = "none";
                e && (a.r = e);
                B.rSetter = function (a, c, m) {
                    p(m, { rx: a, ry: a });
                };
                return B.attr(a);
            },
            setSize: function (a, c, m) {
                var b = this.alignedObjects,
                    e = b.length;
                this.width = a;
                this.height = c;
                for (
                    this.boxWrapper.animate(
                        { width: a, height: c },
                        {
                            step: function () {
                                this.attr({
                                    viewBox:
                                        "0 0 " +
                                        this.attr("width") +
                                        " " +
                                        this.attr("height"),
                                });
                            },
                            duration: D(m, !0) ? void 0 : 0,
                        }
                    );
                    e--;

                )
                    b[e].align();
            },
            g: function (a) {
                var c = this.createElement("g");
                return a ? c.attr({ class: "highcharts-" + a }) : c;
            },
            image: function (a, c, m, b, e) {
                var z = { preserveAspectRatio: "none" };
                1 < arguments.length &&
                    t(z, { x: c, y: m, width: b, height: e });
                z = this.createElement("image").attr(z);
                z.element.setAttributeNS
                    ? z.element.setAttributeNS(
                          "http://www.w3.org/1999/xlink",
                          "href",
                          a
                      )
                    : z.element.setAttribute("hc-svg-href", a);
                return z;
            },
            symbol: function (a, c, m, b, e, z) {
                var q = this,
                    B,
                    y = this.symbols[a],
                    h = r(c) && y && y(Math.round(c), Math.round(m), b, e, z),
                    k = /^url\((.*?)\)$/,
                    x,
                    f;
                y
                    ? ((B = this.path(h)),
                      B.attr("fill", "none"),
                      t(B, { symbolName: a, x: c, y: m, width: b, height: e }),
                      z && t(B, z))
                    : k.test(a) &&
                      ((x = a.match(k)[1]),
                      (B = this.image(x)),
                      (B.imgwidth = D(O[x] && O[x].width, z && z.width)),
                      (B.imgheight = D(O[x] && O[x].height, z && z.height)),
                      (f = function () {
                          B.attr({ width: B.width, height: B.height });
                      }),
                      w(["width", "height"], function (a) {
                          B[a + "Setter"] = function (a, c) {
                              var m = {},
                                  b = this["img" + c],
                                  e =
                                      "width" === c
                                          ? "translateX"
                                          : "translateY";
                              this[c] = a;
                              r(b) &&
                                  (this.element &&
                                      this.element.setAttribute(c, b),
                                  this.alignByTranslate ||
                                      ((m[e] = ((this[c] || 0) - b) / 2),
                                      this.attr(m)));
                          };
                      }),
                      r(c) && B.attr({ x: c, y: m }),
                      (B.isImg = !0),
                      r(B.imgwidth) && r(B.imgheight)
                          ? f()
                          : (B.attr({ width: 0, height: 0 }),
                            l("img", {
                                onload: function () {
                                    var a = d[q.chartIndex];
                                    0 === this.width &&
                                        (v(this, {
                                            position: "absolute",
                                            top: "-999em",
                                        }),
                                        n.body.appendChild(this));
                                    O[x] = {
                                        width: this.width,
                                        height: this.height,
                                    };
                                    B.imgwidth = this.width;
                                    B.imgheight = this.height;
                                    B.element && f();
                                    this.parentNode &&
                                        this.parentNode.removeChild(this);
                                    q.imgCount--;
                                    if (!q.imgCount && a && a.onload)
                                        a.onload();
                                },
                                src: x,
                            }),
                            this.imgCount++));
                return B;
            },
            symbols: {
                circle: function (a, c, m, b) {
                    var e = 0.166 * m;
                    return [
                        "M",
                        a + m / 2,
                        c,
                        "C",
                        a + m + e,
                        c,
                        a + m + e,
                        c + b,
                        a + m / 2,
                        c + b,
                        "C",
                        a - e,
                        c + b,
                        a - e,
                        c,
                        a + m / 2,
                        c,
                        "Z",
                    ];
                },
                square: function (a, c, m, b) {
                    return [
                        "M",
                        a,
                        c,
                        "L",
                        a + m,
                        c,
                        a + m,
                        c + b,
                        a,
                        c + b,
                        "Z",
                    ];
                },
                triangle: function (a, c, m, b) {
                    return [
                        "M",
                        a + m / 2,
                        c,
                        "L",
                        a + m,
                        c + b,
                        a,
                        c + b,
                        "Z",
                    ];
                },
                "triangle-down": function (a, c, m, b) {
                    return ["M", a, c, "L", a + m, c, a + m / 2, c + b, "Z"];
                },
                diamond: function (a, c, m, b) {
                    return [
                        "M",
                        a + m / 2,
                        c,
                        "L",
                        a + m,
                        c + b / 2,
                        a + m / 2,
                        c + b,
                        a,
                        c + b / 2,
                        "Z",
                    ];
                },
                arc: function (a, c, m, b, e) {
                    var z = e.start;
                    m = e.r || m || b;
                    var q = e.end - 0.001;
                    b = e.innerR;
                    var B = e.open,
                        h = Math.cos(z),
                        k = Math.sin(z),
                        y = Math.cos(q),
                        q = Math.sin(q);
                    e = e.end - z < Math.PI ? 0 : 1;
                    return [
                        "M",
                        a + m * h,
                        c + m * k,
                        "A",
                        m,
                        m,
                        0,
                        e,
                        1,
                        a + m * y,
                        c + m * q,
                        B ? "M" : "L",
                        a + b * y,
                        c + b * q,
                        "A",
                        b,
                        b,
                        0,
                        e,
                        0,
                        a + b * h,
                        c + b * k,
                        B ? "" : "Z",
                    ];
                },
                callout: function (a, c, m, b, e) {
                    var z = Math.min((e && e.r) || 0, m, b),
                        q = z + 6,
                        B = e && e.anchorX;
                    e = e && e.anchorY;
                    var h;
                    h = [
                        "M",
                        a + z,
                        c,
                        "L",
                        a + m - z,
                        c,
                        "C",
                        a + m,
                        c,
                        a + m,
                        c,
                        a + m,
                        c + z,
                        "L",
                        a + m,
                        c + b - z,
                        "C",
                        a + m,
                        c + b,
                        a + m,
                        c + b,
                        a + m - z,
                        c + b,
                        "L",
                        a + z,
                        c + b,
                        "C",
                        a,
                        c + b,
                        a,
                        c + b,
                        a,
                        c + b - z,
                        "L",
                        a,
                        c + z,
                        "C",
                        a,
                        c,
                        a,
                        c,
                        a + z,
                        c,
                    ];
                    B && B > m
                        ? e > c + q && e < c + b - q
                            ? h.splice(
                                  13,
                                  3,
                                  "L",
                                  a + m,
                                  e - 6,
                                  a + m + 6,
                                  e,
                                  a + m,
                                  e + 6,
                                  a + m,
                                  c + b - z
                              )
                            : h.splice(
                                  13,
                                  3,
                                  "L",
                                  a + m,
                                  b / 2,
                                  B,
                                  e,
                                  a + m,
                                  b / 2,
                                  a + m,
                                  c + b - z
                              )
                        : B && 0 > B
                        ? e > c + q && e < c + b - q
                            ? h.splice(
                                  33,
                                  3,
                                  "L",
                                  a,
                                  e + 6,
                                  a - 6,
                                  e,
                                  a,
                                  e - 6,
                                  a,
                                  c + z
                              )
                            : h.splice(
                                  33,
                                  3,
                                  "L",
                                  a,
                                  b / 2,
                                  B,
                                  e,
                                  a,
                                  b / 2,
                                  a,
                                  c + z
                              )
                        : e && e > b && B > a + q && B < a + m - q
                        ? h.splice(
                              23,
                              3,
                              "L",
                              B + 6,
                              c + b,
                              B,
                              c + b + 6,
                              B - 6,
                              c + b,
                              a + z,
                              c + b
                          )
                        : e &&
                          0 > e &&
                          B > a + q &&
                          B < a + m - q &&
                          h.splice(
                              3,
                              3,
                              "L",
                              B - 6,
                              c,
                              B,
                              c - 6,
                              B + 6,
                              c,
                              m - z,
                              c
                          );
                    return h;
                },
            },
            clipRect: function (c, m, b, e) {
                var z = a.uniqueKey(),
                    q = this.createElement("clipPath")
                        .attr({ id: z })
                        .add(this.defs);
                c = this.rect(c, m, b, e, 0).add(q);
                c.id = z;
                c.clipPath = q;
                c.count = 0;
                return c;
            },
            text: function (a, c, b, e) {
                var z = !m && this.forExport,
                    q = {};
                if (e && (this.allowHTML || !this.forExport))
                    return this.html(a, c, b);
                q.x = Math.round(c || 0);
                b && (q.y = Math.round(b));
                if (a || 0 === a) q.text = a;
                a = this.createElement("text").attr(q);
                z && a.css({ position: "absolute" });
                e ||
                    (a.xSetter = function (a, c, m) {
                        var b = m.getElementsByTagName("tspan"),
                            e,
                            z = m.getAttribute(c),
                            q;
                        for (q = 0; q < b.length; q++)
                            (e = b[q]),
                                e.getAttribute(c) === z && e.setAttribute(c, a);
                        m.setAttribute(c, a);
                    });
                return a;
            },
            fontMetrics: function (a, c) {
                a =
                    a ||
                    (c && c.style && c.style.fontSize) ||
                    (this.style && this.style.fontSize);
                a = /px/.test(a)
                    ? G(a)
                    : /em/.test(a)
                    ? parseFloat(a) *
                      (c ? this.fontMetrics(null, c.parentNode).f : 16)
                    : 12;
                c = 24 > a ? a + 3 : Math.round(1.2 * a);
                return { h: c, b: Math.round(0.8 * c), f: a };
            },
            rotCorr: function (a, c, m) {
                var b = a;
                c && m && (b = Math.max(b * Math.cos(c * f), 4));
                return { x: (-a / 3) * Math.sin(c * f), y: b };
            },
            label: function (a, c, m, b, e, z, q, h, k) {
                var B = this,
                    x = B.g("button" !== k && "label"),
                    f = (x.text = B.text("", 0, 0, q).attr({ zIndex: 1 })),
                    n,
                    u,
                    D = 0,
                    y = 3,
                    l = 0,
                    G,
                    d,
                    C,
                    g,
                    K,
                    P = {},
                    O,
                    v,
                    N = /^url\((.*?)\)$/.test(b),
                    Q = N,
                    J,
                    p,
                    S,
                    R;
                k && x.addClass("highcharts-" + k);
                Q = N;
                J = function () {
                    return ((O || 0) % 2) / 2;
                };
                p = function () {
                    var a = f.element.style,
                        c = {};
                    u =
                        (void 0 === G || void 0 === d || K) &&
                        r(f.textStr) &&
                        f.getBBox();
                    x.width = (G || u.width || 0) + 2 * y + l;
                    x.height = (d || u.height || 0) + 2 * y;
                    v = y + B.fontMetrics(a && a.fontSize, f).b;
                    Q &&
                        (n ||
                            ((x.box = n =
                                B.symbols[b] || N ? B.symbol(b) : B.rect()),
                            n.addClass(
                                ("button" === k ? "" : "highcharts-label-box") +
                                    (k ? " highcharts-" + k + "-box" : "")
                            ),
                            n.add(x),
                            (a = J()),
                            (c.x = a),
                            (c.y = (h ? -v : 0) + a)),
                        (c.width = Math.round(x.width)),
                        (c.height = Math.round(x.height)),
                        n.attr(t(c, P)),
                        (P = {}));
                };
                S = function () {
                    var a = l + y,
                        c;
                    c = h ? 0 : v;
                    r(G) &&
                        u &&
                        ("center" === K || "right" === K) &&
                        (a += { center: 0.5, right: 1 }[K] * (G - u.width));
                    if (a !== f.x || c !== f.y)
                        f.attr("x", a), void 0 !== c && f.attr("y", c);
                    f.x = a;
                    f.y = c;
                };
                R = function (a, c) {
                    n ? n.attr(a, c) : (P[a] = c);
                };
                x.onAdd = function () {
                    f.add(x);
                    x.attr({ text: a || 0 === a ? a : "", x: c, y: m });
                    n && r(e) && x.attr({ anchorX: e, anchorY: z });
                };
                x.widthSetter = function (a) {
                    G = a;
                };
                x.heightSetter = function (a) {
                    d = a;
                };
                x["text-alignSetter"] = function (a) {
                    K = a;
                };
                x.paddingSetter = function (a) {
                    r(a) && a !== y && ((y = x.padding = a), S());
                };
                x.paddingLeftSetter = function (a) {
                    r(a) && a !== l && ((l = a), S());
                };
                x.alignSetter = function (a) {
                    a = { left: 0, center: 0.5, right: 1 }[a];
                    a !== D && ((D = a), u && x.attr({ x: C }));
                };
                x.textSetter = function (a) {
                    void 0 !== a && f.textSetter(a);
                    p();
                    S();
                };
                x["stroke-widthSetter"] = function (a, c) {
                    a && (Q = !0);
                    O = this["stroke-width"] = a;
                    R(c, a);
                };
                x.strokeSetter =
                    x.fillSetter =
                    x.rSetter =
                        function (a, c) {
                            "fill" === c && a && (Q = !0);
                            R(c, a);
                        };
                x.anchorXSetter = function (a, c) {
                    e = a;
                    R(c, Math.round(a) - J() - C);
                };
                x.anchorYSetter = function (a, c) {
                    z = a;
                    R(c, a - g);
                };
                x.xSetter = function (a) {
                    x.x = a;
                    D && (a -= D * ((G || u.width) + 2 * y));
                    C = Math.round(a);
                    x.attr("translateX", C);
                };
                x.ySetter = function (a) {
                    g = x.y = Math.round(a);
                    x.attr("translateY", g);
                };
                var V = x.css;
                return t(x, {
                    css: function (a) {
                        if (a) {
                            var c = {};
                            a = I(a);
                            w(x.textProps, function (m) {
                                void 0 !== a[m] && ((c[m] = a[m]), delete a[m]);
                            });
                            f.css(c);
                        }
                        return V.call(x, a);
                    },
                    getBBox: function () {
                        return {
                            width: u.width + 2 * y,
                            height: u.height + 2 * y,
                            x: u.x - y,
                            y: u.y - y,
                        };
                    },
                    shadow: function (a) {
                        a && (p(), n && n.shadow(a));
                        return x;
                    },
                    destroy: function () {
                        L(x.element, "mouseenter");
                        L(x.element, "mouseleave");
                        f && (f = f.destroy());
                        n && (n = n.destroy());
                        E.prototype.destroy.call(x);
                        x = B = p = S = R = null;
                    },
                });
            },
        };
        a.Renderer = A;
    })(M);
    (function (a) {
        var E = a.attr,
            A = a.createElement,
            F = a.css,
            H = a.defined,
            p = a.each,
            d = a.extend,
            g = a.isFirefox,
            v = a.isMS,
            l = a.isWebKit,
            r = a.pInt,
            f = a.SVGRenderer,
            b = a.win,
            n = a.wrap;
        d(a.SVGElement.prototype, {
            htmlCss: function (a) {
                var b = this.element;
                if ((b = a && "SPAN" === b.tagName && a.width))
                    delete a.width,
                        (this.textWidth = b),
                        this.updateTransform();
                a &&
                    "ellipsis" === a.textOverflow &&
                    ((a.whiteSpace = "nowrap"), (a.overflow = "hidden"));
                this.styles = d(this.styles, a);
                F(this.element, a);
                return this;
            },
            htmlGetBBox: function () {
                var a = this.element;
                "text" === a.nodeName && (a.style.position = "absolute");
                return {
                    x: a.offsetLeft,
                    y: a.offsetTop,
                    width: a.offsetWidth,
                    height: a.offsetHeight,
                };
            },
            htmlUpdateTransform: function () {
                if (this.added) {
                    var a = this.renderer,
                        b = this.element,
                        k = this.translateX || 0,
                        e = this.translateY || 0,
                        h = this.x || 0,
                        f = this.y || 0,
                        n = this.textAlign || "left",
                        c = { left: 0, center: 0.5, right: 1 }[n],
                        q = this.styles;
                    F(b, { marginLeft: k, marginTop: e });
                    this.shadows &&
                        p(this.shadows, function (a) {
                            F(a, { marginLeft: k + 1, marginTop: e + 1 });
                        });
                    this.inverted &&
                        p(b.childNodes, function (c) {
                            a.invertChild(c, b);
                        });
                    if ("SPAN" === b.tagName) {
                        var x = this.rotation,
                            d = r(this.textWidth),
                            g = q && q.whiteSpace,
                            v = [
                                x,
                                n,
                                b.innerHTML,
                                this.textWidth,
                                this.textAlign,
                            ].join();
                        v !== this.cTT &&
                            ((q = a.fontMetrics(b.style.fontSize).b),
                            H(x) && this.setSpanRotation(x, c, q),
                            F(b, { width: "", whiteSpace: g || "nowrap" }),
                            b.offsetWidth > d &&
                                /[ \-]/.test(b.textContent || b.innerText) &&
                                F(b, {
                                    width: d + "px",
                                    display: "block",
                                    whiteSpace: g || "normal",
                                }),
                            this.getSpanCorrection(b.offsetWidth, q, c, x, n));
                        F(b, {
                            left: h + (this.xCorr || 0) + "px",
                            top: f + (this.yCorr || 0) + "px",
                        });
                        l && (q = b.offsetHeight);
                        this.cTT = v;
                    }
                } else this.alignOnAdd = !0;
            },
            setSpanRotation: function (a, f, k) {
                var e = {},
                    h = v
                        ? "-ms-transform"
                        : l
                        ? "-webkit-transform"
                        : g
                        ? "MozTransform"
                        : b.opera
                        ? "-o-transform"
                        : "";
                e[h] = e.transform = "rotate(" + a + "deg)";
                e[h + (g ? "Origin" : "-origin")] = e.transformOrigin =
                    100 * f + "% " + k + "px";
                F(this.element, e);
            },
            getSpanCorrection: function (a, b, k) {
                this.xCorr = -a * k;
                this.yCorr = -b;
            },
        });
        d(f.prototype, {
            html: function (a, b, k) {
                var e = this.createElement("span"),
                    h = e.element,
                    f = e.renderer,
                    u = f.isSVG,
                    c = function (a, c) {
                        p(["opacity", "visibility"], function (b) {
                            n(a, b + "Setter", function (a, b, e, q) {
                                a.call(this, b, e, q);
                                c[e] = b;
                            });
                        });
                    };
                e.textSetter = function (a) {
                    a !== h.innerHTML && delete this.bBox;
                    h.innerHTML = this.textStr = a;
                    e.htmlUpdateTransform();
                };
                u && c(e, e.element.style);
                e.xSetter =
                    e.ySetter =
                    e.alignSetter =
                    e.rotationSetter =
                        function (a, c) {
                            "align" === c && (c = "textAlign");
                            e[c] = a;
                            e.htmlUpdateTransform();
                        };
                e.attr({ text: a, x: Math.round(b), y: Math.round(k) }).css({
                    fontFamily: this.style.fontFamily,
                    fontSize: this.style.fontSize,
                    position: "absolute",
                });
                h.style.whiteSpace = "nowrap";
                e.css = e.htmlCss;
                u &&
                    (e.add = function (a) {
                        var b,
                            q = f.box.parentNode,
                            k = [];
                        if ((this.parentGroup = a)) {
                            if (((b = a.div), !b)) {
                                for (; a; ) k.push(a), (a = a.parentGroup);
                                p(k.reverse(), function (a) {
                                    var h,
                                        x = E(a.element, "class");
                                    x && (x = { className: x });
                                    b = a.div =
                                        a.div ||
                                        A(
                                            "div",
                                            x,
                                            {
                                                position: "absolute",
                                                left:
                                                    (a.translateX || 0) + "px",
                                                top: (a.translateY || 0) + "px",
                                                display: a.display,
                                                opacity: a.opacity,
                                                pointerEvents:
                                                    a.styles &&
                                                    a.styles.pointerEvents,
                                            },
                                            b || q
                                        );
                                    h = b.style;
                                    d(a, {
                                        on: function () {
                                            e.on.apply(
                                                { element: k[0].div },
                                                arguments
                                            );
                                            return a;
                                        },
                                        translateXSetter: function (c, b) {
                                            h.left = c + "px";
                                            a[b] = c;
                                            a.doTransform = !0;
                                        },
                                        translateYSetter: function (c, b) {
                                            h.top = c + "px";
                                            a[b] = c;
                                            a.doTransform = !0;
                                        },
                                    });
                                    c(a, h);
                                });
                            }
                        } else b = q;
                        b.appendChild(h);
                        e.added = !0;
                        e.alignOnAdd && e.htmlUpdateTransform();
                        return e;
                    });
                return e;
            },
        });
    })(M);
    (function (a) {
        var E,
            A,
            F = a.createElement,
            H = a.css,
            p = a.defined,
            d = a.deg2rad,
            g = a.discardElement,
            v = a.doc,
            l = a.each,
            r = a.erase,
            f = a.extend;
        E = a.extendClass;
        var b = a.isArray,
            n = a.isNumber,
            w = a.isObject,
            t = a.merge;
        A = a.noop;
        var k = a.pick,
            e = a.pInt,
            h = a.SVGElement,
            C = a.SVGRenderer,
            u = a.win;
        a.svg ||
            ((A = {
                docMode8: v && 8 === v.documentMode,
                init: function (a, b) {
                    var c = ["\x3c", b, ' filled\x3d"f" stroked\x3d"f"'],
                        e = ["position: ", "absolute", ";"],
                        q = "div" === b;
                    ("shape" === b || q) &&
                        e.push("left:0;top:0;width:1px;height:1px;");
                    e.push("visibility: ", q ? "hidden" : "visible");
                    c.push(' style\x3d"', e.join(""), '"/\x3e');
                    b &&
                        ((c =
                            q || "span" === b || "img" === b
                                ? c.join("")
                                : a.prepVML(c)),
                        (this.element = F(c)));
                    this.renderer = a;
                },
                add: function (a) {
                    var c = this.renderer,
                        b = this.element,
                        e = c.box,
                        h = a && a.inverted,
                        e = a ? a.element || a : e;
                    a && (this.parentGroup = a);
                    h && c.invertChild(b, e);
                    e.appendChild(b);
                    this.added = !0;
                    this.alignOnAdd &&
                        !this.deferUpdateTransform &&
                        this.updateTransform();
                    if (this.onAdd) this.onAdd();
                    this.className && this.attr("class", this.className);
                    return this;
                },
                updateTransform: h.prototype.htmlUpdateTransform,
                setSpanRotation: function () {
                    var a = this.rotation,
                        b = Math.cos(a * d),
                        e = Math.sin(a * d);
                    H(this.element, {
                        filter: a
                            ? [
                                  "progid:DXImageTransform.Microsoft.Matrix(M11\x3d",
                                  b,
                                  ", M12\x3d",
                                  -e,
                                  ", M21\x3d",
                                  e,
                                  ", M22\x3d",
                                  b,
                                  ", sizingMethod\x3d'auto expand')",
                              ].join("")
                            : "none",
                    });
                },
                getSpanCorrection: function (a, b, e, h, f) {
                    var c = h ? Math.cos(h * d) : 1,
                        q = h ? Math.sin(h * d) : 0,
                        x = k(this.elemHeight, this.element.offsetHeight),
                        n;
                    this.xCorr = 0 > c && -a;
                    this.yCorr = 0 > q && -x;
                    n = 0 > c * q;
                    this.xCorr += q * b * (n ? 1 - e : e);
                    this.yCorr -= c * b * (h ? (n ? e : 1 - e) : 1);
                    f &&
                        "left" !== f &&
                        ((this.xCorr -= a * e * (0 > c ? -1 : 1)),
                        h && (this.yCorr -= x * e * (0 > q ? -1 : 1)),
                        H(this.element, { textAlign: f }));
                },
                pathToVML: function (a) {
                    for (var c = a.length, b = []; c--; )
                        n(a[c])
                            ? (b[c] = Math.round(10 * a[c]) - 5)
                            : "Z" === a[c]
                            ? (b[c] = "x")
                            : ((b[c] = a[c]),
                              !a.isArc ||
                                  ("wa" !== a[c] && "at" !== a[c]) ||
                                  (b[c + 5] === b[c + 7] &&
                                      (b[c + 7] +=
                                          a[c + 7] > a[c + 5] ? 1 : -1),
                                  b[c + 6] === b[c + 8] &&
                                      (b[c + 8] +=
                                          a[c + 8] > a[c + 6] ? 1 : -1)));
                    return b.join(" ") || "x";
                },
                clip: function (a) {
                    var c = this,
                        b;
                    a
                        ? ((b = a.members),
                          r(b, c),
                          b.push(c),
                          (c.destroyClip = function () {
                              r(b, c);
                          }),
                          (a = a.getCSS(c)))
                        : (c.destroyClip && c.destroyClip(),
                          (a = {
                              clip: c.docMode8 ? "inherit" : "rect(auto)",
                          }));
                    return c.css(a);
                },
                css: h.prototype.htmlCss,
                safeRemoveChild: function (a) {
                    a.parentNode && g(a);
                },
                destroy: function () {
                    this.destroyClip && this.destroyClip();
                    return h.prototype.destroy.apply(this);
                },
                on: function (a, b) {
                    this.element["on" + a] = function () {
                        var a = u.event;
                        a.target = a.srcElement;
                        b(a);
                    };
                    return this;
                },
                cutOffPath: function (a, b) {
                    var c;
                    a = a.split(/[ ,]/);
                    c = a.length;
                    if (9 === c || 11 === c)
                        a[c - 4] = a[c - 2] = e(a[c - 2]) - 10 * b;
                    return a.join(" ");
                },
                shadow: function (a, b, h) {
                    var c = [],
                        q,
                        f = this.element,
                        n = this.renderer,
                        x,
                        u = f.style,
                        d,
                        m = f.path,
                        z,
                        l,
                        t,
                        y;
                    m && "string" !== typeof m.value && (m = "x");
                    l = m;
                    if (a) {
                        t = k(a.width, 3);
                        y = (a.opacity || 0.15) / t;
                        for (q = 1; 3 >= q; q++)
                            (z = 2 * t + 1 - 2 * q),
                                h && (l = this.cutOffPath(m.value, z + 0.5)),
                                (d = [
                                    '\x3cshape isShadow\x3d"true" strokeweight\x3d"',
                                    z,
                                    '" filled\x3d"false" path\x3d"',
                                    l,
                                    '" coordsize\x3d"10 10" style\x3d"',
                                    f.style.cssText,
                                    '" /\x3e',
                                ]),
                                (x = F(n.prepVML(d), null, {
                                    left: e(u.left) + k(a.offsetX, 1),
                                    top: e(u.top) + k(a.offsetY, 1),
                                })),
                                h && (x.cutOff = z + 1),
                                (d = [
                                    '\x3cstroke color\x3d"',
                                    a.color || "#000000",
                                    '" opacity\x3d"',
                                    y * q,
                                    '"/\x3e',
                                ]),
                                F(n.prepVML(d), null, null, x),
                                b
                                    ? b.element.appendChild(x)
                                    : f.parentNode.insertBefore(x, f),
                                c.push(x);
                        this.shadows = c;
                    }
                    return this;
                },
                updateShadows: A,
                setAttr: function (a, b) {
                    this.docMode8
                        ? (this.element[a] = b)
                        : this.element.setAttribute(a, b);
                },
                classSetter: function (a) {
                    (this.added ? this.element : this).className = a;
                },
                dashstyleSetter: function (a, b, e) {
                    (e.getElementsByTagName("stroke")[0] ||
                        F(
                            this.renderer.prepVML(["\x3cstroke/\x3e"]),
                            null,
                            null,
                            e
                        ))[b] = a || "solid";
                    this[b] = a;
                },
                dSetter: function (a, b, e) {
                    var c = this.shadows;
                    a = a || [];
                    this.d = a.join && a.join(" ");
                    e.path = a = this.pathToVML(a);
                    if (c)
                        for (e = c.length; e--; )
                            c[e].path = c[e].cutOff
                                ? this.cutOffPath(a, c[e].cutOff)
                                : a;
                    this.setAttr(b, a);
                },
                fillSetter: function (a, b, e) {
                    var c = e.nodeName;
                    "SPAN" === c
                        ? (e.style.color = a)
                        : "IMG" !== c &&
                          ((e.filled = "none" !== a),
                          this.setAttr(
                              "fillcolor",
                              this.renderer.color(a, e, b, this)
                          ));
                },
                "fill-opacitySetter": function (a, b, e) {
                    F(
                        this.renderer.prepVML([
                            "\x3c",
                            b.split("-")[0],
                            ' opacity\x3d"',
                            a,
                            '"/\x3e',
                        ]),
                        null,
                        null,
                        e
                    );
                },
                opacitySetter: A,
                rotationSetter: function (a, b, e) {
                    e = e.style;
                    this[b] = e[b] = a;
                    e.left = -Math.round(Math.sin(a * d) + 1) + "px";
                    e.top = Math.round(Math.cos(a * d)) + "px";
                },
                strokeSetter: function (a, b, e) {
                    this.setAttr(
                        "strokecolor",
                        this.renderer.color(a, e, b, this)
                    );
                },
                "stroke-widthSetter": function (a, b, e) {
                    e.stroked = !!a;
                    this[b] = a;
                    n(a) && (a += "px");
                    this.setAttr("strokeweight", a);
                },
                titleSetter: function (a, b) {
                    this.setAttr(b, a);
                },
                visibilitySetter: function (a, b, e) {
                    "inherit" === a && (a = "visible");
                    this.shadows &&
                        l(this.shadows, function (c) {
                            c.style[b] = a;
                        });
                    "DIV" === e.nodeName &&
                        ((a = "hidden" === a ? "-999em" : 0),
                        this.docMode8 ||
                            (e.style[b] = a ? "visible" : "hidden"),
                        (b = "top"));
                    e.style[b] = a;
                },
                xSetter: function (a, b, e) {
                    this[b] = a;
                    "x" === b ? (b = "left") : "y" === b && (b = "top");
                    this.updateClipping
                        ? ((this[b] = a), this.updateClipping())
                        : (e.style[b] = a);
                },
                zIndexSetter: function (a, b, e) {
                    e.style[b] = a;
                },
            }),
            (A["stroke-opacitySetter"] = A["fill-opacitySetter"]),
            (a.VMLElement = A = E(h, A)),
            (A.prototype.ySetter =
                A.prototype.widthSetter =
                A.prototype.heightSetter =
                    A.prototype.xSetter),
            (A = {
                Element: A,
                isIE8: -1 < u.navigator.userAgent.indexOf("MSIE 8.0"),
                init: function (a, b, e) {
                    var c, h;
                    this.alignedObjects = [];
                    c = this.createElement("div").css({ position: "relative" });
                    h = c.element;
                    a.appendChild(c.element);
                    this.isVML = !0;
                    this.box = h;
                    this.boxWrapper = c;
                    this.gradients = {};
                    this.cache = {};
                    this.cacheKeys = [];
                    this.imgCount = 0;
                    this.setSize(b, e, !1);
                    if (!v.namespaces.hcv) {
                        v.namespaces.add(
                            "hcv",
                            "urn:schemas-microsoft-com:vml"
                        );
                        try {
                            v.createStyleSheet().cssText =
                                "hcv\\:fill, hcv\\:path, hcv\\:shape, hcv\\:stroke{ behavior:url(#default#VML); display: inline-block; } ";
                        } catch (J) {
                            v.styleSheets[0].cssText +=
                                "hcv\\:fill, hcv\\:path, hcv\\:shape, hcv\\:stroke{ behavior:url(#default#VML); display: inline-block; } ";
                        }
                    }
                },
                isHidden: function () {
                    return !this.box.offsetWidth;
                },
                clipRect: function (a, b, e, h) {
                    var c = this.createElement(),
                        q = w(a);
                    return f(c, {
                        members: [],
                        count: 0,
                        left: (q ? a.x : a) + 1,
                        top: (q ? a.y : b) + 1,
                        width: (q ? a.width : e) - 1,
                        height: (q ? a.height : h) - 1,
                        getCSS: function (a) {
                            var c = a.element,
                                b = c.nodeName,
                                e = a.inverted,
                                m =
                                    this.top -
                                    ("shape" === b ? c.offsetTop : 0),
                                z = this.left,
                                c = z + this.width,
                                h = m + this.height,
                                m = {
                                    clip:
                                        "rect(" +
                                        Math.round(e ? z : m) +
                                        "px," +
                                        Math.round(e ? h : c) +
                                        "px," +
                                        Math.round(e ? c : h) +
                                        "px," +
                                        Math.round(e ? m : z) +
                                        "px)",
                                };
                            !e &&
                                a.docMode8 &&
                                "DIV" === b &&
                                f(m, { width: c + "px", height: h + "px" });
                            return m;
                        },
                        updateClipping: function () {
                            l(c.members, function (a) {
                                a.element && a.css(c.getCSS(a));
                            });
                        },
                    });
                },
                color: function (c, b, e, h) {
                    var q = this,
                        k,
                        f = /^rgba/,
                        n,
                        u,
                        x = "none";
                    c && c.linearGradient
                        ? (u = "gradient")
                        : c && c.radialGradient && (u = "pattern");
                    if (u) {
                        var m,
                            z,
                            d = c.linearGradient || c.radialGradient,
                            t,
                            y,
                            B,
                            C,
                            g,
                            r = "";
                        c = c.stops;
                        var w,
                            v = [],
                            K = function () {
                                n = [
                                    '\x3cfill colors\x3d"' +
                                        v.join(",") +
                                        '" opacity\x3d"',
                                    B,
                                    '" o:opacity2\x3d"',
                                    y,
                                    '" type\x3d"',
                                    u,
                                    '" ',
                                    r,
                                    'focus\x3d"100%" method\x3d"any" /\x3e',
                                ];
                                F(q.prepVML(n), null, null, b);
                            };
                        t = c[0];
                        w = c[c.length - 1];
                        0 < t[0] && c.unshift([0, t[1]]);
                        1 > w[0] && c.push([1, w[1]]);
                        l(c, function (c, b) {
                            f.test(c[1])
                                ? ((k = a.color(c[1])),
                                  (m = k.get("rgb")),
                                  (z = k.get("a")))
                                : ((m = c[1]), (z = 1));
                            v.push(100 * c[0] + "% " + m);
                            b ? ((B = z), (C = m)) : ((y = z), (g = m));
                        });
                        if ("fill" === e)
                            if ("gradient" === u)
                                (e = d.x1 || d[0] || 0),
                                    (c = d.y1 || d[1] || 0),
                                    (t = d.x2 || d[2] || 0),
                                    (d = d.y2 || d[3] || 0),
                                    (r =
                                        'angle\x3d"' +
                                        (90 -
                                            (180 *
                                                Math.atan((d - c) / (t - e))) /
                                                Math.PI) +
                                        '"'),
                                    K();
                            else {
                                var x = d.r,
                                    p = 2 * x,
                                    A = 2 * x,
                                    E = d.cx,
                                    H = d.cy,
                                    U = b.radialReference,
                                    T,
                                    x = function () {
                                        U &&
                                            ((T = h.getBBox()),
                                            (E += (U[0] - T.x) / T.width - 0.5),
                                            (H +=
                                                (U[1] - T.y) / T.height - 0.5),
                                            (p *= U[2] / T.width),
                                            (A *= U[2] / T.height));
                                        r =
                                            'src\x3d"' +
                                            a.getOptions().global
                                                .VMLRadialGradientURL +
                                            '" size\x3d"' +
                                            p +
                                            "," +
                                            A +
                                            '" origin\x3d"0.5,0.5" position\x3d"' +
                                            E +
                                            "," +
                                            H +
                                            '" color2\x3d"' +
                                            g +
                                            '" ';
                                        K();
                                    };
                                h.added ? x() : (h.onAdd = x);
                                x = C;
                            }
                        else x = m;
                    } else
                        f.test(c) && "IMG" !== b.tagName
                            ? ((k = a.color(c)),
                              h[e + "-opacitySetter"](k.get("a"), e, b),
                              (x = k.get("rgb")))
                            : ((x = b.getElementsByTagName(e)),
                              x.length &&
                                  ((x[0].opacity = 1), (x[0].type = "solid")),
                              (x = c));
                    return x;
                },
                prepVML: function (a) {
                    var c = this.isIE8;
                    a = a.join("");
                    c
                        ? ((a = a.replace(
                              "/\x3e",
                              ' xmlns\x3d"urn:schemas-microsoft-com:vml" /\x3e'
                          )),
                          (a =
                              -1 === a.indexOf('style\x3d"')
                                  ? a.replace(
                                        "/\x3e",
                                        ' style\x3d"display:inline-block;behavior:url(#default#VML);" /\x3e'
                                    )
                                  : a.replace(
                                        'style\x3d"',
                                        'style\x3d"display:inline-block;behavior:url(#default#VML);'
                                    )))
                        : (a = a.replace("\x3c", "\x3chcv:"));
                    return a;
                },
                text: C.prototype.html,
                path: function (a) {
                    var c = { coordsize: "10 10" };
                    b(a) ? (c.d = a) : w(a) && f(c, a);
                    return this.createElement("shape").attr(c);
                },
                circle: function (a, b, e) {
                    var c = this.symbol("circle");
                    w(a) && ((e = a.r), (b = a.y), (a = a.x));
                    c.isCircle = !0;
                    c.r = e;
                    return c.attr({ x: a, y: b });
                },
                g: function (a) {
                    var b;
                    a &&
                        (b = {
                            className: "highcharts-" + a,
                            class: "highcharts-" + a,
                        });
                    return this.createElement("div").attr(b);
                },
                image: function (a, b, e, h, k) {
                    var c = this.createElement("img").attr({ src: a });
                    1 < arguments.length &&
                        c.attr({ x: b, y: e, width: h, height: k });
                    return c;
                },
                createElement: function (a) {
                    return "rect" === a
                        ? this.symbol(a)
                        : C.prototype.createElement.call(this, a);
                },
                invertChild: function (a, b) {
                    var c = this;
                    b = b.style;
                    var h = "IMG" === a.tagName && a.style;
                    H(a, {
                        flip: "x",
                        left: e(b.width) - (h ? e(h.top) : 1),
                        top: e(b.height) - (h ? e(h.left) : 1),
                        rotation: -90,
                    });
                    l(a.childNodes, function (b) {
                        c.invertChild(b, a);
                    });
                },
                symbols: {
                    arc: function (a, b, e, h, k) {
                        var c = k.start,
                            f = k.end,
                            q = k.r || e || h;
                        e = k.innerR;
                        h = Math.cos(c);
                        var n = Math.sin(c),
                            u = Math.cos(f),
                            m = Math.sin(f);
                        if (0 === f - c) return ["x"];
                        c = [
                            "wa",
                            a - q,
                            b - q,
                            a + q,
                            b + q,
                            a + q * h,
                            b + q * n,
                            a + q * u,
                            b + q * m,
                        ];
                        k.open && !e && c.push("e", "M", a, b);
                        c.push(
                            "at",
                            a - e,
                            b - e,
                            a + e,
                            b + e,
                            a + e * u,
                            b + e * m,
                            a + e * h,
                            b + e * n,
                            "x",
                            "e"
                        );
                        c.isArc = !0;
                        return c;
                    },
                    circle: function (a, b, e, h, k) {
                        k && p(k.r) && (e = h = 2 * k.r);
                        k && k.isCircle && ((a -= e / 2), (b -= h / 2));
                        return [
                            "wa",
                            a,
                            b,
                            a + e,
                            b + h,
                            a + e,
                            b + h / 2,
                            a + e,
                            b + h / 2,
                            "e",
                        ];
                    },
                    rect: function (a, b, e, h, k) {
                        return C.prototype.symbols[
                            p(k) && k.r ? "callout" : "square"
                        ].call(0, a, b, e, h, k);
                    },
                },
            }),
            (a.VMLRenderer = E =
                function () {
                    this.init.apply(this, arguments);
                }),
            (E.prototype = t(C.prototype, A)),
            (a.Renderer = E));
        C.prototype.measureSpanWidth = function (a, b) {
            var c = v.createElement("span");
            a = v.createTextNode(a);
            c.appendChild(a);
            H(c, b);
            this.box.appendChild(c);
            b = c.offsetWidth;
            g(c);
            return b;
        };
    })(M);
    (function (a) {
        function E() {
            var v = a.defaultOptions.global,
                l,
                r = v.useUTC,
                f = r ? "getUTC" : "get",
                b = r ? "setUTC" : "set";
            a.Date = l = v.Date || g.Date;
            l.hcTimezoneOffset = r && v.timezoneOffset;
            l.hcGetTimezoneOffset = r && v.getTimezoneOffset;
            l.hcMakeTime = function (a, b, f, k, e, h) {
                var n;
                r
                    ? ((n = l.UTC.apply(0, arguments)), (n += H(n)))
                    : (n = new l(
                          a,
                          b,
                          d(f, 1),
                          d(k, 0),
                          d(e, 0),
                          d(h, 0)
                      ).getTime());
                return n;
            };
            F("Minutes Hours Day Date Month FullYear".split(" "), function (a) {
                l["hcGet" + a] = f + a;
            });
            F(
                "Milliseconds Seconds Minutes Hours Date Month FullYear".split(
                    " "
                ),
                function (a) {
                    l["hcSet" + a] = b + a;
                }
            );
        }
        var A = a.color,
            F = a.each,
            H = a.getTZOffset,
            p = a.merge,
            d = a.pick,
            g = a.win;
        a.defaultOptions = {
            colors: "#7cb5ec #434348 #90ed7d #f7a35c #8085e9 #f15c80 #e4d354 #2b908f #f45b5b #91e8e1".split(
                " "
            ),
            symbols: [
                "circle",
                "diamond",
                "square",
                "triangle",
                "triangle-down",
            ],
            lang: {
                loading: "Loading...",
                months: "January February March April May June July August September October November December".split(
                    " "
                ),
                shortMonths:
                    "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split(
                        " "
                    ),
                weekdays:
                    "Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(
                        " "
                    ),
                decimalPoint: ".",
                numericSymbols: "kMGTPE".split(""),
                resetZoom: "Reset zoom",
                resetZoomTitle: "Reset zoom level 1:1",
                thousandsSep: " ",
            },
            global: {
                useUTC: !0,
                VMLRadialGradientURL:
                    "http://code.highcharts.com/5.0.5/gfx/vml-radial-gradient.png",
            },
            chart: {
                borderRadius: 0,
                defaultSeriesType: "line",
                ignoreHiddenSeries: !0,
                spacing: [10, 10, 15, 10],
                resetZoomButton: {
                    theme: { zIndex: 20 },
                    position: { align: "right", x: -10, y: 10 },
                },
                width: null,
                height: null,
                borderColor: "#335cad",
                backgroundColor: "#ffffff",
                plotBorderColor: "#cccccc",
            },
            title: {
                text: "Chart title",
                align: "center",
                margin: 15,
                widthAdjust: -44,
            },
            subtitle: { text: "", align: "center", widthAdjust: -44 },
            plotOptions: {},
            labels: { style: { position: "absolute", color: "#333333" } },
            legend: {
                enabled: !0,
                align: "center",
                layout: "horizontal",
                labelFormatter: function () {
                    return this.name;
                },
                borderColor: "#999999",
                borderRadius: 0,
                navigation: {
                    activeColor: "#003399",
                    inactiveColor: "#cccccc",
                },
                itemStyle: {
                    color: "#333333",
                    fontSize: "12px",
                    fontWeight: "bold",
                },
                itemHoverStyle: { color: "#000000" },
                itemHiddenStyle: { color: "#cccccc" },
                shadow: !1,
                itemCheckboxStyle: {
                    position: "absolute",
                    width: "13px",
                    height: "13px",
                },
                squareSymbol: !0,
                symbolPadding: 5,
                verticalAlign: "bottom",
                x: 0,
                y: 0,
                title: { style: { fontWeight: "bold" } },
            },
            loading: {
                labelStyle: {
                    fontWeight: "bold",
                    position: "relative",
                    top: "45%",
                },
                style: {
                    position: "absolute",
                    backgroundColor: "#ffffff",
                    opacity: 0.5,
                    textAlign: "center",
                },
            },
            tooltip: {
                enabled: !0,
                animation: a.svg,
                borderRadius: 3,
                dateTimeLabelFormats: {
                    millisecond: "%A, %b %e, %H:%M:%S.%L",
                    second: "%A, %b %e, %H:%M:%S",
                    minute: "%A, %b %e, %H:%M",
                    hour: "%A, %b %e, %H:%M",
                    day: "%A, %b %e, %Y",
                    week: "Week from %A, %b %e, %Y",
                    month: "%B %Y",
                    year: "%Y",
                },
                footerFormat: "",
                padding: 8,
                snap: a.isTouchDevice ? 25 : 10,
                backgroundColor: A("#f7f7f7").setOpacity(0.85).get(),
                borderWidth: 1,
                headerFormat:
                    '\x3cspan style\x3d"font-size: 10px"\x3e{point.key}\x3c/span\x3e\x3cbr/\x3e',
                pointFormat:
                    '\x3cspan style\x3d"color:{point.color}"\x3e\u25cf\x3c/span\x3e {series.name}: \x3cb\x3e{point.y}\x3c/b\x3e\x3cbr/\x3e',
                shadow: !0,
                style: {
                    color: "#333333",
                    cursor: "default",
                    fontSize: "12px",
                    pointerEvents: "none",
                    whiteSpace: "nowrap",
                },
            },
            credits: {
                enabled: !0,
                href: "http://www.highcharts.com",
                position: {
                    align: "right",
                    x: -10,
                    verticalAlign: "bottom",
                    y: -5,
                },
                style: { cursor: "pointer", color: "#999999", fontSize: "9px" },
                text: "Highcharts.com",
            },
        };
        a.setOptions = function (d) {
            a.defaultOptions = p(!0, a.defaultOptions, d);
            E();
            return a.defaultOptions;
        };
        a.getOptions = function () {
            return a.defaultOptions;
        };
        a.defaultPlotOptions = a.defaultOptions.plotOptions;
        E();
    })(M);
    (function (a) {
        var E = a.arrayMax,
            A = a.arrayMin,
            F = a.defined,
            H = a.destroyObjectProperties,
            p = a.each,
            d = a.erase,
            g = a.merge,
            v = a.pick;
        a.PlotLineOrBand = function (a, d) {
            this.axis = a;
            d && ((this.options = d), (this.id = d.id));
        };
        a.PlotLineOrBand.prototype = {
            render: function () {
                var a = this,
                    d = a.axis,
                    f = d.horiz,
                    b = a.options,
                    n = b.label,
                    w = a.label,
                    t = b.to,
                    k = b.from,
                    e = b.value,
                    h = F(k) && F(t),
                    C = F(e),
                    u = a.svgElem,
                    c = !u,
                    q = [],
                    x,
                    K = b.color,
                    I = v(b.zIndex, 0),
                    p = b.events,
                    q = {
                        class:
                            "highcharts-plot-" +
                            (h ? "band " : "line ") +
                            (b.className || ""),
                    },
                    D = {},
                    G = d.chart.renderer,
                    L = h ? "bands" : "lines",
                    N = d.log2lin;
                d.isLog && ((k = N(k)), (t = N(t)), (e = N(e)));
                C
                    ? ((q = { stroke: K, "stroke-width": b.width }),
                      b.dashStyle && (q.dashstyle = b.dashStyle))
                    : h &&
                      (K && (q.fill = K),
                      b.borderWidth &&
                          ((q.stroke = b.borderColor),
                          (q["stroke-width"] = b.borderWidth)));
                D.zIndex = I;
                L += "-" + I;
                (K = d[L]) ||
                    (d[L] = K =
                        G.g("plot-" + L)
                            .attr(D)
                            .add());
                c && (a.svgElem = u = G.path().attr(q).add(K));
                if (C) q = d.getPlotLinePath(e, u.strokeWidth());
                else if (h) q = d.getPlotBandPath(k, t, b);
                else return;
                if (c && q && q.length) {
                    if ((u.attr({ d: q }), p))
                        for (x in ((b = function (b) {
                            u.on(b, function (c) {
                                p[b].apply(a, [c]);
                            });
                        }),
                        p))
                            b(x);
                } else
                    u &&
                        (q
                            ? (u.show(), u.animate({ d: q }))
                            : (u.hide(), w && (a.label = w = w.destroy())));
                n &&
                F(n.text) &&
                q &&
                q.length &&
                0 < d.width &&
                0 < d.height &&
                !q.flat
                    ? ((n = g(
                          {
                              align: f && h && "center",
                              x: f ? !h && 4 : 10,
                              verticalAlign: !f && h && "middle",
                              y: f ? (h ? 16 : 10) : h ? 6 : -4,
                              rotation: f && !h && 90,
                          },
                          n
                      )),
                      this.renderLabel(n, q, h, I))
                    : w && w.hide();
                return a;
            },
            renderLabel: function (a, d, f, b) {
                var n = this.label,
                    l = this.axis.chart.renderer;
                n ||
                    ((n = {
                        align: a.textAlign || a.align,
                        rotation: a.rotation,
                        class:
                            "highcharts-plot-" +
                            (f ? "band" : "line") +
                            "-label " +
                            (a.className || ""),
                    }),
                    (n.zIndex = b),
                    (this.label = n =
                        l.text(a.text, 0, 0, a.useHTML).attr(n).add()),
                    n.css(a.style));
                b = [d[1], d[4], f ? d[6] : d[1]];
                d = [d[2], d[5], f ? d[7] : d[2]];
                f = A(b);
                l = A(d);
                n.align(a, !1, {
                    x: f,
                    y: l,
                    width: E(b) - f,
                    height: E(d) - l,
                });
                n.show();
            },
            destroy: function () {
                d(this.axis.plotLinesAndBands, this);
                delete this.axis;
                H(this);
            },
        };
        a.AxisPlotLineOrBandExtension = {
            getPlotBandPath: function (a, d) {
                d = this.getPlotLinePath(d, null, null, !0);
                (a = this.getPlotLinePath(a, null, null, !0)) && d
                    ? ((a.flat = a.toString() === d.toString()),
                      a.push(d[4], d[5], d[1], d[2], "z"))
                    : (a = null);
                return a;
            },
            addPlotBand: function (a) {
                return this.addPlotBandOrLine(a, "plotBands");
            },
            addPlotLine: function (a) {
                return this.addPlotBandOrLine(a, "plotLines");
            },
            addPlotBandOrLine: function (d, g) {
                var f = new a.PlotLineOrBand(this, d).render(),
                    b = this.userOptions;
                f &&
                    (g && ((b[g] = b[g] || []), b[g].push(d)),
                    this.plotLinesAndBands.push(f));
                return f;
            },
            removePlotBandOrLine: function (a) {
                for (
                    var g = this.plotLinesAndBands,
                        f = this.options,
                        b = this.userOptions,
                        n = g.length;
                    n--;

                )
                    g[n].id === a && g[n].destroy();
                p(
                    [
                        f.plotLines || [],
                        b.plotLines || [],
                        f.plotBands || [],
                        b.plotBands || [],
                    ],
                    function (b) {
                        for (n = b.length; n--; ) b[n].id === a && d(b, b[n]);
                    }
                );
            },
        };
    })(M);
    (function (a) {
        var E = a.correctFloat,
            A = a.defined,
            F = a.destroyObjectProperties,
            H = a.isNumber,
            p = a.merge,
            d = a.pick,
            g = a.deg2rad;
        a.Tick = function (a, d, g, f) {
            this.axis = a;
            this.pos = d;
            this.type = g || "";
            this.isNew = !0;
            g || f || this.addLabel();
        };
        a.Tick.prototype = {
            addLabel: function () {
                var a = this.axis,
                    g = a.options,
                    r = a.chart,
                    f = a.categories,
                    b = a.names,
                    n = this.pos,
                    w = g.labels,
                    t = a.tickPositions,
                    k = n === t[0],
                    e = n === t[t.length - 1],
                    b = f ? d(f[n], b[n], n) : n,
                    f = this.label,
                    t = t.info,
                    h;
                a.isDatetimeAxis &&
                    t &&
                    (h =
                        g.dateTimeLabelFormats[t.higherRanks[n] || t.unitName]);
                this.isFirst = k;
                this.isLast = e;
                g = a.labelFormatter.call({
                    axis: a,
                    chart: r,
                    isFirst: k,
                    isLast: e,
                    dateTimeLabelFormat: h,
                    value: a.isLog ? E(a.lin2log(b)) : b,
                });
                A(f)
                    ? f && f.attr({ text: g })
                    : ((this.labelLength =
                          (this.label = f =
                              A(g) && w.enabled
                                  ? r.renderer
                                        .text(g, 0, 0, w.useHTML)
                                        .css(p(w.style))
                                        .add(a.labelGroup)
                                  : null) && f.getBBox().width),
                      (this.rotation = 0));
            },
            getLabelSize: function () {
                return this.label
                    ? this.label.getBBox()[this.axis.horiz ? "height" : "width"]
                    : 0;
            },
            handleOverflow: function (a) {
                var l = this.axis,
                    r = a.x,
                    f = l.chart.chartWidth,
                    b = l.chart.spacing,
                    n = d(l.labelLeft, Math.min(l.pos, b[3])),
                    b = d(l.labelRight, Math.max(l.pos + l.len, f - b[1])),
                    w = this.label,
                    t = this.rotation,
                    k = { left: 0, center: 0.5, right: 1 }[l.labelAlign],
                    e = w.getBBox().width,
                    h = l.getSlotWidth(),
                    C = h,
                    u = 1,
                    c,
                    q = {};
                if (t)
                    0 > t && r - k * e < n
                        ? (c = Math.round(r / Math.cos(t * g) - n))
                        : 0 < t &&
                          r + k * e > b &&
                          (c = Math.round((f - r) / Math.cos(t * g)));
                else if (
                    ((f = r + (1 - k) * e),
                    r - k * e < n
                        ? (C = a.x + C * (1 - k) - n)
                        : f > b && ((C = b - a.x + C * k), (u = -1)),
                    (C = Math.min(h, C)),
                    C < h &&
                        "center" === l.labelAlign &&
                        (a.x += u * (h - C - k * (h - Math.min(e, C)))),
                    e > C || (l.autoRotation && (w.styles || {}).width))
                )
                    c = C;
                c &&
                    ((q.width = c),
                    (l.options.labels.style || {}).textOverflow ||
                        (q.textOverflow = "ellipsis"),
                    w.css(q));
            },
            getPosition: function (a, d, g, f) {
                var b = this.axis,
                    n = b.chart,
                    l = (f && n.oldChartHeight) || n.chartHeight;
                return {
                    x: a
                        ? b.translate(d + g, null, null, f) + b.transB
                        : b.left +
                          b.offset +
                          (b.opposite
                              ? ((f && n.oldChartWidth) || n.chartWidth) -
                                b.right -
                                b.left
                              : 0),
                    y: a
                        ? l - b.bottom + b.offset - (b.opposite ? b.height : 0)
                        : l - b.translate(d + g, null, null, f) - b.transB,
                };
            },
            getLabelPosition: function (a, d, r, f, b, n, w, t) {
                var k = this.axis,
                    e = k.transA,
                    h = k.reversed,
                    C = k.staggerLines,
                    u = k.tickRotCorr || { x: 0, y: 0 },
                    c = b.y;
                A(c) ||
                    (c =
                        0 === k.side
                            ? r.rotation
                                ? -8
                                : -r.getBBox().height
                            : 2 === k.side
                            ? u.y + 8
                            : Math.cos(r.rotation * g) *
                              (u.y - r.getBBox(!1, 0).height / 2));
                a = a + b.x + u.x - (n && f ? n * e * (h ? -1 : 1) : 0);
                d = d + c - (n && !f ? n * e * (h ? 1 : -1) : 0);
                C &&
                    ((r = (w / (t || 1)) % C),
                    k.opposite && (r = C - r - 1),
                    (d += (k.labelOffset / C) * r));
                return { x: a, y: Math.round(d) };
            },
            getMarkPath: function (a, d, g, f, b, n) {
                return n.crispLine(
                    ["M", a, d, "L", a + (b ? 0 : -g), d + (b ? g : 0)],
                    f
                );
            },
            render: function (a, g, r) {
                var f = this.axis,
                    b = f.options,
                    n = f.chart.renderer,
                    l = f.horiz,
                    t = this.type,
                    k = this.label,
                    e = this.pos,
                    h = b.labels,
                    C = this.gridLine,
                    u = t ? t + "Tick" : "tick",
                    c = f.tickSize(u),
                    q = this.mark,
                    x = !q,
                    K = h.step,
                    I = {},
                    p = !0,
                    D = f.tickmarkOffset,
                    G = this.getPosition(l, e, D, g),
                    L = G.x,
                    G = G.y,
                    v =
                        (l && L === f.pos + f.len) || (!l && G === f.pos)
                            ? -1
                            : 1,
                    m = t ? t + "Grid" : "grid",
                    z = b[m + "LineWidth"],
                    O = b[m + "LineColor"],
                    P = b[m + "LineDashStyle"],
                    m = d(b[u + "Width"], !t && f.isXAxis ? 1 : 0),
                    u = b[u + "Color"];
                r = d(r, 1);
                this.isActive = !0;
                C ||
                    ((I.stroke = O),
                    (I["stroke-width"] = z),
                    P && (I.dashstyle = P),
                    t || (I.zIndex = 1),
                    g && (I.opacity = 0),
                    (this.gridLine = C =
                        n
                            .path()
                            .attr(I)
                            .addClass(
                                "highcharts-" + (t ? t + "-" : "") + "grid-line"
                            )
                            .add(f.gridGroup)));
                if (
                    !g &&
                    C &&
                    (e = f.getPlotLinePath(e + D, C.strokeWidth() * v, g, !0))
                )
                    C[this.isNew ? "attr" : "animate"]({ d: e, opacity: r });
                c &&
                    (f.opposite && (c[0] = -c[0]),
                    x &&
                        ((this.mark = q =
                            n
                                .path()
                                .addClass(
                                    "highcharts-" + (t ? t + "-" : "") + "tick"
                                )
                                .add(f.axisGroup)),
                        q.attr({ stroke: u, "stroke-width": m })),
                    q[x ? "attr" : "animate"]({
                        d: this.getMarkPath(
                            L,
                            G,
                            c[0],
                            q.strokeWidth() * v,
                            l,
                            n
                        ),
                        opacity: r,
                    }));
                k &&
                    H(L) &&
                    ((k.xy = G = this.getLabelPosition(L, G, k, l, h, D, a, K)),
                    (this.isFirst && !this.isLast && !d(b.showFirstLabel, 1)) ||
                    (this.isLast && !this.isFirst && !d(b.showLastLabel, 1))
                        ? (p = !1)
                        : !l ||
                          f.isRadial ||
                          h.step ||
                          h.rotation ||
                          g ||
                          0 === r ||
                          this.handleOverflow(G),
                    K && a % K && (p = !1),
                    p && H(G.y)
                        ? ((G.opacity = r),
                          k[this.isNew ? "attr" : "animate"](G))
                        : k.attr("y", -9999),
                    (this.isNew = !1));
            },
            destroy: function () {
                F(this, this.axis);
            },
        };
    })(M);
    (function (a) {
        var E = a.addEvent,
            A = a.animObject,
            F = a.arrayMax,
            H = a.arrayMin,
            p = a.AxisPlotLineOrBandExtension,
            d = a.color,
            g = a.correctFloat,
            v = a.defaultOptions,
            l = a.defined,
            r = a.deg2rad,
            f = a.destroyObjectProperties,
            b = a.each,
            n = a.error,
            w = a.extend,
            t = a.fireEvent,
            k = a.format,
            e = a.getMagnitude,
            h = a.grep,
            C = a.inArray,
            u = a.isArray,
            c = a.isNumber,
            q = a.isString,
            x = a.merge,
            K = a.normalizeTickInterval,
            I = a.pick,
            J = a.PlotLineOrBand,
            D = a.removeEvent,
            G = a.splat,
            L = a.syncTimeout,
            N = a.Tick;
        a.Axis = function () {
            this.init.apply(this, arguments);
        };
        a.Axis.prototype = {
            defaultOptions: {
                dateTimeLabelFormats: {
                    millisecond: "%H:%M:%S.%L",
                    second: "%H:%M:%S",
                    minute: "%H:%M",
                    hour: "%H:%M",
                    day: "%e. %b",
                    week: "%e. %b",
                    month: "%b '%y",
                    year: "%Y",
                },
                endOnTick: !1,
                labels: {
                    enabled: !0,
                    style: {
                        color: "#666666",
                        cursor: "default",
                        fontSize: "11px",
                    },
                    x: 0,
                },
                minPadding: 0.01,
                maxPadding: 0.01,
                minorTickLength: 2,
                minorTickPosition: "outside",
                startOfWeek: 1,
                startOnTick: !1,
                tickLength: 10,
                tickmarkPlacement: "between",
                tickPixelInterval: 100,
                tickPosition: "outside",
                title: { align: "middle", style: { color: "#666666" } },
                type: "linear",
                minorGridLineColor: "#f2f2f2",
                minorGridLineWidth: 1,
                minorTickColor: "#999999",
                lineColor: "#ccd6eb",
                lineWidth: 1,
                gridLineColor: "#e6e6e6",
                tickColor: "#ccd6eb",
            },
            defaultYAxisOptions: {
                endOnTick: !0,
                tickPixelInterval: 72,
                showLastLabel: !0,
                labels: { x: -8 },
                maxPadding: 0.05,
                minPadding: 0.05,
                startOnTick: !0,
                title: { rotation: 270, text: "Values" },
                stackLabels: {
                    enabled: !1,
                    formatter: function () {
                        return a.numberFormat(this.total, -1);
                    },
                    style: {
                        fontSize: "11px",
                        fontWeight: "bold",
                        color: "#000000",
                        textOutline: "1px contrast",
                    },
                },
                gridLineWidth: 1,
                lineWidth: 0,
            },
            defaultLeftAxisOptions: {
                labels: { x: -15 },
                title: { rotation: 270 },
            },
            defaultRightAxisOptions: {
                labels: { x: 15 },
                title: { rotation: 90 },
            },
            defaultBottomAxisOptions: {
                labels: { autoRotation: [-45], x: 0 },
                title: { rotation: 0 },
            },
            defaultTopAxisOptions: {
                labels: { autoRotation: [-45], x: 0 },
                title: { rotation: 0 },
            },
            init: function (a, b) {
                var c = b.isX;
                this.chart = a;
                this.horiz = a.inverted ? !c : c;
                this.isXAxis = c;
                this.coll = this.coll || (c ? "xAxis" : "yAxis");
                this.opposite = b.opposite;
                this.side =
                    b.side ||
                    (this.horiz
                        ? this.opposite
                            ? 0
                            : 2
                        : this.opposite
                        ? 1
                        : 3);
                this.setOptions(b);
                var e = this.options,
                    m = e.type;
                this.labelFormatter =
                    e.labels.formatter || this.defaultLabelFormatter;
                this.userOptions = b;
                this.minPixelPadding = 0;
                this.reversed = e.reversed;
                this.visible = !1 !== e.visible;
                this.zoomEnabled = !1 !== e.zoomEnabled;
                this.hasNames = "category" === m || !0 === e.categories;
                this.categories = e.categories || this.hasNames;
                this.names = this.names || [];
                this.isLog = "logarithmic" === m;
                this.isDatetimeAxis = "datetime" === m;
                this.isLinked = l(e.linkedTo);
                this.ticks = {};
                this.labelEdge = [];
                this.minorTicks = {};
                this.plotLinesAndBands = [];
                this.alternateBands = {};
                this.len = 0;
                this.minRange = this.userMinRange = e.minRange || e.maxZoom;
                this.range = e.range;
                this.offset = e.offset || 0;
                this.stacks = {};
                this.oldStacks = {};
                this.stacksTouched = 0;
                this.min = this.max = null;
                this.crosshair = I(
                    e.crosshair,
                    G(a.options.tooltip.crosshairs)[c ? 0 : 1],
                    !1
                );
                var z;
                b = this.options.events;
                -1 === C(this, a.axes) &&
                    (c
                        ? a.axes.splice(a.xAxis.length, 0, this)
                        : a.axes.push(this),
                    a[this.coll].push(this));
                this.series = this.series || [];
                a.inverted &&
                    c &&
                    void 0 === this.reversed &&
                    (this.reversed = !0);
                this.removePlotLine = this.removePlotBand =
                    this.removePlotBandOrLine;
                for (z in b) E(this, z, b[z]);
                this.isLog &&
                    ((this.val2lin = this.log2lin),
                    (this.lin2val = this.lin2log));
            },
            setOptions: function (a) {
                this.options = x(
                    this.defaultOptions,
                    "yAxis" === this.coll && this.defaultYAxisOptions,
                    [
                        this.defaultTopAxisOptions,
                        this.defaultRightAxisOptions,
                        this.defaultBottomAxisOptions,
                        this.defaultLeftAxisOptions,
                    ][this.side],
                    x(v[this.coll], a)
                );
            },
            defaultLabelFormatter: function () {
                var b = this.axis,
                    c = this.value,
                    e = b.categories,
                    h = this.dateTimeLabelFormat,
                    f = v.lang,
                    B = f.numericSymbols,
                    f = f.numericSymbolMagnitude || 1e3,
                    q = B && B.length,
                    d,
                    n = b.options.labels.format,
                    b = b.isLog ? c : b.tickInterval;
                if (n) d = k(n, this);
                else if (e) d = c;
                else if (h) d = a.dateFormat(h, c);
                else if (q && 1e3 <= b)
                    for (; q-- && void 0 === d; )
                        (e = Math.pow(f, q + 1)),
                            b >= e &&
                                0 === (10 * c) % e &&
                                null !== B[q] &&
                                0 !== c &&
                                (d = a.numberFormat(c / e, -1) + B[q]);
                void 0 === d &&
                    (d =
                        1e4 <= Math.abs(c)
                            ? a.numberFormat(c, -1)
                            : a.numberFormat(c, -1, void 0, ""));
                return d;
            },
            getSeriesExtremes: function () {
                var a = this,
                    e = a.chart;
                a.hasVisibleSeries = !1;
                a.dataMin = a.dataMax = a.threshold = null;
                a.softThreshold = !a.isXAxis;
                a.buildStacks && a.buildStacks();
                b(a.series, function (b) {
                    if (b.visible || !e.options.chart.ignoreHiddenSeries) {
                        var m = b.options,
                            z = m.threshold,
                            k;
                        a.hasVisibleSeries = !0;
                        a.isLog && 0 >= z && (z = null);
                        if (a.isXAxis)
                            (m = b.xData),
                                m.length &&
                                    ((b = H(m)),
                                    c(b) ||
                                        b instanceof Date ||
                                        ((m = h(m, function (a) {
                                            return c(a);
                                        })),
                                        (b = H(m))),
                                    (a.dataMin = Math.min(
                                        I(a.dataMin, m[0]),
                                        b
                                    )),
                                    (a.dataMax = Math.max(
                                        I(a.dataMax, m[0]),
                                        F(m)
                                    )));
                        else if (
                            (b.getExtremes(),
                            (k = b.dataMax),
                            (b = b.dataMin),
                            l(b) &&
                                l(k) &&
                                ((a.dataMin = Math.min(I(a.dataMin, b), b)),
                                (a.dataMax = Math.max(I(a.dataMax, k), k))),
                            l(z) && (a.threshold = z),
                            !m.softThreshold || a.isLog)
                        )
                            a.softThreshold = !1;
                    }
                });
            },
            translate: function (a, b, e, h, k, B) {
                var m = this.linkedParent || this,
                    z = 1,
                    f = 0,
                    q = h ? m.oldTransA : m.transA;
                h = h ? m.oldMin : m.min;
                var d = m.minPixelPadding;
                k = (m.isOrdinal || m.isBroken || (m.isLog && k)) && m.lin2val;
                q || (q = m.transA);
                e && ((z *= -1), (f = m.len));
                m.reversed && ((z *= -1), (f -= z * (m.sector || m.len)));
                b
                    ? ((a = (a * z + f - d) / q + h), k && (a = m.lin2val(a)))
                    : (k && (a = m.val2lin(a)),
                      (a = z * (a - h) * q + f + z * d + (c(B) ? q * B : 0)));
                return a;
            },
            toPixels: function (a, b) {
                return (
                    this.translate(a, !1, !this.horiz, null, !0) +
                    (b ? 0 : this.pos)
                );
            },
            toValue: function (a, b) {
                return this.translate(
                    a - (b ? 0 : this.pos),
                    !0,
                    !this.horiz,
                    null,
                    !0
                );
            },
            getPlotLinePath: function (a, b, e, h, k) {
                var m = this.chart,
                    z = this.left,
                    f = this.top,
                    q,
                    d,
                    n = (e && m.oldChartHeight) || m.chartHeight,
                    u = (e && m.oldChartWidth) || m.chartWidth,
                    g;
                q = this.transB;
                var t = function (a, b, c) {
                    if (a < b || a > c)
                        h ? (a = Math.min(Math.max(b, a), c)) : (g = !0);
                    return a;
                };
                k = I(k, this.translate(a, null, null, e));
                a = e = Math.round(k + q);
                q = d = Math.round(n - k - q);
                c(k)
                    ? this.horiz
                        ? ((q = f),
                          (d = n - this.bottom),
                          (a = e = t(a, z, z + this.width)))
                        : ((a = z),
                          (e = u - this.right),
                          (q = d = t(q, f, f + this.height)))
                    : (g = !0);
                return g && !h
                    ? null
                    : m.renderer.crispLine(["M", a, q, "L", e, d], b || 1);
            },
            getLinearTickPositions: function (a, b, e) {
                var m,
                    z = g(Math.floor(b / a) * a),
                    h = g(Math.ceil(e / a) * a),
                    k = [];
                if (b === e && c(b)) return [b];
                for (b = z; b <= h; ) {
                    k.push(b);
                    b = g(b + a);
                    if (b === m) break;
                    m = b;
                }
                return k;
            },
            getMinorTickPositions: function () {
                var a = this.options,
                    b = this.tickPositions,
                    c = this.minorTickInterval,
                    e = [],
                    h,
                    k = this.pointRangePadding || 0;
                h = this.min - k;
                var k = this.max + k,
                    f = k - h;
                if (f && f / c < this.len / 3)
                    if (this.isLog)
                        for (k = b.length, h = 1; h < k; h++)
                            e = e.concat(
                                this.getLogTickPositions(c, b[h - 1], b[h], !0)
                            );
                    else if (
                        this.isDatetimeAxis &&
                        "auto" === a.minorTickInterval
                    )
                        e = e.concat(
                            this.getTimeTicks(
                                this.normalizeTimeTickInterval(c),
                                h,
                                k,
                                a.startOfWeek
                            )
                        );
                    else
                        for (
                            b = h + ((b[0] - h) % c);
                            b <= k && b !== e[0];
                            b += c
                        )
                            e.push(b);
                0 !== e.length && this.trimTicks(e, a.startOnTick, a.endOnTick);
                return e;
            },
            adjustForMinRange: function () {
                var a = this.options,
                    c = this.min,
                    e = this.max,
                    h,
                    k = this.dataMax - this.dataMin >= this.minRange,
                    f,
                    q,
                    d,
                    n,
                    u,
                    g;
                this.isXAxis &&
                    void 0 === this.minRange &&
                    !this.isLog &&
                    (l(a.min) || l(a.max)
                        ? (this.minRange = null)
                        : (b(this.series, function (a) {
                              n = a.xData;
                              for (
                                  q = u = a.xIncrement ? 1 : n.length - 1;
                                  0 < q;
                                  q--
                              )
                                  if (
                                      ((d = n[q] - n[q - 1]),
                                      void 0 === f || d < f)
                                  )
                                      f = d;
                          }),
                          (this.minRange = Math.min(
                              5 * f,
                              this.dataMax - this.dataMin
                          ))));
                e - c < this.minRange &&
                    ((g = this.minRange),
                    (h = (g - e + c) / 2),
                    (h = [c - h, I(a.min, c - h)]),
                    k &&
                        (h[2] = this.isLog
                            ? this.log2lin(this.dataMin)
                            : this.dataMin),
                    (c = F(h)),
                    (e = [c + g, I(a.max, c + g)]),
                    k &&
                        (e[2] = this.isLog
                            ? this.log2lin(this.dataMax)
                            : this.dataMax),
                    (e = H(e)),
                    e - c < g &&
                        ((h[0] = e - g), (h[1] = I(a.min, e - g)), (c = F(h))));
                this.min = c;
                this.max = e;
            },
            getClosest: function () {
                var a;
                this.categories
                    ? (a = 1)
                    : b(this.series, function (b) {
                          var c = b.closestPointRange,
                              e =
                                  b.visible ||
                                  !b.chart.options.chart.ignoreHiddenSeries;
                          !b.noSharedTooltip &&
                              l(c) &&
                              e &&
                              (a = l(a) ? Math.min(a, c) : c);
                      });
                return a;
            },
            nameToX: function (a) {
                var b = u(this.categories),
                    c = b ? this.categories : this.names,
                    e = a.options.x,
                    m;
                a.series.requireSorting = !1;
                l(e) ||
                    (e =
                        !1 === this.options.uniqueNames
                            ? a.series.autoIncrement()
                            : C(a.name, c));
                -1 === e ? b || (m = c.length) : (m = e);
                this.names[m] = a.name;
                return m;
            },
            updateNames: function () {
                var a = this;
                0 < this.names.length &&
                    ((this.names.length = 0),
                    (this.minRange = void 0),
                    b(this.series || [], function (c) {
                        c.xIncrement = null;
                        if (!c.points || c.isDirtyData)
                            c.processData(), c.generatePoints();
                        b(c.points, function (b, e) {
                            var m;
                            b.options &&
                                void 0 === b.options.x &&
                                ((m = a.nameToX(b)),
                                m !== b.x && ((b.x = m), (c.xData[e] = m)));
                        });
                    }));
            },
            setAxisTranslation: function (a) {
                var c = this,
                    e = c.max - c.min,
                    m = c.axisPointRange || 0,
                    h,
                    k = 0,
                    f = 0,
                    d = c.linkedParent,
                    n = !!c.categories,
                    u = c.transA,
                    g = c.isXAxis;
                if (g || n || m)
                    (h = c.getClosest()),
                        d
                            ? ((k = d.minPointOffset),
                              (f = d.pointRangePadding))
                            : b(c.series, function (a) {
                                  var b = n
                                      ? 1
                                      : g
                                      ? I(a.options.pointRange, h, 0)
                                      : c.axisPointRange || 0;
                                  a = a.options.pointPlacement;
                                  m = Math.max(m, b);
                                  c.single ||
                                      ((k = Math.max(k, q(a) ? 0 : b / 2)),
                                      (f = Math.max(f, "on" === a ? 0 : b)));
                              }),
                        (d = c.ordinalSlope && h ? c.ordinalSlope / h : 1),
                        (c.minPointOffset = k *= d),
                        (c.pointRangePadding = f *= d),
                        (c.pointRange = Math.min(m, e)),
                        g && (c.closestPointRange = h);
                a && (c.oldTransA = u);
                c.translationSlope = c.transA = u = c.len / (e + f || 1);
                c.transB = c.horiz ? c.left : c.bottom;
                c.minPixelPadding = u * k;
            },
            minFromRange: function () {
                return this.max - this.range;
            },
            setTickInterval: function (a) {
                var m = this,
                    h = m.chart,
                    k = m.options,
                    f = m.isLog,
                    q = m.log2lin,
                    d = m.isDatetimeAxis,
                    u = m.isXAxis,
                    D = m.isLinked,
                    x = k.maxPadding,
                    C = k.minPadding,
                    G = k.tickInterval,
                    r = k.tickPixelInterval,
                    L = m.categories,
                    w = m.threshold,
                    p = m.softThreshold,
                    v,
                    N,
                    J,
                    A;
                d || L || D || this.getTickAmount();
                J = I(m.userMin, k.min);
                A = I(m.userMax, k.max);
                D
                    ? ((m.linkedParent = h[m.coll][k.linkedTo]),
                      (h = m.linkedParent.getExtremes()),
                      (m.min = I(h.min, h.dataMin)),
                      (m.max = I(h.max, h.dataMax)),
                      k.type !== m.linkedParent.options.type && n(11, 1))
                    : (!p &&
                          l(w) &&
                          (m.dataMin >= w
                              ? ((v = w), (C = 0))
                              : m.dataMax <= w && ((N = w), (x = 0))),
                      (m.min = I(J, v, m.dataMin)),
                      (m.max = I(A, N, m.dataMax)));
                f &&
                    (!a &&
                        0 >= Math.min(m.min, I(m.dataMin, m.min)) &&
                        n(10, 1),
                    (m.min = g(q(m.min), 15)),
                    (m.max = g(q(m.max), 15)));
                m.range &&
                    l(m.max) &&
                    ((m.userMin =
                        m.min =
                        J =
                            Math.max(m.min, m.minFromRange())),
                    (m.userMax = A = m.max),
                    (m.range = null));
                t(m, "foundExtremes");
                m.beforePadding && m.beforePadding();
                m.adjustForMinRange();
                !(L || m.axisPointRange || m.usePercentage || D) &&
                    l(m.min) &&
                    l(m.max) &&
                    (q = m.max - m.min) &&
                    (!l(J) && C && (m.min -= q * C),
                    !l(A) && x && (m.max += q * x));
                c(k.floor)
                    ? (m.min = Math.max(m.min, k.floor))
                    : c(k.softMin) && (m.min = Math.min(m.min, k.softMin));
                c(k.ceiling)
                    ? (m.max = Math.min(m.max, k.ceiling))
                    : c(k.softMax) && (m.max = Math.max(m.max, k.softMax));
                p &&
                    l(m.dataMin) &&
                    ((w = w || 0),
                    !l(J) && m.min < w && m.dataMin >= w
                        ? (m.min = w)
                        : !l(A) && m.max > w && m.dataMax <= w && (m.max = w));
                m.tickInterval =
                    m.min === m.max || void 0 === m.min || void 0 === m.max
                        ? 1
                        : D &&
                          !G &&
                          r === m.linkedParent.options.tickPixelInterval
                        ? (G = m.linkedParent.tickInterval)
                        : I(
                              G,
                              this.tickAmount
                                  ? (m.max - m.min) /
                                        Math.max(this.tickAmount - 1, 1)
                                  : void 0,
                              L ? 1 : ((m.max - m.min) * r) / Math.max(m.len, r)
                          );
                u &&
                    !a &&
                    b(m.series, function (a) {
                        a.processData(m.min !== m.oldMin || m.max !== m.oldMax);
                    });
                m.setAxisTranslation(!0);
                m.beforeSetTickPositions && m.beforeSetTickPositions();
                m.postProcessTickInterval &&
                    (m.tickInterval = m.postProcessTickInterval(
                        m.tickInterval
                    ));
                m.pointRange &&
                    !G &&
                    (m.tickInterval = Math.max(m.pointRange, m.tickInterval));
                a = I(
                    k.minTickInterval,
                    m.isDatetimeAxis && m.closestPointRange
                );
                !G && m.tickInterval < a && (m.tickInterval = a);
                d ||
                    f ||
                    G ||
                    (m.tickInterval = K(
                        m.tickInterval,
                        null,
                        e(m.tickInterval),
                        I(
                            k.allowDecimals,
                            !(
                                0.5 < m.tickInterval &&
                                5 > m.tickInterval &&
                                1e3 < m.max &&
                                9999 > m.max
                            )
                        ),
                        !!this.tickAmount
                    ));
                this.tickAmount || (m.tickInterval = m.unsquish());
                this.setTickPositions();
            },
            setTickPositions: function () {
                var a = this.options,
                    b,
                    c = a.tickPositions,
                    e = a.tickPositioner,
                    h = a.startOnTick,
                    k = a.endOnTick,
                    f;
                this.tickmarkOffset =
                    this.categories &&
                    "between" === a.tickmarkPlacement &&
                    1 === this.tickInterval
                        ? 0.5
                        : 0;
                this.minorTickInterval =
                    "auto" === a.minorTickInterval && this.tickInterval
                        ? this.tickInterval / 5
                        : a.minorTickInterval;
                this.tickPositions = b = c && c.slice();
                !b &&
                    ((b = this.isDatetimeAxis
                        ? this.getTimeTicks(
                              this.normalizeTimeTickInterval(
                                  this.tickInterval,
                                  a.units
                              ),
                              this.min,
                              this.max,
                              a.startOfWeek,
                              this.ordinalPositions,
                              this.closestPointRange,
                              !0
                          )
                        : this.isLog
                        ? this.getLogTickPositions(
                              this.tickInterval,
                              this.min,
                              this.max
                          )
                        : this.getLinearTickPositions(
                              this.tickInterval,
                              this.min,
                              this.max
                          )),
                    b.length > this.len && (b = [b[0], b.pop()]),
                    (this.tickPositions = b),
                    e && (e = e.apply(this, [this.min, this.max]))) &&
                    (this.tickPositions = b = e);
                this.isLinked ||
                    (this.trimTicks(b, h, k),
                    this.min === this.max &&
                        l(this.min) &&
                        !this.tickAmount &&
                        ((f = !0), (this.min -= 0.5), (this.max += 0.5)),
                    (this.single = f),
                    c || e || this.adjustTickAmount());
            },
            trimTicks: function (a, b, c) {
                var e = a[0],
                    m = a[a.length - 1],
                    h = this.minPointOffset || 0;
                if (b) this.min = e;
                else for (; this.min - h > a[0]; ) a.shift();
                if (c) this.max = m;
                else for (; this.max + h < a[a.length - 1]; ) a.pop();
                0 === a.length && l(e) && a.push((m + e) / 2);
            },
            alignToOthers: function () {
                var a = {},
                    c,
                    e = this.options;
                !1 !== this.chart.options.chart.alignTicks &&
                    !1 !== e.alignTicks &&
                    b(this.chart[this.coll], function (b) {
                        var e = b.options,
                            e = [
                                b.horiz ? e.left : e.top,
                                e.width,
                                e.height,
                                e.pane,
                            ].join();
                        b.series.length && (a[e] ? (c = !0) : (a[e] = 1));
                    });
                return c;
            },
            getTickAmount: function () {
                var a = this.options,
                    b = a.tickAmount,
                    c = a.tickPixelInterval;
                !l(a.tickInterval) &&
                    this.len < c &&
                    !this.isRadial &&
                    !this.isLog &&
                    a.startOnTick &&
                    a.endOnTick &&
                    (b = 2);
                !b && this.alignToOthers() && (b = Math.ceil(this.len / c) + 1);
                4 > b && ((this.finalTickAmt = b), (b = 5));
                this.tickAmount = b;
            },
            adjustTickAmount: function () {
                var a = this.tickInterval,
                    b = this.tickPositions,
                    c = this.tickAmount,
                    e = this.finalTickAmt,
                    h = b && b.length;
                if (h < c) {
                    for (; b.length < c; ) b.push(g(b[b.length - 1] + a));
                    this.transA *= (h - 1) / (c - 1);
                    this.max = b[b.length - 1];
                } else
                    h > c &&
                        ((this.tickInterval *= 2), this.setTickPositions());
                if (l(e)) {
                    for (a = c = b.length; a--; )
                        ((3 === e && 1 === a % 2) ||
                            (2 >= e && 0 < a && a < c - 1)) &&
                            b.splice(a, 1);
                    this.finalTickAmt = void 0;
                }
            },
            setScale: function () {
                var a, c;
                this.oldMin = this.min;
                this.oldMax = this.max;
                this.oldAxisLength = this.len;
                this.setAxisSize();
                c = this.len !== this.oldAxisLength;
                b(this.series, function (b) {
                    if (b.isDirtyData || b.isDirty || b.xAxis.isDirty) a = !0;
                });
                c ||
                a ||
                this.isLinked ||
                this.forceRedraw ||
                this.userMin !== this.oldUserMin ||
                this.userMax !== this.oldUserMax ||
                this.alignToOthers()
                    ? (this.resetStacks && this.resetStacks(),
                      (this.forceRedraw = !1),
                      this.getSeriesExtremes(),
                      this.setTickInterval(),
                      (this.oldUserMin = this.userMin),
                      (this.oldUserMax = this.userMax),
                      this.isDirty ||
                          (this.isDirty =
                              c ||
                              this.min !== this.oldMin ||
                              this.max !== this.oldMax))
                    : this.cleanStacks && this.cleanStacks();
            },
            setExtremes: function (a, c, e, h, k) {
                var m = this,
                    f = m.chart;
                e = I(e, !0);
                b(m.series, function (a) {
                    delete a.kdTree;
                });
                k = w(k, { min: a, max: c });
                t(m, "setExtremes", k, function () {
                    m.userMin = a;
                    m.userMax = c;
                    m.eventArgs = k;
                    e && f.redraw(h);
                });
            },
            zoom: function (a, b) {
                var c = this.dataMin,
                    e = this.dataMax,
                    m = this.options,
                    h = Math.min(c, I(m.min, c)),
                    m = Math.max(e, I(m.max, e));
                if (a !== this.min || b !== this.max)
                    this.allowZoomOutside ||
                        (l(c) && (a < h && (a = h), a > m && (a = m)),
                        l(e) && (b < h && (b = h), b > m && (b = m))),
                        (this.displayBtn = void 0 !== a || void 0 !== b),
                        this.setExtremes(a, b, !1, void 0, { trigger: "zoom" });
                return !0;
            },
            setAxisSize: function () {
                var a = this.chart,
                    b = this.options,
                    c = b.offsetLeft || 0,
                    e = this.horiz,
                    h = I(b.width, a.plotWidth - c + (b.offsetRight || 0)),
                    k = I(b.height, a.plotHeight),
                    f = I(b.top, a.plotTop),
                    b = I(b.left, a.plotLeft + c),
                    c = /%$/;
                c.test(k) &&
                    (k = Math.round((parseFloat(k) / 100) * a.plotHeight));
                c.test(f) &&
                    (f = Math.round(
                        (parseFloat(f) / 100) * a.plotHeight + a.plotTop
                    ));
                this.left = b;
                this.top = f;
                this.width = h;
                this.height = k;
                this.bottom = a.chartHeight - k - f;
                this.right = a.chartWidth - h - b;
                this.len = Math.max(e ? h : k, 0);
                this.pos = e ? b : f;
            },
            getExtremes: function () {
                var a = this.isLog,
                    b = this.lin2log;
                return {
                    min: a ? g(b(this.min)) : this.min,
                    max: a ? g(b(this.max)) : this.max,
                    dataMin: this.dataMin,
                    dataMax: this.dataMax,
                    userMin: this.userMin,
                    userMax: this.userMax,
                };
            },
            getThreshold: function (a) {
                var b = this.isLog,
                    c = this.lin2log,
                    e = b ? c(this.min) : this.min,
                    b = b ? c(this.max) : this.max;
                null === a ? (a = e) : e > a ? (a = e) : b < a && (a = b);
                return this.translate(a, 0, 1, 0, 1);
            },
            autoLabelAlign: function (a) {
                a = (I(a, 0) - 90 * this.side + 720) % 360;
                return 15 < a && 165 > a
                    ? "right"
                    : 195 < a && 345 > a
                    ? "left"
                    : "center";
            },
            tickSize: function (a) {
                var b = this.options,
                    c = b[a + "Length"],
                    e = I(b[a + "Width"], "tick" === a && this.isXAxis ? 1 : 0);
                if (e && c)
                    return "inside" === b[a + "Position"] && (c = -c), [c, e];
            },
            labelMetrics: function () {
                return this.chart.renderer.fontMetrics(
                    this.options.labels.style &&
                        this.options.labels.style.fontSize,
                    this.ticks[0] && this.ticks[0].label
                );
            },
            unsquish: function () {
                var a = this.options.labels,
                    c = this.horiz,
                    e = this.tickInterval,
                    h = e,
                    k =
                        this.len /
                        (((this.categories ? 1 : 0) + this.max - this.min) / e),
                    f,
                    q = a.rotation,
                    d = this.labelMetrics(),
                    n,
                    u = Number.MAX_VALUE,
                    g,
                    t = function (a) {
                        a /= k || 1;
                        a = 1 < a ? Math.ceil(a) : 1;
                        return a * e;
                    };
                c
                    ? (g =
                          !a.staggerLines &&
                          !a.step &&
                          (l(q)
                              ? [q]
                              : k < I(a.autoRotationLimit, 80) &&
                                a.autoRotation)) &&
                      b(g, function (a) {
                          var b;
                          if (a === q || (a && -90 <= a && 90 >= a))
                              (n = t(Math.abs(d.h / Math.sin(r * a)))),
                                  (b = n + Math.abs(a / 360)),
                                  b < u && ((u = b), (f = a), (h = n));
                      })
                    : a.step || (h = t(d.h));
                this.autoRotation = g;
                this.labelRotation = I(f, q);
                return h;
            },
            getSlotWidth: function () {
                var a = this.chart,
                    b = this.horiz,
                    c = this.options.labels,
                    e = Math.max(
                        this.tickPositions.length - (this.categories ? 0 : 1),
                        1
                    ),
                    h = a.margin[3];
                return (
                    (b &&
                        2 > (c.step || 0) &&
                        !c.rotation &&
                        ((this.staggerLines || 1) * a.plotWidth) / e) ||
                    (!b && ((h && h - a.spacing[3]) || 0.33 * a.chartWidth))
                );
            },
            renderUnsquish: function () {
                var a = this.chart,
                    c = a.renderer,
                    e = this.tickPositions,
                    h = this.ticks,
                    k = this.options.labels,
                    f = this.horiz,
                    d = this.getSlotWidth(),
                    n = Math.max(1, Math.round(d - 2 * (k.padding || 5))),
                    u = {},
                    g = this.labelMetrics(),
                    t = k.style && k.style.textOverflow,
                    D,
                    C = 0,
                    G,
                    l;
                q(k.rotation) || (u.rotation = k.rotation || 0);
                b(e, function (a) {
                    (a = h[a]) && a.labelLength > C && (C = a.labelLength);
                });
                this.maxLabelLength = C;
                if (this.autoRotation)
                    C > n && C > g.h
                        ? (u.rotation = this.labelRotation)
                        : (this.labelRotation = 0);
                else if (d && ((D = { width: n + "px" }), !t))
                    for (D.textOverflow = "clip", G = e.length; !f && G--; )
                        if (((l = e[G]), (n = h[l].label)))
                            n.styles && "ellipsis" === n.styles.textOverflow
                                ? n.css({ textOverflow: "clip" })
                                : h[l].labelLength > d &&
                                  n.css({ width: d + "px" }),
                                n.getBBox().height >
                                    this.len / e.length - (g.h - g.f) &&
                                    (n.specCss = { textOverflow: "ellipsis" });
                u.rotation &&
                    ((D = {
                        width:
                            (C > 0.5 * a.chartHeight
                                ? 0.33 * a.chartHeight
                                : a.chartHeight) + "px",
                    }),
                    t || (D.textOverflow = "ellipsis"));
                if (
                    (this.labelAlign =
                        k.align || this.autoLabelAlign(this.labelRotation))
                )
                    u.align = this.labelAlign;
                b(e, function (a) {
                    var b = (a = h[a]) && a.label;
                    b &&
                        (b.attr(u),
                        D && b.css(x(D, b.specCss)),
                        delete b.specCss,
                        (a.rotation = u.rotation));
                });
                this.tickRotCorr = c.rotCorr(
                    g.b,
                    this.labelRotation || 0,
                    0 !== this.side
                );
            },
            hasData: function () {
                return (
                    this.hasVisibleSeries ||
                    (l(this.min) && l(this.max) && !!this.tickPositions)
                );
            },
            getOffset: function () {
                var a = this,
                    c = a.chart,
                    e = c.renderer,
                    h = a.options,
                    k = a.tickPositions,
                    f = a.ticks,
                    q = a.horiz,
                    d = a.side,
                    n = c.inverted ? [1, 0, 3, 2][d] : d,
                    u,
                    g,
                    t = 0,
                    D,
                    x = 0,
                    C = h.title,
                    G = h.labels,
                    r = 0,
                    L = a.opposite,
                    w = c.axisOffset,
                    c = c.clipOffset,
                    p = [-1, 1, 1, -1][d],
                    K,
                    v = h.className,
                    J = a.axisParent,
                    A = this.tickSize("tick");
                u = a.hasData();
                a.showAxis = g = u || I(h.showEmpty, !0);
                a.staggerLines = a.horiz && G.staggerLines;
                a.axisGroup ||
                    ((a.gridGroup = e
                        .g("grid")
                        .attr({ zIndex: h.gridZIndex || 1 })
                        .addClass(
                            "highcharts-" +
                                this.coll.toLowerCase() +
                                "-grid " +
                                (v || "")
                        )
                        .add(J)),
                    (a.axisGroup = e
                        .g("axis")
                        .attr({ zIndex: h.zIndex || 2 })
                        .addClass(
                            "highcharts-" +
                                this.coll.toLowerCase() +
                                " " +
                                (v || "")
                        )
                        .add(J)),
                    (a.labelGroup = e
                        .g("axis-labels")
                        .attr({ zIndex: G.zIndex || 7 })
                        .addClass(
                            "highcharts-" +
                                a.coll.toLowerCase() +
                                "-labels " +
                                (v || "")
                        )
                        .add(J)));
                if (u || a.isLinked)
                    b(k, function (b) {
                        f[b] ? f[b].addLabel() : (f[b] = new N(a, b));
                    }),
                        a.renderUnsquish(),
                        !1 === G.reserveSpace ||
                            (0 !== d &&
                                2 !== d &&
                                { 1: "left", 3: "right" }[d] !== a.labelAlign &&
                                "center" !== a.labelAlign) ||
                            b(k, function (a) {
                                r = Math.max(f[a].getLabelSize(), r);
                            }),
                        a.staggerLines &&
                            ((r *= a.staggerLines),
                            (a.labelOffset = r * (a.opposite ? -1 : 1)));
                else for (K in f) f[K].destroy(), delete f[K];
                C &&
                    C.text &&
                    !1 !== C.enabled &&
                    (a.axisTitle ||
                        ((K = C.textAlign) ||
                            (K = (
                                q
                                    ? {
                                          low: "left",
                                          middle: "center",
                                          high: "right",
                                      }
                                    : {
                                          low: L ? "right" : "left",
                                          middle: "center",
                                          high: L ? "left" : "right",
                                      }
                            )[C.align]),
                        (a.axisTitle = e
                            .text(C.text, 0, 0, C.useHTML)
                            .attr({
                                zIndex: 7,
                                rotation: C.rotation || 0,
                                align: K,
                            })
                            .addClass("highcharts-axis-title")
                            .css(C.style)
                            .add(a.axisGroup)),
                        (a.axisTitle.isNew = !0)),
                    g &&
                        ((t = a.axisTitle.getBBox()[q ? "height" : "width"]),
                        (D = C.offset),
                        (x = l(D) ? 0 : I(C.margin, q ? 5 : 10))),
                    a.axisTitle[g ? "show" : "hide"](!0));
                a.renderLine();
                a.offset = p * I(h.offset, w[d]);
                a.tickRotCorr = a.tickRotCorr || { x: 0, y: 0 };
                e =
                    0 === d
                        ? -a.labelMetrics().h
                        : 2 === d
                        ? a.tickRotCorr.y
                        : 0;
                x = Math.abs(r) + x;
                r &&
                    (x =
                        x -
                        e +
                        p * (q ? I(G.y, a.tickRotCorr.y + 8 * p) : G.x));
                a.axisTitleMargin = I(D, x);
                w[d] = Math.max(
                    w[d],
                    a.axisTitleMargin + t + p * a.offset,
                    x,
                    u && k.length && A ? A[0] : 0
                );
                h = h.offset ? 0 : 2 * Math.floor(a.axisLine.strokeWidth() / 2);
                c[n] = Math.max(c[n], h);
            },
            getLinePath: function (a) {
                var b = this.chart,
                    c = this.opposite,
                    e = this.offset,
                    m = this.horiz,
                    h = this.left + (c ? this.width : 0) + e,
                    e = b.chartHeight - this.bottom - (c ? this.height : 0) + e;
                c && (a *= -1);
                return b.renderer.crispLine(
                    [
                        "M",
                        m ? this.left : h,
                        m ? e : this.top,
                        "L",
                        m ? b.chartWidth - this.right : h,
                        m ? e : b.chartHeight - this.bottom,
                    ],
                    a
                );
            },
            renderLine: function () {
                this.axisLine ||
                    ((this.axisLine = this.chart.renderer
                        .path()
                        .addClass("highcharts-axis-line")
                        .add(this.axisGroup)),
                    this.axisLine.attr({
                        stroke: this.options.lineColor,
                        "stroke-width": this.options.lineWidth,
                        zIndex: 7,
                    }));
            },
            getTitlePosition: function () {
                var a = this.horiz,
                    b = this.left,
                    c = this.top,
                    e = this.len,
                    h = this.options.title,
                    k = a ? b : c,
                    f = this.opposite,
                    q = this.offset,
                    d = h.x || 0,
                    n = h.y || 0,
                    u = this.chart.renderer.fontMetrics(
                        h.style && h.style.fontSize,
                        this.axisTitle
                    ).f,
                    e = {
                        low: k + (a ? 0 : e),
                        middle: k + e / 2,
                        high: k + (a ? e : 0),
                    }[h.align],
                    b =
                        (a ? c + this.height : b) +
                        (a ? 1 : -1) * (f ? -1 : 1) * this.axisTitleMargin +
                        (2 === this.side ? u : 0);
                return {
                    x: a ? e + d : b + (f ? this.width : 0) + q + d,
                    y: a ? b + n - (f ? this.height : 0) + q : e + n,
                };
            },
            render: function () {
                var a = this,
                    e = a.chart,
                    h = e.renderer,
                    k = a.options,
                    f = a.isLog,
                    q = a.lin2log,
                    d = a.isLinked,
                    n = a.tickPositions,
                    u = a.axisTitle,
                    g = a.ticks,
                    t = a.minorTicks,
                    D = a.alternateBands,
                    C = k.stackLabels,
                    x = k.alternateGridColor,
                    G = a.tickmarkOffset,
                    l = a.axisLine,
                    r = e.hasRendered && c(a.oldMin),
                    w = a.showAxis,
                    I = A(h.globalAnimation),
                    p,
                    K;
                a.labelEdge.length = 0;
                a.overlap = !1;
                b([g, t, D], function (a) {
                    for (var b in a) a[b].isActive = !1;
                });
                if (a.hasData() || d)
                    a.minorTickInterval &&
                        !a.categories &&
                        b(a.getMinorTickPositions(), function (b) {
                            t[b] || (t[b] = new N(a, b, "minor"));
                            r && t[b].isNew && t[b].render(null, !0);
                            t[b].render(null, !1, 1);
                        }),
                        n.length &&
                            (b(n, function (b, c) {
                                if (!d || (b >= a.min && b <= a.max))
                                    g[b] || (g[b] = new N(a, b)),
                                        r &&
                                            g[b].isNew &&
                                            g[b].render(c, !0, 0.1),
                                        g[b].render(c);
                            }),
                            G &&
                                (0 === a.min || a.single) &&
                                (g[-1] || (g[-1] = new N(a, -1, null, !0)),
                                g[-1].render(-1))),
                        x &&
                            b(n, function (b, c) {
                                K =
                                    void 0 !== n[c + 1]
                                        ? n[c + 1] + G
                                        : a.max - G;
                                0 === c % 2 &&
                                    b < a.max &&
                                    K <= a.max + (e.polar ? -G : G) &&
                                    (D[b] || (D[b] = new J(a)),
                                    (p = b + G),
                                    (D[b].options = {
                                        from: f ? q(p) : p,
                                        to: f ? q(K) : K,
                                        color: x,
                                    }),
                                    D[b].render(),
                                    (D[b].isActive = !0));
                            }),
                        a._addedPlotLB ||
                            (b(
                                (k.plotLines || []).concat(k.plotBands || []),
                                function (b) {
                                    a.addPlotBandOrLine(b);
                                }
                            ),
                            (a._addedPlotLB = !0));
                b([g, t, D], function (a) {
                    var b,
                        c,
                        h = [],
                        k = I.duration;
                    for (b in a)
                        a[b].isActive ||
                            (a[b].render(b, !1, 0),
                            (a[b].isActive = !1),
                            h.push(b));
                    L(
                        function () {
                            for (c = h.length; c--; )
                                a[h[c]] &&
                                    !a[h[c]].isActive &&
                                    (a[h[c]].destroy(), delete a[h[c]]);
                        },
                        a !== D && e.hasRendered && k ? k : 0
                    );
                });
                l &&
                    (l[l.isPlaced ? "animate" : "attr"]({
                        d: this.getLinePath(l.strokeWidth()),
                    }),
                    (l.isPlaced = !0),
                    l[w ? "show" : "hide"](!0));
                u &&
                    w &&
                    (u[u.isNew ? "attr" : "animate"](a.getTitlePosition()),
                    (u.isNew = !1));
                C && C.enabled && a.renderStackTotals();
                a.isDirty = !1;
            },
            redraw: function () {
                this.visible &&
                    (this.render(),
                    b(this.plotLinesAndBands, function (a) {
                        a.render();
                    }));
                b(this.series, function (a) {
                    a.isDirty = !0;
                });
            },
            keepProps: "extKey hcEvents names series userMax userMin".split(
                " "
            ),
            destroy: function (a) {
                var c = this,
                    e = c.stacks,
                    h,
                    k = c.plotLinesAndBands,
                    m;
                a || D(c);
                for (h in e) f(e[h]), (e[h] = null);
                b([c.ticks, c.minorTicks, c.alternateBands], function (a) {
                    f(a);
                });
                if (k) for (a = k.length; a--; ) k[a].destroy();
                b(
                    "stackTotalGroup axisLine axisTitle axisGroup gridGroup labelGroup cross".split(
                        " "
                    ),
                    function (a) {
                        c[a] && (c[a] = c[a].destroy());
                    }
                );
                for (m in c)
                    c.hasOwnProperty(m) &&
                        -1 === C(m, c.keepProps) &&
                        delete c[m];
            },
            drawCrosshair: function (a, b) {
                var c,
                    e = this.crosshair,
                    h = I(e.snap, !0),
                    k,
                    m = this.cross;
                a || (a = this.cross && this.cross.e);
                this.crosshair && !1 !== (l(b) || !h)
                    ? (h
                          ? l(b) &&
                            (k = this.isXAxis ? b.plotX : this.len - b.plotY)
                          : (k =
                                a &&
                                (this.horiz
                                    ? a.chartX - this.pos
                                    : this.len - a.chartY + this.pos)),
                      l(k) &&
                          (c =
                              this.getPlotLinePath(
                                  b && (this.isXAxis ? b.x : I(b.stackY, b.y)),
                                  null,
                                  null,
                                  null,
                                  k
                              ) || null),
                      l(c)
                          ? ((b = this.categories && !this.isRadial),
                            m ||
                                ((this.cross = m =
                                    this.chart.renderer
                                        .path()
                                        .addClass(
                                            "highcharts-crosshair highcharts-crosshair-" +
                                                (b ? "category " : "thin ") +
                                                e.className
                                        )
                                        .attr({ zIndex: I(e.zIndex, 2) })
                                        .add()),
                                m.attr({
                                    stroke:
                                        e.color ||
                                        (b
                                            ? d("#ccd6eb")
                                                  .setOpacity(0.25)
                                                  .get()
                                            : "#cccccc"),
                                    "stroke-width": I(e.width, 1),
                                }),
                                e.dashStyle &&
                                    m.attr({ dashstyle: e.dashStyle })),
                            m.show().attr({ d: c }),
                            b &&
                                !e.width &&
                                m.attr({ "stroke-width": this.transA }),
                            (this.cross.e = a))
                          : this.hideCrosshair())
                    : this.hideCrosshair();
            },
            hideCrosshair: function () {
                this.cross && this.cross.hide();
            },
        };
        w(a.Axis.prototype, p);
    })(M);
    (function (a) {
        var E = a.Axis,
            A = a.Date,
            F = a.dateFormat,
            H = a.defaultOptions,
            p = a.defined,
            d = a.each,
            g = a.extend,
            v = a.getMagnitude,
            l = a.getTZOffset,
            r = a.normalizeTickInterval,
            f = a.pick,
            b = a.timeUnits;
        E.prototype.getTimeTicks = function (a, r, t, k) {
            var e = [],
                h = {},
                n = H.global.useUTC,
                u,
                c = new A(r - l(r)),
                q = A.hcMakeTime,
                x = a.unitRange,
                w = a.count,
                I;
            if (p(r)) {
                c[A.hcSetMilliseconds](
                    x >= b.second ? 0 : w * Math.floor(c.getMilliseconds() / w)
                );
                if (x >= b.second)
                    c[A.hcSetSeconds](
                        x >= b.minute ? 0 : w * Math.floor(c.getSeconds() / w)
                    );
                if (x >= b.minute)
                    c[A.hcSetMinutes](
                        x >= b.hour
                            ? 0
                            : w * Math.floor(c[A.hcGetMinutes]() / w)
                    );
                if (x >= b.hour)
                    c[A.hcSetHours](
                        x >= b.day ? 0 : w * Math.floor(c[A.hcGetHours]() / w)
                    );
                if (x >= b.day)
                    c[A.hcSetDate](
                        x >= b.month ? 1 : w * Math.floor(c[A.hcGetDate]() / w)
                    );
                x >= b.month &&
                    (c[A.hcSetMonth](
                        x >= b.year ? 0 : w * Math.floor(c[A.hcGetMonth]() / w)
                    ),
                    (u = c[A.hcGetFullYear]()));
                if (x >= b.year) c[A.hcSetFullYear](u - (u % w));
                if (x === b.week)
                    c[A.hcSetDate](
                        c[A.hcGetDate]() - c[A.hcGetDay]() + f(k, 1)
                    );
                u = c[A.hcGetFullYear]();
                k = c[A.hcGetMonth]();
                var v = c[A.hcGetDate](),
                    D = c[A.hcGetHours]();
                if (A.hcTimezoneOffset || A.hcGetTimezoneOffset)
                    (I =
                        (!n || !!A.hcGetTimezoneOffset) &&
                        (t - r > 4 * b.month || l(r) !== l(t))),
                        (c = c.getTime()),
                        (c = new A(c + l(c)));
                n = c.getTime();
                for (r = 1; n < t; )
                    e.push(n),
                        (n =
                            x === b.year
                                ? q(u + r * w, 0)
                                : x === b.month
                                ? q(u, k + r * w)
                                : !I || (x !== b.day && x !== b.week)
                                ? I && x === b.hour
                                    ? q(u, k, v, D + r * w)
                                    : n + x * w
                                : q(u, k, v + r * w * (x === b.day ? 1 : 7))),
                        r++;
                e.push(n);
                x <= b.hour &&
                    d(e, function (a) {
                        "000000000" === F("%H%M%S%L", a) && (h[a] = "day");
                    });
            }
            e.info = g(a, { higherRanks: h, totalRange: x * w });
            return e;
        };
        E.prototype.normalizeTimeTickInterval = function (a, f) {
            var d = f || [
                ["millisecond", [1, 2, 5, 10, 20, 25, 50, 100, 200, 500]],
                ["second", [1, 2, 5, 10, 15, 30]],
                ["minute", [1, 2, 5, 10, 15, 30]],
                ["hour", [1, 2, 3, 4, 6, 8, 12]],
                ["day", [1, 2]],
                ["week", [1, 2]],
                ["month", [1, 2, 3, 4, 6]],
                ["year", null],
            ];
            f = d[d.length - 1];
            var k = b[f[0]],
                e = f[1],
                h;
            for (
                h = 0;
                h < d.length &&
                !((f = d[h]),
                (k = b[f[0]]),
                (e = f[1]),
                d[h + 1] && a <= (k * e[e.length - 1] + b[d[h + 1][0]]) / 2);
                h++
            );
            k === b.year && a < 5 * k && (e = [1, 2, 5]);
            a = r(a / k, e, "year" === f[0] ? Math.max(v(a / k), 1) : 1);
            return { unitRange: k, count: a, unitName: f[0] };
        };
    })(M);
    (function (a) {
        var E = a.Axis,
            A = a.getMagnitude,
            F = a.map,
            H = a.normalizeTickInterval,
            p = a.pick;
        E.prototype.getLogTickPositions = function (a, g, v, l) {
            var d = this.options,
                f = this.len,
                b = this.lin2log,
                n = this.log2lin,
                w = [];
            l || (this._minorAutoInterval = null);
            if (0.5 <= a)
                (a = Math.round(a)), (w = this.getLinearTickPositions(a, g, v));
            else if (0.08 <= a)
                for (
                    var f = Math.floor(g),
                        t,
                        k,
                        e,
                        h,
                        C,
                        d =
                            0.3 < a
                                ? [1, 2, 4]
                                : 0.15 < a
                                ? [1, 2, 4, 6, 8]
                                : [1, 2, 3, 4, 5, 6, 7, 8, 9];
                    f < v + 1 && !C;
                    f++
                )
                    for (k = d.length, t = 0; t < k && !C; t++)
                        (e = n(b(f) * d[t])),
                            e > g &&
                                (!l || h <= v) &&
                                void 0 !== h &&
                                w.push(h),
                            h > v && (C = !0),
                            (h = e);
            else
                (g = b(g)),
                    (v = b(v)),
                    (a = d[l ? "minorTickInterval" : "tickInterval"]),
                    (a = p(
                        "auto" === a ? null : a,
                        this._minorAutoInterval,
                        ((d.tickPixelInterval / (l ? 5 : 1)) * (v - g)) /
                            ((l ? f / this.tickPositions.length : f) || 1)
                    )),
                    (a = H(a, null, A(a))),
                    (w = F(this.getLinearTickPositions(a, g, v), n)),
                    l || (this._minorAutoInterval = a / 5);
            l || (this.tickInterval = a);
            return w;
        };
        E.prototype.log2lin = function (a) {
            return Math.log(a) / Math.LN10;
        };
        E.prototype.lin2log = function (a) {
            return Math.pow(10, a);
        };
    })(M);
    (function (a) {
        var E = a.dateFormat,
            A = a.each,
            F = a.extend,
            H = a.format,
            p = a.isNumber,
            d = a.map,
            g = a.merge,
            v = a.pick,
            l = a.splat,
            r = a.syncTimeout,
            f = a.timeUnits;
        a.Tooltip = function () {
            this.init.apply(this, arguments);
        };
        a.Tooltip.prototype = {
            init: function (a, f) {
                this.chart = a;
                this.options = f;
                this.crosshairs = [];
                this.now = { x: 0, y: 0 };
                this.isHidden = !0;
                this.split = f.split && !a.inverted;
                this.shared = f.shared || this.split;
            },
            cleanSplit: function (a) {
                A(this.chart.series, function (b) {
                    var f = b && b.tt;
                    f &&
                        (!f.isActive || a
                            ? (b.tt = f.destroy())
                            : (f.isActive = !1));
                });
            },
            getLabel: function () {
                var a = this.chart.renderer,
                    f = this.options;
                this.label ||
                    (this.split
                        ? (this.label = a.g("tooltip"))
                        : ((this.label = a
                              .label(
                                  "",
                                  0,
                                  0,
                                  f.shape || "callout",
                                  null,
                                  null,
                                  f.useHTML,
                                  null,
                                  "tooltip"
                              )
                              .attr({ padding: f.padding, r: f.borderRadius })),
                          this.label
                              .attr({
                                  fill: f.backgroundColor,
                                  "stroke-width": f.borderWidth,
                              })
                              .css(f.style)
                              .shadow(f.shadow)),
                    this.label.attr({ zIndex: 8 }).add());
                return this.label;
            },
            update: function (a) {
                this.destroy();
                this.init(this.chart, g(!0, this.options, a));
            },
            destroy: function () {
                this.label && (this.label = this.label.destroy());
                this.split &&
                    this.tt &&
                    (this.cleanSplit(this.chart, !0),
                    (this.tt = this.tt.destroy()));
                clearTimeout(this.hideTimer);
                clearTimeout(this.tooltipTimeout);
            },
            move: function (a, f, d, g) {
                var b = this,
                    e = b.now,
                    h =
                        !1 !== b.options.animation &&
                        !b.isHidden &&
                        (1 < Math.abs(a - e.x) || 1 < Math.abs(f - e.y)),
                    n = b.followPointer || 1 < b.len;
                F(e, {
                    x: h ? (2 * e.x + a) / 3 : a,
                    y: h ? (e.y + f) / 2 : f,
                    anchorX: n ? void 0 : h ? (2 * e.anchorX + d) / 3 : d,
                    anchorY: n ? void 0 : h ? (e.anchorY + g) / 2 : g,
                });
                b.getLabel().attr(e);
                h &&
                    (clearTimeout(this.tooltipTimeout),
                    (this.tooltipTimeout = setTimeout(function () {
                        b && b.move(a, f, d, g);
                    }, 32)));
            },
            hide: function (a) {
                var b = this;
                clearTimeout(this.hideTimer);
                a = v(a, this.options.hideDelay, 500);
                this.isHidden ||
                    (this.hideTimer = r(function () {
                        b.getLabel()[a ? "fadeOut" : "hide"]();
                        b.isHidden = !0;
                    }, a));
            },
            getAnchor: function (a, f) {
                var b,
                    n = this.chart,
                    k = n.inverted,
                    e = n.plotTop,
                    h = n.plotLeft,
                    g = 0,
                    u = 0,
                    c,
                    q;
                a = l(a);
                b = a[0].tooltipPos;
                this.followPointer &&
                    f &&
                    (void 0 === f.chartX && (f = n.pointer.normalize(f)),
                    (b = [f.chartX - n.plotLeft, f.chartY - e]));
                b ||
                    (A(a, function (a) {
                        c = a.series.yAxis;
                        q = a.series.xAxis;
                        g += a.plotX + (!k && q ? q.left - h : 0);
                        u +=
                            (a.plotLow
                                ? (a.plotLow + a.plotHigh) / 2
                                : a.plotY) + (!k && c ? c.top - e : 0);
                    }),
                    (g /= a.length),
                    (u /= a.length),
                    (b = [
                        k ? n.plotWidth - u : g,
                        this.shared && !k && 1 < a.length && f
                            ? f.chartY - e
                            : k
                            ? n.plotHeight - g
                            : u,
                    ]));
                return d(b, Math.round);
            },
            getPosition: function (a, f, d) {
                var b = this.chart,
                    k = this.distance,
                    e = {},
                    h = d.h || 0,
                    n,
                    u = [
                        "y",
                        b.chartHeight,
                        f,
                        d.plotY + b.plotTop,
                        b.plotTop,
                        b.plotTop + b.plotHeight,
                    ],
                    c = [
                        "x",
                        b.chartWidth,
                        a,
                        d.plotX + b.plotLeft,
                        b.plotLeft,
                        b.plotLeft + b.plotWidth,
                    ],
                    q =
                        !this.followPointer &&
                        v(d.ttBelow, !b.inverted === !!d.negative),
                    g = function (a, b, c, f, m, d) {
                        var n = c < f - k,
                            u = f + k + c < b,
                            g = f - k - c;
                        f += k;
                        if (q && u) e[a] = f;
                        else if (!q && n) e[a] = g;
                        else if (n)
                            e[a] = Math.min(d - c, 0 > g - h ? g : g - h);
                        else if (u)
                            e[a] = Math.max(m, f + h + c > b ? f : f + h);
                        else return !1;
                    },
                    l = function (a, b, c, h) {
                        var m;
                        h < k || h > b - k
                            ? (m = !1)
                            : (e[a] =
                                  h < c / 2
                                      ? 1
                                      : h > b - c / 2
                                      ? b - c - 2
                                      : h - c / 2);
                        return m;
                    },
                    r = function (a) {
                        var b = u;
                        u = c;
                        c = b;
                        n = a;
                    },
                    p = function () {
                        !1 !== g.apply(0, u)
                            ? !1 !== l.apply(0, c) || n || (r(!0), p())
                            : n
                            ? (e.x = e.y = 0)
                            : (r(!0), p());
                    };
                (b.inverted || 1 < this.len) && r();
                p();
                return e;
            },
            defaultFormatter: function (a) {
                var b = this.points || l(this),
                    f;
                f = [a.tooltipFooterHeaderFormatter(b[0])];
                f = f.concat(a.bodyFormatter(b));
                f.push(a.tooltipFooterHeaderFormatter(b[0], !0));
                return f;
            },
            refresh: function (a, f) {
                var b = this.chart,
                    d,
                    k = this.options,
                    e,
                    h,
                    n = {},
                    u = [];
                d = k.formatter || this.defaultFormatter;
                var n = b.hoverPoints,
                    c = this.shared;
                clearTimeout(this.hideTimer);
                this.followPointer =
                    l(a)[0].series.tooltipOptions.followPointer;
                h = this.getAnchor(a, f);
                f = h[0];
                e = h[1];
                !c || (a.series && a.series.noSharedTooltip)
                    ? (n = a.getLabelConfig())
                    : ((b.hoverPoints = a),
                      n &&
                          A(n, function (a) {
                              a.setState();
                          }),
                      A(a, function (a) {
                          a.setState("hover");
                          u.push(a.getLabelConfig());
                      }),
                      (n = { x: a[0].category, y: a[0].y }),
                      (n.points = u),
                      (this.len = u.length),
                      (a = a[0]));
                n = d.call(n, this);
                c = a.series;
                this.distance = v(c.tooltipOptions.distance, 16);
                !1 === n
                    ? this.hide()
                    : ((d = this.getLabel()),
                      this.isHidden && d.attr({ opacity: 1 }).show(),
                      this.split
                          ? this.renderSplit(n, b.hoverPoints)
                          : (d.attr({ text: n && n.join ? n.join("") : n }),
                            d
                                .removeClass(/highcharts-color-[\d]+/g)
                                .addClass(
                                    "highcharts-color-" +
                                        v(a.colorIndex, c.colorIndex)
                                ),
                            d.attr({
                                stroke:
                                    k.borderColor ||
                                    a.color ||
                                    c.color ||
                                    "#666666",
                            }),
                            this.updatePosition({
                                plotX: f,
                                plotY: e,
                                negative: a.negative,
                                ttBelow: a.ttBelow,
                                h: h[2] || 0,
                            })),
                      (this.isHidden = !1));
            },
            renderSplit: function (b, f) {
                var d = this,
                    n = [],
                    k = this.chart,
                    e = k.renderer,
                    h = !0,
                    g = this.options,
                    u,
                    c = this.getLabel();
                A(b.slice(0, b.length - 1), function (a, b) {
                    b = f[b - 1] || { isHeader: !0, plotX: f[0].plotX };
                    var q = b.series || d,
                        t = q.tt,
                        x = b.series || {},
                        D =
                            "highcharts-color-" +
                            v(b.colorIndex, x.colorIndex, "none");
                    t ||
                        (q.tt = t =
                            e
                                .label(null, null, null, "callout")
                                .addClass("highcharts-tooltip-box " + D)
                                .attr({
                                    padding: g.padding,
                                    r: g.borderRadius,
                                    fill: g.backgroundColor,
                                    stroke: b.color || x.color || "#333333",
                                    "stroke-width": g.borderWidth,
                                })
                                .add(c));
                    t.isActive = !0;
                    t.attr({ text: a });
                    t.css(g.style);
                    a = t.getBBox();
                    x = a.width + t.strokeWidth();
                    b.isHeader
                        ? ((u = a.height),
                          (x = Math.max(
                              0,
                              Math.min(
                                  b.plotX + k.plotLeft - x / 2,
                                  k.chartWidth - x
                              )
                          )))
                        : (x = b.plotX + k.plotLeft - v(g.distance, 16) - x);
                    0 > x && (h = !1);
                    a =
                        (b.series && b.series.yAxis && b.series.yAxis.pos) +
                        (b.plotY || 0);
                    a -= k.plotTop;
                    n.push({
                        target: b.isHeader ? k.plotHeight + u : a,
                        rank: b.isHeader ? 1 : 0,
                        size: q.tt.getBBox().height + 1,
                        point: b,
                        x: x,
                        tt: t,
                    });
                });
                this.cleanSplit();
                a.distribute(n, k.plotHeight + u);
                A(n, function (a) {
                    var b = a.point;
                    a.tt.attr({
                        visibility: void 0 === a.pos ? "hidden" : "inherit",
                        x:
                            h || b.isHeader
                                ? a.x
                                : b.plotX + k.plotLeft + v(g.distance, 16),
                        y: a.pos + k.plotTop,
                        anchorX: b.plotX + k.plotLeft,
                        anchorY: b.isHeader
                            ? a.pos + k.plotTop - 15
                            : b.plotY + k.plotTop,
                    });
                });
            },
            updatePosition: function (a) {
                var b = this.chart,
                    f = this.getLabel(),
                    f = (this.options.positioner || this.getPosition).call(
                        this,
                        f.width,
                        f.height,
                        a
                    );
                this.move(
                    Math.round(f.x),
                    Math.round(f.y || 0),
                    a.plotX + b.plotLeft,
                    a.plotY + b.plotTop
                );
            },
            getXDateFormat: function (a, d, g) {
                var b;
                d = d.dateTimeLabelFormats;
                var k = g && g.closestPointRange,
                    e,
                    h = {
                        millisecond: 15,
                        second: 12,
                        minute: 9,
                        hour: 6,
                        day: 3,
                    },
                    n,
                    u = "millisecond";
                if (k) {
                    n = E("%m-%d %H:%M:%S.%L", a.x);
                    for (e in f) {
                        if (
                            k === f.week &&
                            +E("%w", a.x) === g.options.startOfWeek &&
                            "00:00:00.000" === n.substr(6)
                        ) {
                            e = "week";
                            break;
                        }
                        if (f[e] > k) {
                            e = u;
                            break;
                        }
                        if (
                            h[e] &&
                            n.substr(h[e]) !== "01-01 00:00:00.000".substr(h[e])
                        )
                            break;
                        "week" !== e && (u = e);
                    }
                    e && (b = d[e]);
                } else b = d.day;
                return b || d.year;
            },
            tooltipFooterHeaderFormatter: function (a, f) {
                var b = f ? "footer" : "header";
                f = a.series;
                var d = f.tooltipOptions,
                    k = d.xDateFormat,
                    e = f.xAxis,
                    h = e && "datetime" === e.options.type && p(a.key),
                    b = d[b + "Format"];
                h && !k && (k = this.getXDateFormat(a, d, e));
                h &&
                    k &&
                    (b = b.replace("{point.key}", "{point.key:" + k + "}"));
                return H(b, { point: a, series: f });
            },
            bodyFormatter: function (a) {
                return d(a, function (a) {
                    var b = a.series.tooltipOptions;
                    return (b.pointFormatter || a.point.tooltipFormatter).call(
                        a.point,
                        b.pointFormat
                    );
                });
            },
        };
    })(M);
    (function (a) {
        var E = a.addEvent,
            A = a.attr,
            F = a.charts,
            H = a.color,
            p = a.css,
            d = a.defined,
            g = a.doc,
            v = a.each,
            l = a.extend,
            r = a.fireEvent,
            f = a.offset,
            b = a.pick,
            n = a.removeEvent,
            w = a.splat,
            t = a.Tooltip,
            k = a.win;
        a.Pointer = function (a, b) {
            this.init(a, b);
        };
        a.Pointer.prototype = {
            init: function (a, h) {
                this.options = h;
                this.chart = a;
                this.runChartClick = h.chart.events && !!h.chart.events.click;
                this.pinchDown = [];
                this.lastValidTouch = {};
                t &&
                    h.tooltip.enabled &&
                    ((a.tooltip = new t(a, h.tooltip)),
                    (this.followTouchMove = b(h.tooltip.followTouchMove, !0)));
                this.setDOMEvents();
            },
            zoomOption: function (a) {
                var e = this.chart,
                    f = e.options.chart,
                    k = f.zoomType || "",
                    e = e.inverted;
                /touch/.test(a.type) && (k = b(f.pinchType, k));
                this.zoomX = a = /x/.test(k);
                this.zoomY = k = /y/.test(k);
                this.zoomHor = (a && !e) || (k && e);
                this.zoomVert = (k && !e) || (a && e);
                this.hasZoom = a || k;
            },
            normalize: function (a, b) {
                var e, h;
                a = a || k.event;
                a.target || (a.target = a.srcElement);
                h = a.touches
                    ? a.touches.length
                        ? a.touches.item(0)
                        : a.changedTouches[0]
                    : a;
                b || (this.chartPosition = b = f(this.chart.container));
                void 0 === h.pageX
                    ? ((e = Math.max(a.x, a.clientX - b.left)), (b = a.y))
                    : ((e = h.pageX - b.left), (b = h.pageY - b.top));
                return l(a, { chartX: Math.round(e), chartY: Math.round(b) });
            },
            getCoordinates: function (a) {
                var b = { xAxis: [], yAxis: [] };
                v(this.chart.axes, function (e) {
                    b[e.isXAxis ? "xAxis" : "yAxis"].push({
                        axis: e,
                        value: e.toValue(a[e.horiz ? "chartX" : "chartY"]),
                    });
                });
                return b;
            },
            runPointActions: function (e) {
                var h = this.chart,
                    f = h.series,
                    k = h.tooltip,
                    c = k ? k.shared : !1,
                    d = !0,
                    n = h.hoverPoint,
                    t = h.hoverSeries,
                    l,
                    r,
                    D,
                    G = [],
                    L;
                if (!c && !t)
                    for (l = 0; l < f.length; l++)
                        if (f[l].directTouch || !f[l].options.stickyTracking)
                            f = [];
                t && (c ? t.noSharedTooltip : t.directTouch) && n
                    ? (G = [n])
                    : (c || !t || t.options.stickyTracking || (f = [t]),
                      v(f, function (a) {
                          r = a.noSharedTooltip && c;
                          D = !c && a.directTouch;
                          a.visible &&
                              !r &&
                              !D &&
                              b(a.options.enableMouseTracking, !0) &&
                              (L = a.searchPoint(
                                  e,
                                  !r && 1 === a.kdDimensions
                              )) &&
                              L.series &&
                              G.push(L);
                      }),
                      G.sort(function (a, b) {
                          var e = a.distX - b.distX,
                              h = a.dist - b.dist,
                              k = b.series.group.zIndex - a.series.group.zIndex;
                          return 0 !== e && c
                              ? e
                              : 0 !== h
                              ? h
                              : 0 !== k
                              ? k
                              : a.series.index > b.series.index
                              ? -1
                              : 1;
                      }));
                if (c)
                    for (l = G.length; l--; )
                        (G[l].x !== G[0].x || G[l].series.noSharedTooltip) &&
                            G.splice(l, 1);
                if (G[0] && (G[0] !== this.prevKDPoint || (k && k.isHidden))) {
                    if (c && !G[0].series.noSharedTooltip) {
                        for (l = 0; l < G.length; l++)
                            G[l].onMouseOver(
                                e,
                                G[l] !== ((t && t.directTouch && n) || G[0])
                            );
                        G.length &&
                            k &&
                            k.refresh(
                                G.sort(function (a, b) {
                                    return a.series.index - b.series.index;
                                }),
                                e
                            );
                    } else if ((k && k.refresh(G[0], e), !t || !t.directTouch))
                        G[0].onMouseOver(e);
                    this.prevKDPoint = G[0];
                    d = !1;
                }
                d &&
                    ((f = t && t.tooltipOptions.followPointer),
                    k &&
                        f &&
                        !k.isHidden &&
                        ((f = k.getAnchor([{}], e)),
                        k.updatePosition({ plotX: f[0], plotY: f[1] })));
                this.unDocMouseMove ||
                    (this.unDocMouseMove = E(g, "mousemove", function (b) {
                        if (F[a.hoverChartIndex])
                            F[a.hoverChartIndex].pointer.onDocumentMouseMove(b);
                    }));
                v(c ? G : [b(n, G[0])], function (a) {
                    v(h.axes, function (b) {
                        (!a || (a.series && a.series[b.coll] === b)) &&
                            b.drawCrosshair(e, a);
                    });
                });
            },
            reset: function (a, b) {
                var e = this.chart,
                    h = e.hoverSeries,
                    c = e.hoverPoint,
                    k = e.hoverPoints,
                    f = e.tooltip,
                    d = f && f.shared ? k : c;
                a &&
                    d &&
                    v(w(d), function (b) {
                        b.series.isCartesian && void 0 === b.plotX && (a = !1);
                    });
                if (a)
                    f &&
                        d &&
                        (f.refresh(d),
                        c &&
                            (c.setState(c.state, !0),
                            v(e.axes, function (a) {
                                a.crosshair && a.drawCrosshair(null, c);
                            })));
                else {
                    if (c) c.onMouseOut();
                    k &&
                        v(k, function (a) {
                            a.setState();
                        });
                    if (h) h.onMouseOut();
                    f && f.hide(b);
                    this.unDocMouseMove &&
                        (this.unDocMouseMove = this.unDocMouseMove());
                    v(e.axes, function (a) {
                        a.hideCrosshair();
                    });
                    this.hoverX =
                        this.prevKDPoint =
                        e.hoverPoints =
                        e.hoverPoint =
                            null;
                }
            },
            scaleGroups: function (a, b) {
                var e = this.chart,
                    h;
                v(e.series, function (c) {
                    h = a || c.getPlotBox();
                    c.xAxis &&
                        c.xAxis.zoomEnabled &&
                        c.group &&
                        (c.group.attr(h),
                        c.markerGroup &&
                            (c.markerGroup.attr(h),
                            c.markerGroup.clip(b ? e.clipRect : null)),
                        c.dataLabelsGroup && c.dataLabelsGroup.attr(h));
                });
                e.clipRect.attr(b || e.clipBox);
            },
            dragStart: function (a) {
                var b = this.chart;
                b.mouseIsDown = a.type;
                b.cancelClick = !1;
                b.mouseDownX = this.mouseDownX = a.chartX;
                b.mouseDownY = this.mouseDownY = a.chartY;
            },
            drag: function (a) {
                var b = this.chart,
                    e = b.options.chart,
                    k = a.chartX,
                    c = a.chartY,
                    f = this.zoomHor,
                    d = this.zoomVert,
                    g = b.plotLeft,
                    n = b.plotTop,
                    t = b.plotWidth,
                    D = b.plotHeight,
                    l,
                    r = this.selectionMarker,
                    p = this.mouseDownX,
                    m = this.mouseDownY,
                    z = e.panKey && a[e.panKey + "Key"];
                (r && r.touch) ||
                    (k < g ? (k = g) : k > g + t && (k = g + t),
                    c < n ? (c = n) : c > n + D && (c = n + D),
                    (this.hasDragged = Math.sqrt(
                        Math.pow(p - k, 2) + Math.pow(m - c, 2)
                    )),
                    10 < this.hasDragged &&
                        ((l = b.isInsidePlot(p - g, m - n)),
                        b.hasCartesianSeries &&
                            (this.zoomX || this.zoomY) &&
                            l &&
                            !z &&
                            !r &&
                            (this.selectionMarker = r =
                                b.renderer
                                    .rect(g, n, f ? 1 : t, d ? 1 : D, 0)
                                    .attr({
                                        fill:
                                            e.selectionMarkerFill ||
                                            H("#335cad").setOpacity(0.25).get(),
                                        class: "highcharts-selection-marker",
                                        zIndex: 7,
                                    })
                                    .add()),
                        r &&
                            f &&
                            ((k -= p),
                            r.attr({
                                width: Math.abs(k),
                                x: (0 < k ? 0 : k) + p,
                            })),
                        r &&
                            d &&
                            ((k = c - m),
                            r.attr({
                                height: Math.abs(k),
                                y: (0 < k ? 0 : k) + m,
                            })),
                        l && !r && e.panning && b.pan(a, e.panning)));
            },
            drop: function (a) {
                var b = this,
                    e = this.chart,
                    k = this.hasPinched;
                if (this.selectionMarker) {
                    var c = { originalEvent: a, xAxis: [], yAxis: [] },
                        f = this.selectionMarker,
                        g = f.attr ? f.attr("x") : f.x,
                        n = f.attr ? f.attr("y") : f.y,
                        t = f.attr ? f.attr("width") : f.width,
                        w = f.attr ? f.attr("height") : f.height,
                        D;
                    if (this.hasDragged || k)
                        v(e.axes, function (e) {
                            if (
                                e.zoomEnabled &&
                                d(e.min) &&
                                (k ||
                                    b[
                                        { xAxis: "zoomX", yAxis: "zoomY" }[
                                            e.coll
                                        ]
                                    ])
                            ) {
                                var f = e.horiz,
                                    h =
                                        "touchend" === a.type
                                            ? e.minPixelPadding
                                            : 0,
                                    m = e.toValue((f ? g : n) + h),
                                    f = e.toValue((f ? g + t : n + w) - h);
                                c[e.coll].push({
                                    axis: e,
                                    min: Math.min(m, f),
                                    max: Math.max(m, f),
                                });
                                D = !0;
                            }
                        }),
                            D &&
                                r(e, "selection", c, function (a) {
                                    e.zoom(l(a, k ? { animation: !1 } : null));
                                });
                    this.selectionMarker = this.selectionMarker.destroy();
                    k && this.scaleGroups();
                }
                e &&
                    (p(e.container, { cursor: e._cursor }),
                    (e.cancelClick = 10 < this.hasDragged),
                    (e.mouseIsDown = this.hasDragged = this.hasPinched = !1),
                    (this.pinchDown = []));
            },
            onContainerMouseDown: function (a) {
                a = this.normalize(a);
                this.zoomOption(a);
                a.preventDefault && a.preventDefault();
                this.dragStart(a);
            },
            onDocumentMouseUp: function (b) {
                F[a.hoverChartIndex] && F[a.hoverChartIndex].pointer.drop(b);
            },
            onDocumentMouseMove: function (a) {
                var b = this.chart,
                    e = this.chartPosition;
                a = this.normalize(a, e);
                !e ||
                    this.inClass(a.target, "highcharts-tracker") ||
                    b.isInsidePlot(
                        a.chartX - b.plotLeft,
                        a.chartY - b.plotTop
                    ) ||
                    this.reset();
            },
            onContainerMouseLeave: function (b) {
                var e = F[a.hoverChartIndex];
                e &&
                    (b.relatedTarget || b.toElement) &&
                    (e.pointer.reset(), (e.pointer.chartPosition = null));
            },
            onContainerMouseMove: function (b) {
                var e = this.chart;
                (d(a.hoverChartIndex) &&
                    F[a.hoverChartIndex] &&
                    F[a.hoverChartIndex].mouseIsDown) ||
                    (a.hoverChartIndex = e.index);
                b = this.normalize(b);
                b.returnValue = !1;
                "mousedown" === e.mouseIsDown && this.drag(b);
                (!this.inClass(b.target, "highcharts-tracker") &&
                    !e.isInsidePlot(
                        b.chartX - e.plotLeft,
                        b.chartY - e.plotTop
                    )) ||
                    e.openMenu ||
                    this.runPointActions(b);
            },
            inClass: function (a, b) {
                for (var e; a; ) {
                    if ((e = A(a, "class"))) {
                        if (-1 !== e.indexOf(b)) return !0;
                        if (-1 !== e.indexOf("highcharts-container")) return !1;
                    }
                    a = a.parentNode;
                }
            },
            onTrackerMouseOut: function (a) {
                var b = this.chart.hoverSeries;
                a = a.relatedTarget || a.toElement;
                if (
                    !(
                        !b ||
                        !a ||
                        b.options.stickyTracking ||
                        this.inClass(a, "highcharts-tooltip") ||
                        (this.inClass(a, "highcharts-series-" + b.index) &&
                            this.inClass(a, "highcharts-tracker"))
                    )
                )
                    b.onMouseOut();
            },
            onContainerClick: function (a) {
                var b = this.chart,
                    e = b.hoverPoint,
                    f = b.plotLeft,
                    c = b.plotTop;
                a = this.normalize(a);
                b.cancelClick ||
                    (e && this.inClass(a.target, "highcharts-tracker")
                        ? (r(e.series, "click", l(a, { point: e })),
                          b.hoverPoint && e.firePointEvent("click", a))
                        : (l(a, this.getCoordinates(a)),
                          b.isInsidePlot(a.chartX - f, a.chartY - c) &&
                              r(b, "click", a)));
            },
            setDOMEvents: function () {
                var b = this,
                    f = b.chart.container;
                f.onmousedown = function (a) {
                    b.onContainerMouseDown(a);
                };
                f.onmousemove = function (a) {
                    b.onContainerMouseMove(a);
                };
                f.onclick = function (a) {
                    b.onContainerClick(a);
                };
                E(f, "mouseleave", b.onContainerMouseLeave);
                1 === a.chartCount && E(g, "mouseup", b.onDocumentMouseUp);
                a.hasTouch &&
                    ((f.ontouchstart = function (a) {
                        b.onContainerTouchStart(a);
                    }),
                    (f.ontouchmove = function (a) {
                        b.onContainerTouchMove(a);
                    }),
                    1 === a.chartCount &&
                        E(g, "touchend", b.onDocumentTouchEnd));
            },
            destroy: function () {
                var b;
                n(
                    this.chart.container,
                    "mouseleave",
                    this.onContainerMouseLeave
                );
                a.chartCount ||
                    (n(g, "mouseup", this.onDocumentMouseUp),
                    n(g, "touchend", this.onDocumentTouchEnd));
                clearInterval(this.tooltipTimeout);
                for (b in this) this[b] = null;
            },
        };
    })(M);
    (function (a) {
        var E = a.charts,
            A = a.each,
            F = a.extend,
            H = a.map,
            p = a.noop,
            d = a.pick;
        F(a.Pointer.prototype, {
            pinchTranslate: function (a, d, l, r, f, b) {
                this.zoomHor &&
                    this.pinchTranslateDirection(!0, a, d, l, r, f, b);
                this.zoomVert &&
                    this.pinchTranslateDirection(!1, a, d, l, r, f, b);
            },
            pinchTranslateDirection: function (a, d, l, r, f, b, n, p) {
                var g = this.chart,
                    k = a ? "x" : "y",
                    e = a ? "X" : "Y",
                    h = "chart" + e,
                    v = a ? "width" : "height",
                    u = g["plot" + (a ? "Left" : "Top")],
                    c,
                    q,
                    x = p || 1,
                    w = g.inverted,
                    I = g.bounds[a ? "h" : "v"],
                    J = 1 === d.length,
                    D = d[0][h],
                    G = l[0][h],
                    L = !J && d[1][h],
                    N = !J && l[1][h],
                    m;
                l = function () {
                    !J &&
                        20 < Math.abs(D - L) &&
                        (x = p || Math.abs(G - N) / Math.abs(D - L));
                    q = (u - G) / x + D;
                    c = g["plot" + (a ? "Width" : "Height")] / x;
                };
                l();
                d = q;
                d < I.min
                    ? ((d = I.min), (m = !0))
                    : d + c > I.max && ((d = I.max - c), (m = !0));
                m
                    ? ((G -= 0.8 * (G - n[k][0])),
                      J || (N -= 0.8 * (N - n[k][1])),
                      l())
                    : (n[k] = [G, N]);
                w || ((b[k] = q - u), (b[v] = c));
                b = w ? 1 / x : x;
                f[v] = c;
                f[k] = d;
                r[w ? (a ? "scaleY" : "scaleX") : "scale" + e] = x;
                r["translate" + e] = b * u + (G - b * D);
            },
            pinch: function (a) {
                var g = this,
                    l = g.chart,
                    r = g.pinchDown,
                    f = a.touches,
                    b = f.length,
                    n = g.lastValidTouch,
                    w = g.hasZoom,
                    t = g.selectionMarker,
                    k = {},
                    e =
                        1 === b &&
                        ((g.inClass(a.target, "highcharts-tracker") &&
                            l.runTrackerClick) ||
                            g.runChartClick),
                    h = {};
                1 < b && (g.initiated = !0);
                w && g.initiated && !e && a.preventDefault();
                H(f, function (a) {
                    return g.normalize(a);
                });
                "touchstart" === a.type
                    ? (A(f, function (a, b) {
                          r[b] = { chartX: a.chartX, chartY: a.chartY };
                      }),
                      (n.x = [r[0].chartX, r[1] && r[1].chartX]),
                      (n.y = [r[0].chartY, r[1] && r[1].chartY]),
                      A(l.axes, function (a) {
                          if (a.zoomEnabled) {
                              var b = l.bounds[a.horiz ? "h" : "v"],
                                  c = a.minPixelPadding,
                                  e = a.toPixels(d(a.options.min, a.dataMin)),
                                  f = a.toPixels(d(a.options.max, a.dataMax)),
                                  k = Math.max(e, f);
                              b.min = Math.min(a.pos, Math.min(e, f) - c);
                              b.max = Math.max(a.pos + a.len, k + c);
                          }
                      }),
                      (g.res = !0))
                    : g.followTouchMove && 1 === b
                    ? this.runPointActions(g.normalize(a))
                    : r.length &&
                      (t ||
                          (g.selectionMarker = t =
                              F({ destroy: p, touch: !0 }, l.plotBox)),
                      g.pinchTranslate(r, f, k, t, h, n),
                      (g.hasPinched = w),
                      g.scaleGroups(k, h),
                      g.res && ((g.res = !1), this.reset(!1, 0)));
            },
            touch: function (g, p) {
                var l = this.chart,
                    r,
                    f;
                if (l.index !== a.hoverChartIndex)
                    this.onContainerMouseLeave({ relatedTarget: !0 });
                a.hoverChartIndex = l.index;
                1 === g.touches.length
                    ? ((g = this.normalize(g)),
                      (f = l.isInsidePlot(
                          g.chartX - l.plotLeft,
                          g.chartY - l.plotTop
                      )) && !l.openMenu
                          ? (p && this.runPointActions(g),
                            "touchmove" === g.type &&
                                ((p = this.pinchDown),
                                (r = p[0]
                                    ? 4 <=
                                      Math.sqrt(
                                          Math.pow(p[0].chartX - g.chartX, 2) +
                                              Math.pow(
                                                  p[0].chartY - g.chartY,
                                                  2
                                              )
                                      )
                                    : !1)),
                            d(r, !0) && this.pinch(g))
                          : p && this.reset())
                    : 2 === g.touches.length && this.pinch(g);
            },
            onContainerTouchStart: function (a) {
                this.zoomOption(a);
                this.touch(a, !0);
            },
            onContainerTouchMove: function (a) {
                this.touch(a);
            },
            onDocumentTouchEnd: function (d) {
                E[a.hoverChartIndex] && E[a.hoverChartIndex].pointer.drop(d);
            },
        });
    })(M);
    (function (a) {
        var E = a.addEvent,
            A = a.charts,
            F = a.css,
            H = a.doc,
            p = a.extend,
            d = a.noop,
            g = a.Pointer,
            v = a.removeEvent,
            l = a.win,
            r = a.wrap;
        if (l.PointerEvent || l.MSPointerEvent) {
            var f = {},
                b = !!l.PointerEvent,
                n = function () {
                    var a,
                        b = [];
                    b.item = function (a) {
                        return this[a];
                    };
                    for (a in f)
                        f.hasOwnProperty(a) &&
                            b.push({
                                pageX: f[a].pageX,
                                pageY: f[a].pageY,
                                target: f[a].target,
                            });
                    return b;
                },
                w = function (b, f, e, h) {
                    ("touch" !== b.pointerType &&
                        b.pointerType !== b.MSPOINTER_TYPE_TOUCH) ||
                        !A[a.hoverChartIndex] ||
                        (h(b),
                        (h = A[a.hoverChartIndex].pointer),
                        h[f]({
                            type: e,
                            target: b.currentTarget,
                            preventDefault: d,
                            touches: n(),
                        }));
                };
            p(g.prototype, {
                onContainerPointerDown: function (a) {
                    w(a, "onContainerTouchStart", "touchstart", function (a) {
                        f[a.pointerId] = {
                            pageX: a.pageX,
                            pageY: a.pageY,
                            target: a.currentTarget,
                        };
                    });
                },
                onContainerPointerMove: function (a) {
                    w(a, "onContainerTouchMove", "touchmove", function (a) {
                        f[a.pointerId] = { pageX: a.pageX, pageY: a.pageY };
                        f[a.pointerId].target ||
                            (f[a.pointerId].target = a.currentTarget);
                    });
                },
                onDocumentPointerUp: function (a) {
                    w(a, "onDocumentTouchEnd", "touchend", function (a) {
                        delete f[a.pointerId];
                    });
                },
                batchMSEvents: function (a) {
                    a(
                        this.chart.container,
                        b ? "pointerdown" : "MSPointerDown",
                        this.onContainerPointerDown
                    );
                    a(
                        this.chart.container,
                        b ? "pointermove" : "MSPointerMove",
                        this.onContainerPointerMove
                    );
                    a(
                        H,
                        b ? "pointerup" : "MSPointerUp",
                        this.onDocumentPointerUp
                    );
                },
            });
            r(g.prototype, "init", function (a, b, e) {
                a.call(this, b, e);
                this.hasZoom &&
                    F(b.container, {
                        "-ms-touch-action": "none",
                        "touch-action": "none",
                    });
            });
            r(g.prototype, "setDOMEvents", function (a) {
                a.apply(this);
                (this.hasZoom || this.followTouchMove) && this.batchMSEvents(E);
            });
            r(g.prototype, "destroy", function (a) {
                this.batchMSEvents(v);
                a.call(this);
            });
        }
    })(M);
    (function (a) {
        var E,
            A = a.addEvent,
            F = a.css,
            H = a.discardElement,
            p = a.defined,
            d = a.each,
            g = a.extend,
            v = a.isFirefox,
            l = a.marginNames,
            r = a.merge,
            f = a.pick,
            b = a.setAnimation,
            n = a.stableSort,
            w = a.win,
            t = a.wrap;
        E = a.Legend = function (a, b) {
            this.init(a, b);
        };
        E.prototype = {
            init: function (a, b) {
                this.chart = a;
                this.setOptions(b);
                b.enabled &&
                    (this.render(),
                    A(this.chart, "endResize", function () {
                        this.legend.positionCheckboxes();
                    }));
            },
            setOptions: function (a) {
                var b = f(a.padding, 8);
                this.options = a;
                this.itemStyle = a.itemStyle;
                this.itemHiddenStyle = r(this.itemStyle, a.itemHiddenStyle);
                this.itemMarginTop = a.itemMarginTop || 0;
                this.initialItemX = this.padding = b;
                this.initialItemY = b - 5;
                this.itemHeight = this.maxItemWidth = 0;
                this.symbolWidth = f(a.symbolWidth, 16);
                this.pages = [];
            },
            update: function (a, b) {
                var e = this.chart;
                this.setOptions(r(!0, this.options, a));
                this.destroy();
                e.isDirtyLegend = e.isDirtyBox = !0;
                f(b, !0) && e.redraw();
            },
            colorizeItem: function (a, b) {
                a.legendGroup[b ? "removeClass" : "addClass"](
                    "highcharts-legend-item-hidden"
                );
                var e = this.options,
                    f = a.legendItem,
                    k = a.legendLine,
                    c = a.legendSymbol,
                    d = this.itemHiddenStyle.color,
                    e = b ? e.itemStyle.color : d,
                    g = b ? a.color || d : d,
                    n = a.options && a.options.marker,
                    l = { fill: g },
                    t;
                f && f.css({ fill: e, color: e });
                k && k.attr({ stroke: g });
                if (c) {
                    if (n && c.isMarker && ((l = a.pointAttribs()), !b))
                        for (t in l) l[t] = d;
                    c.attr(l);
                }
            },
            positionItem: function (a) {
                var b = this.options,
                    f = b.symbolPadding,
                    b = !b.rtl,
                    k = a._legendItemPos,
                    d = k[0],
                    k = k[1],
                    c = a.checkbox;
                (a = a.legendGroup) &&
                    a.element &&
                    a.translate(b ? d : this.legendWidth - d - 2 * f - 4, k);
                c && ((c.x = d), (c.y = k));
            },
            destroyItem: function (a) {
                var b = a.checkbox;
                d(
                    ["legendItem", "legendLine", "legendSymbol", "legendGroup"],
                    function (b) {
                        a[b] && (a[b] = a[b].destroy());
                    }
                );
                b && H(a.checkbox);
            },
            destroy: function () {
                var a = this.group,
                    b = this.box;
                b && (this.box = b.destroy());
                d(this.getAllItems(), function (a) {
                    d(["legendItem", "legendGroup"], function (b) {
                        a[b] && (a[b] = a[b].destroy());
                    });
                });
                a && (this.group = a.destroy());
                this.display = null;
            },
            positionCheckboxes: function (a) {
                var b = this.group && this.group.alignAttr,
                    f,
                    k = this.clipHeight || this.legendHeight,
                    g = this.titleHeight;
                b &&
                    ((f = b.translateY),
                    d(this.allItems, function (c) {
                        var e = c.checkbox,
                            h;
                        e &&
                            ((h = f + g + e.y + (a || 0) + 3),
                            F(e, {
                                left:
                                    b.translateX +
                                    c.checkboxOffset +
                                    e.x -
                                    20 +
                                    "px",
                                top: h + "px",
                                display:
                                    h > f - 6 && h < f + k - 6 ? "" : "none",
                            }));
                    }));
            },
            renderTitle: function () {
                var a = this.padding,
                    b = this.options.title,
                    f = 0;
                b.text &&
                    (this.title ||
                        (this.title = this.chart.renderer
                            .label(
                                b.text,
                                a - 3,
                                a - 4,
                                null,
                                null,
                                null,
                                null,
                                null,
                                "legend-title"
                            )
                            .attr({ zIndex: 1 })
                            .css(b.style)
                            .add(this.group)),
                    (a = this.title.getBBox()),
                    (f = a.height),
                    (this.offsetWidth = a.width),
                    this.contentGroup.attr({ translateY: f }));
                this.titleHeight = f;
            },
            setText: function (b) {
                var e = this.options;
                b.legendItem.attr({
                    text: e.labelFormat
                        ? a.format(e.labelFormat, b)
                        : e.labelFormatter.call(b),
                });
            },
            renderItem: function (a) {
                var b = this.chart,
                    h = b.renderer,
                    k = this.options,
                    d = "horizontal" === k.layout,
                    c = this.symbolWidth,
                    g = k.symbolPadding,
                    n = this.itemStyle,
                    l = this.itemHiddenStyle,
                    t = this.padding,
                    p = d ? f(k.itemDistance, 20) : 0,
                    D = !k.rtl,
                    G = k.width,
                    L = k.itemMarginBottom || 0,
                    w = this.itemMarginTop,
                    m = this.initialItemX,
                    z = a.legendItem,
                    v = !a.series,
                    P = !v && a.series.drawLegendSymbol ? a.series : a,
                    y = P.options,
                    y = this.createCheckboxForItem && y && y.showCheckbox,
                    B = k.useHTML;
                z ||
                    ((a.legendGroup = h
                        .g("legend-item")
                        .addClass(
                            "highcharts-" +
                                P.type +
                                "-series highcharts-color-" +
                                a.colorIndex +
                                (a.options.className
                                    ? " " + a.options.className
                                    : "") +
                                (v ? " highcharts-series-" + a.index : "")
                        )
                        .attr({ zIndex: 1 })
                        .add(this.scrollGroup)),
                    (a.legendItem = z =
                        h
                            .text("", D ? c + g : -g, this.baseline || 0, B)
                            .css(r(a.visible ? n : l))
                            .attr({ align: D ? "left" : "right", zIndex: 2 })
                            .add(a.legendGroup)),
                    this.baseline ||
                        ((n = n.fontSize),
                        (this.fontMetrics = h.fontMetrics(n, z)),
                        (this.baseline = this.fontMetrics.f + 3 + w),
                        z.attr("y", this.baseline)),
                    P.drawLegendSymbol(this, a),
                    this.setItemEvents && this.setItemEvents(a, z, B),
                    y && this.createCheckboxForItem(a));
                this.colorizeItem(a, a.visible);
                this.setText(a);
                h = z.getBBox();
                c = a.checkboxOffset =
                    k.itemWidth ||
                    a.legendItemWidth ||
                    c + g + h.width + p + (y ? 20 : 0);
                this.itemHeight = g = Math.round(
                    a.legendItemHeight || h.height
                );
                d &&
                    this.itemX - m + c >
                        (G || b.chartWidth - 2 * t - m - k.x) &&
                    ((this.itemX = m),
                    (this.itemY += w + this.lastLineHeight + L),
                    (this.lastLineHeight = 0));
                this.maxItemWidth = Math.max(this.maxItemWidth, c);
                this.lastItemY = w + this.itemY + L;
                this.lastLineHeight = Math.max(g, this.lastLineHeight);
                a._legendItemPos = [this.itemX, this.itemY];
                d
                    ? (this.itemX += c)
                    : ((this.itemY += w + g + L), (this.lastLineHeight = g));
                this.offsetWidth =
                    G ||
                    Math.max(
                        (d ? this.itemX - m - p : c) + t,
                        this.offsetWidth
                    );
            },
            getAllItems: function () {
                var a = [];
                d(this.chart.series, function (b) {
                    var e = b && b.options;
                    b &&
                        f(e.showInLegend, p(e.linkedTo) ? !1 : void 0, !0) &&
                        (a = a.concat(
                            b.legendItems ||
                                ("point" === e.legendType ? b.data : b)
                        ));
                });
                return a;
            },
            adjustMargins: function (a, b) {
                var e = this.chart,
                    k = this.options,
                    g =
                        k.align.charAt(0) +
                        k.verticalAlign.charAt(0) +
                        k.layout.charAt(0);
                k.floating ||
                    d(
                        [
                            /(lth|ct|rth)/,
                            /(rtv|rm|rbv)/,
                            /(rbh|cb|lbh)/,
                            /(lbv|lm|ltv)/,
                        ],
                        function (c, d) {
                            c.test(g) &&
                                !p(a[d]) &&
                                (e[l[d]] = Math.max(
                                    e[l[d]],
                                    e.legend[
                                        (d + 1) % 2
                                            ? "legendHeight"
                                            : "legendWidth"
                                    ] +
                                        [1, -1, -1, 1][d] *
                                            k[d % 2 ? "x" : "y"] +
                                        f(k.margin, 12) +
                                        b[d]
                                ));
                        }
                    );
            },
            render: function () {
                var a = this,
                    b = a.chart,
                    f = b.renderer,
                    l = a.group,
                    u,
                    c,
                    q,
                    t,
                    r = a.box,
                    p = a.options,
                    w = a.padding;
                a.itemX = a.initialItemX;
                a.itemY = a.initialItemY;
                a.offsetWidth = 0;
                a.lastItemY = 0;
                l ||
                    ((a.group = l = f.g("legend").attr({ zIndex: 7 }).add()),
                    (a.contentGroup = f.g().attr({ zIndex: 1 }).add(l)),
                    (a.scrollGroup = f.g().add(a.contentGroup)));
                a.renderTitle();
                u = a.getAllItems();
                n(u, function (a, b) {
                    return (
                        ((a.options && a.options.legendIndex) || 0) -
                        ((b.options && b.options.legendIndex) || 0)
                    );
                });
                p.reversed && u.reverse();
                a.allItems = u;
                a.display = c = !!u.length;
                a.lastLineHeight = 0;
                d(u, function (b) {
                    a.renderItem(b);
                });
                q = (p.width || a.offsetWidth) + w;
                t = a.lastItemY + a.lastLineHeight + a.titleHeight;
                t = a.handleOverflow(t);
                t += w;
                r ||
                    ((a.box = r =
                        f
                            .rect()
                            .addClass("highcharts-legend-box")
                            .attr({ r: p.borderRadius })
                            .add(l)),
                    (r.isNew = !0));
                r.attr({
                    stroke: p.borderColor,
                    "stroke-width": p.borderWidth || 0,
                    fill: p.backgroundColor || "none",
                }).shadow(p.shadow);
                0 < q &&
                    0 < t &&
                    (r[r.isNew ? "attr" : "animate"](
                        r.crisp(
                            { x: 0, y: 0, width: q, height: t },
                            r.strokeWidth()
                        )
                    ),
                    (r.isNew = !1));
                r[c ? "show" : "hide"]();
                a.legendWidth = q;
                a.legendHeight = t;
                d(u, function (b) {
                    a.positionItem(b);
                });
                c && l.align(g({ width: q, height: t }, p), !0, "spacingBox");
                b.isResizing || this.positionCheckboxes();
            },
            handleOverflow: function (a) {
                var b = this,
                    h = this.chart,
                    k = h.renderer,
                    g = this.options,
                    c = g.y,
                    h =
                        h.spacingBox.height +
                        ("top" === g.verticalAlign ? -c : c) -
                        this.padding,
                    c = g.maxHeight,
                    n,
                    l = this.clipRect,
                    t = g.navigation,
                    r = f(t.animation, !0),
                    p = t.arrowSize || 12,
                    D = this.nav,
                    G = this.pages,
                    L = this.padding,
                    w,
                    m = this.allItems,
                    z = function (a) {
                        a
                            ? l.attr({ height: a })
                            : l &&
                              ((b.clipRect = l.destroy()),
                              b.contentGroup.clip());
                        b.contentGroup.div &&
                            (b.contentGroup.div.style.clip = a
                                ? "rect(" + L + "px,9999px," + (L + a) + "px,0)"
                                : "auto");
                    };
                "horizontal" !== g.layout ||
                    "middle" === g.verticalAlign ||
                    g.floating ||
                    (h /= 2);
                c && (h = Math.min(h, c));
                G.length = 0;
                a > h && !1 !== t.enabled
                    ? ((this.clipHeight = n =
                          Math.max(h - 20 - this.titleHeight - L, 0)),
                      (this.currentPage = f(this.currentPage, 1)),
                      (this.fullHeight = a),
                      d(m, function (a, b) {
                          var c = a._legendItemPos[1];
                          a = Math.round(a.legendItem.getBBox().height);
                          var e = G.length;
                          if (!e || (c - G[e - 1] > n && (w || c) !== G[e - 1]))
                              G.push(w || c), e++;
                          b === m.length - 1 &&
                              c + a - G[e - 1] > n &&
                              G.push(c);
                          c !== w && (w = c);
                      }),
                      l ||
                          ((l = b.clipRect = k.clipRect(0, L, 9999, 0)),
                          b.contentGroup.clip(l)),
                      z(n),
                      D ||
                          ((this.nav = D =
                              k.g().attr({ zIndex: 1 }).add(this.group)),
                          (this.up = k
                              .symbol("triangle", 0, 0, p, p)
                              .on("click", function () {
                                  b.scroll(-1, r);
                              })
                              .add(D)),
                          (this.pager = k
                              .text("", 15, 10)
                              .addClass("highcharts-legend-navigation")
                              .css(t.style)
                              .add(D)),
                          (this.down = k
                              .symbol("triangle-down", 0, 0, p, p)
                              .on("click", function () {
                                  b.scroll(1, r);
                              })
                              .add(D))),
                      b.scroll(0),
                      (a = h))
                    : D &&
                      (z(),
                      D.hide(),
                      this.scrollGroup.attr({ translateY: 1 }),
                      (this.clipHeight = 0));
                return a;
            },
            scroll: function (a, e) {
                var f = this.pages,
                    d = f.length;
                a = this.currentPage + a;
                var k = this.clipHeight,
                    c = this.options.navigation,
                    g = this.pager,
                    n = this.padding;
                a > d && (a = d);
                0 < a &&
                    (void 0 !== e && b(e, this.chart),
                    this.nav.attr({
                        translateX: n,
                        translateY: k + this.padding + 7 + this.titleHeight,
                        visibility: "visible",
                    }),
                    this.up.attr({
                        class:
                            1 === a
                                ? "highcharts-legend-nav-inactive"
                                : "highcharts-legend-nav-active",
                    }),
                    g.attr({ text: a + "/" + d }),
                    this.down.attr({
                        x: 18 + this.pager.getBBox().width,
                        class:
                            a === d
                                ? "highcharts-legend-nav-inactive"
                                : "highcharts-legend-nav-active",
                    }),
                    this.up
                        .attr({
                            fill: 1 === a ? c.inactiveColor : c.activeColor,
                        })
                        .css({ cursor: 1 === a ? "default" : "pointer" }),
                    this.down
                        .attr({
                            fill: a === d ? c.inactiveColor : c.activeColor,
                        })
                        .css({ cursor: a === d ? "default" : "pointer" }),
                    (e = -f[a - 1] + this.initialItemY),
                    this.scrollGroup.animate({ translateY: e }),
                    (this.currentPage = a),
                    this.positionCheckboxes(e));
            },
        };
        a.LegendSymbolMixin = {
            drawRectangle: function (a, b) {
                var e = a.options,
                    d = e.symbolHeight || a.fontMetrics.f,
                    e = e.squareSymbol;
                b.legendSymbol = this.chart.renderer
                    .rect(
                        e ? (a.symbolWidth - d) / 2 : 0,
                        a.baseline - d + 1,
                        e ? d : a.symbolWidth,
                        d,
                        f(a.options.symbolRadius, d / 2)
                    )
                    .addClass("highcharts-point")
                    .attr({ zIndex: 3 })
                    .add(b.legendGroup);
            },
            drawLineMarker: function (a) {
                var b = this.options,
                    f = b.marker,
                    d = a.symbolWidth,
                    k = this.chart.renderer,
                    c = this.legendGroup;
                a = a.baseline - Math.round(0.3 * a.fontMetrics.b);
                var g;
                g = { "stroke-width": b.lineWidth || 0 };
                b.dashStyle && (g.dashstyle = b.dashStyle);
                this.legendLine = k
                    .path(["M", 0, a, "L", d, a])
                    .addClass("highcharts-graph")
                    .attr(g)
                    .add(c);
                f &&
                    !1 !== f.enabled &&
                    ((b = 0 === this.symbol.indexOf("url") ? 0 : f.radius),
                    (this.legendSymbol = f =
                        k
                            .symbol(
                                this.symbol,
                                d / 2 - b,
                                a - b,
                                2 * b,
                                2 * b,
                                f
                            )
                            .addClass("highcharts-point")
                            .add(c)),
                    (f.isMarker = !0));
            },
        };
        (/Trident\/7\.0/.test(w.navigator.userAgent) || v) &&
            t(E.prototype, "positionItem", function (a, b) {
                var e = this,
                    f = function () {
                        b._legendItemPos && a.call(e, b);
                    };
                f();
                setTimeout(f);
            });
    })(M);
    (function (a) {
        var E = a.addEvent,
            A = a.animate,
            F = a.animObject,
            H = a.attr,
            p = a.doc,
            d = a.Axis,
            g = a.createElement,
            v = a.defaultOptions,
            l = a.discardElement,
            r = a.charts,
            f = a.css,
            b = a.defined,
            n = a.each,
            w = a.error,
            t = a.extend,
            k = a.fireEvent,
            e = a.getStyle,
            h = a.grep,
            C = a.isNumber,
            u = a.isObject,
            c = a.isString,
            q = a.Legend,
            x = a.marginNames,
            K = a.merge,
            I = a.Pointer,
            J = a.pick,
            D = a.pInt,
            G = a.removeEvent,
            L = a.seriesTypes,
            N = a.splat,
            m = a.svg,
            z = a.syncTimeout,
            O = a.win,
            P = a.Renderer,
            y = (a.Chart = function () {
                this.getArgs.apply(this, arguments);
            });
        a.chart = function (a, b, c) {
            return new y(a, b, c);
        };
        y.prototype = {
            callbacks: [],
            getArgs: function () {
                var a = [].slice.call(arguments);
                if (c(a[0]) || a[0].nodeName) this.renderTo = a.shift();
                this.init(a[0], a[1]);
            },
            init: function (b, c) {
                var e,
                    f = b.series;
                b.series = null;
                e = K(v, b);
                e.series = b.series = f;
                this.userOptions = b;
                this.respRules = [];
                b = e.chart;
                f = b.events;
                this.margin = [];
                this.spacing = [];
                this.bounds = { h: {}, v: {} };
                this.callback = c;
                this.isResizing = 0;
                this.options = e;
                this.axes = [];
                this.series = [];
                this.hasCartesianSeries = b.showAxes;
                var d;
                this.index = r.length;
                r.push(this);
                a.chartCount++;
                if (f) for (d in f) E(this, d, f[d]);
                this.xAxis = [];
                this.yAxis = [];
                this.pointCount = this.colorCounter = this.symbolCounter = 0;
                this.firstRender();
            },
            initSeries: function (a) {
                var b = this.options.chart;
                (b = L[a.type || b.type || b.defaultSeriesType]) || w(17, !0);
                b = new b();
                b.init(this, a);
                return b;
            },
            isInsidePlot: function (a, b, c) {
                var e = c ? b : a;
                a = c ? a : b;
                return (
                    0 <= e &&
                    e <= this.plotWidth &&
                    0 <= a &&
                    a <= this.plotHeight
                );
            },
            redraw: function (b) {
                var c = this.axes,
                    e = this.series,
                    f = this.pointer,
                    d = this.legend,
                    m = this.isDirtyLegend,
                    h,
                    g,
                    q = this.hasCartesianSeries,
                    l = this.isDirtyBox,
                    D = e.length,
                    u = D,
                    B = this.renderer,
                    r = B.isHidden(),
                    G = [];
                a.setAnimation(b, this);
                r && this.cloneRenderTo();
                for (this.layOutTitles(); u--; )
                    if (
                        ((b = e[u]),
                        b.options.stacking && ((h = !0), b.isDirty))
                    ) {
                        g = !0;
                        break;
                    }
                if (g)
                    for (u = D; u--; )
                        (b = e[u]), b.options.stacking && (b.isDirty = !0);
                n(e, function (a) {
                    a.isDirty &&
                        "point" === a.options.legendType &&
                        (a.updateTotals && a.updateTotals(), (m = !0));
                    a.isDirtyData && k(a, "updatedData");
                });
                m &&
                    d.options.enabled &&
                    (d.render(), (this.isDirtyLegend = !1));
                h && this.getStacks();
                q &&
                    n(c, function (a) {
                        a.updateNames();
                        a.setScale();
                    });
                this.getMargins();
                q &&
                    (n(c, function (a) {
                        a.isDirty && (l = !0);
                    }),
                    n(c, function (a) {
                        var b = a.min + "," + a.max;
                        a.extKey !== b &&
                            ((a.extKey = b),
                            G.push(function () {
                                k(
                                    a,
                                    "afterSetExtremes",
                                    t(a.eventArgs, a.getExtremes())
                                );
                                delete a.eventArgs;
                            }));
                        (l || h) && a.redraw();
                    }));
                l && this.drawChartBox();
                n(e, function (a) {
                    (l || a.isDirty) && a.visible && a.redraw();
                });
                f && f.reset(!0);
                B.draw();
                k(this, "redraw");
                r && this.cloneRenderTo(!0);
                n(G, function (a) {
                    a.call();
                });
            },
            get: function (a) {
                var b = this.axes,
                    c = this.series,
                    e,
                    f;
                for (e = 0; e < b.length; e++)
                    if (b[e].options.id === a) return b[e];
                for (e = 0; e < c.length; e++)
                    if (c[e].options.id === a) return c[e];
                for (e = 0; e < c.length; e++)
                    for (f = c[e].points || [], b = 0; b < f.length; b++)
                        if (f[b].id === a) return f[b];
                return null;
            },
            getAxes: function () {
                var a = this,
                    b = this.options,
                    c = (b.xAxis = N(b.xAxis || {})),
                    b = (b.yAxis = N(b.yAxis || {}));
                n(c, function (a, b) {
                    a.index = b;
                    a.isX = !0;
                });
                n(b, function (a, b) {
                    a.index = b;
                });
                c = c.concat(b);
                n(c, function (b) {
                    new d(a, b);
                });
            },
            getSelectedPoints: function () {
                var a = [];
                n(this.series, function (b) {
                    a = a.concat(
                        h(b.points || [], function (a) {
                            return a.selected;
                        })
                    );
                });
                return a;
            },
            getSelectedSeries: function () {
                return h(this.series, function (a) {
                    return a.selected;
                });
            },
            setTitle: function (a, b, c) {
                var e = this,
                    f = e.options,
                    d;
                d = f.title = K(
                    {
                        style: {
                            color: "#333333",
                            fontSize: f.isStock ? "16px" : "18px",
                        },
                    },
                    f.title,
                    a
                );
                f = f.subtitle = K(
                    { style: { color: "#666666" } },
                    f.subtitle,
                    b
                );
                n(
                    [
                        ["title", a, d],
                        ["subtitle", b, f],
                    ],
                    function (a, b) {
                        var c = a[0],
                            f = e[c],
                            d = a[1];
                        a = a[2];
                        f && d && (e[c] = f = f.destroy());
                        a &&
                            a.text &&
                            !f &&
                            ((e[c] = e.renderer
                                .text(a.text, 0, 0, a.useHTML)
                                .attr({
                                    align: a.align,
                                    class: "highcharts-" + c,
                                    zIndex: a.zIndex || 4,
                                })
                                .add()),
                            (e[c].update = function (a) {
                                e.setTitle(!b && a, b && a);
                            }),
                            e[c].css(a.style));
                    }
                );
                e.layOutTitles(c);
            },
            layOutTitles: function (a) {
                var b = 0,
                    c,
                    e = this.renderer,
                    f = this.spacingBox;
                n(
                    ["title", "subtitle"],
                    function (a) {
                        var c = this[a],
                            d = this.options[a],
                            m;
                        c &&
                            ((m = d.style.fontSize),
                            (m = e.fontMetrics(m, c).b),
                            c
                                .css({
                                    width:
                                        (d.width || f.width + d.widthAdjust) +
                                        "px",
                                })
                                .align(
                                    t(
                                        { y: b + m + ("title" === a ? -3 : 2) },
                                        d
                                    ),
                                    !1,
                                    "spacingBox"
                                ),
                            d.floating ||
                                d.verticalAlign ||
                                (b = Math.ceil(b + c.getBBox().height)));
                    },
                    this
                );
                c = this.titleOffset !== b;
                this.titleOffset = b;
                !this.isDirtyBox &&
                    c &&
                    ((this.isDirtyBox = c),
                    this.hasRendered &&
                        J(a, !0) &&
                        this.isDirtyBox &&
                        this.redraw());
            },
            getChartSize: function () {
                var a = this.options.chart,
                    c = a.width,
                    a = a.height,
                    f = this.renderToClone || this.renderTo;
                b(c) || (this.containerWidth = e(f, "width"));
                b(a) || (this.containerHeight = e(f, "height"));
                this.chartWidth = Math.max(0, c || this.containerWidth || 600);
                this.chartHeight = Math.max(
                    0,
                    J(a, 19 < this.containerHeight ? this.containerHeight : 400)
                );
            },
            cloneRenderTo: function (a) {
                var b = this.renderToClone,
                    c = this.container;
                if (a) {
                    if (b) {
                        for (; b.childNodes.length; )
                            this.renderTo.appendChild(b.firstChild);
                        l(b);
                        delete this.renderToClone;
                    }
                } else
                    c &&
                        c.parentNode === this.renderTo &&
                        this.renderTo.removeChild(c),
                        (this.renderToClone = b = this.renderTo.cloneNode(0)),
                        f(b, {
                            position: "absolute",
                            top: "-9999px",
                            display: "block",
                        }),
                        b.style.setProperty &&
                            b.style.setProperty(
                                "display",
                                "block",
                                "important"
                            ),
                        p.body.appendChild(b),
                        c && b.appendChild(c);
            },
            setClassName: function (a) {
                this.container.className = "highcharts-container " + (a || "");
            },
            getContainer: function () {
                var b,
                    e = this.options,
                    f = e.chart,
                    d,
                    m;
                b = this.renderTo;
                var h = a.uniqueKey(),
                    k;
                b || (this.renderTo = b = f.renderTo);
                c(b) && (this.renderTo = b = p.getElementById(b));
                b || w(13, !0);
                d = D(H(b, "data-highcharts-chart"));
                C(d) && r[d] && r[d].hasRendered && r[d].destroy();
                H(b, "data-highcharts-chart", this.index);
                b.innerHTML = "";
                f.skipClone || b.offsetWidth || this.cloneRenderTo();
                this.getChartSize();
                d = this.chartWidth;
                m = this.chartHeight;
                k = t(
                    {
                        position: "relative",
                        overflow: "hidden",
                        width: d + "px",
                        height: m + "px",
                        textAlign: "left",
                        lineHeight: "normal",
                        zIndex: 0,
                        "-webkit-tap-highlight-color": "rgba(0,0,0,0)",
                    },
                    f.style
                );
                this.container = b = g(
                    "div",
                    { id: h },
                    k,
                    this.renderToClone || b
                );
                this._cursor = b.style.cursor;
                this.renderer = new (a[f.renderer] || P)(
                    b,
                    d,
                    m,
                    null,
                    f.forExport,
                    e.exporting && e.exporting.allowHTML
                );
                this.setClassName(f.className);
                this.renderer.setStyle(f.style);
                this.renderer.chartIndex = this.index;
            },
            getMargins: function (a) {
                var c = this.spacing,
                    e = this.margin,
                    f = this.titleOffset;
                this.resetMargins();
                f &&
                    !b(e[0]) &&
                    (this.plotTop = Math.max(
                        this.plotTop,
                        f + this.options.title.margin + c[0]
                    ));
                this.legend.display && this.legend.adjustMargins(e, c);
                this.extraBottomMargin &&
                    (this.marginBottom += this.extraBottomMargin);
                this.extraTopMargin && (this.plotTop += this.extraTopMargin);
                a || this.getAxisMargins();
            },
            getAxisMargins: function () {
                var a = this,
                    c = (a.axisOffset = [0, 0, 0, 0]),
                    e = a.margin;
                a.hasCartesianSeries &&
                    n(a.axes, function (a) {
                        a.visible && a.getOffset();
                    });
                n(x, function (f, d) {
                    b(e[d]) || (a[f] += c[d]);
                });
                a.setChartSize();
            },
            reflow: function (a) {
                var c = this,
                    f = c.options.chart,
                    d = c.renderTo,
                    m = b(f.width),
                    h = f.width || e(d, "width"),
                    f = f.height || e(d, "height"),
                    d = a ? a.target : O;
                if (!m && !c.isPrinting && h && f && (d === O || d === p)) {
                    if (h !== c.containerWidth || f !== c.containerHeight)
                        clearTimeout(c.reflowTimeout),
                            (c.reflowTimeout = z(
                                function () {
                                    c.container &&
                                        c.setSize(void 0, void 0, !1);
                                },
                                a ? 100 : 0
                            ));
                    c.containerWidth = h;
                    c.containerHeight = f;
                }
            },
            initReflow: function () {
                var a = this,
                    b;
                b = E(O, "resize", function (b) {
                    a.reflow(b);
                });
                E(a, "destroy", b);
            },
            setSize: function (b, c, e) {
                var d = this,
                    m = d.renderer;
                d.isResizing += 1;
                a.setAnimation(e, d);
                d.oldChartHeight = d.chartHeight;
                d.oldChartWidth = d.chartWidth;
                void 0 !== b && (d.options.chart.width = b);
                void 0 !== c && (d.options.chart.height = c);
                d.getChartSize();
                b = m.globalAnimation;
                (b ? A : f)(
                    d.container,
                    {
                        width: d.chartWidth + "px",
                        height: d.chartHeight + "px",
                    },
                    b
                );
                d.setChartSize(!0);
                m.setSize(d.chartWidth, d.chartHeight, e);
                n(d.axes, function (a) {
                    a.isDirty = !0;
                    a.setScale();
                });
                d.isDirtyLegend = !0;
                d.isDirtyBox = !0;
                d.layOutTitles();
                d.getMargins();
                d.setResponsive && d.setResponsive(!1);
                d.redraw(e);
                d.oldChartHeight = null;
                k(d, "resize");
                z(function () {
                    d &&
                        k(d, "endResize", null, function () {
                            --d.isResizing;
                        });
                }, F(b).duration);
            },
            setChartSize: function (a) {
                var b = this.inverted,
                    c = this.renderer,
                    e = this.chartWidth,
                    f = this.chartHeight,
                    d = this.options.chart,
                    m = this.spacing,
                    h = this.clipOffset,
                    k,
                    g,
                    q,
                    l;
                this.plotLeft = k = Math.round(this.plotLeft);
                this.plotTop = g = Math.round(this.plotTop);
                this.plotWidth = q = Math.max(
                    0,
                    Math.round(e - k - this.marginRight)
                );
                this.plotHeight = l = Math.max(
                    0,
                    Math.round(f - g - this.marginBottom)
                );
                this.plotSizeX = b ? l : q;
                this.plotSizeY = b ? q : l;
                this.plotBorderWidth = d.plotBorderWidth || 0;
                this.spacingBox = c.spacingBox = {
                    x: m[3],
                    y: m[0],
                    width: e - m[3] - m[1],
                    height: f - m[0] - m[2],
                };
                this.plotBox = c.plotBox = { x: k, y: g, width: q, height: l };
                e = 2 * Math.floor(this.plotBorderWidth / 2);
                b = Math.ceil(Math.max(e, h[3]) / 2);
                c = Math.ceil(Math.max(e, h[0]) / 2);
                this.clipBox = {
                    x: b,
                    y: c,
                    width: Math.floor(
                        this.plotSizeX - Math.max(e, h[1]) / 2 - b
                    ),
                    height: Math.max(
                        0,
                        Math.floor(this.plotSizeY - Math.max(e, h[2]) / 2 - c)
                    ),
                };
                a ||
                    n(this.axes, function (a) {
                        a.setAxisSize();
                        a.setAxisTranslation();
                    });
            },
            resetMargins: function () {
                var a = this,
                    b = a.options.chart;
                n(["margin", "spacing"], function (c) {
                    var e = b[c],
                        f = u(e) ? e : [e, e, e, e];
                    n(["Top", "Right", "Bottom", "Left"], function (e, d) {
                        a[c][d] = J(b[c + e], f[d]);
                    });
                });
                n(x, function (b, c) {
                    a[b] = J(a.margin[c], a.spacing[c]);
                });
                a.axisOffset = [0, 0, 0, 0];
                a.clipOffset = [0, 0, 0, 0];
            },
            drawChartBox: function () {
                var a = this.options.chart,
                    b = this.renderer,
                    c = this.chartWidth,
                    e = this.chartHeight,
                    f = this.chartBackground,
                    d = this.plotBackground,
                    m = this.plotBorder,
                    h,
                    k = this.plotBGImage,
                    g = a.backgroundColor,
                    n = a.plotBackgroundColor,
                    q = a.plotBackgroundImage,
                    l,
                    D = this.plotLeft,
                    u = this.plotTop,
                    t = this.plotWidth,
                    r = this.plotHeight,
                    G = this.plotBox,
                    p = this.clipRect,
                    x = this.clipBox,
                    z = "animate";
                f ||
                    ((this.chartBackground = f =
                        b.rect().addClass("highcharts-background").add()),
                    (z = "attr"));
                h = a.borderWidth || 0;
                l = h + (a.shadow ? 8 : 0);
                g = { fill: g || "none" };
                if (h || f["stroke-width"])
                    (g.stroke = a.borderColor), (g["stroke-width"] = h);
                f.attr(g).shadow(a.shadow);
                f[z]({
                    x: l / 2,
                    y: l / 2,
                    width: c - l - (h % 2),
                    height: e - l - (h % 2),
                    r: a.borderRadius,
                });
                z = "animate";
                d ||
                    ((z = "attr"),
                    (this.plotBackground = d =
                        b.rect().addClass("highcharts-plot-background").add()));
                d[z](G);
                d.attr({ fill: n || "none" }).shadow(a.plotShadow);
                q &&
                    (k
                        ? k.animate(G)
                        : (this.plotBGImage = b.image(q, D, u, t, r).add()));
                p
                    ? p.animate({ width: x.width, height: x.height })
                    : (this.clipRect = b.clipRect(x));
                z = "animate";
                m ||
                    ((z = "attr"),
                    (this.plotBorder = m =
                        b
                            .rect()
                            .addClass("highcharts-plot-border")
                            .attr({ zIndex: 1 })
                            .add()));
                m.attr({
                    stroke: a.plotBorderColor,
                    "stroke-width": a.plotBorderWidth || 0,
                    fill: "none",
                });
                m[z](
                    m.crisp(
                        { x: D, y: u, width: t, height: r },
                        -m.strokeWidth()
                    )
                );
                this.isDirtyBox = !1;
            },
            propFromSeries: function () {
                var a = this,
                    b = a.options.chart,
                    c,
                    e = a.options.series,
                    f,
                    d;
                n(["inverted", "angular", "polar"], function (m) {
                    c = L[b.type || b.defaultSeriesType];
                    d = b[m] || (c && c.prototype[m]);
                    for (f = e && e.length; !d && f--; )
                        (c = L[e[f].type]) && c.prototype[m] && (d = !0);
                    a[m] = d;
                });
            },
            linkSeries: function () {
                var a = this,
                    b = a.series;
                n(b, function (a) {
                    a.linkedSeries.length = 0;
                });
                n(b, function (b) {
                    var e = b.options.linkedTo;
                    c(e) &&
                        (e =
                            ":previous" === e
                                ? a.series[b.index - 1]
                                : a.get(e)) &&
                        e.linkedParent !== b &&
                        (e.linkedSeries.push(b),
                        (b.linkedParent = e),
                        (b.visible = J(
                            b.options.visible,
                            e.options.visible,
                            b.visible
                        )));
                });
            },
            renderSeries: function () {
                n(this.series, function (a) {
                    a.translate();
                    a.render();
                });
            },
            renderLabels: function () {
                var a = this,
                    b = a.options.labels;
                b.items &&
                    n(b.items, function (c) {
                        var e = t(b.style, c.style),
                            f = D(e.left) + a.plotLeft,
                            d = D(e.top) + a.plotTop + 12;
                        delete e.left;
                        delete e.top;
                        a.renderer
                            .text(c.html, f, d)
                            .attr({ zIndex: 2 })
                            .css(e)
                            .add();
                    });
            },
            render: function () {
                var a = this.axes,
                    b = this.renderer,
                    c = this.options,
                    e,
                    f,
                    d;
                this.setTitle();
                this.legend = new q(this, c.legend);
                this.getStacks && this.getStacks();
                this.getMargins(!0);
                this.setChartSize();
                c = this.plotWidth;
                e = this.plotHeight -= 21;
                n(a, function (a) {
                    a.setScale();
                });
                this.getAxisMargins();
                f = 1.1 < c / this.plotWidth;
                d = 1.05 < e / this.plotHeight;
                if (f || d)
                    n(a, function (a) {
                        ((a.horiz && f) || (!a.horiz && d)) &&
                            a.setTickInterval(!0);
                    }),
                        this.getMargins();
                this.drawChartBox();
                this.hasCartesianSeries &&
                    n(a, function (a) {
                        a.visible && a.render();
                    });
                this.seriesGroup ||
                    (this.seriesGroup = b
                        .g("series-group")
                        .attr({ zIndex: 3 })
                        .add());
                this.renderSeries();
                this.renderLabels();
                this.addCredits();
                this.setResponsive && this.setResponsive();
                this.hasRendered = !0;
            },
            addCredits: function (a) {
                var b = this;
                a = K(!0, this.options.credits, a);
                a.enabled &&
                    !this.credits &&
                    ((this.credits = this.renderer
                        .text(a.text + (this.mapCredits || ""), 0, 0)
                        .addClass("highcharts-credits")
                        .on("click", function () {
                            a.href && (O.location.href = a.href);
                        })
                        .attr({ align: a.position.align, zIndex: 8 })
                        .css(a.style)
                        .add()
                        .align(a.position)),
                    (this.credits.update = function (a) {
                        b.credits = b.credits.destroy();
                        b.addCredits(a);
                    }));
            },
            destroy: function () {
                var b = this,
                    c = b.axes,
                    e = b.series,
                    f = b.container,
                    d,
                    m = f && f.parentNode;
                k(b, "destroy");
                r[b.index] = void 0;
                a.chartCount--;
                b.renderTo.removeAttribute("data-highcharts-chart");
                G(b);
                for (d = c.length; d--; ) c[d] = c[d].destroy();
                this.scroller &&
                    this.scroller.destroy &&
                    this.scroller.destroy();
                for (d = e.length; d--; ) e[d] = e[d].destroy();
                n(
                    "title subtitle chartBackground plotBackground plotBGImage plotBorder seriesGroup clipRect credits pointer rangeSelector legend resetZoomButton tooltip renderer".split(
                        " "
                    ),
                    function (a) {
                        var c = b[a];
                        c && c.destroy && (b[a] = c.destroy());
                    }
                );
                f && ((f.innerHTML = ""), G(f), m && l(f));
                for (d in b) delete b[d];
            },
            isReadyToRender: function () {
                var a = this;
                return m || O != O.top || "complete" === p.readyState
                    ? !0
                    : (p.attachEvent("onreadystatechange", function () {
                          p.detachEvent("onreadystatechange", a.firstRender);
                          "complete" === p.readyState && a.firstRender();
                      }),
                      !1);
            },
            firstRender: function () {
                var a = this,
                    b = a.options;
                if (a.isReadyToRender()) {
                    a.getContainer();
                    k(a, "init");
                    a.resetMargins();
                    a.setChartSize();
                    a.propFromSeries();
                    a.getAxes();
                    n(b.series || [], function (b) {
                        a.initSeries(b);
                    });
                    a.linkSeries();
                    k(a, "beforeRender");
                    I && (a.pointer = new I(a, b));
                    a.render();
                    a.renderer.draw();
                    if (!a.renderer.imgCount && a.onload) a.onload();
                    a.cloneRenderTo(!0);
                }
            },
            onload: function () {
                n(
                    [this.callback].concat(this.callbacks),
                    function (a) {
                        a && void 0 !== this.index && a.apply(this, [this]);
                    },
                    this
                );
                k(this, "load");
                !1 !== this.options.chart.reflow && this.initReflow();
                this.onload = null;
            },
        };
    })(M);
    (function (a) {
        var E,
            A = a.each,
            F = a.extend,
            H = a.erase,
            p = a.fireEvent,
            d = a.format,
            g = a.isArray,
            v = a.isNumber,
            l = a.pick,
            r = a.removeEvent;
        E = a.Point = function () {};
        E.prototype = {
            init: function (a, b, d) {
                this.series = a;
                this.color = a.color;
                this.applyOptions(b, d);
                a.options.colorByPoint
                    ? ((b = a.options.colors || a.chart.options.colors),
                      (this.color = this.color || b[a.colorCounter]),
                      (b = b.length),
                      (d = a.colorCounter),
                      a.colorCounter++,
                      a.colorCounter === b && (a.colorCounter = 0))
                    : (d = a.colorIndex);
                this.colorIndex = l(this.colorIndex, d);
                a.chart.pointCount++;
                return this;
            },
            applyOptions: function (a, b) {
                var f = this.series,
                    d = f.options.pointValKey || f.pointValKey;
                a = E.prototype.optionsToObject.call(this, a);
                F(this, a);
                this.options = this.options ? F(this.options, a) : a;
                a.group && delete this.group;
                d && (this.y = this[d]);
                this.isNull = l(
                    this.isValid && !this.isValid(),
                    null === this.x || !v(this.y, !0)
                );
                this.selected && (this.state = "select");
                "name" in this &&
                    void 0 === b &&
                    f.xAxis &&
                    f.xAxis.hasNames &&
                    (this.x = f.xAxis.nameToX(this));
                void 0 === this.x &&
                    f &&
                    (this.x = void 0 === b ? f.autoIncrement(this) : b);
                return this;
            },
            optionsToObject: function (a) {
                var b = {},
                    f = this.series,
                    d = f.options.keys,
                    l = d || f.pointArrayMap || ["y"],
                    k = l.length,
                    e = 0,
                    h = 0;
                if (v(a) || null === a) b[l[0]] = a;
                else if (g(a))
                    for (
                        !d &&
                        a.length > k &&
                        ((f = typeof a[0]),
                        "string" === f
                            ? (b.name = a[0])
                            : "number" === f && (b.x = a[0]),
                        e++);
                        h < k;

                    )
                        (d && void 0 === a[e]) || (b[l[h]] = a[e]), e++, h++;
                else
                    "object" === typeof a &&
                        ((b = a),
                        a.dataLabels && (f._hasPointLabels = !0),
                        a.marker && (f._hasPointMarkers = !0));
                return b;
            },
            getClassName: function () {
                return (
                    "highcharts-point" +
                    (this.selected ? " highcharts-point-select" : "") +
                    (this.negative ? " highcharts-negative" : "") +
                    (this.isNull ? " highcharts-null-point" : "") +
                    (void 0 !== this.colorIndex
                        ? " highcharts-color-" + this.colorIndex
                        : "") +
                    (this.options.className ? " " + this.options.className : "")
                );
            },
            getZone: function () {
                var a = this.series,
                    b = a.zones,
                    a = a.zoneAxis || "y",
                    d = 0,
                    g;
                for (g = b[d]; this[a] >= g.value; ) g = b[++d];
                g && g.color && !this.options.color && (this.color = g.color);
                return g;
            },
            destroy: function () {
                var a = this.series.chart,
                    b = a.hoverPoints,
                    d;
                a.pointCount--;
                b &&
                    (this.setState(),
                    H(b, this),
                    b.length || (a.hoverPoints = null));
                if (this === a.hoverPoint) this.onMouseOut();
                if (this.graphic || this.dataLabel)
                    r(this), this.destroyElements();
                this.legendItem && a.legend.destroyItem(this);
                for (d in this) this[d] = null;
            },
            destroyElements: function () {
                for (
                    var a = [
                            "graphic",
                            "dataLabel",
                            "dataLabelUpper",
                            "connector",
                            "shadowGroup",
                        ],
                        b,
                        d = 6;
                    d--;

                )
                    (b = a[d]), this[b] && (this[b] = this[b].destroy());
            },
            getLabelConfig: function () {
                return {
                    x: this.category,
                    y: this.y,
                    color: this.color,
                    key: this.name || this.category,
                    series: this.series,
                    point: this,
                    percentage: this.percentage,
                    total: this.total || this.stackTotal,
                };
            },
            tooltipFormatter: function (a) {
                var b = this.series,
                    f = b.tooltipOptions,
                    g = l(f.valueDecimals, ""),
                    t = f.valuePrefix || "",
                    k = f.valueSuffix || "";
                A(b.pointArrayMap || ["y"], function (b) {
                    b = "{point." + b;
                    if (t || k) a = a.replace(b + "}", t + b + "}" + k);
                    a = a.replace(b + "}", b + ":,." + g + "f}");
                });
                return d(a, { point: this, series: this.series });
            },
            firePointEvent: function (a, b, d) {
                var f = this,
                    g = this.series.options;
                (g.point.events[a] ||
                    (f.options && f.options.events && f.options.events[a])) &&
                    this.importEvents();
                "click" === a &&
                    g.allowPointSelect &&
                    (d = function (a) {
                        f.select &&
                            f.select(
                                null,
                                a.ctrlKey || a.metaKey || a.shiftKey
                            );
                    });
                p(this, a, b, d);
            },
            visible: !0,
        };
    })(M);
    (function (a) {
        var E = a.addEvent,
            A = a.animObject,
            F = a.arrayMax,
            H = a.arrayMin,
            p = a.correctFloat,
            d = a.Date,
            g = a.defaultOptions,
            v = a.defaultPlotOptions,
            l = a.defined,
            r = a.each,
            f = a.erase,
            b = a.error,
            n = a.extend,
            w = a.fireEvent,
            t = a.grep,
            k = a.isArray,
            e = a.isNumber,
            h = a.isString,
            C = a.merge,
            u = a.pick,
            c = a.removeEvent,
            q = a.splat,
            x = a.stableSort,
            K = a.SVGElement,
            I = a.syncTimeout,
            J = a.win;
        a.Series = a.seriesType(
            "line",
            null,
            {
                lineWidth: 2,
                allowPointSelect: !1,
                showCheckbox: !1,
                animation: { duration: 1e3 },
                events: {},
                marker: {
                    lineWidth: 0,
                    lineColor: "#ffffff",
                    radius: 4,
                    states: {
                        hover: {
                            animation: { duration: 50 },
                            enabled: !0,
                            radiusPlus: 2,
                            lineWidthPlus: 1,
                        },
                        select: {
                            fillColor: "#cccccc",
                            lineColor: "#000000",
                            lineWidth: 2,
                        },
                    },
                },
                point: { events: {} },
                dataLabels: {
                    align: "center",
                    formatter: function () {
                        return null === this.y
                            ? ""
                            : a.numberFormat(this.y, -1);
                    },
                    style: {
                        fontSize: "11px",
                        fontWeight: "bold",
                        color: "contrast",
                        textOutline: "1px contrast",
                    },
                    verticalAlign: "bottom",
                    x: 0,
                    y: 0,
                    padding: 5,
                },
                cropThreshold: 300,
                pointRange: 0,
                softThreshold: !0,
                states: {
                    hover: {
                        lineWidthPlus: 1,
                        marker: {},
                        halo: { size: 10, opacity: 0.25 },
                    },
                    select: { marker: {} },
                },
                stickyTracking: !0,
                turboThreshold: 1e3,
            },
            {
                isCartesian: !0,
                pointClass: a.Point,
                sorted: !0,
                requireSorting: !0,
                directTouch: !1,
                axisTypes: ["xAxis", "yAxis"],
                colorCounter: 0,
                parallelArrays: ["x", "y"],
                coll: "series",
                init: function (a, b) {
                    var c = this,
                        e,
                        f,
                        d = a.series,
                        h,
                        k = function (a, b) {
                            return (
                                u(a.options.index, a._i) -
                                u(b.options.index, b._i)
                            );
                        };
                    c.chart = a;
                    c.options = b = c.setOptions(b);
                    c.linkedSeries = [];
                    c.bindAxes();
                    n(c, {
                        name: b.name,
                        state: "",
                        visible: !1 !== b.visible,
                        selected: !0 === b.selected,
                    });
                    f = b.events;
                    for (e in f) E(c, e, f[e]);
                    if (
                        (f && f.click) ||
                        (b.point && b.point.events && b.point.events.click) ||
                        b.allowPointSelect
                    )
                        a.runTrackerClick = !0;
                    c.getColor();
                    c.getSymbol();
                    r(c.parallelArrays, function (a) {
                        c[a + "Data"] = [];
                    });
                    c.setData(b.data, !1);
                    c.isCartesian && (a.hasCartesianSeries = !0);
                    d.length && (h = d[d.length - 1]);
                    c._i = u(h && h._i, -1) + 1;
                    d.push(c);
                    x(d, k);
                    this.yAxis && x(this.yAxis.series, k);
                    r(d, function (a, b) {
                        a.index = b;
                        a.name = a.name || "Series " + (b + 1);
                    });
                },
                bindAxes: function () {
                    var a = this,
                        c = a.options,
                        e = a.chart,
                        f;
                    r(a.axisTypes || [], function (d) {
                        r(e[d], function (b) {
                            f = b.options;
                            if (
                                c[d] === f.index ||
                                (void 0 !== c[d] && c[d] === f.id) ||
                                (void 0 === c[d] && 0 === f.index)
                            )
                                b.series.push(a), (a[d] = b), (b.isDirty = !0);
                        });
                        a[d] || a.optionalAxis === d || b(18, !0);
                    });
                },
                updateParallelArrays: function (a, b) {
                    var c = a.series,
                        f = arguments,
                        d = e(b)
                            ? function (e) {
                                  var f =
                                      "y" === e && c.toYData
                                          ? c.toYData(a)
                                          : a[e];
                                  c[e + "Data"][b] = f;
                              }
                            : function (a) {
                                  Array.prototype[b].apply(
                                      c[a + "Data"],
                                      Array.prototype.slice.call(f, 2)
                                  );
                              };
                    r(c.parallelArrays, d);
                },
                autoIncrement: function () {
                    var a = this.options,
                        b = this.xIncrement,
                        c,
                        e = a.pointIntervalUnit,
                        b = u(b, a.pointStart, 0);
                    this.pointInterval = c = u(
                        this.pointInterval,
                        a.pointInterval,
                        1
                    );
                    e &&
                        ((a = new d(b)),
                        "day" === e
                            ? (a = +a[d.hcSetDate](a[d.hcGetDate]() + c))
                            : "month" === e
                            ? (a = +a[d.hcSetMonth](a[d.hcGetMonth]() + c))
                            : "year" === e &&
                              (a = +a[d.hcSetFullYear](
                                  a[d.hcGetFullYear]() + c
                              )),
                        (c = a - b));
                    this.xIncrement = b + c;
                    return b;
                },
                setOptions: function (a) {
                    var b = this.chart,
                        c = b.options.plotOptions,
                        b = b.userOptions || {},
                        e = b.plotOptions || {},
                        f = c[this.type];
                    this.userOptions = a;
                    c = C(f, c.series, a);
                    this.tooltipOptions = C(
                        g.tooltip,
                        g.plotOptions[this.type].tooltip,
                        b.tooltip,
                        e.series && e.series.tooltip,
                        e[this.type] && e[this.type].tooltip,
                        a.tooltip
                    );
                    null === f.marker && delete c.marker;
                    this.zoneAxis = c.zoneAxis;
                    a = this.zones = (c.zones || []).slice();
                    (!c.negativeColor && !c.negativeFillColor) ||
                        c.zones ||
                        a.push({
                            value:
                                c[this.zoneAxis + "Threshold"] ||
                                c.threshold ||
                                0,
                            className: "highcharts-negative",
                            color: c.negativeColor,
                            fillColor: c.negativeFillColor,
                        });
                    a.length &&
                        l(a[a.length - 1].value) &&
                        a.push({
                            color: this.color,
                            fillColor: this.fillColor,
                        });
                    return c;
                },
                getCyclic: function (a, b, c) {
                    var e,
                        f = this.userOptions,
                        d = a + "Index",
                        h = a + "Counter",
                        k = c
                            ? c.length
                            : u(
                                  this.chart.options.chart[a + "Count"],
                                  this.chart[a + "Count"]
                              );
                    b ||
                        ((e = u(f[d], f["_" + d])),
                        l(e) ||
                            ((f["_" + d] = e = this.chart[h] % k),
                            (this.chart[h] += 1)),
                        c && (b = c[e]));
                    void 0 !== e && (this[d] = e);
                    this[a] = b;
                },
                getColor: function () {
                    this.options.colorByPoint
                        ? (this.options.color = null)
                        : this.getCyclic(
                              "color",
                              this.options.color || v[this.type].color,
                              this.chart.options.colors
                          );
                },
                getSymbol: function () {
                    this.getCyclic(
                        "symbol",
                        this.options.marker.symbol,
                        this.chart.options.symbols
                    );
                },
                drawLegendSymbol: a.LegendSymbolMixin.drawLineMarker,
                setData: function (a, c, f, d) {
                    var m = this,
                        g = m.points,
                        n = (g && g.length) || 0,
                        q,
                        l = m.options,
                        t = m.chart,
                        D = null,
                        p = m.xAxis,
                        x = l.turboThreshold,
                        G = this.xData,
                        w = this.yData,
                        v = (q = m.pointArrayMap) && q.length;
                    a = a || [];
                    q = a.length;
                    c = u(c, !0);
                    if (
                        !1 !== d &&
                        q &&
                        n === q &&
                        !m.cropped &&
                        !m.hasGroupedData &&
                        m.visible
                    )
                        r(a, function (a, b) {
                            g[b].update &&
                                a !== l.data[b] &&
                                g[b].update(a, !1, null, !1);
                        });
                    else {
                        m.xIncrement = null;
                        m.colorCounter = 0;
                        r(this.parallelArrays, function (a) {
                            m[a + "Data"].length = 0;
                        });
                        if (x && q > x) {
                            for (f = 0; null === D && f < q; ) (D = a[f]), f++;
                            if (e(D))
                                for (f = 0; f < q; f++)
                                    (G[f] = this.autoIncrement()),
                                        (w[f] = a[f]);
                            else if (k(D))
                                if (v)
                                    for (f = 0; f < q; f++)
                                        (D = a[f]),
                                            (G[f] = D[0]),
                                            (w[f] = D.slice(1, v + 1));
                                else
                                    for (f = 0; f < q; f++)
                                        (D = a[f]),
                                            (G[f] = D[0]),
                                            (w[f] = D[1]);
                            else b(12);
                        } else
                            for (f = 0; f < q; f++)
                                void 0 !== a[f] &&
                                    ((D = { series: m }),
                                    m.pointClass.prototype.applyOptions.apply(
                                        D,
                                        [a[f]]
                                    ),
                                    m.updateParallelArrays(D, f));
                        h(w[0]) && b(14, !0);
                        m.data = [];
                        m.options.data = m.userOptions.data = a;
                        for (f = n; f--; )
                            g[f] && g[f].destroy && g[f].destroy();
                        p && (p.minRange = p.userMinRange);
                        m.isDirty = t.isDirtyBox = !0;
                        m.isDirtyData = !!g;
                        f = !1;
                    }
                    "point" === l.legendType &&
                        (this.processData(), this.generatePoints());
                    c && t.redraw(f);
                },
                processData: function (a) {
                    var c = this.xData,
                        e = this.yData,
                        f = c.length,
                        d;
                    d = 0;
                    var h,
                        k,
                        g = this.xAxis,
                        q,
                        n = this.options;
                    q = n.cropThreshold;
                    var l = this.getExtremesFromAll || n.getExtremesFromAll,
                        u = this.isCartesian,
                        n = g && g.val2lin,
                        t = g && g.isLog,
                        r,
                        D;
                    if (
                        u &&
                        !this.isDirty &&
                        !g.isDirty &&
                        !this.yAxis.isDirty &&
                        !a
                    )
                        return !1;
                    g && ((a = g.getExtremes()), (r = a.min), (D = a.max));
                    if (
                        u &&
                        this.sorted &&
                        !l &&
                        (!q || f > q || this.forceCrop)
                    )
                        if (c[f - 1] < r || c[0] > D) (c = []), (e = []);
                        else if (c[0] < r || c[f - 1] > D)
                            (d = this.cropData(this.xData, this.yData, r, D)),
                                (c = d.xData),
                                (e = d.yData),
                                (d = d.start),
                                (h = !0);
                    for (q = c.length || 1; --q; )
                        (f = t ? n(c[q]) - n(c[q - 1]) : c[q] - c[q - 1]),
                            0 < f && (void 0 === k || f < k)
                                ? (k = f)
                                : 0 > f && this.requireSorting && b(15);
                    this.cropped = h;
                    this.cropStart = d;
                    this.processedXData = c;
                    this.processedYData = e;
                    this.closestPointRange = k;
                },
                cropData: function (a, b, c, e) {
                    var f = a.length,
                        d = 0,
                        h = f,
                        k = u(this.cropShoulder, 1),
                        g;
                    for (g = 0; g < f; g++)
                        if (a[g] >= c) {
                            d = Math.max(0, g - k);
                            break;
                        }
                    for (c = g; c < f; c++)
                        if (a[c] > e) {
                            h = c + k;
                            break;
                        }
                    return {
                        xData: a.slice(d, h),
                        yData: b.slice(d, h),
                        start: d,
                        end: h,
                    };
                },
                generatePoints: function () {
                    var a = this.options.data,
                        b = this.data,
                        c,
                        e = this.processedXData,
                        f = this.processedYData,
                        d = this.pointClass,
                        h = e.length,
                        g = this.cropStart || 0,
                        k,
                        n = this.hasGroupedData,
                        l,
                        u = [],
                        t;
                    b ||
                        n ||
                        ((b = []), (b.length = a.length), (b = this.data = b));
                    for (t = 0; t < h; t++)
                        (k = g + t),
                            n
                                ? ((l = new d().init(
                                      this,
                                      [e[t]].concat(q(f[t]))
                                  )),
                                  (l.dataGroup = this.groupMap[t]))
                                : (l = b[k]) ||
                                  void 0 === a[k] ||
                                  (b[k] = l = new d().init(this, a[k], e[t])),
                            (l.index = k),
                            (u[t] = l);
                    if (b && (h !== (c = b.length) || n))
                        for (t = 0; t < c; t++)
                            t !== g || n || (t += h),
                                b[t] &&
                                    (b[t].destroyElements(),
                                    (b[t].plotX = void 0));
                    this.data = b;
                    this.points = u;
                },
                getExtremes: function (a) {
                    var b = this.yAxis,
                        c = this.processedXData,
                        f,
                        d = [],
                        h = 0;
                    f = this.xAxis.getExtremes();
                    var g = f.min,
                        q = f.max,
                        n,
                        l,
                        t,
                        u;
                    a = a || this.stackedYData || this.processedYData || [];
                    f = a.length;
                    for (u = 0; u < f; u++)
                        if (
                            ((l = c[u]),
                            (t = a[u]),
                            (n =
                                (e(t, !0) || k(t)) &&
                                (!b.isLog || t.length || 0 < t)),
                            (l =
                                this.getExtremesFromAll ||
                                this.options.getExtremesFromAll ||
                                this.cropped ||
                                ((c[u + 1] || l) >= g && (c[u - 1] || l) <= q)),
                            n && l)
                        )
                            if ((n = t.length))
                                for (; n--; ) null !== t[n] && (d[h++] = t[n]);
                            else d[h++] = t;
                    this.dataMin = H(d);
                    this.dataMax = F(d);
                },
                translate: function () {
                    this.processedXData || this.processData();
                    this.generatePoints();
                    var a = this.options,
                        b = a.stacking,
                        c = this.xAxis,
                        f = c.categories,
                        d = this.yAxis,
                        h = this.points,
                        g = h.length,
                        k = !!this.modifyValue,
                        n = a.pointPlacement,
                        q = "between" === n || e(n),
                        t = a.threshold,
                        r = a.startFromThreshold ? t : 0,
                        x,
                        w,
                        v,
                        I,
                        K = Number.MAX_VALUE;
                    "between" === n && (n = 0.5);
                    e(n) && (n *= u(a.pointRange || c.pointRange));
                    for (a = 0; a < g; a++) {
                        var C = h[a],
                            J = C.x,
                            A = C.y;
                        w = C.low;
                        var E =
                                b &&
                                d.stacks[
                                    (this.negStacks && A < (r ? 0 : t)
                                        ? "-"
                                        : "") + this.stackKey
                                ],
                            F;
                        d.isLog && null !== A && 0 >= A && (C.isNull = !0);
                        C.plotX = x = p(
                            Math.min(
                                Math.max(
                                    -1e5,
                                    c.translate(
                                        J,
                                        0,
                                        0,
                                        0,
                                        1,
                                        n,
                                        "flags" === this.type
                                    )
                                ),
                                1e5
                            )
                        );
                        b &&
                            this.visible &&
                            !C.isNull &&
                            E &&
                            E[J] &&
                            ((I = this.getStackIndicator(I, J, this.index)),
                            (F = E[J]),
                            (A = F.points[I.key]),
                            (w = A[0]),
                            (A = A[1]),
                            w === r && I.key === E[J].base && (w = u(t, d.min)),
                            d.isLog && 0 >= w && (w = null),
                            (C.total = C.stackTotal = F.total),
                            (C.percentage = F.total && (C.y / F.total) * 100),
                            (C.stackY = A),
                            F.setOffset(
                                this.pointXOffset || 0,
                                this.barW || 0
                            ));
                        C.yBottom = l(w) ? d.translate(w, 0, 1, 0, 1) : null;
                        k && (A = this.modifyValue(A, C));
                        C.plotY = w =
                            "number" === typeof A && Infinity !== A
                                ? Math.min(
                                      Math.max(
                                          -1e5,
                                          d.translate(A, 0, 1, 0, 1)
                                      ),
                                      1e5
                                  )
                                : void 0;
                        C.isInside =
                            void 0 !== w &&
                            0 <= w &&
                            w <= d.len &&
                            0 <= x &&
                            x <= c.len;
                        C.clientX = q ? p(c.translate(J, 0, 0, 0, 1, n)) : x;
                        C.negative = C.y < (t || 0);
                        C.category = f && void 0 !== f[C.x] ? f[C.x] : C.x;
                        C.isNull ||
                            (void 0 !== v && (K = Math.min(K, Math.abs(x - v))),
                            (v = x));
                    }
                    this.closestPointRangePx = K;
                },
                getValidPoints: function (a, b) {
                    var c = this.chart;
                    return t(a || this.points || [], function (a) {
                        return b &&
                            !c.isInsidePlot(a.plotX, a.plotY, c.inverted)
                            ? !1
                            : !a.isNull;
                    });
                },
                setClip: function (a) {
                    var b = this.chart,
                        c = this.options,
                        e = b.renderer,
                        f = b.inverted,
                        d = this.clipBox,
                        h = d || b.clipBox,
                        g =
                            this.sharedClipKey ||
                            [
                                "_sharedClip",
                                a && a.duration,
                                a && a.easing,
                                h.height,
                                c.xAxis,
                                c.yAxis,
                            ].join(),
                        k = b[g],
                        n = b[g + "m"];
                    k ||
                        (a &&
                            ((h.width = 0),
                            (b[g + "m"] = n =
                                e.clipRect(
                                    -99,
                                    f ? -b.plotLeft : -b.plotTop,
                                    99,
                                    f ? b.chartWidth : b.chartHeight
                                ))),
                        (b[g] = k = e.clipRect(h)),
                        (k.count = { length: 0 }));
                    a &&
                        !k.count[this.index] &&
                        ((k.count[this.index] = !0), (k.count.length += 1));
                    !1 !== c.clip &&
                        (this.group.clip(a || d ? k : b.clipRect),
                        this.markerGroup.clip(n),
                        (this.sharedClipKey = g));
                    a ||
                        (k.count[this.index] &&
                            (delete k.count[this.index], --k.count.length),
                        0 === k.count.length &&
                            g &&
                            b[g] &&
                            (d || (b[g] = b[g].destroy()),
                            b[g + "m"] && (b[g + "m"] = b[g + "m"].destroy())));
                },
                animate: function (a) {
                    var b = this.chart,
                        c = A(this.options.animation),
                        e;
                    a
                        ? this.setClip(c)
                        : ((e = this.sharedClipKey),
                          (a = b[e]) && a.animate({ width: b.plotSizeX }, c),
                          b[e + "m"] &&
                              b[e + "m"].animate(
                                  { width: b.plotSizeX + 99 },
                                  c
                              ),
                          (this.animate = null));
                },
                afterAnimate: function () {
                    this.setClip();
                    w(this, "afterAnimate");
                },
                drawPoints: function () {
                    var a = this.points,
                        b = this.chart,
                        c,
                        f,
                        d,
                        h,
                        g = this.options.marker,
                        k,
                        n,
                        q,
                        l,
                        t = this.markerGroup,
                        r = u(
                            g.enabled,
                            this.xAxis.isRadial ? !0 : null,
                            this.closestPointRangePx > 2 * g.radius
                        );
                    if (!1 !== g.enabled || this._hasPointMarkers)
                        for (f = a.length; f--; )
                            (d = a[f]),
                                (c = d.plotY),
                                (h = d.graphic),
                                (k = d.marker || {}),
                                (n = !!d.marker),
                                (q = (r && void 0 === k.enabled) || k.enabled),
                                (l = d.isInside),
                                q && e(c) && null !== d.y
                                    ? ((c = u(k.symbol, this.symbol)),
                                      (d.hasImage = 0 === c.indexOf("url")),
                                      (q = this.markerAttribs(
                                          d,
                                          d.selected && "select"
                                      )),
                                      h
                                          ? h[l ? "show" : "hide"](!0).animate(
                                                q
                                            )
                                          : l &&
                                            (0 < q.width || d.hasImage) &&
                                            (d.graphic = h =
                                                b.renderer
                                                    .symbol(
                                                        c,
                                                        q.x,
                                                        q.y,
                                                        q.width,
                                                        q.height,
                                                        n ? k : g
                                                    )
                                                    .add(t)),
                                      h &&
                                          h.attr(
                                              this.pointAttribs(
                                                  d,
                                                  d.selected && "select"
                                              )
                                          ),
                                      h && h.addClass(d.getClassName(), !0))
                                    : h && (d.graphic = h.destroy());
                },
                markerAttribs: function (a, b) {
                    var c = this.options.marker,
                        e = a && a.options,
                        f = (e && e.marker) || {},
                        e = u(f.radius, c.radius);
                    b &&
                        ((c = c.states[b]),
                        (b = f.states && f.states[b]),
                        (e = u(
                            b && b.radius,
                            c && c.radius,
                            e + ((c && c.radiusPlus) || 0)
                        )));
                    a.hasImage && (e = 0);
                    a = { x: Math.floor(a.plotX) - e, y: a.plotY - e };
                    e && (a.width = a.height = 2 * e);
                    return a;
                },
                pointAttribs: function (a, b) {
                    var c = this.options.marker,
                        e = a && a.options,
                        f = (e && e.marker) || {},
                        d = this.color,
                        h = e && e.color,
                        g = a && a.color,
                        e = u(f.lineWidth, c.lineWidth),
                        k;
                    a &&
                        this.zones.length &&
                        (a = a.getZone()) &&
                        a.color &&
                        (k = a.color);
                    d = h || k || g || d;
                    k = f.fillColor || c.fillColor || d;
                    d = f.lineColor || c.lineColor || d;
                    b &&
                        ((c = c.states[b]),
                        (b = (f.states && f.states[b]) || {}),
                        (e = u(
                            b.lineWidth,
                            c.lineWidth,
                            e + u(b.lineWidthPlus, c.lineWidthPlus, 0)
                        )),
                        (k = b.fillColor || c.fillColor || k),
                        (d = b.lineColor || c.lineColor || d));
                    return { stroke: d, "stroke-width": e, fill: k };
                },
                destroy: function () {
                    var a = this,
                        b = a.chart,
                        e = /AppleWebKit\/533/.test(J.navigator.userAgent),
                        d,
                        h = a.data || [],
                        k,
                        g,
                        n;
                    w(a, "destroy");
                    c(a);
                    r(a.axisTypes || [], function (b) {
                        (n = a[b]) &&
                            n.series &&
                            (f(n.series, a), (n.isDirty = n.forceRedraw = !0));
                    });
                    a.legendItem && a.chart.legend.destroyItem(a);
                    for (d = h.length; d--; )
                        (k = h[d]) && k.destroy && k.destroy();
                    a.points = null;
                    clearTimeout(a.animationTimeout);
                    for (g in a)
                        a[g] instanceof K &&
                            !a[g].survive &&
                            ((d = e && "group" === g ? "hide" : "destroy"),
                            a[g][d]());
                    b.hoverSeries === a && (b.hoverSeries = null);
                    f(b.series, a);
                    for (g in a) delete a[g];
                },
                getGraphPath: function (a, b, c) {
                    var e = this,
                        f = e.options,
                        d = f.step,
                        h,
                        k = [],
                        g = [],
                        n;
                    a = a || e.points;
                    (h = a.reversed) && a.reverse();
                    (d = { right: 1, center: 2 }[d] || (d && 3)) &&
                        h &&
                        (d = 4 - d);
                    !f.connectNulls || b || c || (a = this.getValidPoints(a));
                    r(a, function (h, m) {
                        var q = h.plotX,
                            t = h.plotY,
                            u = a[m - 1];
                        (h.leftCliff || (u && u.rightCliff)) && !c && (n = !0);
                        h.isNull && !l(b) && 0 < m
                            ? (n = !f.connectNulls)
                            : h.isNull && !b
                            ? (n = !0)
                            : (0 === m || n
                                  ? (m = ["M", h.plotX, h.plotY])
                                  : e.getPointSpline
                                  ? (m = e.getPointSpline(a, h, m))
                                  : d
                                  ? ((m =
                                        1 === d
                                            ? ["L", u.plotX, t]
                                            : 2 === d
                                            ? [
                                                  "L",
                                                  (u.plotX + q) / 2,
                                                  u.plotY,
                                                  "L",
                                                  (u.plotX + q) / 2,
                                                  t,
                                              ]
                                            : ["L", q, u.plotY]),
                                    m.push("L", q, t))
                                  : (m = ["L", q, t]),
                              g.push(h.x),
                              d && g.push(h.x),
                              k.push.apply(k, m),
                              (n = !1));
                    });
                    k.xMap = g;
                    return (e.graphPath = k);
                },
                drawGraph: function () {
                    var a = this,
                        b = this.options,
                        c = (this.gappedPath || this.getGraphPath).call(this),
                        e = [
                            [
                                "graph",
                                "highcharts-graph",
                                b.lineColor || this.color,
                                b.dashStyle,
                            ],
                        ];
                    r(this.zones, function (c, f) {
                        e.push([
                            "zone-graph-" + f,
                            "highcharts-graph highcharts-zone-graph-" +
                                f +
                                " " +
                                (c.className || ""),
                            c.color || a.color,
                            c.dashStyle || b.dashStyle,
                        ]);
                    });
                    r(e, function (e, f) {
                        var d = e[0],
                            h = a[d];
                        h
                            ? ((h.endX = c.xMap), h.animate({ d: c }))
                            : c.length &&
                              ((a[d] = a.chart.renderer
                                  .path(c)
                                  .addClass(e[1])
                                  .attr({ zIndex: 1 })
                                  .add(a.group)),
                              (h = {
                                  stroke: e[2],
                                  "stroke-width": b.lineWidth,
                                  fill: (a.fillGraph && a.color) || "none",
                              }),
                              e[3]
                                  ? (h.dashstyle = e[3])
                                  : "square" !== b.linecap &&
                                    (h["stroke-linecap"] = h[
                                        "stroke-linejoin"
                                    ] =
                                        "round"),
                              (h = a[d].attr(h).shadow(2 > f && b.shadow)));
                        h && ((h.startX = c.xMap), (h.isArea = c.isArea));
                    });
                },
                applyZones: function () {
                    var a = this,
                        b = this.chart,
                        c = b.renderer,
                        e = this.zones,
                        f,
                        d,
                        h = this.clips || [],
                        k,
                        g = this.graph,
                        n = this.area,
                        q = Math.max(b.chartWidth, b.chartHeight),
                        l = this[(this.zoneAxis || "y") + "Axis"],
                        t,
                        p,
                        x = b.inverted,
                        w,
                        v,
                        I,
                        K,
                        C = !1;
                    e.length &&
                        (g || n) &&
                        l &&
                        void 0 !== l.min &&
                        ((p = l.reversed),
                        (w = l.horiz),
                        g && g.hide(),
                        n && n.hide(),
                        (t = l.getExtremes()),
                        r(e, function (e, m) {
                            f = p
                                ? w
                                    ? b.plotWidth
                                    : 0
                                : w
                                ? 0
                                : l.toPixels(t.min);
                            f = Math.min(Math.max(u(d, f), 0), q);
                            d = Math.min(
                                Math.max(
                                    Math.round(
                                        l.toPixels(u(e.value, t.max), !0)
                                    ),
                                    0
                                ),
                                q
                            );
                            C && (f = d = l.toPixels(t.max));
                            v = Math.abs(f - d);
                            I = Math.min(f, d);
                            K = Math.max(f, d);
                            l.isXAxis
                                ? ((k = {
                                      x: x ? K : I,
                                      y: 0,
                                      width: v,
                                      height: q,
                                  }),
                                  w || (k.x = b.plotHeight - k.x))
                                : ((k = {
                                      x: 0,
                                      y: x ? K : I,
                                      width: q,
                                      height: v,
                                  }),
                                  w && (k.y = b.plotWidth - k.y));
                            x &&
                                c.isVML &&
                                (k = l.isXAxis
                                    ? {
                                          x: 0,
                                          y: p ? I : K,
                                          height: k.width,
                                          width: b.chartWidth,
                                      }
                                    : {
                                          x: k.y - b.plotLeft - b.spacingBox.x,
                                          y: 0,
                                          width: k.height,
                                          height: b.chartHeight,
                                      });
                            h[m]
                                ? h[m].animate(k)
                                : ((h[m] = c.clipRect(k)),
                                  g && a["zone-graph-" + m].clip(h[m]),
                                  n && a["zone-area-" + m].clip(h[m]));
                            C = e.value > t.max;
                        }),
                        (this.clips = h));
                },
                invertGroups: function (a) {
                    function b() {
                        var b = { width: c.yAxis.len, height: c.xAxis.len };
                        r(["group", "markerGroup"], function (e) {
                            c[e] && c[e].attr(b).invert(a);
                        });
                    }
                    var c = this,
                        e;
                    c.xAxis &&
                        ((e = E(c.chart, "resize", b)),
                        E(c, "destroy", e),
                        b(a),
                        (c.invertGroups = b));
                },
                plotGroup: function (a, b, c, e, f) {
                    var d = this[a],
                        h = !d;
                    h &&
                        ((this[a] = d =
                            this.chart.renderer
                                .g(b)
                                .attr({ zIndex: e || 0.1 })
                                .add(f)),
                        d.addClass(
                            "highcharts-series-" +
                                this.index +
                                " highcharts-" +
                                this.type +
                                "-series highcharts-color-" +
                                this.colorIndex +
                                " " +
                                (this.options.className || "")
                        ));
                    d.attr({ visibility: c })[h ? "attr" : "animate"](
                        this.getPlotBox()
                    );
                    return d;
                },
                getPlotBox: function () {
                    var a = this.chart,
                        b = this.xAxis,
                        c = this.yAxis;
                    a.inverted && ((b = c), (c = this.xAxis));
                    return {
                        translateX: b ? b.left : a.plotLeft,
                        translateY: c ? c.top : a.plotTop,
                        scaleX: 1,
                        scaleY: 1,
                    };
                },
                render: function () {
                    var a = this,
                        b = a.chart,
                        c,
                        e = a.options,
                        f =
                            !!a.animate &&
                            b.renderer.isSVG &&
                            A(e.animation).duration,
                        d = a.visible ? "inherit" : "hidden",
                        h = e.zIndex,
                        k = a.hasRendered,
                        g = b.seriesGroup,
                        n = b.inverted;
                    c = a.plotGroup("group", "series", d, h, g);
                    a.markerGroup = a.plotGroup(
                        "markerGroup",
                        "markers",
                        d,
                        h,
                        g
                    );
                    f && a.animate(!0);
                    c.inverted = a.isCartesian ? n : !1;
                    a.drawGraph && (a.drawGraph(), a.applyZones());
                    a.drawDataLabels && a.drawDataLabels();
                    a.visible && a.drawPoints();
                    a.drawTracker &&
                        !1 !== a.options.enableMouseTracking &&
                        a.drawTracker();
                    a.invertGroups(n);
                    !1 === e.clip || a.sharedClipKey || k || c.clip(b.clipRect);
                    f && a.animate();
                    k ||
                        (a.animationTimeout = I(function () {
                            a.afterAnimate();
                        }, f));
                    a.isDirty = a.isDirtyData = !1;
                    a.hasRendered = !0;
                },
                redraw: function () {
                    var a = this.chart,
                        b = this.isDirty || this.isDirtyData,
                        c = this.group,
                        e = this.xAxis,
                        f = this.yAxis;
                    c &&
                        (a.inverted &&
                            c.attr({
                                width: a.plotWidth,
                                height: a.plotHeight,
                            }),
                        c.animate({
                            translateX: u(e && e.left, a.plotLeft),
                            translateY: u(f && f.top, a.plotTop),
                        }));
                    this.translate();
                    this.render();
                    b && delete this.kdTree;
                },
                kdDimensions: 1,
                kdAxisArray: ["clientX", "plotY"],
                searchPoint: function (a, b) {
                    var c = this.xAxis,
                        e = this.yAxis,
                        f = this.chart.inverted;
                    return this.searchKDTree(
                        {
                            clientX: f
                                ? c.len - a.chartY + c.pos
                                : a.chartX - c.pos,
                            plotY: f
                                ? e.len - a.chartX + e.pos
                                : a.chartY - e.pos,
                        },
                        b
                    );
                },
                buildKDTree: function () {
                    function a(c, e, f) {
                        var d, h;
                        if ((h = c && c.length))
                            return (
                                (d = b.kdAxisArray[e % f]),
                                c.sort(function (a, b) {
                                    return a[d] - b[d];
                                }),
                                (h = Math.floor(h / 2)),
                                {
                                    point: c[h],
                                    left: a(c.slice(0, h), e + 1, f),
                                    right: a(c.slice(h + 1), e + 1, f),
                                }
                            );
                    }
                    var b = this,
                        c = b.kdDimensions;
                    delete b.kdTree;
                    I(
                        function () {
                            b.kdTree = a(
                                b.getValidPoints(null, !b.directTouch),
                                c,
                                c
                            );
                        },
                        b.options.kdNow ? 0 : 1
                    );
                },
                searchKDTree: function (a, b) {
                    function c(a, b, k, g) {
                        var m = b.point,
                            n = e.kdAxisArray[k % g],
                            q,
                            t,
                            u = m;
                        t =
                            l(a[f]) && l(m[f])
                                ? Math.pow(a[f] - m[f], 2)
                                : null;
                        q =
                            l(a[d]) && l(m[d])
                                ? Math.pow(a[d] - m[d], 2)
                                : null;
                        q = (t || 0) + (q || 0);
                        m.dist = l(q) ? Math.sqrt(q) : Number.MAX_VALUE;
                        m.distX = l(t) ? Math.sqrt(t) : Number.MAX_VALUE;
                        n = a[n] - m[n];
                        q = 0 > n ? "left" : "right";
                        t = 0 > n ? "right" : "left";
                        b[q] &&
                            ((q = c(a, b[q], k + 1, g)),
                            (u = q[h] < u[h] ? q : m));
                        b[t] &&
                            Math.sqrt(n * n) < u[h] &&
                            ((a = c(a, b[t], k + 1, g)),
                            (u = a[h] < u[h] ? a : u));
                        return u;
                    }
                    var e = this,
                        f = this.kdAxisArray[0],
                        d = this.kdAxisArray[1],
                        h = b ? "distX" : "dist";
                    this.kdTree || this.buildKDTree();
                    if (this.kdTree)
                        return c(
                            a,
                            this.kdTree,
                            this.kdDimensions,
                            this.kdDimensions
                        );
                },
            }
        );
    })(M);
    (function (a) {
        function E(a, f, b, d, g) {
            var n = a.chart.inverted;
            this.axis = a;
            this.isNegative = b;
            this.options = f;
            this.x = d;
            this.total = null;
            this.points = {};
            this.stack = g;
            this.rightCliff = this.leftCliff = 0;
            this.alignOptions = {
                align: f.align || (n ? (b ? "left" : "right") : "center"),
                verticalAlign:
                    f.verticalAlign || (n ? "middle" : b ? "bottom" : "top"),
                y: l(f.y, n ? 4 : b ? 14 : -6),
                x: l(f.x, n ? (b ? -6 : 6) : 0),
            };
            this.textAlign =
                f.textAlign || (n ? (b ? "right" : "left") : "center");
        }
        var A = a.Axis,
            F = a.Chart,
            H = a.correctFloat,
            p = a.defined,
            d = a.destroyObjectProperties,
            g = a.each,
            v = a.format,
            l = a.pick;
        a = a.Series;
        E.prototype = {
            destroy: function () {
                d(this, this.axis);
            },
            render: function (a) {
                var f = this.options,
                    b = f.format,
                    b = b ? v(b, this) : f.formatter.call(this);
                this.label
                    ? this.label.attr({ text: b, visibility: "hidden" })
                    : (this.label = this.axis.chart.renderer
                          .text(b, null, null, f.useHTML)
                          .css(f.style)
                          .attr({
                              align: this.textAlign,
                              rotation: f.rotation,
                              visibility: "hidden",
                          })
                          .add(a));
            },
            setOffset: function (a, f) {
                var b = this.axis,
                    d = b.chart,
                    g = d.inverted,
                    l = b.reversed,
                    l = (this.isNegative && !l) || (!this.isNegative && l),
                    k = b.translate(
                        b.usePercentage ? 100 : this.total,
                        0,
                        0,
                        0,
                        1
                    ),
                    b = b.translate(0),
                    b = Math.abs(k - b);
                a = d.xAxis[0].translate(this.x) + a;
                var e = d.plotHeight,
                    g = {
                        x: g ? (l ? k : k - b) : a,
                        y: g ? e - a - f : l ? e - k - b : e - k,
                        width: g ? b : f,
                        height: g ? f : b,
                    };
                if ((f = this.label))
                    f.align(this.alignOptions, null, g),
                        (g = f.alignAttr),
                        f[
                            !1 === this.options.crop || d.isInsidePlot(g.x, g.y)
                                ? "show"
                                : "hide"
                        ](!0);
            },
        };
        F.prototype.getStacks = function () {
            var a = this;
            g(a.yAxis, function (a) {
                a.stacks && a.hasVisibleSeries && (a.oldStacks = a.stacks);
            });
            g(a.series, function (f) {
                !f.options.stacking ||
                    (!0 !== f.visible &&
                        !1 !== a.options.chart.ignoreHiddenSeries) ||
                    (f.stackKey = f.type + l(f.options.stack, ""));
            });
        };
        A.prototype.buildStacks = function () {
            var a = this.series,
                f,
                b = l(this.options.reversedStacks, !0),
                d = a.length,
                g;
            if (!this.isXAxis) {
                this.usePercentage = !1;
                for (g = d; g--; ) a[b ? g : d - g - 1].setStackedPoints();
                for (g = d; g--; )
                    (f = a[b ? g : d - g - 1]),
                        f.setStackCliffs && f.setStackCliffs();
                if (this.usePercentage)
                    for (g = 0; g < d; g++) a[g].setPercentStacks();
            }
        };
        A.prototype.renderStackTotals = function () {
            var a = this.chart,
                f = a.renderer,
                b = this.stacks,
                d,
                g,
                l = this.stackTotalGroup;
            l ||
                (this.stackTotalGroup = l =
                    f
                        .g("stack-labels")
                        .attr({ visibility: "visible", zIndex: 6 })
                        .add());
            l.translate(a.plotLeft, a.plotTop);
            for (d in b) for (g in ((a = b[d]), a)) a[g].render(l);
        };
        A.prototype.resetStacks = function () {
            var a = this.stacks,
                f,
                b;
            if (!this.isXAxis)
                for (f in a)
                    for (b in a[f])
                        a[f][b].touched < this.stacksTouched
                            ? (a[f][b].destroy(), delete a[f][b])
                            : ((a[f][b].total = null), (a[f][b].cum = null));
        };
        A.prototype.cleanStacks = function () {
            var a, f, b;
            if (!this.isXAxis)
                for (f in (this.oldStacks && (a = this.stacks = this.oldStacks),
                a))
                    for (b in a[f]) a[f][b].cum = a[f][b].total;
        };
        a.prototype.setStackedPoints = function () {
            if (
                this.options.stacking &&
                (!0 === this.visible ||
                    !1 === this.chart.options.chart.ignoreHiddenSeries)
            ) {
                var a = this.processedXData,
                    f = this.processedYData,
                    b = [],
                    d = f.length,
                    g = this.options,
                    t = g.threshold,
                    k = g.startFromThreshold ? t : 0,
                    e = g.stack,
                    g = g.stacking,
                    h = this.stackKey,
                    v = "-" + h,
                    u = this.negStacks,
                    c = this.yAxis,
                    q = c.stacks,
                    x = c.oldStacks,
                    K,
                    I,
                    J,
                    D,
                    G,
                    A,
                    F;
                c.stacksTouched += 1;
                for (G = 0; G < d; G++)
                    (A = a[G]),
                        (F = f[G]),
                        (K = this.getStackIndicator(K, A, this.index)),
                        (D = K.key),
                        (J = (I = u && F < (k ? 0 : t)) ? v : h),
                        q[J] || (q[J] = {}),
                        q[J][A] ||
                            (x[J] && x[J][A]
                                ? ((q[J][A] = x[J][A]), (q[J][A].total = null))
                                : (q[J][A] = new E(
                                      c,
                                      c.options.stackLabels,
                                      I,
                                      A,
                                      e
                                  ))),
                        (J = q[J][A]),
                        null !== F &&
                            ((J.points[D] = J.points[this.index] =
                                [l(J.cum, k)]),
                            p(J.cum) || (J.base = D),
                            (J.touched = c.stacksTouched),
                            0 < K.index &&
                                !1 === this.singleStacks &&
                                (J.points[D][0] =
                                    J.points[this.index + "," + A + ",0"][0])),
                        "percent" === g
                            ? ((I = I ? h : v),
                              u && q[I] && q[I][A]
                                  ? ((I = q[I][A]),
                                    (J.total = I.total =
                                        Math.max(I.total, J.total) +
                                            Math.abs(F) || 0))
                                  : (J.total = H(J.total + (Math.abs(F) || 0))))
                            : (J.total = H(J.total + (F || 0))),
                        (J.cum = l(J.cum, k) + (F || 0)),
                        null !== F && (J.points[D].push(J.cum), (b[G] = J.cum));
                "percent" === g && (c.usePercentage = !0);
                this.stackedYData = b;
                c.oldStacks = {};
            }
        };
        a.prototype.setPercentStacks = function () {
            var a = this,
                f = a.stackKey,
                b = a.yAxis.stacks,
                d = a.processedXData,
                l;
            g([f, "-" + f], function (f) {
                for (var g = d.length, e, h; g--; )
                    if (
                        ((e = d[g]),
                        (l = a.getStackIndicator(l, e, a.index, f)),
                        (e = (h = b[f] && b[f][e]) && h.points[l.key]))
                    )
                        (h = h.total ? 100 / h.total : 0),
                            (e[0] = H(e[0] * h)),
                            (e[1] = H(e[1] * h)),
                            (a.stackedYData[g] = e[1]);
            });
        };
        a.prototype.getStackIndicator = function (a, f, b, d) {
            !p(a) || a.x !== f || (d && a.key !== d)
                ? (a = { x: f, index: 0, key: d })
                : a.index++;
            a.key = [b, f, a.index].join();
            return a;
        };
    })(M);
    (function (a) {
        var E = a.addEvent,
            A = a.animate,
            F = a.Axis,
            H = a.createElement,
            p = a.css,
            d = a.defined,
            g = a.each,
            v = a.erase,
            l = a.extend,
            r = a.fireEvent,
            f = a.inArray,
            b = a.isNumber,
            n = a.isObject,
            w = a.merge,
            t = a.pick,
            k = a.Point,
            e = a.Series,
            h = a.seriesTypes,
            C = a.setAnimation,
            u = a.splat;
        l(a.Chart.prototype, {
            addSeries: function (a, b, e) {
                var c,
                    f = this;
                a &&
                    ((b = t(b, !0)),
                    r(f, "addSeries", { options: a }, function () {
                        c = f.initSeries(a);
                        f.isDirtyLegend = !0;
                        f.linkSeries();
                        b && f.redraw(e);
                    }));
                return c;
            },
            addAxis: function (a, b, e, f) {
                var c = b ? "xAxis" : "yAxis",
                    d = this.options;
                a = w(a, { index: this[c].length, isX: b });
                new F(this, a);
                d[c] = u(d[c] || {});
                d[c].push(a);
                t(e, !0) && this.redraw(f);
            },
            showLoading: function (a) {
                var b = this,
                    c = b.options,
                    e = b.loadingDiv,
                    f = c.loading,
                    d = function () {
                        e &&
                            p(e, {
                                left: b.plotLeft + "px",
                                top: b.plotTop + "px",
                                width: b.plotWidth + "px",
                                height: b.plotHeight + "px",
                            });
                    };
                e ||
                    ((b.loadingDiv = e =
                        H(
                            "div",
                            {
                                className:
                                    "highcharts-loading highcharts-loading-hidden",
                            },
                            null,
                            b.container
                        )),
                    (b.loadingSpan = H(
                        "span",
                        { className: "highcharts-loading-inner" },
                        null,
                        e
                    )),
                    E(b, "redraw", d));
                e.className = "highcharts-loading";
                b.loadingSpan.innerHTML = a || c.lang.loading;
                p(e, l(f.style, { zIndex: 10 }));
                p(b.loadingSpan, f.labelStyle);
                b.loadingShown ||
                    (p(e, { opacity: 0, display: "" }),
                    A(
                        e,
                        { opacity: f.style.opacity || 0.5 },
                        { duration: f.showDuration || 0 }
                    ));
                b.loadingShown = !0;
                d();
            },
            hideLoading: function () {
                var a = this.options,
                    b = this.loadingDiv;
                b &&
                    ((b.className =
                        "highcharts-loading highcharts-loading-hidden"),
                    A(
                        b,
                        { opacity: 0 },
                        {
                            duration: a.loading.hideDuration || 100,
                            complete: function () {
                                p(b, { display: "none" });
                            },
                        }
                    ));
                this.loadingShown = !1;
            },
            propsRequireDirtyBox:
                "backgroundColor borderColor borderWidth margin marginTop marginRight marginBottom marginLeft spacing spacingTop spacingRight spacingBottom spacingLeft borderRadius plotBackgroundColor plotBackgroundImage plotBorderColor plotBorderWidth plotShadow shadow".split(
                    " "
                ),
            propsRequireUpdateSeries:
                "chart.inverted chart.polar chart.ignoreHiddenSeries chart.type colors plotOptions".split(
                    " "
                ),
            update: function (a, e) {
                var c,
                    h = {
                        credits: "addCredits",
                        title: "setTitle",
                        subtitle: "setSubtitle",
                    },
                    k = a.chart,
                    n,
                    q;
                if (k) {
                    w(!0, this.options.chart, k);
                    "className" in k && this.setClassName(k.className);
                    if ("inverted" in k || "polar" in k)
                        this.propFromSeries(), (n = !0);
                    for (c in k)
                        k.hasOwnProperty(c) &&
                            (-1 !==
                                f(
                                    "chart." + c,
                                    this.propsRequireUpdateSeries
                                ) && (q = !0),
                            -1 !== f(c, this.propsRequireDirtyBox) &&
                                (this.isDirtyBox = !0));
                    "style" in k && this.renderer.setStyle(k.style);
                }
                for (c in a) {
                    if (this[c] && "function" === typeof this[c].update)
                        this[c].update(a[c], !1);
                    else if ("function" === typeof this[h[c]]) this[h[c]](a[c]);
                    "chart" !== c &&
                        -1 !== f(c, this.propsRequireUpdateSeries) &&
                        (q = !0);
                }
                a.colors && (this.options.colors = a.colors);
                a.plotOptions && w(!0, this.options.plotOptions, a.plotOptions);
                g(
                    ["xAxis", "yAxis", "series"],
                    function (b) {
                        a[b] &&
                            g(
                                u(a[b]),
                                function (a) {
                                    var c =
                                        (d(a.id) && this.get(a.id)) ||
                                        this[b][0];
                                    c && c.coll === b && c.update(a, !1);
                                },
                                this
                            );
                    },
                    this
                );
                n &&
                    g(this.axes, function (a) {
                        a.update({}, !1);
                    });
                q &&
                    g(this.series, function (a) {
                        a.update({}, !1);
                    });
                a.loading && w(!0, this.options.loading, a.loading);
                c = k && k.width;
                k = k && k.height;
                (b(c) && c !== this.chartWidth) ||
                (b(k) && k !== this.chartHeight)
                    ? this.setSize(c, k)
                    : t(e, !0) && this.redraw();
            },
            setSubtitle: function (a) {
                this.setTitle(void 0, a);
            },
        });
        l(k.prototype, {
            update: function (a, b, e, f) {
                function c() {
                    d.applyOptions(a);
                    null === d.y && g && (d.graphic = g.destroy());
                    n(a, !0) &&
                        (g &&
                            g.element &&
                            a &&
                            a.marker &&
                            a.marker.symbol &&
                            (d.graphic = g.destroy()),
                        a &&
                            a.dataLabels &&
                            d.dataLabel &&
                            (d.dataLabel = d.dataLabel.destroy()));
                    k = d.index;
                    h.updateParallelArrays(d, k);
                    m.data[k] = n(m.data[k], !0) ? d.options : a;
                    h.isDirty = h.isDirtyData = !0;
                    !h.fixedBox && h.hasCartesianSeries && (q.isDirtyBox = !0);
                    "point" === m.legendType && (q.isDirtyLegend = !0);
                    b && q.redraw(e);
                }
                var d = this,
                    h = d.series,
                    g = d.graphic,
                    k,
                    q = h.chart,
                    m = h.options;
                b = t(b, !0);
                !1 === f ? c() : d.firePointEvent("update", { options: a }, c);
            },
            remove: function (a, b) {
                this.series.removePoint(f(this, this.series.data), a, b);
            },
        });
        l(e.prototype, {
            addPoint: function (a, b, e, f) {
                var c = this.options,
                    d = this.data,
                    h = this.chart,
                    g = this.xAxis && this.xAxis.names,
                    k = c.data,
                    n,
                    m,
                    q = this.xData,
                    l,
                    u;
                b = t(b, !0);
                n = { series: this };
                this.pointClass.prototype.applyOptions.apply(n, [a]);
                u = n.x;
                l = q.length;
                if (this.requireSorting && u < q[l - 1])
                    for (m = !0; l && q[l - 1] > u; ) l--;
                this.updateParallelArrays(n, "splice", l, 0, 0);
                this.updateParallelArrays(n, l);
                g && n.name && (g[u] = n.name);
                k.splice(l, 0, a);
                m && (this.data.splice(l, 0, null), this.processData());
                "point" === c.legendType && this.generatePoints();
                e &&
                    (d[0] && d[0].remove
                        ? d[0].remove(!1)
                        : (d.shift(),
                          this.updateParallelArrays(n, "shift"),
                          k.shift()));
                this.isDirtyData = this.isDirty = !0;
                b && h.redraw(f);
            },
            removePoint: function (a, b, e) {
                var c = this,
                    f = c.data,
                    d = f[a],
                    h = c.points,
                    g = c.chart,
                    k = function () {
                        h && h.length === f.length && h.splice(a, 1);
                        f.splice(a, 1);
                        c.options.data.splice(a, 1);
                        c.updateParallelArrays(
                            d || { series: c },
                            "splice",
                            a,
                            1
                        );
                        d && d.destroy();
                        c.isDirty = !0;
                        c.isDirtyData = !0;
                        b && g.redraw();
                    };
                C(e, g);
                b = t(b, !0);
                d ? d.firePointEvent("remove", null, k) : k();
            },
            remove: function (a, b, e) {
                function c() {
                    f.destroy();
                    d.isDirtyLegend = d.isDirtyBox = !0;
                    d.linkSeries();
                    t(a, !0) && d.redraw(b);
                }
                var f = this,
                    d = f.chart;
                !1 !== e ? r(f, "remove", null, c) : c();
            },
            update: function (a, b) {
                var c = this,
                    e = this.chart,
                    f = this.userOptions,
                    d = this.type,
                    k = a.type || f.type || e.options.chart.type,
                    n = h[d].prototype,
                    q = ["group", "markerGroup", "dataLabelsGroup"],
                    u;
                if ((k && k !== d) || void 0 !== a.zIndex) q.length = 0;
                g(q, function (a) {
                    q[a] = c[a];
                    delete c[a];
                });
                a = w(
                    f,
                    {
                        animation: !1,
                        index: this.index,
                        pointStart: this.xData[0],
                    },
                    { data: this.options.data },
                    a
                );
                this.remove(!1, null, !1);
                for (u in n) this[u] = void 0;
                l(this, h[k || d].prototype);
                g(q, function (a) {
                    c[a] = q[a];
                });
                this.init(e, a);
                e.linkSeries();
                t(b, !0) && e.redraw(!1);
            },
        });
        l(F.prototype, {
            update: function (a, b) {
                var c = this.chart;
                a = c.options[this.coll][this.options.index] = w(
                    this.userOptions,
                    a
                );
                this.destroy(!0);
                this.init(c, l(a, { events: void 0 }));
                c.isDirtyBox = !0;
                t(b, !0) && c.redraw();
            },
            remove: function (a) {
                for (
                    var b = this.chart,
                        c = this.coll,
                        e = this.series,
                        f = e.length;
                    f--;

                )
                    e[f] && e[f].remove(!1);
                v(b.axes, this);
                v(b[c], this);
                b.options[c].splice(this.options.index, 1);
                g(b[c], function (a, b) {
                    a.options.index = b;
                });
                this.destroy();
                b.isDirtyBox = !0;
                t(a, !0) && b.redraw();
            },
            setTitle: function (a, b) {
                this.update({ title: a }, b);
            },
            setCategories: function (a, b) {
                this.update({ categories: a }, b);
            },
        });
    })(M);
    (function (a) {
        var E = a.color,
            A = a.each,
            F = a.map,
            H = a.pick,
            p = a.Series,
            d = a.seriesType;
        d(
            "area",
            "line",
            { softThreshold: !1, threshold: 0 },
            {
                singleStacks: !1,
                getStackPoints: function () {
                    var a = [],
                        d = [],
                        l = this.xAxis,
                        p = this.yAxis,
                        f = p.stacks[this.stackKey],
                        b = {},
                        n = this.points,
                        w = this.index,
                        t = p.series,
                        k = t.length,
                        e,
                        h = H(p.options.reversedStacks, !0) ? 1 : -1,
                        C,
                        u;
                    if (this.options.stacking) {
                        for (C = 0; C < n.length; C++) b[n[C].x] = n[C];
                        for (u in f) null !== f[u].total && d.push(u);
                        d.sort(function (a, b) {
                            return a - b;
                        });
                        e = F(t, function () {
                            return this.visible;
                        });
                        A(d, function (c, g) {
                            var n = 0,
                                q,
                                u;
                            if (b[c] && !b[c].isNull)
                                a.push(b[c]),
                                    A([-1, 1], function (a) {
                                        var n =
                                                1 === a
                                                    ? "rightNull"
                                                    : "leftNull",
                                            l = 0,
                                            t = f[d[g + a]];
                                        if (t)
                                            for (C = w; 0 <= C && C < k; )
                                                (q = t.points[C]),
                                                    q ||
                                                        (C === w
                                                            ? (b[c][n] = !0)
                                                            : e[C] &&
                                                              (u =
                                                                  f[c].points[
                                                                      C
                                                                  ]) &&
                                                              (l -=
                                                                  u[1] - u[0])),
                                                    (C += h);
                                        b[c][
                                            1 === a ? "rightCliff" : "leftCliff"
                                        ] = l;
                                    });
                            else {
                                for (C = w; 0 <= C && C < k; ) {
                                    if ((q = f[c].points[C])) {
                                        n = q[1];
                                        break;
                                    }
                                    C += h;
                                }
                                n = p.toPixels(n, !0);
                                a.push({
                                    isNull: !0,
                                    plotX: l.toPixels(c, !0),
                                    plotY: n,
                                    yBottom: n,
                                });
                            }
                        });
                    }
                    return a;
                },
                getGraphPath: function (a) {
                    var d = p.prototype.getGraphPath,
                        g = this.options,
                        r = g.stacking,
                        f = this.yAxis,
                        b,
                        n,
                        w = [],
                        t = [],
                        k = this.index,
                        e,
                        h = f.stacks[this.stackKey],
                        C = g.threshold,
                        u = f.getThreshold(g.threshold),
                        c,
                        g = g.connectNulls || "percent" === r,
                        q = function (b, c, d) {
                            var g = a[b];
                            b = r && h[g.x].points[k];
                            var n = g[d + "Null"] || 0;
                            d = g[d + "Cliff"] || 0;
                            var q,
                                l,
                                g = !0;
                            d || n
                                ? ((q = (n ? b[0] : b[1]) + d),
                                  (l = b[0] + d),
                                  (g = !!n))
                                : !r && a[c] && a[c].isNull && (q = l = C);
                            void 0 !== q &&
                                (t.push({
                                    plotX: e,
                                    plotY: null === q ? u : f.getThreshold(q),
                                    isNull: g,
                                }),
                                w.push({
                                    plotX: e,
                                    plotY: null === l ? u : f.getThreshold(l),
                                    doCurve: !1,
                                }));
                        };
                    a = a || this.points;
                    r && (a = this.getStackPoints());
                    for (b = 0; b < a.length; b++)
                        if (
                            ((n = a[b].isNull),
                            (e = H(a[b].rectPlotX, a[b].plotX)),
                            (c = H(a[b].yBottom, u)),
                            !n || g)
                        )
                            g || q(b, b - 1, "left"),
                                (n && !r && g) ||
                                    (t.push(a[b]),
                                    w.push({ x: b, plotX: e, plotY: c })),
                                g || q(b, b + 1, "right");
                    b = d.call(this, t, !0, !0);
                    w.reversed = !0;
                    n = d.call(this, w, !0, !0);
                    n.length && (n[0] = "L");
                    n = b.concat(n);
                    d = d.call(this, t, !1, g);
                    n.xMap = b.xMap;
                    this.areaPath = n;
                    return d;
                },
                drawGraph: function () {
                    this.areaPath = [];
                    p.prototype.drawGraph.apply(this);
                    var a = this,
                        d = this.areaPath,
                        l = this.options,
                        r = [
                            [
                                "area",
                                "highcharts-area",
                                this.color,
                                l.fillColor,
                            ],
                        ];
                    A(this.zones, function (d, b) {
                        r.push([
                            "zone-area-" + b,
                            "highcharts-area highcharts-zone-area-" +
                                b +
                                " " +
                                d.className,
                            d.color || a.color,
                            d.fillColor || l.fillColor,
                        ]);
                    });
                    A(r, function (f) {
                        var b = f[0],
                            g = a[b];
                        g
                            ? ((g.endX = d.xMap), g.animate({ d: d }))
                            : ((g = a[b] =
                                  a.chart.renderer
                                      .path(d)
                                      .addClass(f[1])
                                      .attr({
                                          fill: H(
                                              f[3],
                                              E(f[2])
                                                  .setOpacity(
                                                      H(l.fillOpacity, 0.75)
                                                  )
                                                  .get()
                                          ),
                                          zIndex: 0,
                                      })
                                      .add(a.group)),
                              (g.isArea = !0));
                        g.startX = d.xMap;
                        g.shiftUnit = l.step ? 2 : 1;
                    });
                },
                drawLegendSymbol: a.LegendSymbolMixin.drawRectangle,
            }
        );
    })(M);
    (function (a) {
        var E = a.pick;
        a = a.seriesType;
        a(
            "spline",
            "line",
            {},
            {
                getPointSpline: function (a, F, H) {
                    var p = F.plotX,
                        d = F.plotY,
                        g = a[H - 1];
                    H = a[H + 1];
                    var v, l, r, f;
                    if (
                        g &&
                        !g.isNull &&
                        !1 !== g.doCurve &&
                        H &&
                        !H.isNull &&
                        !1 !== H.doCurve
                    ) {
                        a = g.plotY;
                        r = H.plotX;
                        H = H.plotY;
                        var b = 0;
                        v = (1.5 * p + g.plotX) / 2.5;
                        l = (1.5 * d + a) / 2.5;
                        r = (1.5 * p + r) / 2.5;
                        f = (1.5 * d + H) / 2.5;
                        r !== v && (b = ((f - l) * (r - p)) / (r - v) + d - f);
                        l += b;
                        f += b;
                        l > a && l > d
                            ? ((l = Math.max(a, d)), (f = 2 * d - l))
                            : l < a &&
                              l < d &&
                              ((l = Math.min(a, d)), (f = 2 * d - l));
                        f > H && f > d
                            ? ((f = Math.max(H, d)), (l = 2 * d - f))
                            : f < H &&
                              f < d &&
                              ((f = Math.min(H, d)), (l = 2 * d - f));
                        F.rightContX = r;
                        F.rightContY = f;
                    }
                    F = [
                        "C",
                        E(g.rightContX, g.plotX),
                        E(g.rightContY, g.plotY),
                        E(v, p),
                        E(l, d),
                        p,
                        d,
                    ];
                    g.rightContX = g.rightContY = null;
                    return F;
                },
            }
        );
    })(M);
    (function (a) {
        var E = a.seriesTypes.area.prototype,
            A = a.seriesType;
        A("areaspline", "spline", a.defaultPlotOptions.area, {
            getStackPoints: E.getStackPoints,
            getGraphPath: E.getGraphPath,
            setStackCliffs: E.setStackCliffs,
            drawGraph: E.drawGraph,
            drawLegendSymbol: a.LegendSymbolMixin.drawRectangle,
        });
    })(M);
    (function (a) {
        var E = a.animObject,
            A = a.color,
            F = a.each,
            H = a.extend,
            p = a.isNumber,
            d = a.merge,
            g = a.pick,
            v = a.Series,
            l = a.seriesType,
            r = a.svg;
        l(
            "column",
            "line",
            {
                borderRadius: 0,
                groupPadding: 0.2,
                marker: null,
                pointPadding: 0.1,
                minPointLength: 0,
                cropThreshold: 50,
                pointRange: null,
                states: {
                    hover: { halo: !1, brightness: 0.1, shadow: !1 },
                    select: {
                        color: "#cccccc",
                        borderColor: "#000000",
                        shadow: !1,
                    },
                },
                dataLabels: { align: null, verticalAlign: null, y: null },
                softThreshold: !1,
                startFromThreshold: !0,
                stickyTracking: !1,
                tooltip: { distance: 6 },
                threshold: 0,
                borderColor: "#ffffff",
            },
            {
                cropShoulder: 0,
                directTouch: !0,
                trackerGroups: ["group", "dataLabelsGroup"],
                negStacks: !0,
                init: function () {
                    v.prototype.init.apply(this, arguments);
                    var a = this,
                        b = a.chart;
                    b.hasRendered &&
                        F(b.series, function (b) {
                            b.type === a.type && (b.isDirty = !0);
                        });
                },
                getColumnMetrics: function () {
                    var a = this,
                        b = a.options,
                        d = a.xAxis,
                        l = a.yAxis,
                        t = d.reversed,
                        k,
                        e = {},
                        h = 0;
                    !1 === b.grouping
                        ? (h = 1)
                        : F(a.chart.series, function (b) {
                              var c = b.options,
                                  d = b.yAxis,
                                  f;
                              b.type === a.type &&
                                  b.visible &&
                                  l.len === d.len &&
                                  l.pos === d.pos &&
                                  (c.stacking
                                      ? ((k = b.stackKey),
                                        void 0 === e[k] && (e[k] = h++),
                                        (f = e[k]))
                                      : !1 !== c.grouping && (f = h++),
                                  (b.columnIndex = f));
                          });
                    var p = Math.min(
                            Math.abs(d.transA) *
                                (d.ordinalSlope ||
                                    b.pointRange ||
                                    d.closestPointRange ||
                                    d.tickInterval ||
                                    1),
                            d.len
                        ),
                        u = p * b.groupPadding,
                        c = (p - 2 * u) / h,
                        b = Math.min(
                            b.maxPointWidth || d.len,
                            g(b.pointWidth, c * (1 - 2 * b.pointPadding))
                        );
                    a.columnMetrics = {
                        width: b,
                        offset:
                            (c - b) / 2 +
                            (u +
                                ((a.columnIndex || 0) + (t ? 1 : 0)) * c -
                                p / 2) *
                                (t ? -1 : 1),
                    };
                    return a.columnMetrics;
                },
                crispCol: function (a, b, d, g) {
                    var f = this.chart,
                        k = this.borderWidth,
                        e = -(k % 2 ? 0.5 : 0),
                        k = k % 2 ? 0.5 : 1;
                    f.inverted && f.renderer.isVML && (k += 1);
                    d = Math.round(a + d) + e;
                    a = Math.round(a) + e;
                    g = Math.round(b + g) + k;
                    e = 0.5 >= Math.abs(b) && 0.5 < g;
                    b = Math.round(b) + k;
                    g -= b;
                    e && g && (--b, (g += 1));
                    return { x: a, y: b, width: d - a, height: g };
                },
                translate: function () {
                    var a = this,
                        b = a.chart,
                        d = a.options,
                        l = (a.dense =
                            2 > a.closestPointRange * a.xAxis.transA),
                        l = (a.borderWidth = g(d.borderWidth, l ? 0 : 1)),
                        t = a.yAxis,
                        k = (a.translatedThreshold = t.getThreshold(
                            d.threshold
                        )),
                        e = g(d.minPointLength, 5),
                        h = a.getColumnMetrics(),
                        p = h.width,
                        u = (a.barW = Math.max(p, 1 + 2 * l)),
                        c = (a.pointXOffset = h.offset);
                    b.inverted && (k -= 0.5);
                    d.pointPadding && (u = Math.ceil(u));
                    v.prototype.translate.apply(a);
                    F(a.points, function (d) {
                        var f = g(d.yBottom, k),
                            h = 999 + Math.abs(f),
                            h = Math.min(Math.max(-h, d.plotY), t.len + h),
                            n = d.plotX + c,
                            l = u,
                            q = Math.min(h, f),
                            r,
                            v = Math.max(h, f) - q;
                        Math.abs(v) < e &&
                            e &&
                            ((v = e),
                            (r =
                                (!t.reversed && !d.negative) ||
                                (t.reversed && d.negative)),
                            (q =
                                Math.abs(q - k) > e ? f - e : k - (r ? e : 0)));
                        d.barX = n;
                        d.pointWidth = p;
                        d.tooltipPos = b.inverted
                            ? [
                                  t.len + t.pos - b.plotLeft - h,
                                  a.xAxis.len - n - l / 2,
                                  v,
                              ]
                            : [n + l / 2, h + t.pos - b.plotTop, v];
                        d.shapeType = "rect";
                        d.shapeArgs = a.crispCol.apply(
                            a,
                            d.isNull ? [d.plotX, t.len / 2, 0, 0] : [n, q, l, v]
                        );
                    });
                },
                getSymbol: a.noop,
                drawLegendSymbol: a.LegendSymbolMixin.drawRectangle,
                drawGraph: function () {
                    this.group[this.dense ? "addClass" : "removeClass"](
                        "highcharts-dense-data"
                    );
                },
                pointAttribs: function (a, b) {
                    var d = this.options,
                        f = this.pointAttrToOptions || {},
                        g = f.stroke || "borderColor",
                        k = f["stroke-width"] || "borderWidth",
                        e = (a && a.color) || this.color,
                        h = a[g] || d[g] || this.color || e,
                        f = d.dashStyle,
                        l;
                    a &&
                        this.zones.length &&
                        (e =
                            ((e = a.getZone()) && e.color) ||
                            a.options.color ||
                            this.color);
                    b &&
                        ((b = d.states[b]),
                        (l = b.brightness),
                        (e =
                            b.color ||
                            (void 0 !== l &&
                                A(e).brighten(b.brightness).get()) ||
                            e),
                        (h = b[g] || h),
                        (f = b.dashStyle || f));
                    a = {
                        fill: e,
                        stroke: h,
                        "stroke-width": a[k] || d[k] || this[k] || 0,
                    };
                    d.borderRadius && (a.r = d.borderRadius);
                    f && (a.dashstyle = f);
                    return a;
                },
                drawPoints: function () {
                    var a = this,
                        b = this.chart,
                        g = a.options,
                        l = b.renderer,
                        t = g.animationLimit || 250,
                        k;
                    F(a.points, function (e) {
                        var f = e.graphic;
                        if (p(e.plotY) && null !== e.y) {
                            k = e.shapeArgs;
                            if (f)
                                f[b.pointCount < t ? "animate" : "attr"](d(k));
                            else
                                e.graphic = f = l[e.shapeType](k)
                                    .attr({ class: e.getClassName() })
                                    .add(e.group || a.group);
                            f.attr(
                                a.pointAttribs(e, e.selected && "select")
                            ).shadow(
                                g.shadow,
                                null,
                                g.stacking && !g.borderRadius
                            );
                        } else f && (e.graphic = f.destroy());
                    });
                },
                animate: function (a) {
                    var b = this,
                        d = this.yAxis,
                        f = b.options,
                        g = this.chart.inverted,
                        k = {};
                    r &&
                        (a
                            ? ((k.scaleY = 0.001),
                              (a = Math.min(
                                  d.pos + d.len,
                                  Math.max(d.pos, d.toPixels(f.threshold))
                              )),
                              g
                                  ? (k.translateX = a - d.len)
                                  : (k.translateY = a),
                              b.group.attr(k))
                            : ((k[g ? "translateX" : "translateY"] = d.pos),
                              b.group.animate(
                                  k,
                                  H(E(b.options.animation), {
                                      step: function (a, d) {
                                          b.group.attr({
                                              scaleY: Math.max(0.001, d.pos),
                                          });
                                      },
                                  })
                              ),
                              (b.animate = null)));
                },
                remove: function () {
                    var a = this,
                        b = a.chart;
                    b.hasRendered &&
                        F(b.series, function (b) {
                            b.type === a.type && (b.isDirty = !0);
                        });
                    v.prototype.remove.apply(a, arguments);
                },
            }
        );
    })(M);
    (function (a) {
        a = a.seriesType;
        a("bar", "column", null, { inverted: !0 });
    })(M);
    (function (a) {
        var E = a.Series;
        a = a.seriesType;
        a(
            "scatter",
            "line",
            {
                lineWidth: 0,
                marker: { enabled: !0 },
                tooltip: {
                    headerFormat:
                        '\x3cspan style\x3d"color:{point.color}"\x3e\u25cf\x3c/span\x3e \x3cspan style\x3d"font-size: 0.85em"\x3e {series.name}\x3c/span\x3e\x3cbr/\x3e',
                    pointFormat:
                        "x: \x3cb\x3e{point.x}\x3c/b\x3e\x3cbr/\x3ey: \x3cb\x3e{point.y}\x3c/b\x3e\x3cbr/\x3e",
                },
            },
            {
                sorted: !1,
                requireSorting: !1,
                noSharedTooltip: !0,
                trackerGroups: ["group", "markerGroup", "dataLabelsGroup"],
                takeOrdinalPosition: !1,
                kdDimensions: 2,
                drawGraph: function () {
                    this.options.lineWidth && E.prototype.drawGraph.call(this);
                },
            }
        );
    })(M);
    (function (a) {
        var E = a.pick,
            A = a.relativeLength;
        a.CenteredSeriesMixin = {
            getCenter: function () {
                var a = this.options,
                    H = this.chart,
                    p = 2 * (a.slicedOffset || 0),
                    d = H.plotWidth - 2 * p,
                    H = H.plotHeight - 2 * p,
                    g = a.center,
                    g = [
                        E(g[0], "50%"),
                        E(g[1], "50%"),
                        a.size || "100%",
                        a.innerSize || 0,
                    ],
                    v = Math.min(d, H),
                    l,
                    r;
                for (l = 0; 4 > l; ++l)
                    (r = g[l]),
                        (a = 2 > l || (2 === l && /%$/.test(r))),
                        (g[l] = A(r, [d, H, v, g[2]][l]) + (a ? p : 0));
                g[3] > g[2] && (g[3] = g[2]);
                return g;
            },
        };
    })(M);
    (function (a) {
        var E = a.addEvent,
            A = a.defined,
            F = a.each,
            H = a.extend,
            p = a.inArray,
            d = a.noop,
            g = a.pick,
            v = a.Point,
            l = a.Series,
            r = a.seriesType,
            f = a.setAnimation;
        r(
            "pie",
            "line",
            {
                center: [null, null],
                clip: !1,
                colorByPoint: !0,
                dataLabels: {
                    distance: 30,
                    enabled: !0,
                    formatter: function () {
                        return null === this.y ? void 0 : this.point.name;
                    },
                    x: 0,
                },
                ignoreHiddenPoint: !0,
                legendType: "point",
                marker: null,
                size: null,
                showInLegend: !1,
                slicedOffset: 10,
                stickyTracking: !1,
                tooltip: { followPointer: !0 },
                borderColor: "#ffffff",
                borderWidth: 1,
                states: { hover: { brightness: 0.1, shadow: !1 } },
            },
            {
                isCartesian: !1,
                requireSorting: !1,
                directTouch: !0,
                noSharedTooltip: !0,
                trackerGroups: ["group", "dataLabelsGroup"],
                axisTypes: [],
                pointAttribs: a.seriesTypes.column.prototype.pointAttribs,
                animate: function (a) {
                    var b = this,
                        d = b.points,
                        f = b.startAngleRad;
                    a ||
                        (F(d, function (a) {
                            var e = a.graphic,
                                d = a.shapeArgs;
                            e &&
                                (e.attr({
                                    r: a.startR || b.center[3] / 2,
                                    start: f,
                                    end: f,
                                }),
                                e.animate(
                                    { r: d.r, start: d.start, end: d.end },
                                    b.options.animation
                                ));
                        }),
                        (b.animate = null));
                },
                updateTotals: function () {
                    var a,
                        d = 0,
                        f = this.points,
                        g = f.length,
                        k,
                        e = this.options.ignoreHiddenPoint;
                    for (a = 0; a < g; a++)
                        (k = f[a]),
                            0 > k.y && (k.y = null),
                            (d += e && !k.visible ? 0 : k.y);
                    this.total = d;
                    for (a = 0; a < g; a++)
                        (k = f[a]),
                            (k.percentage =
                                0 < d && (k.visible || !e)
                                    ? (k.y / d) * 100
                                    : 0),
                            (k.total = d);
                },
                generatePoints: function () {
                    l.prototype.generatePoints.call(this);
                    this.updateTotals();
                },
                translate: function (a) {
                    this.generatePoints();
                    var b = 0,
                        d = this.options,
                        f = d.slicedOffset,
                        k = f + (d.borderWidth || 0),
                        e,
                        h,
                        l,
                        u = d.startAngle || 0,
                        c = (this.startAngleRad = (Math.PI / 180) * (u - 90)),
                        u =
                            (this.endAngleRad =
                                (Math.PI / 180) *
                                (g(d.endAngle, u + 360) - 90)) - c,
                        q = this.points,
                        p = d.dataLabels.distance,
                        d = d.ignoreHiddenPoint,
                        r,
                        v = q.length,
                        A;
                    a || (this.center = a = this.getCenter());
                    this.getX = function (b, c) {
                        l = Math.asin(Math.min((b - a[1]) / (a[2] / 2 + p), 1));
                        return (
                            a[0] + (c ? -1 : 1) * Math.cos(l) * (a[2] / 2 + p)
                        );
                    };
                    for (r = 0; r < v; r++) {
                        A = q[r];
                        e = c + b * u;
                        if (!d || A.visible) b += A.percentage / 100;
                        h = c + b * u;
                        A.shapeType = "arc";
                        A.shapeArgs = {
                            x: a[0],
                            y: a[1],
                            r: a[2] / 2,
                            innerR: a[3] / 2,
                            start: Math.round(1e3 * e) / 1e3,
                            end: Math.round(1e3 * h) / 1e3,
                        };
                        l = (h + e) / 2;
                        l > 1.5 * Math.PI
                            ? (l -= 2 * Math.PI)
                            : l < -Math.PI / 2 && (l += 2 * Math.PI);
                        A.slicedTranslation = {
                            translateX: Math.round(Math.cos(l) * f),
                            translateY: Math.round(Math.sin(l) * f),
                        };
                        e = (Math.cos(l) * a[2]) / 2;
                        h = (Math.sin(l) * a[2]) / 2;
                        A.tooltipPos = [a[0] + 0.7 * e, a[1] + 0.7 * h];
                        A.half = l < -Math.PI / 2 || l > Math.PI / 2 ? 1 : 0;
                        A.angle = l;
                        k = Math.min(k, p / 5);
                        A.labelPos = [
                            a[0] + e + Math.cos(l) * p,
                            a[1] + h + Math.sin(l) * p,
                            a[0] + e + Math.cos(l) * k,
                            a[1] + h + Math.sin(l) * k,
                            a[0] + e,
                            a[1] + h,
                            0 > p ? "center" : A.half ? "right" : "left",
                            l,
                        ];
                    }
                },
                drawGraph: null,
                drawPoints: function () {
                    var a = this,
                        d = a.chart.renderer,
                        f,
                        g,
                        k,
                        e,
                        h = a.options.shadow;
                    h &&
                        !a.shadowGroup &&
                        (a.shadowGroup = d.g("shadow").add(a.group));
                    F(a.points, function (b) {
                        if (null !== b.y) {
                            g = b.graphic;
                            e = b.shapeArgs;
                            f = b.sliced ? b.slicedTranslation : {};
                            var l = b.shadowGroup;
                            h &&
                                !l &&
                                (l = b.shadowGroup =
                                    d.g("shadow").add(a.shadowGroup));
                            l && l.attr(f);
                            k = a.pointAttribs(b, b.selected && "select");
                            g
                                ? g
                                      .setRadialReference(a.center)
                                      .attr(k)
                                      .animate(H(e, f))
                                : ((b.graphic = g =
                                      d[b.shapeType](e)
                                          .addClass(b.getClassName())
                                          .setRadialReference(a.center)
                                          .attr(f)
                                          .add(a.group)),
                                  b.visible || g.attr({ visibility: "hidden" }),
                                  g
                                      .attr(k)
                                      .attr({ "stroke-linejoin": "round" })
                                      .shadow(h, l));
                        }
                    });
                },
                searchPoint: d,
                sortByAngle: function (a, d) {
                    a.sort(function (a, b) {
                        return void 0 !== a.angle && (b.angle - a.angle) * d;
                    });
                },
                drawLegendSymbol: a.LegendSymbolMixin.drawRectangle,
                getCenter: a.CenteredSeriesMixin.getCenter,
                getSymbol: d,
            },
            {
                init: function () {
                    v.prototype.init.apply(this, arguments);
                    var a = this,
                        d;
                    a.name = g(a.name, "Slice");
                    d = function (b) {
                        a.slice("select" === b.type);
                    };
                    E(a, "select", d);
                    E(a, "unselect", d);
                    return a;
                },
                setVisible: function (a, d) {
                    var b = this,
                        f = b.series,
                        k = f.chart,
                        e = f.options.ignoreHiddenPoint;
                    d = g(d, e);
                    a !== b.visible &&
                        ((b.visible =
                            b.options.visible =
                            a =
                                void 0 === a ? !b.visible : a),
                        (f.options.data[p(b, f.data)] = b.options),
                        F(
                            [
                                "graphic",
                                "dataLabel",
                                "connector",
                                "shadowGroup",
                            ],
                            function (e) {
                                if (b[e]) b[e][a ? "show" : "hide"](!0);
                            }
                        ),
                        b.legendItem && k.legend.colorizeItem(b, a),
                        a || "hover" !== b.state || b.setState(""),
                        e && (f.isDirty = !0),
                        d && k.redraw());
                },
                slice: function (a, d, l) {
                    var b = this.series;
                    f(l, b.chart);
                    g(d, !0);
                    this.sliced =
                        this.options.sliced =
                        a =
                            A(a) ? a : !this.sliced;
                    b.options.data[p(this, b.data)] = this.options;
                    a = a
                        ? this.slicedTranslation
                        : { translateX: 0, translateY: 0 };
                    this.graphic.animate(a);
                    this.shadowGroup && this.shadowGroup.animate(a);
                },
                haloPath: function (a) {
                    var b = this.shapeArgs;
                    return this.sliced || !this.visible
                        ? []
                        : this.series.chart.renderer.symbols.arc(
                              b.x,
                              b.y,
                              b.r + a,
                              b.r + a,
                              {
                                  innerR: this.shapeArgs.r,
                                  start: b.start,
                                  end: b.end,
                              }
                          );
                },
            }
        );
    })(M);
    (function (a) {
        var E = a.addEvent,
            A = a.arrayMax,
            F = a.defined,
            H = a.each,
            p = a.extend,
            d = a.format,
            g = a.map,
            v = a.merge,
            l = a.noop,
            r = a.pick,
            f = a.relativeLength,
            b = a.Series,
            n = a.seriesTypes,
            w = a.stableSort;
        a.distribute = function (a, b) {
            function e(a, b) {
                return a.target - b.target;
            }
            var d,
                f = !0,
                k = a,
                c = [],
                l;
            l = 0;
            for (d = a.length; d--; ) l += a[d].size;
            if (l > b) {
                w(a, function (a, b) {
                    return (b.rank || 0) - (a.rank || 0);
                });
                for (l = d = 0; l <= b; ) (l += a[d].size), d++;
                c = a.splice(d - 1, a.length);
            }
            w(a, e);
            for (
                a = g(a, function (a) {
                    return { size: a.size, targets: [a.target] };
                });
                f;

            ) {
                for (d = a.length; d--; )
                    (f = a[d]),
                        (l =
                            (Math.min.apply(0, f.targets) +
                                Math.max.apply(0, f.targets)) /
                            2),
                        (f.pos = Math.min(
                            Math.max(0, l - f.size / 2),
                            b - f.size
                        ));
                d = a.length;
                for (f = !1; d--; )
                    0 < d &&
                        a[d - 1].pos + a[d - 1].size > a[d].pos &&
                        ((a[d - 1].size += a[d].size),
                        (a[d - 1].targets = a[d - 1].targets.concat(
                            a[d].targets
                        )),
                        a[d - 1].pos + a[d - 1].size > b &&
                            (a[d - 1].pos = b - a[d - 1].size),
                        a.splice(d, 1),
                        (f = !0));
            }
            d = 0;
            H(a, function (a) {
                var b = 0;
                H(a.targets, function () {
                    k[d].pos = a.pos + b;
                    b += k[d].size;
                    d++;
                });
            });
            k.push.apply(k, c);
            w(k, e);
        };
        b.prototype.drawDataLabels = function () {
            var a = this,
                b = a.options,
                e = b.dataLabels,
                f = a.points,
                g,
                l,
                c = a.hasRendered || 0,
                q,
                n,
                w = r(e.defer, !0),
                I = a.chart.renderer;
            if (e.enabled || a._hasPointLabels)
                a.dlProcessOptions && a.dlProcessOptions(e),
                    (n = a.plotGroup(
                        "dataLabelsGroup",
                        "data-labels",
                        w && !c ? "hidden" : "visible",
                        e.zIndex || 6
                    )),
                    w &&
                        (n.attr({ opacity: +c }),
                        c ||
                            E(a, "afterAnimate", function () {
                                a.visible && n.show(!0);
                                n[b.animation ? "animate" : "attr"](
                                    { opacity: 1 },
                                    { duration: 200 }
                                );
                            })),
                    (l = e),
                    H(f, function (c) {
                        var f,
                            h = c.dataLabel,
                            k,
                            u,
                            m = c.connector,
                            t = !0,
                            x,
                            w = {};
                        g = c.dlOptions || (c.options && c.options.dataLabels);
                        f = r(g && g.enabled, l.enabled) && null !== c.y;
                        if (h && !f) c.dataLabel = h.destroy();
                        else if (f) {
                            e = v(l, g);
                            x = e.style;
                            f = e.rotation;
                            k = c.getLabelConfig();
                            q = e.format
                                ? d(e.format, k)
                                : e.formatter.call(k, e);
                            x.color = r(e.color, x.color, a.color, "#000000");
                            if (h)
                                F(q)
                                    ? (h.attr({ text: q }), (t = !1))
                                    : ((c.dataLabel = h = h.destroy()),
                                      m && (c.connector = m.destroy()));
                            else if (F(q)) {
                                h = {
                                    fill: e.backgroundColor,
                                    stroke: e.borderColor,
                                    "stroke-width": e.borderWidth,
                                    r: e.borderRadius || 0,
                                    rotation: f,
                                    padding: e.padding,
                                    zIndex: 1,
                                };
                                "contrast" === x.color &&
                                    (w.color =
                                        e.inside || 0 > e.distance || b.stacking
                                            ? I.getContrast(c.color || a.color)
                                            : "#000000");
                                b.cursor && (w.cursor = b.cursor);
                                for (u in h) void 0 === h[u] && delete h[u];
                                h = c.dataLabel = I[f ? "text" : "label"](
                                    q,
                                    0,
                                    -9999,
                                    e.shape,
                                    null,
                                    null,
                                    e.useHTML,
                                    null,
                                    "data-label"
                                ).attr(h);
                                h.addClass(
                                    "highcharts-data-label-color-" +
                                        c.colorIndex +
                                        " " +
                                        (e.className || "") +
                                        (e.useHTML ? "highcharts-tracker" : "")
                                );
                                h.css(p(x, w));
                                h.add(n);
                                h.shadow(e.shadow);
                            }
                            h && a.alignDataLabel(c, h, e, null, t);
                        }
                    });
        };
        b.prototype.alignDataLabel = function (a, b, e, d, f) {
            var g = this.chart,
                c = g.inverted,
                h = r(a.plotX, -9999),
                k = r(a.plotY, -9999),
                l = b.getBBox(),
                n,
                t = e.rotation,
                v = e.align,
                w =
                    this.visible &&
                    (a.series.forceDL ||
                        g.isInsidePlot(h, Math.round(k), c) ||
                        (d &&
                            g.isInsidePlot(
                                h,
                                c ? d.x + 1 : d.y + d.height - 1,
                                c
                            ))),
                A = "justify" === r(e.overflow, "justify");
            w &&
                ((n = e.style.fontSize),
                (n = g.renderer.fontMetrics(n, b).b),
                (d = p(
                    {
                        x: c ? g.plotWidth - k : h,
                        y: Math.round(c ? g.plotHeight - h : k),
                        width: 0,
                        height: 0,
                    },
                    d
                )),
                p(e, { width: l.width, height: l.height }),
                t
                    ? ((A = !1),
                      (c = g.renderer.rotCorr(n, t)),
                      (c = {
                          x: d.x + e.x + d.width / 2 + c.x,
                          y:
                              d.y +
                              e.y +
                              { top: 0, middle: 0.5, bottom: 1 }[
                                  e.verticalAlign
                              ] *
                                  d.height,
                      }),
                      b[f ? "attr" : "animate"](c).attr({ align: v }),
                      (h = (t + 720) % 360),
                      (h = 180 < h && 360 > h),
                      "left" === v
                          ? (c.y -= h ? l.height : 0)
                          : "center" === v
                          ? ((c.x -= l.width / 2), (c.y -= l.height / 2))
                          : "right" === v &&
                            ((c.x -= l.width), (c.y -= h ? 0 : l.height)))
                    : (b.align(e, null, d), (c = b.alignAttr)),
                A
                    ? this.justifyDataLabel(b, e, c, l, d, f)
                    : r(e.crop, !0) &&
                      (w =
                          g.isInsidePlot(c.x, c.y) &&
                          g.isInsidePlot(c.x + l.width, c.y + l.height)),
                e.shape &&
                    !t &&
                    b.attr({ anchorX: a.plotX, anchorY: a.plotY }));
            w || (b.attr({ y: -9999 }), (b.placed = !1));
        };
        b.prototype.justifyDataLabel = function (a, b, e, d, f, g) {
            var c = this.chart,
                h = b.align,
                k = b.verticalAlign,
                l,
                n,
                u = a.box ? 0 : a.padding || 0;
            l = e.x + u;
            0 > l &&
                ("right" === h ? (b.align = "left") : (b.x = -l), (n = !0));
            l = e.x + d.width - u;
            l > c.plotWidth &&
                ("left" === h ? (b.align = "right") : (b.x = c.plotWidth - l),
                (n = !0));
            l = e.y + u;
            0 > l &&
                ("bottom" === k ? (b.verticalAlign = "top") : (b.y = -l),
                (n = !0));
            l = e.y + d.height - u;
            l > c.plotHeight &&
                ("top" === k
                    ? (b.verticalAlign = "bottom")
                    : (b.y = c.plotHeight - l),
                (n = !0));
            n && ((a.placed = !g), a.align(b, null, f));
        };
        n.pie &&
            ((n.pie.prototype.drawDataLabels = function () {
                var d = this,
                    f = d.data,
                    e,
                    h = d.chart,
                    l = d.options.dataLabels,
                    n = r(l.connectorPadding, 10),
                    c = r(l.connectorWidth, 1),
                    q = h.plotWidth,
                    p = h.plotHeight,
                    v,
                    w = l.distance,
                    E = d.center,
                    D = E[2] / 2,
                    G = E[1],
                    F = 0 < w,
                    N,
                    m,
                    z,
                    O,
                    M = [[], []],
                    y,
                    B,
                    Q,
                    R,
                    S = [0, 0, 0, 0];
                d.visible &&
                    (l.enabled || d._hasPointLabels) &&
                    (b.prototype.drawDataLabels.apply(d),
                    H(f, function (a) {
                        a.dataLabel &&
                            a.visible &&
                            (M[a.half].push(a), (a.dataLabel._pos = null));
                    }),
                    H(M, function (b, c) {
                        var f,
                            k,
                            u = b.length,
                            r,
                            t,
                            v;
                        if (u)
                            for (
                                d.sortByAngle(b, c - 0.5),
                                    0 < w &&
                                        ((f = Math.max(0, G - D - w)),
                                        (k = Math.min(G + D + w, h.plotHeight)),
                                        (r = g(b, function (a) {
                                            if (a.dataLabel)
                                                return (
                                                    (v =
                                                        a.dataLabel.getBBox()
                                                            .height || 21),
                                                    {
                                                        target:
                                                            a.labelPos[1] -
                                                            f +
                                                            v / 2,
                                                        size: v,
                                                        rank: a.y,
                                                    }
                                                );
                                        })),
                                        a.distribute(r, k + v - f)),
                                    R = 0;
                                R < u;
                                R++
                            )
                                (e = b[R]),
                                    (z = e.labelPos),
                                    (N = e.dataLabel),
                                    (Q =
                                        !1 === e.visible
                                            ? "hidden"
                                            : "inherit"),
                                    (t = z[1]),
                                    r
                                        ? void 0 === r[R].pos
                                            ? (Q = "hidden")
                                            : ((O = r[R].size),
                                              (B = f + r[R].pos))
                                        : (B = t),
                                    (y = l.justify
                                        ? E[0] + (c ? -1 : 1) * (D + w)
                                        : d.getX(
                                              B < f + 2 || B > k - 2 ? t : B,
                                              c
                                          )),
                                    (N._attr = { visibility: Q, align: z[6] }),
                                    (N._pos = {
                                        x:
                                            y +
                                            l.x +
                                            ({ left: n, right: -n }[z[6]] || 0),
                                        y: B + l.y - 10,
                                    }),
                                    (z.x = y),
                                    (z.y = B),
                                    null === d.options.size &&
                                        ((m = N.width),
                                        y - m < n
                                            ? (S[3] = Math.max(
                                                  Math.round(m - y + n),
                                                  S[3]
                                              ))
                                            : y + m > q - n &&
                                              (S[1] = Math.max(
                                                  Math.round(y + m - q + n),
                                                  S[1]
                                              )),
                                        0 > B - O / 2
                                            ? (S[0] = Math.max(
                                                  Math.round(-B + O / 2),
                                                  S[0]
                                              ))
                                            : B + O / 2 > p &&
                                              (S[2] = Math.max(
                                                  Math.round(B + O / 2 - p),
                                                  S[2]
                                              )));
                    }),
                    0 === A(S) || this.verifyDataLabelOverflow(S)) &&
                    (this.placeDataLabels(),
                    F &&
                        c &&
                        H(this.points, function (a) {
                            var b;
                            v = a.connector;
                            if ((N = a.dataLabel) && N._pos && a.visible) {
                                Q = N._attr.visibility;
                                if ((b = !v))
                                    (a.connector = v =
                                        h.renderer
                                            .path()
                                            .addClass(
                                                "highcharts-data-label-connector highcharts-color-" +
                                                    a.colorIndex
                                            )
                                            .add(d.dataLabelsGroup)),
                                        v.attr({
                                            "stroke-width": c,
                                            stroke:
                                                l.connectorColor ||
                                                a.color ||
                                                "#666666",
                                        });
                                v[b ? "attr" : "animate"]({
                                    d: d.connectorPath(a.labelPos),
                                });
                                v.attr("visibility", Q);
                            } else v && (a.connector = v.destroy());
                        }));
            }),
            (n.pie.prototype.connectorPath = function (a) {
                var b = a.x,
                    d = a.y;
                return r(this.options.dataLabels.softConnector, !0)
                    ? [
                          "M",
                          b + ("left" === a[6] ? 5 : -5),
                          d,
                          "C",
                          b,
                          d,
                          2 * a[2] - a[4],
                          2 * a[3] - a[5],
                          a[2],
                          a[3],
                          "L",
                          a[4],
                          a[5],
                      ]
                    : [
                          "M",
                          b + ("left" === a[6] ? 5 : -5),
                          d,
                          "L",
                          a[2],
                          a[3],
                          "L",
                          a[4],
                          a[5],
                      ];
            }),
            (n.pie.prototype.placeDataLabels = function () {
                H(this.points, function (a) {
                    var b = a.dataLabel;
                    b &&
                        a.visible &&
                        ((a = b._pos)
                            ? (b.attr(b._attr),
                              b[b.moved ? "animate" : "attr"](a),
                              (b.moved = !0))
                            : b && b.attr({ y: -9999 }));
                });
            }),
            (n.pie.prototype.alignDataLabel = l),
            (n.pie.prototype.verifyDataLabelOverflow = function (a) {
                var b = this.center,
                    d = this.options,
                    g = d.center,
                    l = d.minSize || 80,
                    n,
                    c;
                null !== g[0]
                    ? (n = Math.max(b[2] - Math.max(a[1], a[3]), l))
                    : ((n = Math.max(b[2] - a[1] - a[3], l)),
                      (b[0] += (a[3] - a[1]) / 2));
                null !== g[1]
                    ? (n = Math.max(
                          Math.min(n, b[2] - Math.max(a[0], a[2])),
                          l
                      ))
                    : ((n = Math.max(Math.min(n, b[2] - a[0] - a[2]), l)),
                      (b[1] += (a[0] - a[2]) / 2));
                n < b[2]
                    ? ((b[2] = n),
                      (b[3] = Math.min(f(d.innerSize || 0, n), n)),
                      this.translate(b),
                      this.drawDataLabels && this.drawDataLabels())
                    : (c = !0);
                return c;
            }));
        n.column &&
            (n.column.prototype.alignDataLabel = function (a, d, e, f, g) {
                var h = this.chart.inverted,
                    c = a.series,
                    k = a.dlBox || a.shapeArgs,
                    l = r(
                        a.below,
                        a.plotY > r(this.translatedThreshold, c.yAxis.len)
                    ),
                    n = r(e.inside, !!this.options.stacking);
                k &&
                    ((f = v(k)),
                    0 > f.y && ((f.height += f.y), (f.y = 0)),
                    (k = f.y + f.height - c.yAxis.len),
                    0 < k && (f.height -= k),
                    h &&
                        (f = {
                            x: c.yAxis.len - f.y - f.height,
                            y: c.xAxis.len - f.x - f.width,
                            width: f.height,
                            height: f.width,
                        }),
                    n ||
                        (h
                            ? ((f.x += l ? 0 : f.width), (f.width = 0))
                            : ((f.y += l ? f.height : 0), (f.height = 0))));
                e.align = r(e.align, !h || n ? "center" : l ? "right" : "left");
                e.verticalAlign = r(
                    e.verticalAlign,
                    h || n ? "middle" : l ? "top" : "bottom"
                );
                b.prototype.alignDataLabel.call(this, a, d, e, f, g);
            });
    })(M);
    (function (a) {
        var E = a.Chart,
            A = a.each,
            F = a.pick,
            H = a.addEvent;
        E.prototype.callbacks.push(function (a) {
            function d() {
                var d = [];
                A(a.series, function (a) {
                    var g = a.options.dataLabels,
                        p = a.dataLabelCollections || ["dataLabel"];
                    (g.enabled || a._hasPointLabels) &&
                        !g.allowOverlap &&
                        a.visible &&
                        A(p, function (f) {
                            A(a.points, function (a) {
                                a[f] &&
                                    ((a[f].labelrank = F(
                                        a.labelrank,
                                        a.shapeArgs && a.shapeArgs.height
                                    )),
                                    d.push(a[f]));
                            });
                        });
                });
                a.hideOverlappingLabels(d);
            }
            d();
            H(a, "redraw", d);
        });
        E.prototype.hideOverlappingLabels = function (a) {
            var d = a.length,
                g,
                p,
                l,
                r,
                f,
                b,
                n,
                w,
                t,
                k = function (a, b, d, f, c, g, k, l) {
                    return !(c > a + d || c + k < a || g > b + f || g + l < b);
                };
            for (p = 0; p < d; p++)
                if ((g = a[p])) (g.oldOpacity = g.opacity), (g.newOpacity = 1);
            a.sort(function (a, b) {
                return (b.labelrank || 0) - (a.labelrank || 0);
            });
            for (p = 0; p < d; p++)
                for (l = a[p], g = p + 1; g < d; ++g)
                    if (
                        ((r = a[g]),
                        l &&
                            r &&
                            l.placed &&
                            r.placed &&
                            0 !== l.newOpacity &&
                            0 !== r.newOpacity &&
                            ((f = l.alignAttr),
                            (b = r.alignAttr),
                            (n = l.parentGroup),
                            (w = r.parentGroup),
                            (t = 2 * (l.box ? 0 : l.padding)),
                            (f = k(
                                f.x + n.translateX,
                                f.y + n.translateY,
                                l.width - t,
                                l.height - t,
                                b.x + w.translateX,
                                b.y + w.translateY,
                                r.width - t,
                                r.height - t
                            ))))
                    )
                        (l.labelrank < r.labelrank ? l : r).newOpacity = 0;
            A(a, function (a) {
                var b, d;
                a &&
                    ((d = a.newOpacity),
                    a.oldOpacity !== d &&
                        a.placed &&
                        (d
                            ? a.show(!0)
                            : (b = function () {
                                  a.hide();
                              }),
                        (a.alignAttr.opacity = d),
                        a[a.isOld ? "animate" : "attr"](a.alignAttr, null, b)),
                    (a.isOld = !0));
            });
        };
    })(M);
    (function (a) {
        var E = a.addEvent,
            A = a.Chart,
            F = a.createElement,
            H = a.css,
            p = a.defaultOptions,
            d = a.defaultPlotOptions,
            g = a.each,
            v = a.extend,
            l = a.fireEvent,
            r = a.hasTouch,
            f = a.inArray,
            b = a.isObject,
            n = a.Legend,
            w = a.merge,
            t = a.pick,
            k = a.Point,
            e = a.Series,
            h = a.seriesTypes,
            C = a.svg;
        a = a.TrackerMixin = {
            drawTrackerPoint: function () {
                var a = this,
                    b = a.chart,
                    d = b.pointer,
                    e = function (a) {
                        for (var c = a.target, d; c && !d; )
                            (d = c.point), (c = c.parentNode);
                        if (void 0 !== d && d !== b.hoverPoint)
                            d.onMouseOver(a);
                    };
                g(a.points, function (a) {
                    a.graphic && (a.graphic.element.point = a);
                    a.dataLabel &&
                        (a.dataLabel.div
                            ? (a.dataLabel.div.point = a)
                            : (a.dataLabel.element.point = a));
                });
                a._hasTracking ||
                    (g(a.trackerGroups, function (b) {
                        if (a[b]) {
                            a[b]
                                .addClass("highcharts-tracker")
                                .on("mouseover", e)
                                .on("mouseout", function (a) {
                                    d.onTrackerMouseOut(a);
                                });
                            if (r) a[b].on("touchstart", e);
                            a.options.cursor &&
                                a[b].css(H).css({ cursor: a.options.cursor });
                        }
                    }),
                    (a._hasTracking = !0));
            },
            drawTrackerGraph: function () {
                var a = this,
                    b = a.options,
                    d = b.trackByArea,
                    e = [].concat(d ? a.areaPath : a.graphPath),
                    f = e.length,
                    h = a.chart,
                    k = h.pointer,
                    l = h.renderer,
                    n = h.options.tooltip.snap,
                    p = a.tracker,
                    t,
                    m = function () {
                        if (h.hoverSeries !== a) a.onMouseOver();
                    },
                    v = "rgba(192,192,192," + (C ? 0.0001 : 0.002) + ")";
                if (f && !d)
                    for (t = f + 1; t--; )
                        "M" === e[t] &&
                            e.splice(t + 1, 0, e[t + 1] - n, e[t + 2], "L"),
                            ((t && "M" === e[t]) || t === f) &&
                                e.splice(t, 0, "L", e[t - 2] + n, e[t - 1]);
                p
                    ? p.attr({ d: e })
                    : a.graph &&
                      ((a.tracker = l
                          .path(e)
                          .attr({
                              "stroke-linejoin": "round",
                              visibility: a.visible ? "visible" : "hidden",
                              stroke: v,
                              fill: d ? v : "none",
                              "stroke-width":
                                  a.graph.strokeWidth() + (d ? 0 : 2 * n),
                              zIndex: 2,
                          })
                          .add(a.group)),
                      g([a.tracker, a.markerGroup], function (a) {
                          a.addClass("highcharts-tracker")
                              .on("mouseover", m)
                              .on("mouseout", function (a) {
                                  k.onTrackerMouseOut(a);
                              });
                          b.cursor && a.css({ cursor: b.cursor });
                          if (r) a.on("touchstart", m);
                      }));
            },
        };
        h.column && (h.column.prototype.drawTracker = a.drawTrackerPoint);
        h.pie && (h.pie.prototype.drawTracker = a.drawTrackerPoint);
        h.scatter && (h.scatter.prototype.drawTracker = a.drawTrackerPoint);
        v(n.prototype, {
            setItemEvents: function (a, b, d) {
                var c = this,
                    e = c.chart,
                    f =
                        "highcharts-legend-" +
                        (a.series ? "point" : "series") +
                        "-active";
                (d ? b : a.legendGroup)
                    .on("mouseover", function () {
                        a.setState("hover");
                        e.seriesGroup.addClass(f);
                        b.css(c.options.itemHoverStyle);
                    })
                    .on("mouseout", function () {
                        b.css(a.visible ? c.itemStyle : c.itemHiddenStyle);
                        e.seriesGroup.removeClass(f);
                        a.setState();
                    })
                    .on("click", function (b) {
                        var c = function () {
                            a.setVisible && a.setVisible();
                        };
                        b = { browserEvent: b };
                        a.firePointEvent
                            ? a.firePointEvent("legendItemClick", b, c)
                            : l(a, "legendItemClick", b, c);
                    });
            },
            createCheckboxForItem: function (a) {
                a.checkbox = F(
                    "input",
                    {
                        type: "checkbox",
                        checked: a.selected,
                        defaultChecked: a.selected,
                    },
                    this.options.itemCheckboxStyle,
                    this.chart.container
                );
                E(a.checkbox, "click", function (b) {
                    l(
                        a.series || a,
                        "checkboxClick",
                        { checked: b.target.checked, item: a },
                        function () {
                            a.select();
                        }
                    );
                });
            },
        });
        p.legend.itemStyle.cursor = "pointer";
        v(A.prototype, {
            showResetZoom: function () {
                var a = this,
                    b = p.lang,
                    d = a.options.chart.resetZoomButton,
                    e = d.theme,
                    f = e.states,
                    g = "chart" === d.relativeTo ? null : "plotBox";
                this.resetZoomButton = a.renderer
                    .button(
                        b.resetZoom,
                        null,
                        null,
                        function () {
                            a.zoomOut();
                        },
                        e,
                        f && f.hover
                    )
                    .attr({ align: d.position.align, title: b.resetZoomTitle })
                    .addClass("highcharts-reset-zoom")
                    .add()
                    .align(d.position, !1, g);
            },
            zoomOut: function () {
                var a = this;
                l(a, "selection", { resetSelection: !0 }, function () {
                    a.zoom();
                });
            },
            zoom: function (a) {
                var c,
                    d = this.pointer,
                    e = !1,
                    f;
                !a || a.resetSelection
                    ? g(this.axes, function (a) {
                          c = a.zoom();
                      })
                    : g(a.xAxis.concat(a.yAxis), function (a) {
                          var b = a.axis;
                          d[b.isXAxis ? "zoomX" : "zoomY"] &&
                              ((c = b.zoom(a.min, a.max)),
                              b.displayBtn && (e = !0));
                      });
                f = this.resetZoomButton;
                e && !f
                    ? this.showResetZoom()
                    : !e && b(f) && (this.resetZoomButton = f.destroy());
                c &&
                    this.redraw(
                        t(
                            this.options.chart.animation,
                            a && a.animation,
                            100 > this.pointCount
                        )
                    );
            },
            pan: function (a, b) {
                var c = this,
                    d = c.hoverPoints,
                    e;
                d &&
                    g(d, function (a) {
                        a.setState();
                    });
                g("xy" === b ? [1, 0] : [1], function (b) {
                    b = c[b ? "xAxis" : "yAxis"][0];
                    var d = b.horiz,
                        f = b.reversed,
                        g = a[d ? "chartX" : "chartY"],
                        d = d ? "mouseDownX" : "mouseDownY",
                        h = c[d],
                        k = (b.pointRange || 0) / (f ? -2 : 2),
                        l = b.getExtremes(),
                        n = b.toValue(h - g, !0) + k,
                        k = b.toValue(h + b.len - g, !0) - k,
                        h = h > g;
                    f && ((h = !h), (f = n), (n = k), (k = f));
                    b.series.length &&
                        (h || n > Math.min(l.dataMin, l.min)) &&
                        (!h || k < Math.max(l.dataMax, l.max)) &&
                        (b.setExtremes(n, k, !1, !1, { trigger: "pan" }),
                        (e = !0));
                    c[d] = g;
                });
                e && c.redraw(!1);
                H(c.container, { cursor: "move" });
            },
        });
        v(k.prototype, {
            select: function (a, b) {
                var c = this,
                    d = c.series,
                    e = d.chart;
                a = t(a, !c.selected);
                c.firePointEvent(
                    a ? "select" : "unselect",
                    { accumulate: b },
                    function () {
                        c.selected = c.options.selected = a;
                        d.options.data[f(c, d.data)] = c.options;
                        c.setState(a && "select");
                        b ||
                            g(e.getSelectedPoints(), function (a) {
                                a.selected &&
                                    a !== c &&
                                    ((a.selected = a.options.selected = !1),
                                    (d.options.data[f(a, d.data)] = a.options),
                                    a.setState(""),
                                    a.firePointEvent("unselect"));
                            });
                    }
                );
            },
            onMouseOver: function (a, b) {
                var c = this.series,
                    d = c.chart,
                    e = d.tooltip,
                    f = d.hoverPoint;
                if (this.series) {
                    if (!b) {
                        if (f && f !== this) f.onMouseOut();
                        if (d.hoverSeries !== c) c.onMouseOver();
                        d.hoverPoint = this;
                    }
                    !e || (e.shared && !c.noSharedTooltip)
                        ? e || this.setState("hover")
                        : (this.setState("hover"), e.refresh(this, a));
                    this.firePointEvent("mouseOver");
                }
            },
            onMouseOut: function () {
                var a = this.series.chart,
                    b = a.hoverPoints;
                this.firePointEvent("mouseOut");
                (b && -1 !== f(this, b)) ||
                    (this.setState(), (a.hoverPoint = null));
            },
            importEvents: function () {
                if (!this.hasImportedEvents) {
                    var a = w(this.series.options.point, this.options).events,
                        b;
                    this.events = a;
                    for (b in a) E(this, b, a[b]);
                    this.hasImportedEvents = !0;
                }
            },
            setState: function (a, b) {
                var c = Math.floor(this.plotX),
                    e = this.plotY,
                    f = this.series,
                    g = f.options.states[a] || {},
                    h = d[f.type].marker && f.options.marker,
                    k = h && !1 === h.enabled,
                    l = (h && h.states && h.states[a]) || {},
                    n = !1 === l.enabled,
                    p = f.stateMarkerGraphic,
                    m = this.marker || {},
                    r = f.chart,
                    u = f.halo,
                    w,
                    y = h && f.markerAttribs;
                a = a || "";
                if (
                    !(
                        (a === this.state && !b) ||
                        (this.selected && "select" !== a) ||
                        !1 === g.enabled ||
                        (a && (n || (k && !1 === l.enabled))) ||
                        (a &&
                            m.states &&
                            m.states[a] &&
                            !1 === m.states[a].enabled)
                    )
                ) {
                    y && (w = f.markerAttribs(this, a));
                    if (this.graphic)
                        this.state &&
                            this.graphic.removeClass(
                                "highcharts-point-" + this.state
                            ),
                            a && this.graphic.addClass("highcharts-point-" + a),
                            this.graphic.attr(f.pointAttribs(this, a)),
                            w &&
                                this.graphic.animate(
                                    w,
                                    t(
                                        r.options.chart.animation,
                                        l.animation,
                                        h.animation
                                    )
                                ),
                            p && p.hide();
                    else {
                        if (a && l) {
                            h = m.symbol || f.symbol;
                            p && p.currentSymbol !== h && (p = p.destroy());
                            if (p)
                                p[b ? "animate" : "attr"]({ x: w.x, y: w.y });
                            else
                                h &&
                                    ((f.stateMarkerGraphic = p =
                                        r.renderer
                                            .symbol(
                                                h,
                                                w.x,
                                                w.y,
                                                w.width,
                                                w.height
                                            )
                                            .add(f.markerGroup)),
                                    (p.currentSymbol = h));
                            p && p.attr(f.pointAttribs(this, a));
                        }
                        p &&
                            (p[
                                a && r.isInsidePlot(c, e, r.inverted)
                                    ? "show"
                                    : "hide"
                            ](),
                            (p.element.point = this));
                    }
                    (c = g.halo) && c.size
                        ? (u ||
                              (f.halo = u =
                                  r.renderer
                                      .path()
                                      .add(y ? f.markerGroup : f.group)),
                          u[b ? "animate" : "attr"]({
                              d: this.haloPath(c.size),
                          }),
                          u.attr({
                              class:
                                  "highcharts-halo highcharts-color-" +
                                  t(this.colorIndex, f.colorIndex),
                          }),
                          u.attr(
                              v(
                                  {
                                      fill: this.color || f.color,
                                      "fill-opacity": c.opacity,
                                      zIndex: -1,
                                  },
                                  c.attributes
                              )
                          ))
                        : u && u.animate({ d: this.haloPath(0) });
                    this.state = a;
                }
            },
            haloPath: function (a) {
                return this.series.chart.renderer.symbols.circle(
                    Math.floor(this.plotX) - a,
                    this.plotY - a,
                    2 * a,
                    2 * a
                );
            },
        });
        v(e.prototype, {
            onMouseOver: function () {
                var a = this.chart,
                    b = a.hoverSeries;
                if (b && b !== this) b.onMouseOut();
                this.options.events.mouseOver && l(this, "mouseOver");
                this.setState("hover");
                a.hoverSeries = this;
            },
            onMouseOut: function () {
                var a = this.options,
                    b = this.chart,
                    d = b.tooltip,
                    e = b.hoverPoint;
                b.hoverSeries = null;
                if (e) e.onMouseOut();
                this && a.events.mouseOut && l(this, "mouseOut");
                !d ||
                    a.stickyTracking ||
                    (d.shared && !this.noSharedTooltip) ||
                    d.hide();
                this.setState();
            },
            setState: function (a) {
                var b = this,
                    d = b.options,
                    e = b.graph,
                    f = d.states,
                    h = d.lineWidth,
                    d = 0;
                a = a || "";
                if (
                    b.state !== a &&
                    (g([b.group, b.markerGroup], function (c) {
                        c &&
                            (b.state &&
                                c.removeClass("highcharts-series-" + b.state),
                            a && c.addClass("highcharts-series-" + a));
                    }),
                    (b.state = a),
                    !f[a] || !1 !== f[a].enabled) &&
                    (a && (h = f[a].lineWidth || h + (f[a].lineWidthPlus || 0)),
                    e && !e.dashstyle)
                )
                    for (
                        f = { "stroke-width": h }, e.attr(f);
                        b["zone-graph-" + d];

                    )
                        b["zone-graph-" + d].attr(f), (d += 1);
            },
            setVisible: function (a, b) {
                var c = this,
                    d = c.chart,
                    e = c.legendItem,
                    f,
                    h = d.options.chart.ignoreHiddenSeries,
                    k = c.visible;
                f = (c.visible =
                    a =
                    c.options.visible =
                    c.userOptions.visible =
                        void 0 === a ? !k : a)
                    ? "show"
                    : "hide";
                g(
                    [
                        "group",
                        "dataLabelsGroup",
                        "markerGroup",
                        "tracker",
                        "tt",
                    ],
                    function (a) {
                        if (c[a]) c[a][f]();
                    }
                );
                if (
                    d.hoverSeries === c ||
                    (d.hoverPoint && d.hoverPoint.series) === c
                )
                    c.onMouseOut();
                e && d.legend.colorizeItem(c, a);
                c.isDirty = !0;
                c.options.stacking &&
                    g(d.series, function (a) {
                        a.options.stacking && a.visible && (a.isDirty = !0);
                    });
                g(c.linkedSeries, function (b) {
                    b.setVisible(a, !1);
                });
                h && (d.isDirtyBox = !0);
                !1 !== b && d.redraw();
                l(c, f);
            },
            show: function () {
                this.setVisible(!0);
            },
            hide: function () {
                this.setVisible(!1);
            },
            select: function (a) {
                this.selected = a = void 0 === a ? !this.selected : a;
                this.checkbox && (this.checkbox.checked = a);
                l(this, a ? "select" : "unselect");
            },
            drawTracker: a.drawTrackerGraph,
        });
    })(M);
    (function (a) {
        var E = a.Chart,
            A = a.each,
            F = a.inArray,
            H = a.isObject,
            p = a.pick,
            d = a.splat;
        E.prototype.setResponsive = function (a) {
            var d = this.options.responsive;
            d &&
                d.rules &&
                A(
                    d.rules,
                    function (d) {
                        this.matchResponsiveRule(d, a);
                    },
                    this
                );
        };
        E.prototype.matchResponsiveRule = function (d, v) {
            var g = this.respRules,
                r = d.condition,
                f;
            f =
                r.callback ||
                function () {
                    return (
                        this.chartWidth <= p(r.maxWidth, Number.MAX_VALUE) &&
                        this.chartHeight <= p(r.maxHeight, Number.MAX_VALUE) &&
                        this.chartWidth >= p(r.minWidth, 0) &&
                        this.chartHeight >= p(r.minHeight, 0)
                    );
                };
            void 0 === d._id && (d._id = a.uniqueKey());
            f = f.call(this);
            !g[d._id] && f
                ? d.chartOptions &&
                  ((g[d._id] = this.currentOptions(d.chartOptions)),
                  this.update(d.chartOptions, v))
                : g[d._id] && !f && (this.update(g[d._id], v), delete g[d._id]);
        };
        E.prototype.currentOptions = function (a) {
            function g(a, f, b) {
                var l, p;
                for (l in a)
                    if (-1 < F(l, ["series", "xAxis", "yAxis"]))
                        for (
                            a[l] = d(a[l]), b[l] = [], p = 0;
                            p < a[l].length;
                            p++
                        )
                            (b[l][p] = {}), g(a[l][p], f[l][p], b[l][p]);
                    else
                        H(a[l])
                            ? ((b[l] = {}), g(a[l], f[l] || {}, b[l]))
                            : (b[l] = f[l] || null);
            }
            var l = {};
            g(a, this.options, l);
            return l;
        };
    })(M);
    return M;
});
