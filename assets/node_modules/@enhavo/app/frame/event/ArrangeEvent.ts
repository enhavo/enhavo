import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class ArrangeEvent extends Event
{
    constructor() {
        super('arrange');
    }
}