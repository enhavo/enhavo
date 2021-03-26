import {assert} from "chai";
import {Container} from "@enhavo/dependency-injection/container/Container";

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
    describe('test call function', () => {
        it('should return value of called function', () => {
            let container = new Container();
            let callable = new Callable();

            container._call('setName', callable, ['foobar']);

            assert.equal('foobar', callable.getName())
        });
    });
});
