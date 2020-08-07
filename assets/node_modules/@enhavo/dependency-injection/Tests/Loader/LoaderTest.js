import ContainerBuilder from "@enhavo/dependency-injection/Container/ContainerBuilder";
import Loader from "@enhavo/dependency-injection/Loader/Loader";
import {assert} from "chai";

class FileSystemMock
{
    constructor() {
        this._file = null;
    }

    readFileSync(file) {
        this._file = file;
    }

    existsSync() {
        return false;
    }

    getFile() {
        return this._file;
    }
}

describe('dependency-injection/Loader/Loader', () => {
    describe('test imports', () => {
        it('should return imported definitions', () => {
            let builder = new ContainerBuilder;
            let fileSystemMock = new FileSystemMock();
            let loader = new Loader(fileSystemMock);

            loader.load({
                'imports': [{
                    'path': './pathToSomeFile.yaml'
                }]
            }, null, builder, '/test');

            assert.equal('/test/pathToSomeFile.yaml', fileSystemMock.getFile());
        });
    });

    describe('test load further files', () => {
        it('should return correct definition', () => {
            let builder = new ContainerBuilder;
            let fileSystemMock = new FileSystemMock();
            let loader = new Loader(fileSystemMock);

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
            assert.equal('dependency', definition.getArgument(0).getName());
            assert.isTrue(definition.hasTag('foo'));
            assert.equal('something', definition.getTag('bar').getParameter('parameterOne'));
        });
    });
});
