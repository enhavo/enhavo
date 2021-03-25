const EncoreUtil = require('@enhavo/core/EncoreUtil');
const path = require('path');
const fs = require('fs');
const yaml = require('js-yaml');

class ThemeLoader
{
    constructor() {
        this.themes = null;
        this.manifestFiles = [];
    }

    loadThemes()
    {
        if(this.themes === null) {
            this.themes = [];

            this.searchFile(EncoreUtil.getProjectDir() + '/assets/theme', 'manifest.yml');
            this.searchFile(EncoreUtil.getProjectDir() + '/assets/theme', 'manifest.yaml');

            for(let file of this.manifestFiles) {
                this.themes.push(this.loadFile(file));
            }
        }
    }

    getThemes()
    {
        this.loadThemes();
        return this.themes;
    }

    getTheme(name)
    {
        this.loadThemes();
        for(let theme of this.themes) {
            if(theme.key === name) {
                return theme;
            }
        }
        return null;
    }

    loadFile(file)
    {
        let manifest = yaml.safeLoad(fs.readFileSync(file, 'utf8'));
        let theme = {
            entries: [],
            key: manifest.key,
            path: path.dirname(file)
        };

        let entries = manifest.webpack.entries;
        for(let key in entries) {
            if (entries.hasOwnProperty(key)) {
                theme.entries.push({
                    name: key,
                    path: path.dirname(file) + '/' + entries[key]
                })
            }
        }

        return theme;
    }

    searchFile(searchPath, filter) {
        if (!fs.existsSync(searchPath)){
            return;
        }

        let files = fs.readdirSync(searchPath);
        for(let i=0;i<files.length;i++){
            let filename = path.join(searchPath, files[i]);
            let stat = fs.lstatSync(filename);
            if (stat.isDirectory()){
                this.searchFile(filename,filter); //recurse
            }
            else if (filename.indexOf(filter)>=0) {
                this.manifestFiles.push(filename);
            }
        }
    }
}


module.exports = new ThemeLoader;