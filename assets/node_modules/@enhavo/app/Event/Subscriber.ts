import { Event } from './Event';

export class Subscriber
{
    eventName: string;
    callback: (event: Event) => void;
}