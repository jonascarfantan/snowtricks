// postcss.config.js
const tailwindcss = require('tailwindcss');

module.exports = {
    plugins: [
        require('tailwindcss'),
        require('autoprefixer'),
        require('postcss-import')
    ]
}
