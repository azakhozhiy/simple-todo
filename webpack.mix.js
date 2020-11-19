const mix = require('laravel-mix');

mix.webpackConfig({
  externals: {
    'jquery': 'jQuery'
  }
}).options({
    processCssUrls: false
  })
  .setPublicPath('public/')
  .js('resources/assets/js/app.js', 'js/app.js')
  .sass('resources/assets/styles/app.scss', 'css/app.css')
  .copy('resources/assets/vendor/summernote/font', 'public/css/font')
  .sourceMaps()
  .version();

if (!mix.inProduction()) {
  mix.browserSync({
    proxy: 'http://unlimint.test'
  });
}


