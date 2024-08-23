import {Event} from "@enhavo/app/frame/EventDispatcher"

export default class ChangeUrlEvent extends Event
{
    id: number;
    url: string;
    clearStorage: boolean;

    constructor(id: number, url: string, clearStorage: boolean = false)
    {
        super('change-url');
        this.id = id;
        this.url = url;
        this.clearStorage = clearStorage;
    }
}

export class ChangeUrlData
{
    changed: boolean
}