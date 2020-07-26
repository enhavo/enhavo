module.exports = function(config) {
    config.set({
        frameworks: ['mocha', 'chai'],
        files: ['public/build/test/**/*.js'],
        reporters: ['progress'],
        port: 9876,
        colors: true,
        logLevel: config.LOG_INFO,
        browsers: ['ChromeHeadless'],
        autoWatch: false,
        concurrency: Infinity
    })
};
