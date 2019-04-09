import * as uuidv4 from "uuid/v4";
import EventDispatcher from '@enhavo/app/ViewStack/EventDispatcher';
import ApplicationBag from "@enhavo/app/ApplicationBag";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
const application = <ApplicationInterface>ApplicationBag.getApplication();

export default class Event
{
    name: string;
    origin: any;
    target: any;
    history: string[] = [];
    uuid: string;
    ttl: number;
    dispatcher: EventDispatcher;

    constructor(name: string)
    {
        this.name = name;
        this.uuid = uuidv4();
    }

    resolve(data: object = {})
    {
        let application = <ApplicationInterface>ApplicationBag.getApplication();
        application.getEventDispatcher().dispatch(new ResolveEvent(this.uuid, data));
    }

    reject(data: object = {})
    {
        let application = <ApplicationInterface>ApplicationBag.getApplication();
        application.getEventDispatcher().dispatch(new RejectEvent(this.uuid, data));
    }
}

export class RejectEvent extends Event
{
    data: object;

    constructor(uuid: string, data: object)
    {
        super('reject');
        this.uuid = uuid;
        this.data = data;
    }
}

export class ResolveEvent extends Event
{
    data: object;

    constructor(uuid: string, data: object)
    {
        super('resolve');
        this.uuid = uuid;
        this.data = data;
    }
}
