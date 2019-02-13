import {ComponentAwareInterface, Event} from "@enhavo/core";

export default class CreateEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('remove');
        this.id = id
    }
}