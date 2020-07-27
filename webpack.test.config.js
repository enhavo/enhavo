const EnhavoEncore = require('./assets/node_modules/@enhavo/core/EnhavoEncore');
const AppTestPackage = require('./assets/node_modules/@enhavo/app/Encore/EncoreTestRegistryPackage');
const TestFinder = require('./assets/node_modules/@enhavo/core/TestFinder');

EnhavoEncore.register(new AppTestPackage());

EnhavoEncore.add('test', (Encore) =>
{
    let files = TestFinder.find([
        'assets/node_modules/@enhavo/**/Tests/*Test.@(js|ts)',
        'assets/node_modules/@enhavo/**/Tests/**/*Test.@(js|ts)',
    ]);

    for (let file of files) {
        Encore.addEntry(file.relativePath, './' + file.relativePath);
    }
});

module.exports = EnhavoEncore.export();
