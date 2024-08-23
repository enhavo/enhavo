import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class RemoveGlobalDataEvent extends Event
{
    public key: string;

    constructor(key: string)
    {
        super('remove-global-data');
        this.key = key;
    }
}