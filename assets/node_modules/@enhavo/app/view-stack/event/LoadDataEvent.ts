import Event from "./Event"

export default class LoadDataEvent extends Event
{
    public key: string;

    constructor(key: string)
    {
        super('load-data');
        this.key = key;
    }
}