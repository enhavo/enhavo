import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class SaveStateEvent extends Event
{
    constructor()
    {
        super('save-state');
    }
}