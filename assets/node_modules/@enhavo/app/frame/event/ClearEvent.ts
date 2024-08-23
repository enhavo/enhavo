import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class ClearEvent extends Event
{
    constructor()
    {
        super('clear');
    }
}