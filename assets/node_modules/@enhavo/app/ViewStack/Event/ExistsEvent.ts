import Event from "./Event"

export default class ExistsEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('exists');
        this.id = id;
    }
}