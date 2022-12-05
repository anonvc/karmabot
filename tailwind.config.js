const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
              brand: {
                'pink-light': '#ff8cc8',
                'pink-dark': '#d8549f',
                'purple-light': '#5d137c',
                'purple-dark': '#3d005d',
                'yellow': '#ffcf4b',
                'orange': '#e87467',
              },
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
