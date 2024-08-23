import {Event} from "@enhavo/app/frame/EventDispatcher"

export class InitEvent extends Event
{
    constructor()
    {
        super('init');
    }
}
