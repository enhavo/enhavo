import Event from "./Event"

export default class ClosedEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('closed');
        this.id = id;
    }
}