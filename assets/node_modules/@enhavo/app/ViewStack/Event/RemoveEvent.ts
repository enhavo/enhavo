import Event from "./Event"

export default class RemoveEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('remove');
        this.id = id;
    }
}