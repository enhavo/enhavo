const EnhavoEncore = require('./assets/node_modules/@enhavo/core/EnhavoEncore');
const AppTestPackage = require('./assets/node_modules/@enhavo/app/Encore/EncoreTestRegistryPackage');

EnhavoEncore.register(new AppTestPackage());

EnhavoEncore.add('test', (Encore) => {
    Encore.addEntry('core/Tests/RegistryTest', './assets/node_modules/@enhavo/core/Tests/RegistryTest.ts')
});

module.exports = EnhavoEncore.export();
