import {FormFactory} from "@enhavo/vue-form/form/FormFactory"
import {TestFactory} from "@enhavo/vue-form/tests/mock/TestFactory"
import {TestForm} from "@enhavo/vue-form/tests/mock/TestForm";
const chai = require("chai");

describe('vue-form/form/FormFactory', () => {
    describe('After register a form factory', () => {
        let factory = new FormFactory();
        let testFactory = new TestFactory();
        factory.registerFactory('test', testFactory);

        it('it should be used if component matches', () => {
            let createForm = <TestForm>factory.createForm({component: 'test'});
            chai.assert(createForm.foo === 'bar')
        });

        it('it should not be used if component not matches', () => {
            let otherForm = <TestForm>factory.createForm({component: 'something'});
            chai.assert(typeof otherForm.foo === 'undefined')
        });
    });
});
