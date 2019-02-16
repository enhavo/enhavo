import EventDispatcher from "./EventDispatcher";
import Event from "./Event/Event";
import View from "./View"
import * as _ from 'lodash';

let view = new View();
let dispatcher = new EventDispatcher(view);

dispatcher.onDispatch((event: Event) => {
    if(event.origin == null) {
        event.origin = view.getId();
    }
    event.history.push(window.location.href);
    if(event.ttl == null) {
        event.ttl = view.isRoot() ? 2 : 3;
    }
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

        if(dispatcher.isDebug()) {
            console.groupCollapsed('receive event ('+ newEvent.name+') on ' + view.getId());
            console.dir(newEvent);
            console.groupEnd();
        }

        dispatcher.dispatch(newEvent);
    }
}, false);

// send event to parent window
if(window.parent && !view.isRoot()) {
    dispatcher.all(function (event: Event) {
        if(event.origin == view.getId()) {
            let serializeEvent = 'view_stack_event|'+JSON.stringify(event);
            window.parent.postMessage(serializeEvent, '*');
        }
    });
}

export default dispatcher;