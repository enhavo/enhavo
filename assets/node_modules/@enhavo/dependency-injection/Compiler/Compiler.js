const ContainerBuilder = require("@enhavo/dependency-injection/Container/ContainerBuilder");

class Compiler
{
    /**
     * @param {ContainerBuilder} builder
     * @returns {string}
     */
    compile(builder)
    {
        let content = '';

        content += `import {Container} from '@enhavo/dependency-injection/Container/Container'\n`;
        content += `class CompiledContainer extends Container {\n`;
        content += this._generateConstructor(builder) + `\n`;
        content += this._generateInitFunction(builder) + `\n`;
        content += this._generateServiceGetters(builder) + `\n`;
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
    _generateServiceGetters(builder) {
        let content = '';
        for(let definition of builder.getDefinitions()) {
            content += this._generateServiceGetter(definition)+`\n`;
        }
        return content;
    }

    /**
     * @param {Definition} definition
     * @returns {string}
     */
    _generateServiceGetter(definition) {
        let content = '';
        content += `async get_`+definition.getHash()+`() {\n`;
        content += `let module = await import("`+definition.getFrom()+`");\n`;

        content += `let service = `+(definition.isStatic() ? '' : 'new')+` module.`+(definition.getImport() ? definition.getImport() : `default`)+`(\n`;
        for (let argument of definition.getArguments()) {
            content += this._generateArgument(argument) + ',\n';
        }
        content +=  `);\n`;

        for (let call of definition.getCalls()) {
            let argumentList = [];
            for (let argument of call.getArguments()) {
                argumentList.push(this._generateArgument(argument));
            }
            content += `this._call(service, [`+argumentList.join(',')+`]);\n`;
        }

        content += `return service;\n`;
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
            return `await this.get("`+argument.getValue()+`")`;
        } else if (argument.getType() === 'string') {
            return `"`+argument.getValue()+`"`;
        } else if (argument.getType() === 'number') {
            return `parseInt("`+argument.getValue()+`")`;
        } else if (argument.getType() === 'param') {
            return `this.getParameter("`+argument.getValue()+`")`;
        } else if (argument.getType() === 'container') {
            return `this`;
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

module.exports = Compiler;