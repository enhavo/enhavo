import Event from "./Event"

export default class SaveGlobalDataEvent extends Event
{
    public key: string;
    public value: string;

    constructor(key: string, value: any)
    {
        super('save-global-data');
        this.key = key;
        this.value = value;
    }
}