const Definition = require("./Definition");
const ContainerBuilder = require("./ContainerBuilder");
const Reference = require("./Reference");

class Compiler
{
    /**
     * @param {ContainerBuilder} builder
     * @returns {string}
     */
    compile(builder)
    {
        let content = '';
        content += this.generateImportStatements(builder) + `\n`;

        content += `import {Container} from '@enhavo/core/DependencyInjection/Container'\n`;
        content += `class CompiledContainer extends Container {\n`;
        content += this.generateServiceGetters(builder) + `\n\n`;
        content += `}\n\n`;
        content += `let container = new CompiledContainer;`;
        content += `export default container;`;

        return content;
    }

    /**
     * @param {ContainerBuilder} builder
     * @returns {string}
     */
    generateImportStatements(builder)
    {
        let content = '';
        for(let definition of builder.getDefinitions()) {
            content += this.generateImportStatement(definition) + `\n`;
        }
        return content;
    }

    /**
     * @param {Definition} definition
     * @returns {string}
     */
    generateImportStatement(definition)
    {
        if (definition.getImport()) {
            return `import {`+definition.getImport()+`} from "`+definition.getFrom()+`"\n`;
        }
        return `import `+definition.getName()+` from "`+definition.getFrom()+`"\n`;
    }

    /**
     * @param {ContainerBuilder} builder
     * @returns {string}
     */
    generateServiceGetters(builder)
    {
        let content = '';
        for(let definition of builder.getDefinitions()) {
            content += this.generateServiceGetter(definition)+`\n`;
        }
        return content;
    }

    /**
     * @param {Definition} definition
     * @returns {string}
     */
    generateServiceGetter(definition)
    {
        let content = '';
        content += `get`+definition.getName()+`() {\n`;
        content += `return new `+(definition.getImport() ? definition.getImport() : definition.getName())+`(\n`;

        for (let argument of definition.getArguments()) {
            if(argument instanceof Reference) {
                content +=  `this.get("`+argument.getName()+`")\n`;
            }
        }

        content +=  `);\n`;
        content +=  `}\n`;
        return content;
    }
}

module.exports = Compiler;