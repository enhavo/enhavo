const ContainerBuilder = require('@enhavo/dependency-injection/container/ContainerBuilder');
const Definition = require('@enhavo/dependency-injection/container/Definition');
const Call = require('@enhavo/dependency-injection/container/Call');
const Argument = require('@enhavo/dependency-injection/container/Argument');
const CompilerPass = require('@enhavo/dependency-injection/container/CompilerPass');
const Tag = require('@enhavo/dependency-injection/container/Tag');
const FileLoadException = require('@enhavo/dependency-injection/exception/FileLoadException');
const glob = require('glob');
const path = require('path');
const YAML = require('yaml');
const _ = require('lodash');
const fs = require('fs');

class Loader
{
    constructor() {
        this.loadedFiles = [];
    }

    _addLoadedFile(file, builder) {
        builder.addFile(file);
        this.loadedFiles.push(file);
    }

    _isFileAlreadyLoaded(file) {
        return this.loadedFiles.indexOf(file) >= 0;
    }

    /**
     * @param {string|object} content
     * @param {string|null} format
     * @param {ContainerBuilder} builder
     * @param {string} cwd
     * @param {object} options
     */
    load(content, format, builder, cwd, options =  {}) {
        let data = this._normalizeData(content, format);
        options = this._configureOptions(options);

        this._loadImports(data, builder, cwd, options);

        if (options.loadDefinition) {
            this._addDefinitions(data, builder, cwd);
        }

        if (options.loadCompilerPasses) {
            this._addCompilerPass(data, builder, cwd);
        }

        return this;
    }

    loadFile(filepath, builder, options =  {}) {
        let files = glob.sync(filepath);
        for (let file of files) {
            if(this._isFileAlreadyLoaded()) {
                continue;
            }
            let extension = path.extname(file);
            if (extension === '.yaml' || extension === '.yml') {
                let content = fs.readFileSync(file)+'';
                try {
                    this.load(content, 'yaml', builder, path.dirname(file), options);
                } catch (e) {
                    throw new FileLoadException('Can\'t load file "'+file+'". Error: ' + e)
                }

            }
            this._addLoadedFile(file, builder);
        }
        return this;
    }

    getLoadedFiles() {
        return this.loadedFiles;
    }

    _normalizeData(content, format)
    {
        if(typeof content === 'object') {
            return content;
        } else if (format === 'yaml') {
            return YAML.parse(content);
        } else if(format === 'json') {
            return JSON.parse(content);
        }

        throw 'format not supported';
    }

    _configureOptions(options) {
        options = _.merge(new Configuration(), options);
        return options;
    }

    _loadImports(data, builder, cwd, options) {
        if (data && data.imports && data.imports.length > 0) {
            for (let importData of data.imports) {
                let filepath = this._buildFilepath(importData.path, cwd);
                if (filepath === null) {
                    throw 'Can\'t find file "'+importData.path+'"'
                }
                this.loadFile(filepath, builder, options);
            }
        }
    }

    _buildFilepath(filepath, cwd, keepModulePath = false) {
        if (filepath.startsWith('.')) {
            return path.resolve(cwd, filepath)
        } else if(filepath.startsWith('/')) {
            return filepath;
        } else if(!keepModulePath) {
            let subPath = cwd;
            let beforePath = null;
            while(subPath !== beforePath) {
                let file = path.resolve(subPath, './node_modules');
                if (fs.existsSync(file)) {
                    let resolved = path.resolve(subPath, './node_modules', filepath);
                    if (!fs.existsSync(resolved)) {
                        if (this._checkGlobDirExists(resolved)) {
                            return resolved;
                        }
                    } else {
                        return resolved;
                    }
                }
                beforePath = subPath;
                subPath = path.resolve(subPath, '..');
            }
            return null;
        }
        return filepath;
    }

