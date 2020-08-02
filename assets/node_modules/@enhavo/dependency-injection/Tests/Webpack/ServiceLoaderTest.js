import {assert} from "chai";
import Container from "@enhavo/dependency-injection/Tests/fixtures/services/test.service.yaml"

describe('dependency-injection/Webpack/ServiceLoader', () => {
    describe('test load', () => {
        it('should return a compiled container with services in it', () => {
            Container.get('@enhavo/dependency-injection/Tests/Mock/TestDependService').then(service => {
                assert.equal('object', typeof service.getService());
                assert.equal('foobar', service.getService().getName());
            });
        });
    });
});
