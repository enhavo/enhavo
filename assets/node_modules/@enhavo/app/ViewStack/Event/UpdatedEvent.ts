import Event from "./Event"

export default class LoadedEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('updated');
        this.id = id;
    }
}