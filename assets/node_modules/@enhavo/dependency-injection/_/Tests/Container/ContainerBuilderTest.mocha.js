const assert = require("chai").assert;
const ContainerBuilder = require("@enhavo/dependency-injection/Container/ContainerBuilder");
const Definition = require("@enhavo/dependency-injection/Container/Definition");

describe('dependency-injection/Container/ContainerBuilder', () => {
    describe('test definition functions', () => {
        it('should return ', () => {
            let builder = new ContainerBuilder();

            let definitionOne = new Definition('test');
            builder.addDefinition(definitionOne);
            assert.isTrue(builder.getDefinition('test') === definitionOne);

            let definitionTwo = new Definition('something');
            builder.addDefinition(definitionTwo);
            assert.isTrue(builder.getDefinition('something') === definitionTwo);

            let definitionThree = new Definition('test');
            builder.addDefinition(definitionThree);
            assert.isTrue(builder.getDefinition('test') === definitionThree);
        });
    });
});
