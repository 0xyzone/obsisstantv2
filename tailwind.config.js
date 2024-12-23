/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/guava/filament-knowledge-base/src/**/*.php",
        "./vendor/guava/filament-knowledge-base/resources/**/*.blade.php",
        './vendor/bezhansalleh/filament-panel-switch/resources/views/panel-switch-menu.blade.php'
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
