import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class ClickEvent extends Event
{
    id: string;

    constructor(id: string)
    {
        super('click');
        this.id = id;
    }
}