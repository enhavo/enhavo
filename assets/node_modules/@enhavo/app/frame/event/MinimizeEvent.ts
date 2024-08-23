import {Event} from "@enhavo/app/frame/EventDispatcher";

export default class MinimizeEvent extends Event
{
    id: number;
    setCustomMinimized: boolean;

    constructor(id: number, setCustomMinimized: boolean = false)
    {
        super('minimize');
        this.id = id;
        this.setCustomMinimized = setCustomMinimized;
    }
}
