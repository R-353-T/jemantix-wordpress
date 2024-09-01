import path from 'node:path';
import url from 'node:url';

const rootDirectory = path.dirname(path.dirname(url.fileURLToPath(import.meta.url)));
const distDirectory = path.join(rootDirectory, 'dist', 'wp-content', 'themes', 'jemantix');
const entry = path.join(rootDirectory, 'source', 'index.js');

/**
 * @type {import('webpack').Configuration}
 */
const webpackDefault = {
    mode: 'production',
    resolve: { extensions: ['.js'] },
    entry,
    output: {
        path: distDirectory,
        filename: 'index.min.js'
    }
};

export default webpackDefault;
