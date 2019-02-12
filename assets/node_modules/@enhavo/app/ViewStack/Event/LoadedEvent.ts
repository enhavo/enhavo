import { Event } from "@enhavo/core";

export default class LoadedEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('loaded');
        this.id = id;
    }
}