    _checkGlobDirExists(path) {
        let parts = path.split('/');
        let index = 0;
        let part = parts[index++];
        let cutPath;

        while (part.indexOf('*') === -1) {
            part = parts[index++];
            if (!part) {
                break;
            }
            cutPath = path.substr(0, path.indexOf(part));
        }

        path = cutPath || path;

        return fs.existsSync(path);
    }

    /**
     * @param {object} data
     * @param {ContainerBuilder} builder
     * @param {string} cwd
     */
    _addDefinitions(data, builder, cwd) {
        if (data && data.services) {
            for (let name in data.services) {
                if (!data.services.hasOwnProperty(name)) {
                    continue;
                }

                let service = data.services[name];
                if(service === null) {
                    service = {};
                }

                let definition = new Definition(name);
                this._checkArguments(service, definition);
                this._checkTags(service, definition);
                this._checkCalls(service, definition);
                this._checkSettings(service, definition, cwd);

                builder.addDefinition(definition);
            }
        }
    }

    /**
     * @param {object} service
     * @param {Definition} definition
     */
    _checkArguments(service, definition) {
        if (service.arguments) {
            for (let argument of service.arguments) {
                definition.addArgument(new Argument(argument));
            }
        }
    }

    /**
     * @param {object} service
     * @param {Definition} definition
     */
    _checkTags(service, definition) {
        if (service.tags) {
            for (let tag of service.tags) {
                if(typeof tag === 'string') {
                    definition.addTag(new Tag(tag));
                } else if(tag.name) {
                    definition.addTag(new Tag(tag.name, tag));
                }
            }
        }
    }

    /**
     * @param {object} service
     * @param {Definition} definition
     * @param {string} cwd
     */
    _checkSettings(service, definition, cwd) {
        service.from ? definition.setFrom(this._buildFilepath(service.from, cwd, true)) : null;
        service.import ? definition.setImport(service.import) : null;
        service.init ? definition.setInit(service.init) : null;
        service.mode ? definition.setMode(service.mode) : null;
        service.prefetch ? definition.setPrefetch(service.mode) : null;
        service.preload ? definition.setPreload(service.mode) : null;
        service.chunk ? definition.setChunkName(service.chunk) : null;
        service.include ? definition.setInclude(service.include) : null;
        service.exclude ? definition.setExclude(service.exclude) : null;
        service.exports ? definition.setExports(service.exports) : null;
        service.static ? definition.setStatic(service.static) : null;
        service.ignore ? definition.setIgnore(service.ignore) : null;
    }

    /**
     * @param {object} service
     * @param {Definition} definition
     * @param {string} cwd
     */
    _checkCalls(service, definition) {
        if (service.calls) {
            for (let call of service.calls) {
                let argumentList = [];
                if (call.length > 1) {
                    for (let value of call[1]) {
                        argumentList.push(new Argument(value));
                    }
                }
                definition.addCall(new Call(call[0], argumentList));
            }
        }
    }

    /**
     * @param {object} data
     * @param {ContainerBuilder} builder
     * @param {string} cwd
     */
    _addCompilerPass(data, builder, cwd) {
        if (data && data.compiler_pass) {
            for (let name in data.compiler_pass) {
                if (!data.compiler_pass.hasOwnProperty(name)) {
                    break;
                }

                let compilerPassConfig = data.compiler_pass[name];
                if(compilerPassConfig === null) {
                    compilerPassConfig = {};
                }

                let priority = typeof compilerPassConfig.priority !== 'undefined' ?  compilerPassConfig.priority : 100;
                let compilerPass = new CompilerPass(name, this._buildFilepath(compilerPassConfig.path, cwd), cwd, priority, compilerPassConfig);
                builder.addCompilerPass(compilerPass);
            }
        }
    }
}

class Configuration
{
    constructor() {
        this.loadImports = true;
        this.loadDefinition = true;
        this.loadCompilerPasses = true;
    }
}

module.exports = Loader;
