import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class LoadDataEvent extends Event
{
    public key: string;

    constructor(key: string)
    {
        super('load-data');
        this.key = key;
    }
}