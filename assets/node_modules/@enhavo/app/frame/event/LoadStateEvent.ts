import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class LoadStateEvent extends Event
{
    constructor()
    {
        super('load-state');
    }
}