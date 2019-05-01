import MainApp from "@enhavo/app/Main/MainApp";
import AbstractApplication from "@enhavo/app/AbstractApplication";
import AppInterface from "@enhavo/app/AppInterface";
import ViewStack from "@enhavo/app/ViewStack/ViewStack";
import MenuManager from "@enhavo/app/Menu/MenuManager";
import MenuRegistry from "@enhavo/app/Menu/MenuRegistry";
import FrameStorage from "@enhavo/app/ViewStack/FrameStorage";
import ViewRegistry from "@enhavo/app/ViewStack/ViewRegistry";
import DataStorageManager from "@enhavo/app/ViewStack/DataStorageManager";
import StateManager from "@enhavo/app/State/StateManager";

export class MainApplication extends AbstractApplication
{
    protected viewStack: ViewStack;
    protected menuManager: MenuManager;
    protected frameStorage: FrameStorage;
    protected viewRegistry: ViewRegistry;
    protected menuRegistry: MenuRegistry;
    protected dataStorageManager: DataStorageManager;
    protected stateManager: StateManager;

    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new MainApp(this.getDataLoader(), this.getViewStack(), this.getMenuManager(), this.getStateManager());
        }
        return this.app;
    }

    public getViewStack(): ViewStack
    {
        if(this.viewStack == null) {
            this.viewStack = new ViewStack(
                this.getDataLoader().load().view_stack,
                this.getMenuManager(),
                this.getEventDispatcher(),
                this.getViewRegistry()
            );
        }
        return this.viewStack;
    }

    public getDataStorageManager(): DataStorageManager
    {
        if(this.dataStorageManager == null) {
            this.dataStorageManager = new DataStorageManager(this.getViewStack(), this.getEventDispatcher());
        }
        return this.dataStorageManager;
    }

    public getMenuManager(): MenuManager
    {
        if(this.menuManager == null) {
            this.menuManager = new MenuManager(this.getDataLoader().load().menu, this.getMenuRegistry());
        }
        return this.menuManager;
    }

    public getMenuRegistry(): MenuRegistry
    {
        if(this.menuRegistry == null) {
            this.menuRegistry = new MenuRegistry();
            this.menuRegistry.load(this);
        }
        return this.menuRegistry;
    }

    public getFrameStorage(): FrameStorage
    {
        if(this.frameStorage == null) {
            this.frameStorage = new FrameStorage(this.getEventDispatcher());
        }
        return this.frameStorage;
    }

    public getViewRegistry(): ViewRegistry
    {
        if(this.viewRegistry == null) {
            this.viewRegistry = new ViewRegistry();
            this.viewRegistry.load();
        }
        return this.viewRegistry;
    }

    public getStateManager(): StateManager
    {
        if(this.stateManager == null) {
            this.stateManager = new StateManager(this.getViewStack(), this.getEventDispatcher());
        }
        return this.stateManager;
    }
}

let application = new MainApplication();
export default application;