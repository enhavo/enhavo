import Event from "./Event"

export default class ResolveEvent extends Event
{
    data: object;

    constructor(uuid: string, data: object)
    {
        super('reject');
        this.uuid = uuid;
        this.data = data;
    }
}