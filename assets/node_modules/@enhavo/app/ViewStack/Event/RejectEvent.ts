import Event from "./Event"

export default class RejectEvent extends Event
{
    data: object;

    constructor(uuid: string, data: object)
    {
        super('reject');
        this.uuid = uuid;
        this.data = data;
    }
}