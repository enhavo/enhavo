const YamlLoader = require('@enhavo/dependency-injection/Loader/YamlLoader');
const Compiler = require('@enhavo/dependency-injection/Compiler/Compiler');
const builder = require('@enhavo/dependency-injection/builder');

module.exports = function(source) {
    let containerBuilder = YamlLoader.load(source, builder);
    let compiler = new Compiler;
    return compiler.compile(containerBuilder);
};
