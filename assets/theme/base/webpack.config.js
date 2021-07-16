const EnhavoEncore = require('@enhavo/core/EnhavoEncore');
const AppThemePackage = require('@enhavo/app/encore/AppThemePackage');
const ThemePackage = require('@enhavo/theme/encore/ThemePackage');
const ThemeLoader = require('@enhavo/theme/encore/ThemeLoader');

EnhavoEncore.add(
    'base',
    [ new AppThemePackage({  themePath: __dirname }), new ThemePackage(ThemeLoader)],
    Encore => {
        Encore
            .addEntry('base', './base')
            .addEntry('form', './form')
            .enableVueLoader()

    },
    config => {}
);

module.exports = EnhavoEncore.export();
