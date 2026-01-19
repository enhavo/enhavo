import {Form} from "@enhavo/vue-form/model/Form";
import { expect, test, assert } from "vitest";


test('add parents and call getParents', () => {
    let child = new Form();
    let mother = new Form();
    let grandmother = new Form();

    child.parent = mother;
    mother.parent = grandmother;

    let parents = child.getParents();

    assert.equal(2, parents.length);
    assert.equal(mother, parents[0]);
    assert.equal(grandmother, parents[1]);
});
