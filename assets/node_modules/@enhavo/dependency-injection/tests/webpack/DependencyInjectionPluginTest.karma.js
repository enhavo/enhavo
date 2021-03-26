import {assert} from "chai";
import Container from "@enhavo/dependency-injection"

describe('dependency-injection/Webpack/DependencyInjectionPlugin', () => {
    describe('test load', () => {
        it('should return a compiled container with services in it', async () => {
            let service = await Container.get('@enhavo/dependency-injection/tests/mock/TestDependService');
            assert.equal('object', typeof service.getService());
            assert.equal('foobar', service.getService().getName());
        });
    });
});
