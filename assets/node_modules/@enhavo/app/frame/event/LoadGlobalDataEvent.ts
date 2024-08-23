import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class LoadGlobalDataEvent extends Event
{
    public key: string;

    constructor(key: string)
    {
        super('load-global-data');
        this.key = key;
    }
}