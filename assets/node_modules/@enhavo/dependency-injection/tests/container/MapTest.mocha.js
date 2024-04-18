const assert = require("chai").assert;
import Map from "@enhavo/dependency-injection/container/Map"

describe('dependency-injection/Container/Map', () => {
    describe('test get and setter', () => {
        it('should return ', () => {
            let map = new Map();

            map.add('test', 'hello');
            assert.equal('hello', map.get('test'));

            map.add('test', 'world');
            assert.equal('world', map.get('test'));

            map.add('foo', 'bar');
            assert.equal(['world', 'bar'].toString(), map.getValues().toString());
        });
    });
});
