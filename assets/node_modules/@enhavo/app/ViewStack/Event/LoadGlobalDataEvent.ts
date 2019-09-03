import Event from "./Event"

export default class LoadGlobalDataEvent extends Event
{
    public key: string;

    constructor(key: string)
    {
        super('load-global-data');
        this.key = key;
    }
}