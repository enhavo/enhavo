import {Form} from "@enhavo/vue-form/model/Form";
import {Theme} from "@enhavo/vue-form/form/Theme";
const assert = require("chai").assert;

describe('vue-form/form/Theme', () =>
{
    let theme = new Theme();

    describe('adding one visitor', () => {
        theme.addVisitorCallback((form: Form) => { return true }, (form: Form) => {})

        it('should return one visitor', () => {
            assert.equal(1, theme.getVisitors().length);
        });
    });
});
