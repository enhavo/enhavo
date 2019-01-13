import { EventManagerInterface } from './EventManagerInterface';

export class EventManager implements EventManagerInterface
{
    private target: EventTarget;

    constructor(target: EventTarget)
    {
        this.target = target;
    }

    dispatchEvent(event: Event): void
    {
        this.target.dispatchEvent(event);
    }

    on(name: string, handle: (event: Event) => void)
    {
        this.target.addEventListener(name, handle);
    }
}