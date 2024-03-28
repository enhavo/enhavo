import uuidv4 from "uuid/v4";
import EventDispatcher from '@enhavo/app/view-stack/EventDispatcher';

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
        this.dispatcher.dispatch(new ResolveEvent(this.uuid, data));
    }

    reject(data: object = {})
    {
        this.dispatcher.dispatch(new RejectEvent(this.uuid, data));
    }

    serialize(): string
    {
        let dispatcher = this.dispatcher;
        this.dispatcher = null;
        let data = JSON.stringify(this);
        this.dispatcher = dispatcher;
        return data
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
