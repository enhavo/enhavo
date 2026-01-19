import Argument from '@enhavo/dependency-injection/container/Argument';
import { expect, test } from 'vitest'

test('argument should return correct type and name', () => {
    expect.assert.equal('service', new Argument('service:@Something/service').getType());
    expect.assert.equal('@Something/service', new Argument('service:@Something/service').getValue());
    expect.assert.equal('@Something/service', new Argument('param:@Something/service').getValue());
    expect.assert.equal('Test', new Argument('string:Test').getValue());
    expect.assert.equal(null, new Argument(null).getValue());
});

test('argument should throw exceptions', () => {
    expect(() => {
        new Argument('service:huhu:@Something/service')
    }).toThrow()

    expect(() => {
        new Argument('foobar:@Something/service')
    }).toThrow()
});
