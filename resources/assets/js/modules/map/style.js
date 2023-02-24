export default {
  version: 8,
  name: "Empty Style",
  sources: {
    base: {
      type: "raster",
      tiles: [
        "https://maps.vnpost.vn/api/tm/{z}/{x}/{y}.png?apikey=81c9873bc91e3b4f32c598dbb6ec6b83e528dc04a50d2a42",
      ],
      tileSize: 256,
    },
  },
  glyphs: "http://titles.ceid.gov.vn/fonts/{fontstack}/{range}.pbf",
  layers: [
    {
      id: "base",
      type: "raster",
      source: "base",
      minzoom: 0,
      maxzoom: 22,
    },
  ],
};
