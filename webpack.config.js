const webpack = require('webpack')
const path = require('path')

const devBuild = process.env.NODE_ENV !== 'production'
const nodeEnv = devBuild ? 'development' : 'production'

var config = {
    entry:   {
        'home': ['whatwg-fetch', './app/Resources/js/home.js', 'webpack/hot/only-dev-server'],
        'index': ['./app/Resources/js/index.js', 'webpack/hot/only-dev-server']
    },
    output:  {
        path:       path.resolve('./web/assets/build/'),
        publicPath: 'http://localhost:8080/assets/build/',
        filename:   path.resolve('/js/[name].js')
    },
    resolve: {
        extensions: ['.js', '.jsx']
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
        rules: [
            {
                test: require.resolve('jquery'),
                use: [
                    'expose-loader?$',
                    'expose-loader?jQuery'
                ]
            },
            {
                test: /\.jsx?$/,
                loader: 'babel-loader',
                exclude: /node_modules/
            },
            {
                test: /\.scss$/i,
                use: [
                    'style-loader',
                    'css-loader',
                    'resolve-url-loader',
                    'sass-loader?sourceMap'
                ]
            },
            {
                test: /\.(woff|woff2)(\?v=\d+\.\d+\.\d+)?$/,
                loader: 'url-loader?limit=10000&mimetype=application/font-woff'
            },
            {
                test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/,
                loader: 'url-loader?limit=10000&mimetype=application/octet-stream'
            },
            {
                test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,
                use: [
                    'url-loader',
                    'file-loader'
                ]
            },
            {
                test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
                loader: 'url-loader?limit=10000&mimetype=image/svg+xml'
            },
            {
                test: /\.(jpe?g|png)$/, loader: 'file-loader'
            }
        ]
    }
}

if (devBuild) {
    console.log('Webpack dev build')
    config.devtool = '#eval-source-map'
    config.devServer = {
        hot: true,
        contentBase: path.resolve('./web/'),
        inline: true
    }
    config.plugins.push(
        new webpack.HotModuleReplacementPlugin()
    )
} else {
    config.plugins.push(
        new webpack.optimize.UglifyJsPlugin({sourceMap: true}),
        new webpack.optimize.AggressiveMergingPlugin()
    )
    console.log('Webpack production build')
}

module.exports = config
