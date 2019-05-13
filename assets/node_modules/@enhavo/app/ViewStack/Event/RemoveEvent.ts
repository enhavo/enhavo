import Event from "./Event"

export default class RemoveEvent extends Event
{
    id: number;
    saveState: boolean;

    constructor(id: number, saveState: boolean = true)
    {
        super('remove');
        this.id = id;
        this.saveState = saveState;
    }
}