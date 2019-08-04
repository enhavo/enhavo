import Event from "./Event"

export default class LoadedEvent extends Event
{
    id: number;
    label: string;

    constructor(id: number, label: string = null)
    {
        super('loaded');
        this.id = id;
        this.label = label;
    }
}