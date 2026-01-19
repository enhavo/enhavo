import { expect, test, describe } from "vitest";
import ParameterBag from "@enhavo/dependency-injection/container/ParameterBag"


describe('test get and set', () => {
    test('should retrieve output for input', () => {
        let bag = new ParameterBag();

        bag.set('data.test', 'Hello World!');
        expect.assert.equal('Hello World!', bag.get('data.test'));

        bag.set('data.something', '42');
        expect.assert.equal('42', bag.get('data.something'));
        expect.assert.equal('Hello World!', bag.get('data.test'));
    });

    test('should resolve correct paths', () => {
        let bag = new ParameterBag();

        bag.set('data', {
            test: 'Hello World!',
            something: '42'
        });

        expect.assert.equal('Hello World!', bag.get('data.test'));
        expect.assert.equal('42', bag.get('data.something'));

        bag.set('data.test', {
            foobar: 'Hello World!',
        });
        expect.assert.equal('Hello World!', bag.get('data.test.foobar'));
    });
});
