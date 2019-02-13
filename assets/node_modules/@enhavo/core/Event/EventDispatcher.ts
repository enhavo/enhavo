import Event from './Event';
import Subscriber from './Subscriber';

export default class EventDispatcher
{
    private subscribers: Subscriber[] = [];

    private dispatchSubscriber: Subscriber[] = [];

    public dispatch(event: Event)
    {
        this.dispatchSubscriber.forEach((subscriber:Subscriber) => {
            subscriber.callback(event);
        });

        this.subscribers.forEach((subscriber:Subscriber) => {
            if(subscriber.eventName == event.name) {
                subscriber.callback(event);
            } else if(subscriber.eventName == null) {
                subscriber.callback(event);
            }
        });
    }

    public on(eventName: string, callback: (event: Event) => void)
    {
        let subscriber = new Subscriber();
        subscriber.eventName = eventName;
        subscriber.callback = callback;
        this.subscribers.push(subscriber);
    }

    public all(callback: (event: Event) => void)
    {
        let subscriber = new Subscriber();
        subscriber.eventName = null;
        subscriber.callback = callback;
        this.subscribers.push(subscriber);
    }

    public onDispatch(callback: (event: Event) => void)
    {
        let subscriber = new Subscriber();
        subscriber.eventName = null;
        subscriber.callback = callback;
        this.dispatchSubscriber.push(subscriber);
    }
}