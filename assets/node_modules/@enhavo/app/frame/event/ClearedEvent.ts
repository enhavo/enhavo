import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class ClearedEvent extends Event
{
    constructor(uuid: string = null) {
        super('cleared');
        this.uuid = uuid;
    }
}