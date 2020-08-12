import {assert} from "chai";
import {Container} from "@enhavo/dependency-injection/Container/Container";

class Callable
{
    constructor() {
        this._name = null;
    }

    setValue(name) {
        this._name = name;
    }

    getName() {
        return this._name;
    }
}


describe('dependency-injection/Container/Container', () => {
    describe('test call function', () => {
        it('should return value of called function', () => {
            let container = new Container();
            let callable = new Callable();

            container._call('setValue', callable, ['foobar']);

            assert.equal('foobar', callable.getName())
        });
    });
});
