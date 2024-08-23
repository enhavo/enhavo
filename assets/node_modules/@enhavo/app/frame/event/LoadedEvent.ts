import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class LoadedEvent extends Event
{
    constructor()
    {
        super('loaded');
    }
}
