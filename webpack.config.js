// WordPress webpack config.
const defaultConfig = require("@wordpress/scripts/config/webpack.config");

// Plugins.
const RemoveEmptyScriptsPlugin = require("webpack-remove-empty-scripts");

const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

// Utilities.
const path = require("path");

// Add any new entry points by extending the webpack config.
module.exports = {
  ...defaultConfig,
  ...{
  devServer: {
			static: {
				directory: path.join(__dirname, "./resources'"),
			},
			client: {
				overlay: true,
			},
			open: ["https://www.basil-book.local/"], // (Optional) Add your local domain here
			hot: false,
			compress: true,
			devMiddleware: {
				writeToDisk: true,
			},
		},
    entry: {
      "js/editor": path.resolve(process.cwd(), "resources/js", "editor.js"),
      "css/screen": path.resolve(
        process.cwd(),
        "resources/scss",
        "screen.scss",
      ),
      "css/editor": path.resolve(
        process.cwd(),
        "resources/scss",
        "editor.scss",
      ),
    },
    plugins: [
      // Include WP's plugin config.
      ...defaultConfig.plugins,

      // Removes the empty `.js` files generated by webpack but
      // sets it after WP has generated its `*.asset.php` file.
      new RemoveEmptyScriptsPlugin({
        stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
      }),
      new BrowserSyncPlugin(
      // BrowserSync options
      {
        // browse to http://localhost:3000/ during development
        host: 'localhost',
        port: 3000,
                // proxy the Webpack Dev Server endpoint
        // (which should be serving on http://localhost:3100/)
        // through BrowserSync
        proxy: 'https://www.basil-book.local/',
        files: [
          {
            match: ['resources', 'parts', 'templates', 'functions.php', 'theme.json'],
            fn(event, file) {
              if (event === 'change') {
                const bs = require('browser-sync').get('bs-webpack-plugin');
                bs.reload();
              }
            },
          },
        ],
      },
      // plugin options
      {
        // prevent BrowserSync from reloading the page
        // and let Webpack Dev Server take care of this
        reload: false
      }
    )
    ],
  },
};
