import {Container} from "@enhavo/dependency-injection/container/Container";
import { describe, expect, test } from "vitest";

class Callable
{
    constructor() {
        this._name = null;
    }

    setName(name) {
        this._name = name;
    }

    getName() {
        return this._name;
    }
}

class TestContainer extends Container
{

}

describe('dependency-injection/Container/Container', () => {
    test('test call function should return value of called function', () => {
        let container = new Container();
        let callable = new Callable();

        container._call('setName', callable, ['foobar']);

        expect.assert.equal('foobar', callable.getName())
    });
});
