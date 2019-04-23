import Event from "./Event"

export default class LoadedEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('loaded');
        this.id = id;
    }
}