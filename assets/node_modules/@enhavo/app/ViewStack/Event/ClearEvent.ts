import Event from "./Event"

export default class ClearEvent extends Event
{
    constructor()
    {
        super('clear');
    }
}