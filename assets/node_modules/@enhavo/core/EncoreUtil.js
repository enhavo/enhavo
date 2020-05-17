const path = require('path');
const fs = require('fs');

class EncoreUtil
{
    static getProjectDir()
    {
        const pathArray = path.dirname(__dirname).split(path.sep);
        while(pathArray.length > 0) {
            var tryPath = pathArray.join(path.sep) + path.sep + 'webpack.config.js';
            if(fs.existsSync(tryPath)) {
                return pathArray.join(path.sep);
            }
            pathArray.pop();
        }
        return null;
    }
}

module.exports = EncoreUtil;