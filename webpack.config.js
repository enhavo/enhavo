const EnhavoEncore = require('./assets/node_modules/@enhavo/core/EnhavoEncore');
const EnhavoThemeEncore = require('./assets/node_modules/@enhavo/theme/encore/EnhavoThemeEncore');
const ThemeLoader = require('./assets/node_modules/@enhavo/theme/encore/ThemeLoader');
const AppPackage = require('./assets/node_modules/@enhavo/app/encore/AppPackage');
const AppThemePackage = require('./assets/node_modules/@enhavo/app/encore/AppThemePackage');
const FormPackage = require('./assets/node_modules/@enhavo/form/encore/FormPackage');
const ThemePackage = require('./assets/node_modules/@enhavo/theme/encore/ThemePackage');

EnhavoEncore.add(
    'enhavo',
    [
        new AppPackage(),
        new FormPackage(),
    ],
    Encore => {},
    config => {
        // Enable watch in enhavo assets/node_modules/@enhavo
        config.watchOptions = {
            ignored: [
                /node_modules([\\]+|\/)+(?!@enhavo)/,
            ]
        }
    }
);

EnhavoThemeEncore.addThemes(EnhavoEncore, ThemeLoader, [
    new AppThemePackage({
        themesPath: './assets/theme'
    }),
    new ThemePackage(ThemeLoader),
]);

module.exports = EnhavoEncore.export();
