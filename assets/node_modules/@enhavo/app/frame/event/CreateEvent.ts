import ComponentAwareInterface from "@enhavo/core/ComponentAwareInterface";
import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class CreateEvent extends Event
{
    data: ComponentAwareInterface;
    parent: number = null;

    constructor(data: ComponentAwareInterface|any, parent: number = null)
    {
        super('create');
        this.data = data;
        this.parent = parent;
    }
}