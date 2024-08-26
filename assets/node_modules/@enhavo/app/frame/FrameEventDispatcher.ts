import generateId from "uuid/v4";

/**
 * @internal
 *
 * The frame event dispatcher can send and receive events in one frame or over multiple frames.
 * It is not implemented for nested iframes, so there are only parent and children.
 *
 * This class should be instantiated in the main frame and the child frame.
 * The child frames must have a name attribute, so if you embed an iframe use the name attribute like here:
 *
 * <iframe src="http://example/one" name="one">
 * <iframe src="http://example/two" name="two">
 *
 * Listen to an event:
 * (new FrameEventDispatcher).on('myEvent', (event) => { });
 *
 * Dispatch event:
 * (new FrameEventDispatcher).dispatch(new Event('myEvent'));
 *
 * We recommend to create your own event class on extend {Event} from '@enhavo/app/frame/FrameEventDispatcher'
 *
 * Events can also be resolved or rejected like a Promise to deliver back some data to the dispatched event.
 *
 * Resolve:
 * (new FrameEventDispatcher).on('myEvent', (event) => { event.resolve({ someData: 'Test resolve'}) });
 *
 * Reject:
 * (new FrameEventDispatcher).on('myEvent', (event) => { event.reject({ someData: 'Test reject'}) });
 *
 * If you expect return data, use the request method
 *
 * Receive resolved data:
 * (new FrameEventDispatcher).request(new Event('myEvent')).then(data => {});
 *
 * Receive reject data:
 * (new FrameEventDispatcher).request(new Event('myEvent')).catch(data => {});
 *
 * It is possible that multiple subscriber will receive an event and resolve or reject it, but only the first on that
 * arrive back will trigger the reject or resolve once.
 */
export class FrameEventDispatcher
{
    private subscribers: Subscriber[] = [];
    private events: EventStore[] = [];

    constructor(
        private debug: boolean = false,
    )
    {
        // receive message events
        let regex = new RegExp('^frame_stack_event');
        window.addEventListener("message", (event) => {
            let data = event.data;
            if (typeof data == 'string' && regex.test(data)) {
                data = data.substring(18);
                let eventData = JSON.parse(data);
                let newEvent = new Event('');
                Object.assign(newEvent, eventData);
                if (this.debug) {
                    console.groupCollapsed('receive event ('+ newEvent.name+') on ' + this.getWindowId());
                    console.dir(newEvent);
                    console.groupEnd();
                }

                newEvent.ttl--;

                if (this.isRootWindow()) {
                    this.pass(newEvent);
                }

                this.notifySubscriber(newEvent);
            }
        }, false);

        this.on('reject', (event: Event) => {
            for (let eventStore of this.events) {
                if (eventStore.event.uuid == event.uuid) {
                    eventStore.reject((event as RejectEvent).data);
                    this.removeEvent(eventStore);
                }
            }
        });

        this.on('resolve', (event: Event) => {
            for (let eventStore of this.events) {
                if (eventStore.event.uuid == event.uuid) {
                    eventStore.resolve((event as ResolveEvent).data);
                    this.removeEvent(eventStore);
                }
            }
        });
    }

    private pass(event: Event)
    {
        if (event.ttl <= 0) {
            return;
        }

        let frames = document.getElementsByTagName('iframe');
        for (let frame of frames) {
            let name = frame.getAttribute('name');

            if (name === '') {
                continue;
            }

            // don't pass back to frame, where the event was come from
            if (event.origin === name) {
                continue;
            }

            if (this.debug) {
                console.groupCollapsed('pass event ('+event.name+') to iframe ' + name);
                console.dir(event);
                console.groupEnd()
            }

            this.sendToFrame(frame, event);
        }
    }

    private notifySubscriber(event: Event)
    {
        event.dispatcher = this;

        for (let subscriber of this.subscribers) {
            if (subscriber.eventName == event.name) {
                subscriber.callback(event);
            }
        }
    }

    private isRootWindow()
    {
        return this.getWindowId() == null;
    }
    
    private getWindowId(): string
    {
        if (window.name != '') {
            return window.name;
        }
        return null;
    }

    private removeEvent(eventStore: EventStore)
    {
        for(let i in this.events) {
            if(eventStore.event.uuid == this.events[i].event.uuid) {
                this.events.splice(parseInt(i), 1);
                return;
            }
        }
    }

