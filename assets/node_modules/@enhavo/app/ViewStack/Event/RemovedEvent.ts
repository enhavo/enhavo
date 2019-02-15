import Event from "./Event"

export default class RemovedEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('remove');
        this.id = id
    }
}