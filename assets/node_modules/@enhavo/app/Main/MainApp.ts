import DataLoader from '@enhavo/app/DataLoader';
import ViewStack from '@enhavo/app/ViewStack/ViewStack';
import MenuManager from '@enhavo/app/Menu/MenuManager';
import StateManager from "@enhavo/app/State/StateManager";
import DataStorageManager from "@enhavo/app/ViewStack/DataStorageManager";

export default class MainApp
{
    private data: any;
    private viewStack: ViewStack;
    private menuManager: MenuManager;
    private stateManager: StateManager;
    private dataStorageManager: DataStorageManager;

    constructor(loader: DataLoader, viewStack: ViewStack, menuManager: MenuManager, stateManager: StateManager, dataStorageManager: DataStorageManager)
    {
        this.data = loader.load();
        this.viewStack = viewStack;
        this.dataStorageManager = dataStorageManager;
        this.menuManager = menuManager;
        this.stateManager = stateManager;
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