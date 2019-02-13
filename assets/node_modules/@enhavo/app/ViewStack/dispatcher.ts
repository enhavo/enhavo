import { EventDispatcher, Event } from "@enhavo/core"
import View from "./View"
import * as _ from 'lodash';

let dispatcher = new EventDispatcher();
let view = new View();

dispatcher.onDispatch((event) => {
    if(event.origin == null) {
        event.origin = view.getId();
    }
    event.history.push(window.location.href);
});

// receive message events
let regex = new RegExp('^view_stack_event');
window.addEventListener("message", (event) => {
    let data = event.data;
    if(typeof data == 'string' && regex.test(data)) {
        data = data.substring(17);
        let eventData = JSON.parse(data);
        let newEvent = new Event('');
        _.extend(newEvent, eventData);
        dispatcher.dispatch(newEvent);
    }
}, false);

// send event to parent window
if(window.parent && !view.isRoot()) {
    dispatcher.all(function (event) {
        if(event.origin == view.getId()) {
            let serializeEvent = 'view_stack_event|'+JSON.stringify(event);
            window.parent.postMessage(serializeEvent, '*');
        }
    });
}

export default dispatcher;