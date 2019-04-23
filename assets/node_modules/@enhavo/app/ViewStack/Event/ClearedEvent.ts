import Event from "./Event"

export default class ClearedEvent extends Event
{
    constructor(uuid: string = null) {
        super('cleared');
        this.uuid = uuid;
    }
}