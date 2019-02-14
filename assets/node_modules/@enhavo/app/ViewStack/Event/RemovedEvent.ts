import {Event} from "@enhavo/core";

export default class RemovedEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('remove');
        this.id = id
    }
}