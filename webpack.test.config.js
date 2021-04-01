const EnhavoEncore = require('./assets/node_modules/@enhavo/core/EnhavoEncore');
const AppTestPackage = require('./assets/node_modules/@enhavo/app/encore/AppTestPackage');
const TestFinder = require('./assets/node_modules/@enhavo/core/TestFinder');

EnhavoEncore.add(
    'test',
    [
        new AppTestPackage()
    ],
    Encore => {
        let files = TestFinder.find([
            'assets/node_modules/@enhavo/**/tests/*Test.karma.@(js|ts)',
            'assets/node_modules/@enhavo/**/tests/**/*Test.karma.@(js|ts)',
        ]);

        for (let file of files) {
            Encore.addEntry(file.relativePath, './' + file.relativePath);
        }
    },
    config => {
        config.node = {
            fs: 'empty'
        };
    }
);

module.exports = EnhavoEncore.export();
