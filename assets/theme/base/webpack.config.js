const EnhavoEncore = require('@enhavo/core/EnhavoEncore');
const AppThemePackage = require('@enhavo/app/encore/AppThemePackage');
const ThemePackage = require('@enhavo/theme/encore/ThemePackage');
const ThemeLoader = require('@enhavo/theme/encore/ThemeLoader');
const ApiPackage = require('./assets/node_modules/@enhavo/api/encore/ApiPackage');

EnhavoEncore.add(
    'base',
    [
        new AppThemePackage({  themePath: __dirname }),
        new ApiPackage(),
        new ThemePackage(ThemeLoader)
    ],
    Encore => {
        Encore
            .addEntry('base', './entrypoints/base')
            .enableVueLoader()

    },
    config => {
        // Enable watch in enhavo assets/node_modules/@enhavo
        config.watchOptions = {
            ignored: /node_modules([\\]+|\/)+(?!@enhavo)/
        }
    }
);

module.exports = EnhavoEncore.export();
