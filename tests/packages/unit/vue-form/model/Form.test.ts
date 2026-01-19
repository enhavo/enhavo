import {Form} from "@enhavo/vue-form/model/Form";
import { describe, test, assert } from "vitest";

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

    test('get property should return chil', () => {
        let element = form.get('something');
        assert.equal(element.name, 'something');
    });

    test('get chained property should return grand child', () => {
        let element = form.get('something.text');
        assert.equal(element.name, 'text');
    });

    test('get root node for grand child should return root node', () => {
        let element = grandChild.getRoot();
        assert.isTrue(element === form);
    });

    test('get root node for root node should return root node', () => {
        let element = form.getRoot();
        assert.isTrue(element === form);
    });

    test('get parents hould return parents array', () => {
        let parents = grandChild.getParents();

        assert.equal(2, parents.length);
        assert.isTrue(parents[0] === child);
        assert.isTrue(parents[1] === form);
    });
});
