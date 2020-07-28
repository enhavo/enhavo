import {assert} from "chai";
import Container from "@enhavo/core/Tests/fixtures/test.container.yaml"
import {TestDependService} from "../fixtures/TestDependService";

describe('core/DependencyInjection/ServiceLoader', () => {
    describe('test load', () => {
        it('should return a compiled container with services in it', () => {
            let service = Container.get('TestDependService');
            assert.equal('object', typeof service.getService());
            assert.equal('foobar', service.getService().getName());
        });
    });
});