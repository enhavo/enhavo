import {FormData} from "@enhavo/vue-form/data/FormData";
import {Form} from "@enhavo/vue-form/form/Form";
import {Theme} from "@enhavo/vue-form/form/Theme";
const assert = require("chai").assert;

describe('vue-form/form/Form', () => {
    let grandChildData = new FormData();
    grandChildData.name = 'text';
    grandChildData.children = {};

    let childData = new FormData();
    childData.name = 'something';
    childData.children = {text:grandChildData};

    let formData = new FormData();
    formData.name = 'root';
    formData.children = {something: childData};

    let form = Form.create(formData);

    describe('get property "something"', () => {
        let element = form.get('something');

        it('should have received "something" ', () => {
            assert.equal(element.name, 'something');
        });
    });

    describe('get property "something.text"', () => {
        let element = form.get('something.text');

        it('should have received "text" ', () => {
            assert.equal(element.name, 'text');
        });
    });
});
