import Event from "./Event"

export default class RemoveGlobalDataEvent extends Event
{
    public key: string;

    constructor(key: string)
    {
        super('remove-global-data');
        this.key = key;
    }
}