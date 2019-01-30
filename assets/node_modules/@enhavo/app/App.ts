import { DataLoader } from './DataLoader';
import { EventDispatcher } from './Event/EventDispatcher';
import { ViewStack } from './View/ViewStack';

export class App
{
    private data: any;

    private eventDispatcher: EventDispatcher;

    private viewStack: ViewStack;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher)
    {
        this.data = loader.load();
        this.eventDispatcher = eventDispatcher;
        this.viewStack = new ViewStack(this.data.views, eventDispatcher);
    }

    getData(): any
    {
        return this.data;
    }
}