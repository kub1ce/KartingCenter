import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    safelist: [
        'bg-indigo-600', 'hover:bg-indigo-700', 'text-white', 'cursor-pointer',
        'bg-gray-300', 'text-gray-500', 'cursor-not-allowed', 'pointer-events-none',
    ],

    plugins: [forms],
};
