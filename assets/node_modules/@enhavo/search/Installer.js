const download = require("download");
const fs = require('fs');
const Path = require('path');

class Installer
{
    constructor(workDir, version)
    {
        this.version = version;

        if (!fs.existsSync(workDir)) {
            fs.mkdirSync(workDir, { recursive: true });
            console.log("Create dir: " + workDir);
        }

        this.workDir = workDir;
    }

    getZipPath()
    {
        return this.workDir + '/elasticsearch-' + this.version + '.zip';
    }

    getDirPath()
    {
        return this.workDir + '/elasticsearch-' + this.version;
    }

    download()
    {
        return new Promise((resolve) => {
            (async () => {
                await download('https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-' + this.version + '.zip', this.workDir, {
                    extract: true
                });
                console.log("Downloaded: " + this.getZipPath());
                resolve();
            })();
        });
    }

    install(path)
    {
        return new Promise((resolve) => {
            if(fs.existsSync(path)) {
                console.error('Path already exists. Can\'t go on with installation');
                resolve();
                return;
            }

            fs.renameSync(this.getDirPath(), path);

            resolve();
        });
    }

    cleanup()
    {
        return new Promise((resolve) => {
            if(fs.existsSync(this.getZipPath())) {
                fs.unlinkSync(this.getZipPath());
                console.log("Removed: " + this.getZipPath());
            }

            if(fs.existsSync(this.getDirPath())) {
                this.deleteFolderRecursive(this.getDirPath());
                console.log("Removed: " + this.getDirPath());
            }

            resolve();
        });
    }

    deleteFolderRecursive(path)
    {
        if (fs.existsSync(path)) {
            fs.readdirSync(path).forEach((file, index) => {
                const curPath = Path.join(path, file);
                if (fs.lstatSync(curPath).isDirectory()) { // recurse
                    this.deleteFolderRecursive(curPath);
                } else { // delete file
                    fs.unlinkSync(curPath);
                }
            });
            fs.rmdirSync(path);
        }
    }
}

module.exports = function(workDir, version = null) {
    return new Installer(workDir, version)
};