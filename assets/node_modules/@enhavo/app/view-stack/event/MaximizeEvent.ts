import Event from "@enhavo/app/view-stack/event/Event";

export default class MaximizeEvent extends Event
{
    id: number;
    setCustomMinimized: boolean;

    constructor(id: number, setCustomMinimized: boolean = false)
    {
        super('maximize');
        this.id = id;
        this.setCustomMinimized = setCustomMinimized;
    }
}
