const Argument = require("@enhavo/dependency-injection/container/Argument");
const Call = require("@enhavo/dependency-injection/container/Call");
const Definition = require("@enhavo/dependency-injection/container/Definition");
const fs = require('fs');
const path = require("path");

const walk = function(dir) {
    let results = [];
    let list = fs.readdirSync(dir);
    list.forEach(function (file) {
        if (file.endsWith('.vue')) {
            file = dir + '/' + file;
            var stat = fs.statSync(file);
            if (stat && stat.isDirectory()) {
                results = results.concat(walk(file));
            } else {
                results.push(file);
            }
        }
    });

    return results;
};

const componentNameFromPath = function(filePath) {
    let filename = path.basename(filePath, '.vue');
    return kebabize(filename);
};

const kebabize = function(str) {
    return str.split('').map((letter, idx) => {
        return letter.toUpperCase() === letter
            ? `${idx !== 0 ? '-' : ''}${letter.toLowerCase()}`
            : letter;
    }).join('');
};

module.exports = function(builder, options, context)
{
    let componentPath = path.resolve(context, options.dir);
    let files = walk(componentPath);
    for (let i in files) {
        files[i] = {
            fullPath: files[i],
            buildPath: files[i].substring(context.length + 1),
        }
    }

    let registry = builder.getDefinition('@enhavo/app/vue/VueFactory');

    for (let file of files) {
        let definition = new Definition(file.buildPath);
        definition.setStatic(true);
        definition.setFrom('./'+file.buildPath);
        builder.addDefinition(definition);

        registry.addCall(new Call('registerComponent', [
            new Argument(componentNameFromPath(file.fullPath), 'string'),
            new Argument(definition.getName()),
        ]));
    }
};
