const Argument = require("@enhavo/dependency-injection/container/Argument");
const Call = require("@enhavo/dependency-injection/container/Call");
const Definition = require("@enhavo/dependency-injection/container/Definition");
const Tag = require("@enhavo/dependency-injection/container/Tag");
const path = require("path");
const fs = require('fs');

/**
 * @param {string} value
 */
function toSnakeCase(value)
{
    value = value.replace( /([A-Z])/g, " $1" ).trim();
    value = value.replace(' ', '_').toLowerCase();
    return value;
}

/**
 * @param {string} key
 * @param {string} filepath
 * @param {string} definitionName
 * @param {ContainerBuilder} builder
 */
function addControllerToBuilder(key, filepath, definitionName, builder)
{
    let definition = new Definition(definitionName);
    definition.setStatic(true);
    definition.setFrom(filepath);
    definition.addTag(new Tag('stimulus.controller', {key: key}))
    builder.addDefinition(definition);
}

/**
 * @param {ContainerBuilder} builder
 * @param {object} options
 */
module.exports = function(builder, options, context)
{
    let controllerPath = path.resolve(context, options.dir);
    let regEx = options.regex ? options.regex : /.*Controller\./;
    let prefix = options.prefix ? options.prefix : 'controller/';
    let matchedFiles = [];

    if (fs.existsSync(controllerPath)) {
        let files = fs.readdirSync(controllerPath);
        for (let file of files) {
            if (file.match(regEx)) {
                matchedFiles.push(file);
            }
        }
    }

    for (let file of matchedFiles) {
        let filename = path.parse(file).name;
        let key = toSnakeCase(filename.replace('Controller', ''));
        let definitionName = prefix + filename;
        let filepath = path.resolve(controllerPath, file);
        addControllerToBuilder(key, filepath, definitionName, builder);
    }
};
