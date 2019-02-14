import { Event } from "@enhavo/core";
import * as uuidv4 from "uuid/v4";

export default class ClearedEvent extends Event
{
    uuid: string;

    constructor(uuid: string = null)
    {
        super('cleared');
        this.uuid = uuid;
        if(this.uuid == null) {
            this.uuid = uuidv4();
        }
    }
}