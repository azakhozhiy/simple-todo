const mix = require('laravel-mix');

mix.webpackConfig({
  resolve: {
    extensions: ['.webpack.js', '.js', '.json', '.scss']
  },
  externals: {
    'jquery': 'jQuery'
  }
}).options({
    processCssUrls: false
  })
  .setPublicPath('public/')
  .js('resources/assets/js/app.js', 'js/app.js')
  .sass('resources/assets/styles/app.scss', 'css/app.css')
  .sourceMaps()
  .version();

if (!mix.inProduction()) {
  mix.browserSync({
    proxy: 'http://unlimint.test'
  });
}


