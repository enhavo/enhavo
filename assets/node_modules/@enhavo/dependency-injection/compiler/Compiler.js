import ContainerBuilder from "@enhavo/dependency-injection/container/ContainerBuilder.js"

export default class Compiler
{
    /**
     * @param {ContainerBuilder} builder
     * @returns {string}
     */
    compile(builder)
    {
        let content = '';

        content += `import {Container} from '@enhavo/dependency-injection/container/Container'\n`;
        content += `class CompiledContainer extends Container {\n`;
        content += this._generateConstructor(builder) + `\n`;
        content += this._generateInitFunction(builder) + `\n`;
        content += this._generateServiceFunctions(builder) + `\n`;
        content += `}\n\n`;
        content += `let container = new CompiledContainer;\n`;
        content += `export default container;\n\n`;

        return content;
    }

    _generateConstructor(builder) {
        let content = '';
        content += `constructor() {\n`;
        content += `super();\n`;

        content += `this._hashes = {\n`;
        for(let definition of builder.getDefinitions()) {
            content += `'`+definition.getName()+`': '`+definition.getHash()+`',\n`
        }
        content += `};\n`;

        content += `}\n`;
        return content;
    }

    /**
     * @param {ContainerBuilder} builder
     * @returns {string}
     */
    _generateServiceFunctions(builder) {
        let content = '';
        for (let definition of builder.getDefinitions()) {
            content += `// <-- `+definition.getName()+`(`+definition.getHash()+`)\n`;
            if (definition.factory) {
                content += this._generateFactoryInstantiate(definition)+`\n`;
            } else {
                content += this._generateLoad(definition)+`\n`;
                content += this._generateGetDependencies(definition)+`\n`;
                content += this._generateGetCallDependencies(definition)+`\n`;
                content += this._generateInstantiate(definition)+`\n`;
                content += this._generateCall(definition);
            }
            content += `// `+definition.getName()+`(`+definition.getHash()+`) --/>\n\n`;
        }
        return content;
    }

    /**
     * @param {Definition} definition
     * @returns {string}
     */
    _generateFactoryInstantiate(definition) {
        let content = '';
        content += `async instantiate_`+definition.getHash()+`() {\n`;
        content += `let instance = await this.get('`+definition.getFactory()+`');\n`;

        if (definition.getFactoryMethod()) {
            content += `return instance.`+definition.getFactoryMethod()+`();\n`;
        } else {
            content += `return instance();\n`;
        }

        content += `}\n`;
        return content;
    }

    /**
     * @param {Definition} definition
     * @returns {string}
     */
    _generateLoad(definition) {
        let content = '';
        content += `async load_`+definition.getHash()+`() {\n`;
        content += `return await import(\n`;
        content += this._generateWebpackOptions(definition)+`\n`;
        content += `"`+definition.getFrom()+`");\n`;
        content += `}\n`;
        return content;
    }


    /**
     * @param {Definition} definition
     * @returns {string}
     */
    _generateWebpackOptions(definition)
    {
        let content = '';

        if(definition.getInclude() !== null) {
            content += `/* webpackInclude: `+definition.getInclude()+` */\n`;
        }

        if(definition.getExclude() !== null) {
            content += `/* webpackExclude: `+definition.getExclude()+` */\n`;
        }

        if(definition.getChunkName() !== null) {
            content += `/* webpackChunkName: "`+definition.getChunkName()+`" */\n`;
        }

        if(definition.getMode() !== null) {
            content += `/* webpackMode: "`+definition.getMode()+`" */\n`;
        }

        if(definition.getPrefetch() !== null) {
            content += `/* webpackPrefetch: `+definition.getPrefetch() ? 'true' : 'false' +` */\n`;
        }

        if(definition.getPreload() !== null) {
            content += `/* webpackPreload: `+definition.getPreload() ? 'true' : 'false' +` */\n`;
        }

        if(definition.getExports() !== null) {
            content += `/* webpackPreload: `+JSON.stringify(definition.getExports())+` */\n`;
        }

        if(definition.getIgnore() !== null) {
            content += `/* webpackPreload: `+definition.getIgnore() ? 'true' : 'false' +` */\n`;
        }

        return content;
    }

    /**
     * @param {Definition} definition
     * @returns {string}
     */
    _generateGetDependencies(definition) {
        let content = '';
        content += `async get_dependencies_`+definition.getHash()+`() {\n`;
        content += `return `+JSON.stringify(definition.getDependDefinitionNames())+`;\n`;
        content += `}\n`;
        return content;
    }

    /**
     * @param {Definition} definition
     * @returns {string}
     */
    _generateGetCallDependencies(definition) {
        let content = '';
        content += `async get_call_dependencies_`+definition.getHash()+`() {\n`;
        content += `return `+JSON.stringify(definition.getDependCallDefinitionNames())+`;\n`;
        content += `}\n`;
        return content;
    }

    /**
     * @param {Definition} definition
     * @returns {string}
     */
    _generateInstantiate(definition) {
        let content = '';
        content += `async instantiate_`+definition.getHash()+`() {\n`;
        content += `let module = this._getService('`+definition.getName()+`').module;\n`;

        let importName = definition.getImport() ? definition.getImport() : `default`;
        if(definition.isStatic()) {
            content += `return module.`+importName+`;\n`;
        } else {
            content += `return new module.`+importName+`(\n`;
            for (let argument of definition.getArguments()) {
                content += this._generateArgument(argument) + ',\n';
            }
            content += `);\n`;
        }

        content += `}\n`;
        return content;
    }

    /**
     * @param {Definition} definition
     * @returns {string}
     */
    _generateCall(definition) {
        let content = '';
        content += `async call_`+definition.getHash()+`(service) {\n`;

        for (let call of definition.getCalls()) {
            let argumentList = [];
            for (let argument of call.getArguments()) {
                argumentList.push(this._generateArgument(argument));
            }
            content += `this._call("`+call.getName()+`", service, [`+argumentList.join(',')+`]);\n`;
        }

        content += `}\n`;
        return content;
    }

    /**
     * @param {Argument} argument
     * @returns {string}
     * @private
     */
    _generateArgument(argument) {
        if(argument.getType() === 'service') {
            return `await this._getService("`+argument.getValue()+`").instance`;
        } else if (argument.getType() === 'string') {
            return `"`+argument.getValue()+`"`;
        } else if (argument.getType() === 'boolean') {
            return argument.getValue() ? 'true' : 'false';
        } else if (argument.getType() === 'number') {
            return `parseInt("`+argument.getValue()+`")`;
        } else if (argument.getType() === 'param') {
            return `this.getParameter("`+argument.getValue()+`")`;
        } else if (argument.getType() === 'container') {
            return `this`;
        } else if (argument.getType() === 'null') {
            return `null`;
        }
    }

    /**
     * @param {ContainerBuilder} builder
     * @returns {string}
     * @private
     */
    _generateInitFunction(builder) {
        let content = '';
        content += `async _init() {\n`;

        for (let service of builder.getInitDefinitions()) {
            content += `await this.get("`+service.getName()+`");\n`;
        }

        content += `}\n`;
        return content;
    }
}
