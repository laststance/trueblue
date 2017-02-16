const webpack = require('webpack')

const devBuild = process.env.NODE_ENV !== 'production'
const nodeEnv = devBuild ? 'development' : 'production'

var config = {
    entry:   {
        'home': ['whatwg-fetch', './app/Resources/js/home.js'],
        'index': './app/Resources/js/index.js'

    },
    output:  {
        path:       './web/assets/build/',
        publicPath: '/assets/build/',
        filename:   '/js/[name].js'
    },
    resolve: {
        extensions: ['', '.js', '.jsx']
    },
    plugins: [
        new webpack.ProvidePlugin({
            _:               'lodash',
            $:               'jquery',
            'jQuery':        'jquery',
            'window.jQuery': 'jquery'
        }),
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: JSON.stringify(nodeEnv)
            }
        })
    ],
    module:  {
        loaders: [
            {test: require.resolve('jquery'), loader: 'expose?$!expose?jQuery'},
            {test: /\.jsx?$/, loader: 'babel-loader', exclude: /node_modules/},
            {test: /\.scss$/i, loader: 'style-loader!css-loader!resolve-url-loader!sass-loader?sourceMap'},
            {test: /\.(woff|woff2)(\?v=\d+\.\d+\.\d+)?$/, loader: 'url?limit=10000&mimetype=application/font-woff'},
            {test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/, loader: 'url?limit=10000&mimetype=application/octet-stream'},
            {test: /\.eot(\?v=\d+\.\d+\.\d+)?$/, loader: 'file'},
            {test: /\.svg(\?v=\d+\.\d+\.\d+)?$/, loader: 'url?limit=10000&mimetype=image/svg+xml'},
            {test: /\.(jpe?g|png)$/, loader: 'file-loader'}
        ]
    }
}

if (devBuild) {
    console.log('Webpack dev build')
    config.devtool = '#eval-source-map'
} else {
    config.plugins.push(
        new webpack.optimize.DedupePlugin(),
        new webpack.optimize.UglifyJsPlugin(),
        new webpack.optimize.OccurenceOrderPlugin(),
        new webpack.optimize.AggressiveMergingPlugin()
    )
    console.log('Webpack production build')
}

module.exports = config
