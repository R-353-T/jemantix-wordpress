import fs from 'node:fs';
import path from 'node:path';
import crypto from 'node:crypto';

/**
 * @param {string} filePath
 * @returns {string}
 */
export function fileMd5(filePath) {
    const md5 = crypto.createHash('md5');
    const input = fs.readFileSync(filePath);
    md5.update(input);
    return md5.digest('hex');
}

/**
 * @param {string} directoryPath
 * @param {string|null} root
 */
export function scanDirMd5(directoryPath, root = null) {
    let result = {};
    const elArray = fs.readdirSync(directoryPath);

    if (root === null) {
        root = directoryPath;
    }

    for (const el of elArray) {
        const absPath = path.join(directoryPath, el);
        const elInfos = {
            absPath,
            relPath: path.join(directoryPath, el).replace(root, ''),
            stats: fs.lstatSync(absPath),
            md5: undefined
        };

        if (elInfos.stats.isDirectory()) {
            result[elInfos.relPath] = elInfos;
            result = Object.assign(
                result,
                scanDirMd5(elInfos.absPath, root)
            );
        } else {
            elInfos.md5 = fileMd5(elInfos.absPath);
            result[elInfos.relPath] = elInfos;
        }
    }

    return result;
}

/**
 * @param {*} inputObj
 * @param {*} outputObj
 */
export function diffScans(inputObj, outputObj) {
    const result = {
        updates: {},
        disposables: {}
    };

    for (const inputKey in inputObj) {
        if (inputKey in outputObj) {
            if ('md5' in inputObj[inputKey] &&
                inputObj[inputKey].md5 !== outputObj[inputKey].md5) {
                result.updates[inputKey] = inputObj[inputKey];
            }
        } else {
            result.updates[inputKey] = inputObj[inputKey];
        }
    }

    for (const outputKey in outputObj) {
        if ((outputKey in inputObj) === false) {
            result.disposables[outputKey] = outputObj[outputKey];
        }
    }

    return result;
}
