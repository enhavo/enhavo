import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class RemoveEvent extends Event
{
    id: number;
    saveState: boolean;

    constructor(id: number, saveState: boolean = true)
    {
        super('remove');
        this.id = id;
        this.saveState = saveState;
    }
}