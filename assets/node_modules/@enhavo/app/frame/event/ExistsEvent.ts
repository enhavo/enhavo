import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class ExistsEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('exists');
        this.id = id;
    }
}