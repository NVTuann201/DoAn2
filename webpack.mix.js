let mix = require("laravel-mix");
const UglifyJSPlugin = require("uglifyjs-webpack-plugin");
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

const webpackConfig = {
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "resources/assets/js/"),
    },
  },
  plugins: [],
};
if (mix.inProduction()) {
  webpackConfig.plugins.push(new UglifyJSPlugin())
  mix.options({
    uglify: false,
  });
}

mix.webpackConfig(webpackConfig);
mix
  .js("resources/assets/js/app.js", "public/js")
  .sass("resources/assets/sass/app.scss", "public/css")
  .sass("resources/assets/sass/admin-app.scss", "public/css");

mix.sass("resources/assets/sass/login.scss", "public/css/chunk/login.css");
