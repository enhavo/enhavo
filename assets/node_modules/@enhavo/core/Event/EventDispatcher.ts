import Event from './Event';
import Subscriber from './Subscriber';

export default class EventDispatcher
{
    private subscribers: Subscriber[] = [];

    public dispatch(event: Event)
    {
        this.subscribers.forEach((subscriber:Subscriber) => {
            if(subscriber.eventName == event.name) {
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
}