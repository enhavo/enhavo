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

        this.files = [];
    }

    addFile(file) {
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

        this._prepared = true;

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
    }

    reset() {
        this.definitions = new Map();
        this.compilerPasses = new Map();
        this.files = [];
    }
}
