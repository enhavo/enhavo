import Event from "./Event"

export default class SaveStateEvent extends Event
{
    constructor()
    {
        super('load-state');
    }
}