var Encore = require('@symfony/webpack-encore');

Encore
	.setOutputPath('dist/')
	.setPublicPath('/dist')
	.enableSassLoader()
	.addStyleEntry('admin', './scss/admin.scss')
	.enableSourceMaps( ! Encore.isProduction() )
	.cleanupOutputBeforeBuild()
	.disableSingleRuntimeChunk()
	.enableVersioning( Encore.isProduction() )
;

var config = Encore.getWebpackConfig();

module.exports = config;
