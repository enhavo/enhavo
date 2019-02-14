import { Event } from "@enhavo/core";

export default class CloseEvent extends Event
{
    id: number;
    options: object;

    constructor(id: number, options: object = {})
    {
        super('close');
        this.id = id;
        this.options = options;
    }
}