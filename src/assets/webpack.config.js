/* jshint: node */
const webpack = require('webpack');
const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const UglifyJsPlugin = require('webpack/lib/optimize/UglifyJsPlugin');
const ENV = process.env.NODE_ENV || 'development';

const plugins = [
  new ExtractTextPlugin((ENV === 'production' ? '[name].bundle.min.css' : '[name].bundle.css')),
];
if (ENV === 'production') {
  plugins.push(new UglifyJsPlugin({
    compressor: {
      screw_ie8: true,
      warnings: false
    },
    mangle: {
      screw_ie8: true
    },
    output: {
      comments: false,
      screw_ie8: true
    },
    // sourceMap: true
  }));
}

module.exports = {
  devtool: 'source-map',
  entry: {
    app: './app/index.js',
  },
  devServer: {
    historyApiFallback: true,
    port: 3000
  },
  resolve: {
    extensions: ['.js', '.scss', '.css'],
    modules: ['node_modules']
  },
  module: {
    loaders: [
      {
        test: /\.(js)$/,
        exclude: /node_modules/,
        use: ['babel-loader','eslint-loader'],
      },
      {
        test: /\.(css|scss)$/,
        loader: ExtractTextPlugin.extract({
          loader: [
            {
              loader:
                'css-loader?importLoaders=1',
              options: {
                url: false,
                sourceMap: ENV !== 'production',
                import: false,
                minimize: ENV === 'production'
                // root: 'dist'
              }
            },
            // { loader: 'resolve-url-loader?sourceMap'},
            { loader: 'sass-loader?sourceMap'}
          ]
        })
      }
    ],
  },
  output: {
    path: path.resolve(__dirname, 'dist/'),
    filename: (ENV === 'production' ? '[name].bundle.min.js' : '[name].bundle.js'),
    publicPath: "/",
  },
  plugins: plugins,
};