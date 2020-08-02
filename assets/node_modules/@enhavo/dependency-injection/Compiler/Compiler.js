const Definition = require("@enhavo/dependency-injection/Container/Definition");
const ContainerBuilder = require("@enhavo/dependency-injection/Container/ContainerBuilder");
const Reference = require("@enhavo/dependency-injection/Container/Reference");

class Compiler
{
    /**
     * @param {ContainerBuilder} builder
     * @returns {string}
     */
    compile(builder)
    {
        let content = '';
        // content += this.generateImportStatements(builder) + `\n`;

        content += `import {Container} from '@enhavo/dependency-injection/Container/Container'\n`;
        content += `class CompiledContainer extends Container {\n`;
        content += this.generateServiceGetters(builder) + `\n`;
        content += `}\n\n`;
        content += `let container = new CompiledContainer;\n`;
        content += `export default container;\n\n`;

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
        content += `async get`+definition.getHash()+`() {\n`;
        content += `let module = await import("`+definition.getFrom()+`")\n`;
        content += `return `+(definition.isStatic() ? '' : 'new')+` module.`+(definition.getImport() ? definition.getImport() : `default`)+`(\n`;

        for (let argument of definition.getArguments()) {
            if(argument instanceof Reference) {
                content +=  `await this.get("`+argument.getName()+`")\n`;
            }
        }

        content +=  `);\n`;
        content +=  `}\n`;
        return content;
    }
}

module.exports = Compiler;