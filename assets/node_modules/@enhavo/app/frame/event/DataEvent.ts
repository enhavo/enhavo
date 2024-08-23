import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class DataEvent extends Event
{
    id: number;
    data: any;

    constructor(id: number, data: any)
    {
        super('data');
        this.id = id;
        this.data = data;
    }
}