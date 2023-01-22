import {FormEventDispatcher} from "@enhavo/vue-form/form/FormEventDispatcher";
import {GenericEvent} from "@enhavo/vue-form/event/GenericEvent";
import {Form} from "@enhavo/vue-form/model/Form";

const assert = require("chai").assert;

describe('vue-form/form/FormEventDispatcher', () => {
    describe('Add and dispatch single event', () => {
        it('it should call the listener after same eventName is dispatched', () => {
            let dispatcher = new FormEventDispatcher();
            let called = false;
            let form = new Form();

            dispatcher.addListener('move', () => {
                called = true;
            });

            dispatcher.dispatchEvent(new GenericEvent(form), 'something');
            assert.isFalse(called);

            dispatcher.dispatchEvent(new GenericEvent(form), 'move');
            assert.isTrue(called)
        });

        it('it should register several event names if passed', () => {
            let dispatcher = new FormEventDispatcher();
            let called = false;
            let form = new Form();

            dispatcher.addListener(['move', 'drag'], () => {
                called = true;
            });

            dispatcher.dispatchEvent(new GenericEvent(form), 'something');
            assert.isFalse(called);

            dispatcher.dispatchEvent(new GenericEvent(form), 'move');
            assert.isTrue(called)

            dispatcher.dispatchEvent(new GenericEvent(form), 'drag');
            assert.isTrue(called)
        });

        it('it should not called of event listener was deleted', () => {
            let dispatcher = new FormEventDispatcher();
            let called = false;
            let form = new Form();

            let listener = dispatcher.addListener(['move', 'drag'], () => {
                called = true;
            });

            dispatcher.removeListener(listener);

            dispatcher.dispatchEvent(new GenericEvent(form), 'move');
            assert.isFalse(called)

            dispatcher.dispatchEvent(new GenericEvent(form), 'drag');
            assert.isFalse(called)
        });

        it('it should not called of event listener was deleted', () => {
            let dispatcher = new FormEventDispatcher();
            let called = false;
            let form = new Form();

            let listener = dispatcher.addListener(['move', 'drag'], () => {
                called = true;
            });

            dispatcher.removeListener(listener);

            dispatcher.dispatchEvent(new GenericEvent(form), 'move');
            assert.isFalse(called)

            dispatcher.dispatchEvent(new GenericEvent(form), 'drag');
            assert.isFalse(called)
        });

        it('it should not dispatch unless dispatcher is stopped', () => {
            let dispatcher = new FormEventDispatcher();
            let form = new Form();
            let called = false;

            let listener = dispatcher.addListener('move', () => {
                called = true;
            });

            dispatcher.stop();
            dispatcher.dispatchEvent(new GenericEvent(form), 'move');
            assert.isFalse(called)

            dispatcher.start();
            dispatcher.dispatchEvent(new GenericEvent(form), 'move');
            assert.isTrue(called)
        });
    });
});
