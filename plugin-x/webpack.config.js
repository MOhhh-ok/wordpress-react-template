module.exports = {
    mode: 'development',
    entry: {
        entry1: './react/src/entry1.tsx',
    },
    output: {
        path: __dirname + '/react/dist',
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                use: 'ts-loader',
            },
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader'],
            },
        ],
    },
    resolve: {
        extensions: ['.ts', '.tsx', '.js'],
    },
};
