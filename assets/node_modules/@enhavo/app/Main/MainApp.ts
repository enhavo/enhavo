import DataLoader from '@enhavo/app/DataLoader';
import ViewStack from '@enhavo/app/ViewStack/ViewStack';
import MenuManager from '@enhavo/app/Menu/MenuManager';

export default class MainApp
{
    private data: any;
    private viewStack: ViewStack;
    private menuManager: MenuManager;

    constructor(loader: DataLoader, viewStack: ViewStack, menuManager: MenuManager)
    {
        this.data = loader.load();
        this.viewStack = viewStack;
        this.menuManager = menuManager;
        if(!this.viewStack.hasViews()) {
            this.menuManager.start();
        }
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