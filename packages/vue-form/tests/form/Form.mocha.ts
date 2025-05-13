import {Form} from "@enhavo/vue-form/model/Form";
const assert = require("chai").assert;

describe('vue-form/model/Form', () => {
    describe('add parents and call getParents', () => {
        let child = new Form();
        let mother = new Form();
        let grandmother = new Form();

        child.parent = mother;
        mother.parent = grandmother;

        let parents = child.getParents();

        it('should return all parents', () => {
            assert.equal(2, parents.length);
            assert.equal(mother, parents[0]);
            assert.equal(grandmother, parents[1]);
        });
    });
});