    public dispatch(event: Event): void
    {
        event.dispatcher = this;
        event.ttl = this.isRootWindow() ? 1 : 2;
        event.origin = this.getWindowId();

        if (this.debug) {
            console.groupCollapsed('dispatch event ('+ event.name+') on ' + this.getWindowId());
            console.dir(event);
            console.groupEnd();
        }

        // send to own frame
        window.postMessage('frame_stack_event|' + event.serialize());

        if (window.parent && !this.isRootWindow()) {
            // send event to parent window
            const url = new URL(window.location != window.parent.location ? document.referrer : document.location.href);
            let port = url.port ? ':' +  url.port : '';
            let target = url.protocol + '//' + url.hostname + port;
            let serializeEvent = 'frame_stack_event|' + event.serialize();
            window.parent.postMessage(serializeEvent, target);
        } else {
            // send event to child frames
            let frames = document.getElementsByTagName('iframe');
            for (let frame of frames) {
                this.sendToFrame(frame, event);
            }
        }
    }

    public request(event: Event): Promise<object|object[]|boolean|string|number>
    {
        this.dispatch(event);

        return new Promise((resolve, reject) => {
            if (event.origin == this.getWindowId()) {
                if (event.name != 'reject' && event.name != 'resolve') {
                    this.events.push(new EventStore(event, resolve, reject));
                }
            }
        });
    }

    private sendToFrame(frame: HTMLIFrameElement, event: Event)
    {
        let src = frame.getAttribute('src');
        let data = 'frame_stack_event|' + event.serialize();

        if (URL.canParse(src)) {
            const url = new URL(src);
            let port = url.port ? ':' + url.port : '';
            let target = url.protocol + '//' + url.hostname + port;
            frame.contentWindow.postMessage(data, target);
        } else {
            frame.contentWindow.postMessage(data);
        }
    }

    public on(eventName: string, callback: (event: Event) => void, priority: number = 10): Subscriber
    {
        let subscriber = new Subscriber();
        subscriber.eventName = eventName;
        subscriber.callback = callback;
        subscriber.priority = priority;
        this.subscribers.push(subscriber);
        this.subscribers = this.subscribers.sort((a, b) => {
            return b.priority - a.priority;
        });

        return subscriber;
    }

    public remove(subscriber: Subscriber)
    {
        const index = this.subscribers.indexOf(subscriber);
        if (index >= 0) {
            this.subscribers.splice(index, 1);
        }
    }
}

export class Subscriber
{
    eventName: string = null;
    callback: (event: Event) => void;
    priority: number;
}

class EventStore
{
    event: Event;
    resolve: (data: object|object[]|boolean|string|number) => void;
    reject: (data: object|object[]|boolean|string|number) => void;

    constructor(event: Event, resolve: (data: object|object[]|boolean|string|number) => void, reject: (data: object|object[]|boolean|string|number) => void)
    {
        this.event = event;
        this.resolve = resolve;
        this.reject = reject;
    }
}


export class Event
{
    name: string;
    origin: any;
    target: any;
    history: string[] = [];
    uuid: string;
    ttl: number;
    dispatcher: FrameEventDispatcher;

    constructor(name: string)
    {
        this.name = name;
        this.uuid = generateId();
    }

    resolve(data: object|object[]|boolean|string|number = null)
    {
        this.dispatcher.dispatch(new ResolveEvent(this.uuid, this.name, data));
    }

    reject(data: object|object[]|boolean|string|number = null)
    {
        this.dispatcher.dispatch(new RejectEvent(this.uuid, this.name, data));
    }

    serialize(): string
    {
        let dispatcher = this.dispatcher;
        this.dispatcher = null;
        let data = JSON.stringify(this);
        this.dispatcher = dispatcher;
        return data
    }
}

export class RejectEvent extends Event
{
    data: object|object[]|boolean|string|number;
    rejectedEventName: string;

    constructor(uuid: string, rejectedEventName: string, data: object|object[]|boolean|string|number)
    {
        super('reject');
        this.uuid = uuid;
        this.data = data;
        this.rejectedEventName = rejectedEventName;
    }
}

export class ResolveEvent extends Event
{
    data: object|object[]|boolean|string|number;
    resolvedEventName: string;

    constructor(uuid: string, resolvedEventName: string, data: object|object[]|boolean|string|number)
    {
        super('resolve');
        this.uuid = uuid;
        this.data = data;
        this.resolvedEventName = resolvedEventName;
    }
}
