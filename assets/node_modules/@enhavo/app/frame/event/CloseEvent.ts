import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class CloseEvent extends Event
{
    id: number;
    saveState: boolean = true;

    constructor(id: number, saveState: boolean = true)
    {
        super('close');
        this.id = id;
        this.saveState = saveState;
    }
}