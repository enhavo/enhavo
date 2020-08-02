const path = require('path');

class ServiceLoader
{
    static path() {
        return path.resolve(__dirname, './service-loader-function.js');
    }

    static test() {
        return /\.yaml$/
    }
}

module.exports = ServiceLoader;
