const Definition = require("@enhavo/dependency-injection/Container/Definition");
const CompilerPass = require("@enhavo/dependency-injection/Container/CompilerPass");
const Entrypoint = require("@enhavo/dependency-injection/Container/Entrypoint");
const Map = require("@enhavo/dependency-injection/Container/Map");
const fs = require("fs");

class ContainerBuilder
{
    constructor() {
        /** @type {Map<Definition>} */
        this.definitions = new Map();
        /** @type {Map<Entrypoint>} */
        this.entrypoints = new Map();
        /** @type {Map<CompilerPass>} */
        this.compilerPasses = new Map();
    }

    /**
     * @param {string} name
     * @returns {Array<Definition>}
     */
    getDefinitionByTagName(name) {
        let definitions = [];
        for (let definition of this.definitions.getValues()) {
            if(definition.hasTag(name)) {
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
     * @param {Entrypoint} entrypoint
     */
    addEntrypoint(entrypoint) {
        this.entrypoints.add(entrypoint.getName(), entrypoint);
    }

    getEntrypoints() {
        return this.entrypoints.getValues();
    }

    /**
     * @param {CompilerPass} compilerPass
     */
    addCompilerPass(compilerPass) {
        this.compilerPasses.add(compilerPass.getName(), compilerPass);
    }

    getCompilerPasses() {
        return this.compilerPasses.getValues();
    }

    prepare() {
        for (let compilerPass of this.getCompilerPasses()) {
            let content = fs.readFileSync(compilerPass.path)+'';
            let m = new module.constructor();
            m.paths = module.paths;
            m._compile(content, compilerPass.path);
            m.exports(this);
        }
    }
}

module.exports = ContainerBuilder;
