import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {

    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // calendar assignment colors
    safelist: [
        'bg-red-100', 'text-red-800', 'dark:bg-red-600', 'dark:text-red-100',
        'bg-orange-100', 'text-orange-800', 'dark:bg-orange-600', 'dark:text-orange-100',
        'bg-amber-100', 'text-amber-800', 'dark:bg-amber-600', 'dark:text-amber-100',
        'bg-yellow-100', 'text-yellow-800', 'dark:bg-yellow-600', 'dark:text-yellow-100',
        'bg-lime-100', 'text-lime-800', 'dark:bg-lime-600', 'dark:text-lime-100',
        'bg-green-100', 'text-green-800', 'dark:bg-green-600', 'dark:text-green-100',
        'bg-emerald-100', 'text-emerald-800', 'dark:bg-emerald-600', 'dark:text-emerald-100',
        'bg-teal-100', 'text-teal-800', 'dark:bg-teal-600', 'dark:text-teal-100',
        'bg-cyan-100', 'text-cyan-800', 'dark:bg-cyan-600', 'dark:text-cyan-100',
        'bg-sky-100', 'text-sky-800', 'dark:bg-sky-600', 'dark:text-sky-100',
        'bg-blue-100', 'text-blue-800', 'dark:bg-blue-600', 'dark:text-blue-100',
        'bg-indigo-100', 'text-indigo-800', 'dark:bg-indigo-600', 'dark:text-indigo-100',
        'bg-violet-100', 'text-violet-800', 'dark:bg-violet-600', 'dark:text-violet-100',
        'bg-purple-100', 'text-purple-800', 'dark:bg-purple-600', 'dark:text-purple-100',
        'bg-fuchsia-100', 'text-fuchsia-800', 'dark:bg-fuchsia-600', 'dark:text-fuchsia-100',
        'bg-pink-100', 'text-pink-800', 'dark:bg-pink-600', 'dark:text-pink-100',
        'bg-rose-100', 'text-rose-800', 'dark:bg-rose-600', 'dark:text-rose-100',
        'bg-slate-100', 'text-slate-800', 'dark:bg-slate-600', 'dark:text-slate-100',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                allerta: ['Allerta', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};
