!function t(e, n, r) {
  function o(a, c) {
    if (!n[a]) {
      if (!e[a]) {
        var u = "function" == typeof require && require;
        if (!c && u) return u(a, !0);
        if (i) return i(a, !0);
        var f = new Error("Cannot find module '" + a + "'");
        throw f.code = "MODULE_NOT_FOUND", f
      }
      var l = n[a] = {exports: {}};
      e[a][0].call(l.exports, function (t) {
        var n = e[a][1][t];
        return o(n ? n : t)
      }, l, l.exports, t, e, n, r)
    }
    return n[a].exports
  }

  for (var i = "function" == typeof require && require, a = 0; a < r.length; a++) o(r[a]);
  return o
}({
  1: [function (t, e, n) {
    "use strict";

    function r(t, e, n) {
      function r() {
        var e = t.get("/api/utils/geoip/country");
        e.then(function (t) {
          200 === t.status && (o.country = t.data, o.country.location[0] = Math.min(o.country.location[0], 40), o.country.location[0] = Math.max(o.country.location[0], -40), o.mapView = {
            center: [o.country.location[0], o.country.location[1]],
            zoom: 3
          })
        })["catch"](function () {
        })
      }

      var o = this;
      n.setTitle("GBIF"), n.drawer(!1), o.mapView = void 0, o.freeTextQuery, o.mapOptions = {points: !0}, o.mapFilter = {}, r(), o.getSuggestions = function (n) {
        return t.get(e.taxon, {params: {q: n, limit: 10}}).then(function (t) {
          return t.data
        })
      }, o.typeaheadSelect = function (t) {
        o.mapFilter = {taxon_key: t.key}
      }, o.searchOnEnter = function () {
        o.searchOnEnter = function (t) {
          13 !== t.which || o.selectedSpecies || (o.mapFilter = {})
        }
      }, o.updateSearch = function () {
        location.href = "/search?q=" + encodeURIComponent(o.freeTextQuery)
      }
    }
  }, {}]
}, {}, [1]);