const assert = require("chai").assert;
const ParameterBag = require("@enhavo/dependency-injection/Container/ParameterBag");

describe('dependency-injection/Container/ParameterBag', () => {
    describe('test get and set', () => {
        it('should retrieve output for input', () => {
            let bag = new ParameterBag();

            bag.set('data.test', 'Hello World!');
            assert.equal('Hello World!', bag.get('data.test'));

            bag.set('data.something', '42');
            assert.equal('42', bag.get('data.something'));
            assert.equal('Hello World!', bag.get('data.test'));
        });

        it('should resolve correct paths', () => {
            let bag = new ParameterBag();

            bag.set('data', {
                test: 'Hello World!',
                something: '42'
            });

            assert.equal('Hello World!', bag.get('data.test'));
            assert.equal('42', bag.get('data.something'));

            bag.set('data.test', {
                foobar: 'Hello World!',
            });
            assert.equal('Hello World!', bag.get('data.test.foobar'));
        });

    });
});
