import {Form} from "@enhavo/vue-form/model/Form";
import {Theme} from "@enhavo/vue-form/form/Theme";
import { assert, test } from "vitest";


let theme = new Theme();

test('adding one visitor should return one visitor', () => {
    theme.addVisitorCallback((form: Form) => { return true }, (form: Form) => {})

    assert.equal(1, theme.getVisitors().length);
});

