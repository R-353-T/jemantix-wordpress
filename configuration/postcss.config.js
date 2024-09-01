/**
 * @type {import('postcss').ProcessOptions}
 */
const configuration = {
    plugins: {
        'postcss-import': {},
        'tailwindcss/nesting': 'postcss-nesting',
        autoprefixer: {},
        cssnano: {},
        tailwindcss: { config: './configuration/tailwind.config.js' }
    }
};

export default configuration;
