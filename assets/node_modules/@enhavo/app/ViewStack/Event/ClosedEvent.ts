import { Event } from "@enhavo/core";

export default class ClosedEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('closed');
        this.id = id;
    }
}