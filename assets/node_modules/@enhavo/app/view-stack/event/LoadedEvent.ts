import Event from "./Event"

export default class LoadedEvent extends Event
{
    id: number;
    label: string;
    closeable: boolean;

    constructor(id: number, label: string = null, closeable: boolean = true)
    {
        super('loaded');
        this.id = id;
        this.label = label;
        this.closeable = closeable;
    }
}