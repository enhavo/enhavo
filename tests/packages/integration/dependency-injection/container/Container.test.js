import { assert, test } from "vitest";
import container from "../mock/test.di.yaml";

test('get manager from container', () => {
    container.init().then(() => {
        container.get('TestManager').then((manager) => {
            assert.equal('foobar',  manager.getName());
        });
    });
});
