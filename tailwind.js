module.exports = {
    mode: "jit",
    purge: {
        enabled: true,
        content: [
            './**/*.php',
            './source/**/*.vue',
            './dist/**/*.js'
        ]
    },
    theme: {
        extend: {
            colors: {
                'plugin': '#3088BF'
            }
        }
    },
    variants: {},
    plugins: [
        require('tailwind-scrollbar'),
        require('@tailwindcss/line-clamp'),
        require('@tailwindcss/custom-forms'),
    ],
}