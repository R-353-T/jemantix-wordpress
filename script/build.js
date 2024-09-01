import fs from 'node:fs';
import path from 'node:path';
import { execSync } from 'node:child_process';
import { COMMANDS, DIST_DIRECTORY, SOURCE_DIRECTORY } from './constant.js';
import { diffScans, scanDirMd5 } from './utils.js';
import { exit } from 'node:process';

export const ignores = [
    "index.min.js",
    "index.min.css"
]

export function main() {
    if (fs.existsSync(DIST_DIRECTORY) === false) {
        fs.mkdirSync(DIST_DIRECTORY, { recursive: true });
    }

    const sources = scanDirMd5(SOURCE_DIRECTORY);
    const dists = scanDirMd5(DIST_DIRECTORY);
    const diffs = diffScans(sources, dists);

    const extensions = [];

    for (const update in diffs.updates) {
        const updateExtension = path.extname(update);
        if (updateExtension.length && extensions.includes(updateExtension) === false) {
            extensions.push(updateExtension);
        }
    }

    const runnables = COMMANDS.filter(command => {
        for (const ext of extensions) {
            if (command.extensions.includes(ext)) {
                return true;
            }
        }
        return false;
    }).map(command => command.command);

    for (const cmd of runnables) {
        try {
            const execResult = execSync(cmd);
        } catch (exception) {
            console.log(`[CMD ERROR][${cmd}]\n${exception.output.join('').toString()}\n[END IF CMD ERROR]`);
            exit(0);
        }
    }

    for (const update in diffs.updates) {
        const updateData = diffs.updates[update];
        const destination = path.join(DIST_DIRECTORY, update);

        if(updateData.md5 !== undefined) {
            console.log(`(+file) ${updateData.absPath} => ${destination}`);
            fs.cpSync(updateData.absPath, destination, { recursive: true });
        } else {
            console.log(`(+directory) ${updateData.absPath} => ${destination}`);
            fs.mkdirSync(destination, { recursive: true });
        }

    }

    for (const disposable in diffs.disposables) {
        if(ignores.includes(path.basename(disposable)) === false) {
            console.log(`(-dispose) ${diffs.disposables[disposable].absPath}`);
            fs.rmSync(diffs.disposables[disposable].absPath);
        }
    }

    console.log("... ended ...")
}

main();
