import Definition from "@enhavo/dependency-injection/container/Definition.js"
import Tag from "@enhavo/dependency-injection/container/Tag.js"
import path from "path"
import fs from 'fs'

/**
 * @param {string} value
 */
function toKebapCase(value)
{
    value = value.replace( /([A-Z])/g, " $1" ).trim();
    value = value.replace(/\s/g, '-').toLowerCase();
    return value;
}

/**
 * @param {string} key
 * @param {string} filepath
 * @param {string} definitionName
 * @param {ContainerBuilder} builder
 */
function addControllerToBuilder(key, filepath, definitionName, builder, chunkName)
{
    let definition = new Definition(definitionName);
    definition.setStatic(true);
    definition.setFrom(filepath);
    definition.addTag(new Tag('stimulus.controller', {key: key}))
    if (chunkName) {
        definition.setChunkName(chunkName)
    }
    builder.addDefinition(definition);
}

function walk(dir, regEx) {
    let results = [];
    let list = fs.readdirSync(dir);
    list.forEach(function (file) {
        file = dir + '/' + file;
        var stat = fs.statSync(file);
        if (stat && stat.isDirectory()) {
            results = results.concat(walk(file));
        } else {
            if (file.match(regEx)) {
                results.push(file);
            }
        }
    });

    return results;
};

/**
 * @param {ContainerBuilder} builder
 * @param {object} options
 */
export default function(builder, options, context)
{
    let controllerPath = path.resolve(context, options.dir);
    let regEx = options.regex ? options.regex : /.*Controller\./;
    let prefix = options.prefix ? options.prefix : 'controller/';
    let chunkName = options.chunkName ? options.chunkName : null;
    let matchedFiles = walk(controllerPath, regEx);

    for (let file of matchedFiles) {
        let filename = path.parse(file).name;
        let key = toKebapCase(filename.replace('Controller', ''));
        let definitionName = prefix + filename;
        let filepath = path.resolve(controllerPath, file);
        addControllerToBuilder(key, filepath, definitionName, builder, chunkName);
    }
};
