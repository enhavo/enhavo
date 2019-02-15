import Event from "./Event"
import * as uuidv4 from "uuid/v4";

export default class ClearedEvent extends Event
{
    constructor(uuid: string = null) {
        super('cleared');
        this.uuid = uuid;
    }
}