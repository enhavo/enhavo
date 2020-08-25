const glob = require('glob');
const EncoreUtil = require('./EncoreUtil');

class TestFinder
{
    /**
     * @param {string} path
     * @return {Array<File>}
     */
    static find(path)
    {
        let projectPath = EncoreUtil.getProjectDir() + '/';
        let files = [];
        if (typeof path  === 'object') {
            for (let value of path) {
                TestFinder.addFiles(files, glob.sync(projectPath + value));
            }
        } else {
            TestFinder.addFiles(files, glob.sync(projectPath + path));
        }

        let data = [];
        for (let file of files) {
            data.push(new File(
                file.substring(projectPath.length),
                file
            ));
        }

        return data;
    }

    /**
     * @param {Array<string>} files
     * @param {Array<string>} additionalFiles
     */
    static addFiles(files , additionalFiles)
    {
        for (let file of additionalFiles) {
            if(files.indexOf(file) === -1) {
                files.push(file);
            }
        }
    }
}

class File
{
    constructor(relativePath, absolutePath) {
        this.relativePath = relativePath;
        this.absolutePath = absolutePath;
    }
}

module.exports = TestFinder;