const EnhavoEncore = require('./assets/node_modules/@enhavo/core/EnhavoEncore');
const EnhavoThemeEncore = require('./assets/node_modules/@enhavo/theme/Encore/EnhavoThemeEncore');
const ThemeLoader = require('./assets/node_modules/@enhavo/theme/Encore/ThemeLoader');
const AppPackage = require('./assets/node_modules/@enhavo/app/Encore/EncoreRegistryPackage');
const FormPackage = require('./assets/node_modules/@enhavo/form/Encore/EncoreRegistryPackage');
const ThemePackage = require('./assets/node_modules/@enhavo/theme/Encore/EncoreRegistryPackage');
const MediaPackage = require('./assets/node_modules/@enhavo/media/Encore/EncoreRegistryPackage');
const DashboardPackage = require('./assets/node_modules/@enhavo/dashboard/Encore/EncoreRegistryPackage');
const UserPackage = require('./assets/node_modules/@enhavo/user/Encore/EncoreRegistryPackage');
const NewsletterPackage = require('./assets/node_modules/@enhavo/newsletter/Encore/EncoreRegistryPackage');

EnhavoEncore
    // register packages
    .register(new AppPackage({copyThemeImages: false}))
    .register(new FormPackage())
    .register(new MediaPackage())
    .register(new DashboardPackage())
    .register(new UserPackage())
    .register(new ThemePackage(ThemeLoader))
    .register(new NewsletterPackage())
;

EnhavoEncore.add('enhavo', (Encore) => {
    // custom encore config
    // Encore.enableBuildNotifications();
});

EnhavoThemeEncore.addThemes(EnhavoEncore, ThemeLoader);

module.exports = EnhavoEncore.export();
