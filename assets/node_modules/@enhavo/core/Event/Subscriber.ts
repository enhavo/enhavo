import Event from './Event';

export default class Subscriber
{
    eventName: string;
    callback: (event: Event) => void;
}