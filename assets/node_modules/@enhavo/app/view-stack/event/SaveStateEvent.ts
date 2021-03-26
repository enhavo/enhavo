import Event from "./Event"

export default class SaveStateEvent extends Event
{
    constructor()
    {
        super('save-state');
    }
}