import {ComponentAwareInterface } from "@enhavo/core";
import Event from "./Event"

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