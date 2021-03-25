const Compiler = require('@enhavo/dependency-injection/compiler/Compiler');
const builderBucket = require('@enhavo/dependency-injection/builder-bucket');
const loaderUtils = require('loader-utils');

module.exports = function(source) {
    let callback = this.async();

    let name = null;
    if (this.resourceQuery) {
        const params = loaderUtils.parseQuery(this.resourceQuery);
        name = params.name;
    }

    let builder = builderBucket.getBuilder(name);

    for (let file of builder.getFiles()) {
        this.addDependency(file);
    }

    (function(err, result, sourceMaps, meta) {
        let compiler = new Compiler;
        let resultData = compiler.compile(builder);
        callback(null, resultData, sourceMaps, meta);
    })();
};
