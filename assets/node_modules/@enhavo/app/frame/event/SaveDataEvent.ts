import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class SaveDataEvent extends Event
{
    public key: string;
    public value: string;

    constructor(key: string, value: any)
    {
        super('save-data');
        this.key = key;
        this.value = value;
    }
}