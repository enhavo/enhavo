const Compiler = require('@enhavo/dependency-injection/compiler/Compiler');
const ContainerBuilder = require('@enhavo/dependency-injection/container/ContainerBuilder');
const Loader = require('@enhavo/dependency-injection/loader/Loader');
const loaderUtils = require('loader-utils');

module.exports = function(source, map, meta) {
    let callback = this.async();

    const options = loaderUtils.getOptions(this);
    const path = this.resourcePath;

    let builder = new ContainerBuilder;
    let loader = new Loader();
    loader.loadFile(path, builder)

    for (let loadedFile of loader.getLoadedFiles()) {
        this.addDependency(loadedFile);
    }

    builder.prepare();
    let compiler = new Compiler;
    let resultData = compiler.compile(builder);

    callback(null, resultData);
};
