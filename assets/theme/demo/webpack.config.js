const EnhavoEncore = require('@enhavo/core/EnhavoEncore');
const AppThemePackage = require('@enhavo/app/Encore/AppThemePackage');
const ThemePackage = require('@enhavo/theme/encore/ThemePackage');
const ThemeLoader = require('@enhavo/theme/encore/ThemeLoader');
const path = require('path');

EnhavoEncore.add(
    'demo',
    [ new AppThemePackage({  themePath: __dirname }), new ThemePackage(ThemeLoader)],
    Encore => {
        Encore
            .addEntry('base', './base')

    },
    config => {}
);

module.exports = EnhavoEncore.export();
