import Event from "./Event"

export default class ClearEvent extends Event
{
    constructor(uuid: string = null)
    {
        super('clear');
        this.uuid = uuid;
    }
}