import { DataLoader } from '@enhavo/app/DataLoader';
import EventDispatcher from '@enhavo/core/Event/EventDispatcher';

export class AppView
{
    private data: any;

    constructor(loader: DataLoader)
    {
        this.data = loader.load();
    }

    getData(): any
    {
        return this.data;
    }
}