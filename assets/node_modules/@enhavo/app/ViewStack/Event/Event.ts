import * as uuidv4 from "uuid/v4";
import dispatcher from "../dispatcher";

export default class Event
{
    name: string;
    origin: any;
    target: any;
    history: string[] = [];
    uuid: string;

    constructor(name: string)
    {
        this.name = name;
        this.uuid = uuidv4();
    }

    resolve(data: object = {})
    {
        dispatcher.dispatch(new ResolveEvent(this.uuid, data));
    }

    reject(data: object = {})
    {
        dispatcher.dispatch(new RejectEvent(this.uuid, data));
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
