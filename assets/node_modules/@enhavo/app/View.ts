import { DataLoader } from '@enhavo/app/DataLoader';
import EventDispatcher from '@enhavo/core/Event/EventDispatcher';

export class AppView
{
    private data: any;

    private eventDispatcher: EventDispatcher;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher)
    {
        this.data = loader.load();
        this.eventDispatcher = eventDispatcher;
    }

    getData(): any
    {
        return this.data;
    }
}