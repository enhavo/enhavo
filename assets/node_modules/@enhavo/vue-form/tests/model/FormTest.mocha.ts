import {Form} from "@enhavo/vue-form/model/Form";
const assert = require("chai").assert;

describe('vue-form/model/Form', () => {
    let grandChild = new Form();
    grandChild.name = 'text';

    let child = new Form();
    child.name = 'something';
    child.children.push(grandChild);

    let form = new Form();
    form.name = 'root';
    form.children.push(child);

    grandChild.parent = child;
    child.parent = form;

    describe('get property', () => {
        let element = form.get('something');

        it('should return chil ', () => {
            assert.equal(element.name, 'something');
        });
    });

    describe('get chained property', () => {
        let element = form.get('something.text');

        it('should return grand child', () => {
            assert.equal(element.name, 'text');
        });
    });

    describe('get root node for grand child', () => {
        let element = grandChild.getRoot();

        it('should return root node ', () => {
            assert.isTrue(element === form);
        });
    });

    describe('get root node for root node', () => {
        let element = form.getRoot();

        it('should return root node ', () => {
            assert.isTrue(element === form);
        });
    });

    describe('get parents', () => {
        let parents = grandChild.getParents();

        it('should return parents array', () => {
            assert.equal(2, parents.length);
            assert.isTrue(parents[0] === child);
            assert.isTrue(parents[1] === form);
        });
    });
});
