import Definition from "@enhavo/dependency-injection/container/Definition.js";
import CompilerPass from "@enhavo/dependency-injection/container/CompilerPass.js"
import Map from "@enhavo/dependency-injection/container/Map.js"
import fs from "fs";

export default class ContainerBuilder
{
    _prepared = false;

    constructor() {
        /** @type {Map<Definition>} */
        this.definitions = new Map();
        /** @type {Map<CompilerPass>} */
        this.compilerPasses = new Map();
        /** @type {Map<Object|String|Number>} */
        this.parameters = new Map();

        this.files = [];
    }

    addFile(file) {
        if (this._prepared) {
            throw 'Can\'t add file to prepared builder';
        }

        this.files.push(file);
    }

    getFiles() {
        return this.files;
    }

    /**
     * @param {string} name
     * @returns {Array<Definition>}
     */
    getDefinitionsByTagName(name) {
        let definitions = [];
        for (let definition of this.definitions.getValues()) {
            if(definition.hasTag(name)) {
                definitions.push(definition);
            }
        }
        return definitions;
    }

    /**
     * @returns {Array<Definition>}
     */
    getInitDefinitions() {
        let definitions = [];
        for (let definition of this.definitions.getValues()) {
            if(definition.isInit()) {
                definitions.push(definition);
            }
        }
        return definitions;
    }

    /**
     * @param {Definition} definition
     */
    addDefinition(definition) {
        if (this._prepared) {
            throw 'Can\'t add definition to prepared builder';
        }
        this.definitions.add(definition.getName(), definition);
    }

    /**
     * @returns {Array<Definition>}
     */
    getDefinitions() {
        return this.definitions.getValues();
    }

    /**
     * @param name
     * @returns {Definition}
     */
    getDefinition(name) {
        return this.definitions.get(name);
    }

    /**
     * @param name
     * @returns {Definition}
     */
    hasDefinition(name) {
        return this.definitions.has(name);
    }

    /**
     * @param {CompilerPass} compilerPass
     */
    addCompilerPass(compilerPass) {
        if (this._prepared) {
            throw 'Can\'t add compiler pass to prepared builder';
        }
        this.compilerPasses.add(compilerPass.getName(), compilerPass);
    }

    /**
     * @return {Array<CompilerPass>}
     */
    getCompilerPasses() {
        return this.compilerPasses.getValues();
    }

    async prepare() {
        if (this._prepared) {
            return;
        }

        let compilers = this.getCompilerPasses().sort((a, b) => {
            return b.priority - a.priority;
        });

        for (let compilerPass of compilers) {
            try {
                let instance = await import(compilerPass.path);
                instance.default(this, compilerPass.getOptions(), compilerPass.getContext());
            } catch (e) {
                throw 'Error occurred while using compiler pass "'+compilerPass.path+'" with error: ' + e + "\n" + e.stack;
            }
        }

        this._prepared = true;
    }

    addParameters(parameters) {
        if (this._prepared) {
            throw 'Can\'t add parameter to prepared builder';
        }
        for (let key in parameters) {
            this.setParameter(key, parameters[key]);
        }
    }

    setParameter(key, value) {
        if (this._prepared) {
            throw 'Can\'t add parameter to prepared builder';
        }
        this.parameters.add(key, value);
    }

    getParameterKeys() {
        return this.parameters.getKeys();
    }

    getParameter(key) {
        return this.parameters.get(key);
    }

    hasParameter(key) {
        return this.parameters.has(key);
    }

    reset() {
        this._prepared = false;
        this.definitions = new Map();
        this.compilerPasses = new Map();
        this.parameters = new Map();
        this.files = [];
    }

    isPrepared() {
        return this._prepared
    }
}
