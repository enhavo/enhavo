import {ComponentAwareInterface, Event} from "@enhavo/core";

export default class CreateEvent extends Event
{
    data: ComponentAwareInterface;
    parent: number = null;

    constructor(data: ComponentAwareInterface, parent: number = null)
    {
        super('create');
        this.data = data;
        this.parent = parent;
    }
}