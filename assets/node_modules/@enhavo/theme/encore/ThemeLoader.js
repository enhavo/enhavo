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

    getThemes()
    {
        this._loadThemes();
        return this.themes;
    }

    getTheme(name)
    {
        this._loadThemes();
        for(let theme of this.themes) {
            if(theme.key === name) {
                return theme;
            }
        }
        return null;
    }
    
    _loadThemes()
    {
        if(this.themes === null) {
            this.themes = [];

            this._searchFile(EncoreUtil.getProjectDir() + '/assets/theme', 'manifest.yml');
            this._searchFile(EncoreUtil.getProjectDir() + '/assets/theme', 'manifest.yaml');

            for(let file of this.manifestFiles) {
                this.themes.push(this._loadFile(file));
            }
        }
    }
    
    _loadFile(file)
    {
        let manifest = yaml.safeLoad(fs.readFileSync(file, 'utf8'));
        return {
            key: manifest.key,
            path: path.dirname(file)
        };
    }

    _searchFile(searchPath, filter) {
        if (!fs.existsSync(searchPath)){
            return;
        }

        let files = fs.readdirSync(searchPath);
        for (let i = 0; i < files.length;i++){
            let filename = path.join(searchPath, files[i]);
            let stat = fs.lstatSync(filename);
            if (stat.isDirectory()){
                this._searchFile(filename,filter);
            } else if (filename.indexOf(filter) >= 0) {
                this.manifestFiles.push(filename);
            }
        }
    }
}

module.exports = new ThemeLoader;