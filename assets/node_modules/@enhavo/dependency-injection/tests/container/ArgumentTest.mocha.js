const assert = require("chai").assert;
import chai from "chai"
const Argument = require('@enhavo/dependency-injection/container/Argument');

describe('dependency-injection/Container/Argument', () => {
    describe('constructor', () => {
        it('should return correct type and name', () => {
            assert.equal('service', new Argument('service:@Something/service').getType());
            assert.equal('@Something/service', new Argument('service:@Something/service').getValue());
            assert.equal('@Something/service', new Argument('param:@Something/service').getValue());
            assert.equal('Test', new Argument('string:Test').getValue());
            assert.equal(null, new Argument(null).getValue());
        });

        it('should throw exceptions', () => {
            chai.expect(() => {
                new Argument('service:huhu:@Something/service')
            }).to.throw();

            chai.expect(() => {
                new Argument('foobar:@Something/service')
            }).to.throw();
        });
    });
});
