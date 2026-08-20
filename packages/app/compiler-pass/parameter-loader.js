import fs from "fs"
import path from "path"
import YAML from "yaml"

/**
 * @param {ContainerBuilder} builder
 * @param {object} options
 * @param {string} options.file - Path to the parameter file. Supports .yaml, .yml and .json
 * @param {string} [options.parameter] - Parameter name. If not set, the entire file content is stored as parameters.
 * @param {string} context
 */
export default function(builder, options, context)
{
    let file = options.file;
    if (!file) {
        throw 'Option "file" is required for parameter-loader compiler pass';
    }

    let filepath = file.startsWith('/') ? file : path.resolve(context, file);

    if (!fs.existsSync(filepath)) {
        throw 'Parameter file "' + filepath + '" does not exist';
    }

    let content = fs.readFileSync(filepath, 'utf8');
    let extension = path.extname(filepath);
    let data;

    if (extension === '.yaml' || extension === '.yml') {
        data = YAML.parse(content);
    } else if (extension === '.json') {
        data = JSON.parse(content);
    } else {
        throw 'Unsupported file format "' + extension + '" for parameter-loader. Use .yaml, .yml or .json';
    }

    if (typeof options.parameter === 'string') {
        builder.setParameter(options.parameter, data);
    } else if (data && typeof data === 'object') {
        builder.addParameters(data);
    }
};
