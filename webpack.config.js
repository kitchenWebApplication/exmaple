const webpack = require('webpack')

module.exports = {
    entry: {
        vendor: __dirname + '/resources/assets/app/vendor.js',
        app: __dirname + '/resources/assets/app/app.js'
    },
    output: {
        path: 'public/assets/app',
        filename: '[name].js'
    },
    module: {
        loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                include: /resources\/assets\/app/
            },
            {
                test: /\.vue$/,
                include: /\/resources\/assets\/app/,
                loader: 'vue'
            },
            {
                test: /\.s?css/,
                loader: 'style!css!sass?sourceMap'
            },
            {
                test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
                loader: 'url-loader',
                query: {
                    limit:    10000,
                    name:     '../../assets/fonts/[hash].[ext]',
                    mimetype: 'application/font-woff'
                }
            },
            {
                test: /\.(ttf|eot|svg)(\?[\s\S]+)?$/,
                loader: 'file',
                query: {
                    name: '../../assets/fonts/[hash].[ext]'
                }
            }
        ]
    },
    babel: {
        presets: ['es2015'],
        plugins: ['transform-runtime'],
        cacheDirectory: true
    },
    plugins: [
        new webpack.ProvidePlugin({
            "moment": "moment"
        }),
        new webpack.DefinePlugin({
            'process.env': {
                'NODE_ENV': JSON.stringify('production')
            }
        }),
    ]
}