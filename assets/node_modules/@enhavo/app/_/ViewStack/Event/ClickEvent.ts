import Event from "./Event"

export default class ClickEvent extends Event
{
    id: number;

    constructor(id: number)
    {
        super('click');
        this.id = id;
    }
}