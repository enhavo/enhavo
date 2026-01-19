import Map from "@enhavo/dependency-injection/container/Map"
import { expect, test } from "vitest";

test('test get and setter', () => {
    let map = new Map();

    map.add('test', 'hello');
    expect.assert.equal('hello', map.get('test'));

    map.add('test', 'world');
    expect.assert.equal('world', map.get('test'));

    map.add('foo', 'bar');
    expect.assert.equal(['world', 'bar'].toString(), map.getValues().toString());
});
