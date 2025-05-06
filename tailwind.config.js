// tailwind.config.js
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/css/**/*.css',
    ],
    purge: [],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {},
    },
    variants: {
        extend: {},
    },
    plugins: [],
}
