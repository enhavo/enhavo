import ContainerBuilder from "@enhavo/dependency-injection/container/ContainerBuilder"
import Loader from "@enhavo/dependency-injection/loader/Loader"
import { expect, test, describe } from "vitest";


describe('test imports', () => {
    test('should return imported definitions', () => {
        let builder = new ContainerBuilder;
        let loader = new Loader();

        loader.load({
            'imports': [{
                'path': '../fixtures/services/*'
            }]
        }, null, builder, __dirname);

        expect.assert.equal('@enhavo/dependency-injection/tests/mock/TestService', builder.getDefinition('@enhavo/dependency-injection/tests/mock/TestService').getName());
    });
});

describe('test load further files', () => {
    test('should return correct definition', () => {
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
        expect.assert.equal('test', definition.getName());
        expect.assert.equal('@dependency', definition.getArgument(0).getValue());
        expect.assert.isTrue(definition.hasTag('foo'));
        expect.assert.equal('something', definition.getTag('bar').getParameter('parameterOne'));
    });
});
