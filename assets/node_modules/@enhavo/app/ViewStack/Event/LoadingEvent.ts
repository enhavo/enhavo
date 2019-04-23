import Event from "./Event"

export default class LoadingEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('loading');
        this.id = id;
    }
}