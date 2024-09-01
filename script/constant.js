import path from 'node:path';
import url from 'node:url';

export const ROOT_DIRECTORY = path.dirname(path.dirname(url.fileURLToPath(import.meta.url)));

export const DIST_DIRECTORY = path.join(ROOT_DIRECTORY, 'dist', 'wp-content', 'themes', 'jemantix');

export const SOURCE_DIRECTORY = path.join(ROOT_DIRECTORY, 'source');

export const COMMANDS = [
    {
        extensions: ['.js'],
        command: 'npm run js:lint && npm run webpack'
    },
    {
        extensions: ['.php'],
        command: 'npm run php:lint'
    },
    {
        extensions: ['.php', '.js', '.scss'],
        command: 'npm run postcss'
    }
];
