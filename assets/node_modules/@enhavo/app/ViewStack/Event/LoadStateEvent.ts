import Event from "./Event"

export default class LoadStateEvent extends Event
{
    constructor()
    {
        super('load-state');
    }
}