import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            screens: {
                xs: '480px',
                sm: '640px',
                md: '768px',
                lg: '1024px',
                xl: '1280px',
            },
            colors: {
                customPrimary: "rgba(var(--custom-primary), <alpha-value>)",
                customPrimaryDark: "rgba(var(--custom-primary-dark), <alpha-value>)",
                customGray: "rgba(var(--custom-gray), <alpha-value>)",
                customGrayDark: "rgba(var(--custom-gray-dark), <alpha-value>)",
                customBlack: "rgba(var(--custom-black), <alpha-value>)",

                error: "rgba(var(--custom-error), <alpha-value>)",
                success: "rgba(var(--custom-success), <alpha-value>)",
                warning: "rgba(var(--custom-warning), <alpha-value>)",
            }
        },
    },

    plugins: [forms, typography],
};
