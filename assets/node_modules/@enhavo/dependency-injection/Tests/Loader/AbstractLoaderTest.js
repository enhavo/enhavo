import {assert} from "chai";
import AbstractLoader from "@enhavo/dependency-injection/Loader/AbstractLoader"
import ContainerBuilder from "@enhavo/dependency-injection/Container/ContainerBuilder"

class ConcreteLoader extends AbstractLoader
{
    load(data, builder) {
        this._addDefinitions(data, builder);
    }
}

describe('dependency-injection/Loader/AbstractLoader', () => {
    describe('test imports', () => {
        it('should return imported definitions', () => {
            let builder = new ContainerBuilder;
            let loader = new ConcreteLoader;

            loader.load({
                'imports': [{
                    'path': 'path'
                }]
            }, builder);
        });
    });

    describe('test load further files', () => {
        it('should return correct definition', () => {
            let builder = new ContainerBuilder;
            let loader = new ConcreteLoader;

            loader.load({
                'services': {
                    'test': {
                        arguments: ['@dependency'],
                        tags: ['foo', {name: 'bar', parameterOne: 'something'}]
                    }
                }
            }, builder);

            let definition = builder.getDefinition('test');
            assert.equal('test', definition.getName());
            assert.equal('dependency', definition.getArgument(0).getName());
            assert.isTrue(definition.hasTag('foo'));
            assert.equal('something', definition.getTag('bar').getParameter('parameterOne'));
        });
    });
});
