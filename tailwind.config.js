import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./resources/js/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Klavika", "Arial", "Helvetica", "sans-serif"],
            },
            // Map “normal” to 500 so typical text utilities look correct with your set
            fontWeight: {
                light: "300",
                normal: "500",   // <- no upright 400 available; use 500 as the baseline
                medium: "500",
                semibold: "600",
                bold: "700",
            },
        },
    },

    plugins: [forms],
};
