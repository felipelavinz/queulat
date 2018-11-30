var Encore = require('@symfony/webpack-encore');

Encore
	.setOutputPath('dist/')
	.setPublicPath('/dist')
	.enableSassLoader()
	.configureCssLoader( function( args ){
		args.minimize = false;
		return args;
	} )
	.addStyleEntry('admin', './scss/admin.scss')
	.enableSourceMaps( ! Encore.isProduction() )
	.cleanupOutputBeforeBuild()
	.disableSingleRuntimeChunk()
	.enableVersioning( Encore.isProduction() )
;

var config = Encore.getWebpackConfig();

module.exports = config;