
const YamlLoader = require('./YamlLoader');
const Compiler = require('./Compiler');

module.exports = function(source) {
    let builder = YamlLoader.load(source);
    let compiler = new Compiler;
    return compiler.compile(builder);
};
