import ContainerBuilder from "@enhavo/dependency-injection/container/ContainerBuilder"
import Loader from "@enhavo/dependency-injection/loader/Loader"
const assert = require("chai").assert;

describe('dependency-injection/Loader/Loader', () => {
    describe('test imports', () => {
        it('should return imported definitions', () => {
            let builder = new ContainerBuilder;
            let loader = new Loader();

            loader.load({
                'imports': [{
                    'path': '../fixtures/services/*'
                }]
            }, null, builder, __dirname);

            assert.equal('@enhavo/dependency-injection/tests/mock/TestService', builder.getDefinition('@enhavo/dependency-injection/tests/mock/TestService').getName());
        });
    });

    describe('test load further files', () => {
        it('should return correct definition', () => {
            let builder = new ContainerBuilder;
            let loader = new Loader();

            loader.load({
                'services': {
                    'test': {
                        arguments: ['@dependency'],
                        tags: ['foo', {name: 'bar', parameterOne: 'something'}]
                    }
                }
            }, null, builder, '/test');

            let definition = builder.getDefinition('test');
            assert.equal('test', definition.getName());
            assert.equal('@dependency', definition.getArgument(0).getValue());
            assert.isTrue(definition.hasTag('foo'));
            assert.equal('something', definition.getTag('bar').getParameter('parameterOne'));
        });
    });
});
