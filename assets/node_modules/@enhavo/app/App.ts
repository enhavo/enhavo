import { DataLoader } from './DataLoader';
import ViewStack from './ViewStack/ViewStack';


export default class App
{
    private data: any;
    private viewStack: ViewStack;

    constructor(loader: DataLoader)
    {
        this.data = loader.load();
        this.viewStack = new ViewStack(this.data.view_stack);
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