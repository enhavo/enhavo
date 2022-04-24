const Definition = require("@enhavo/dependency-injection/container/Definition");
const CompilerPass = require("@enhavo/dependency-injection/container/CompilerPass");
const Map = require("@enhavo/dependency-injection/container/Map");
const fs = require("fs");

class ContainerBuilder
{
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
     * @return {CompilerPass}
     */
    getCompilerPasses() {
        return this.compilerPasses.getValues();
    }

    prepare() {
        for (let compilerPass of this.getCompilerPasses()) {
            let content = fs.readFileSync(compilerPass.path)+'';
            try {
                let m = new module.constructor();
                m.paths = module.paths;
                m._compile(content, compilerPass.path);
                m.exports(this, compilerPass.getOptions());
            } catch (e) {
                throw 'Error occured while using compiler pass "'+compilerPass.path+'" with error: ' + e;
            }
        }
    }

    reset() {
        this.definitions = new Map();
        this.compilerPasses = new Map();
        this.files = [];
    }
}

module.exports = ContainerBuilder;
