const path = require('path');
const fs = require('fs');
const CopyWebpackPlugin = require('copy-webpack-plugin');

class EnhavoEncore
{
    getWebpackConfig(config)
    {
        const projectDir = this.getProjectDir();

        config.plugins.push(new CopyWebpackPlugin([
            { from: 'node_modules/tinymce/skins', to: 'enhavo/skins' },
            { from: 'node_modules/tinymce/plugins', to: 'enhavo/plugins' }
        ]));

        config.module.rules.forEach(function(rule) {
            if(".ts".match(rule.test)) {
                delete rule.exclude;
                rule.use.forEach(function(loader) {
                    if(loader.loader == 'ts-loader') {
                        loader.options.allowTsInNodeModules = true;
                        loader.options.transpileOnly = true;
                    }
                });
            }
            if(".scss".match(rule.test)) {
                rule.use.forEach(function(loader) {
                    if(loader.loader == 'sass-loader') {
                        loader.options.data = '@import "custom";';
                        loader.options.includePaths = [path.join(projectDir, 'assets/enhavo/styles')];
                    }
                });
            }
        });

        config.resolve.alias['jquery'] = path.join(projectDir, 'node_modules/jquery/src/jquery');
        config.resolve.alias['jquery-ui/ui/widget'] = path.join(projectDir, 'node_modules/blueimp-file-upload/js/vendor/jquery.ui.widget.js');
        return config;
    }

    getProjectDir()
    {
        const pathArray = path.dirname(__dirname).split(path.sep);
        while(pathArray.length > 0) {
            var tryPath = pathArray.join(path.sep) + path.sep + 'webpack.config.js';
            if(fs.existsSync(tryPath)) {
                return pathArray.join(path.sep);
            }
            pathArray.pop();
        }
        return null;
    }
}

module.exports = new EnhavoEncore;