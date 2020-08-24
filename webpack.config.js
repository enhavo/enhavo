const EnhavoEncore = require('./assets/node_modules/@enhavo/core/EnhavoEncore');
const EnhavoThemeEncore = require('./assets/node_modules/@enhavo/theme/Encore/EnhavoThemeEncore');
const ThemeLoader = require('./assets/node_modules/@enhavo/theme/Encore/ThemeLoader');
const AppPackage = require('./assets/node_modules/@enhavo/app/Encore/EncoreRegistryPackage');
const FormPackage = require('./assets/node_modules/@enhavo/form/Encore/EncoreRegistryPackage');

EnhavoEncore
    // register packages
     .register(new AppPackage({copyThemeImages: false}))
     .register(new FormPackage({copyThemeImages: false}))
;

EnhavoEncore.add('enhavo', (Encore) => {
}, (config) => {
    config.watchOptions = {
        ignored: [
            /node_modules([\\]+|\/)+(?!@enhavo)/,
        ]
    }
});

EnhavoThemeEncore.addThemes(EnhavoEncore, ThemeLoader);

module.exports = EnhavoEncore.export();
