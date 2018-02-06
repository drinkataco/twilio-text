// webpack.config.js
const Encore = require('@symfony/webpack-encore');

Encore
  // the project directory where all compiled assets will be stored
  .setOutputPath('public/build/')

  // the public path used by the web server to access the previous directory
  .setPublicPath('/build')

  .configureFilenames({
    css: 'css/[name]-[contenthash].css',
    js: 'js/[name]-[chunkhash].js',
  })

  // will create public/build/app.js and public/build/app.css
  .addEntry('app', './assets/js/app.js')
  .addEntry('styles', './assets/sass/app.scss')

  // allow sass/scss files to be processed
  .enableSassLoader()

  // empty the outputPath dir before each build
  .cleanupOutputBeforeBuild()

  // show OS notifications when builds finish/fail
  .enableBuildNotifications();

// export the final configuration
module.exports = Encore.getWebpackConfig();
