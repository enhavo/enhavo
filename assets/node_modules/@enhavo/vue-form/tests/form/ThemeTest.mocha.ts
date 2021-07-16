import {FormData} from "@enhavo/vue-form/data/FormData";
import {Form} from "@enhavo/vue-form/form/Form";
import {Theme} from "@enhavo/vue-form/form/Theme";
const assert = require("chai").assert;

describe('vue-form/form/Theme', () => {
    let childData = new FormData();
    childData.rowComponent = 'form-row'
    childData.component = 'form-text'
    childData.children = {};

    let formData = new FormData();
    formData.rowComponent = 'form-row'
    formData.component = null
    childData.disabled = true;
    childData.required = true;
    formData.children = {text: childData};

    let form = Form.create(formData);

    describe('adding a row component', () => {
        let theme = new Theme();
        theme.component('form-row', 'form-row-custom');
        form.setTheme(theme);

        it('should be changed the row component', () => {
            assert.equal(form.rowComponent, 'form-row-custom');
        });
    });

    describe('adding a component', () => {
        let theme = new Theme();
        theme.component('form-text', 'form-custom-text');
        form.setTheme(theme);

        it('should be changed the child row component', () => {
            assert.equal(form.children.text.component, 'form-custom-text');
        });
    });

    describe('adding two forEach', () => {
        let theme = new Theme();
        theme.forEach((data: FormData) => {
            data.required = false;
        });
        theme.forEach((data: FormData) => {
            data.disabled = false;
        });
        form.setTheme(theme);

        it('should be changed the values', () => {
            assert.strictEqual(form.required, false);
            assert.strictEqual(form.disabled, false);
        });
    });
});
