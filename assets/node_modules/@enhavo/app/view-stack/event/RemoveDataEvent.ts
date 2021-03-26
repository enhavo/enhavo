import Event from "./Event"

export default class RemoveDataEvent extends Event
{
    public key: string;

    constructor(key: string)
    {
        super('remove-data');
        this.key = key;
    }
}