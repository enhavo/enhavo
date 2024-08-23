import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class LoadingEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('loading');
        this.id = id;
    }
}