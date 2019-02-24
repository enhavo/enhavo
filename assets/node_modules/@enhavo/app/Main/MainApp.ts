import DataLoader from '../DataLoader';
import ViewStack from '../ViewStack/ViewStack';

export default class MainApp
{
    private data: any;
    private viewStack: ViewStack;

    constructor(loader: DataLoader, viewStack: ViewStack)
    {
        this.data = loader.load();
        this.viewStack = viewStack;
    }

    getData(): any
    {
        return this.data;
    }

    getViewStack(): ViewStack
    {
        return this.viewStack;
    }
}