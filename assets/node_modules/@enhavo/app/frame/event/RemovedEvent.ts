import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class RemovedEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('removed');
        this.id = id
    }
}