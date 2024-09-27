const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .enableStimulusBridge('./assets/controllers.json')
    .addEntry('app', './assets/app.js')
    .splitEntryChunks()

    

    .enableVueLoader(() => {}, { version: 3 })
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    // Ajoutez ceci pour supporter Vue.js
    .enableVueLoader(() => {})
    
    .configureBabel(null)
    .enableSassLoader()
    .enableTypeScriptLoader();

module.exports = Encore.getWebpackConfig();
