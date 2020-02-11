import MainApp from "@enhavo/app/Main/MainApp";
import Application from "@enhavo/app/Application";
import AppInterface from "@enhavo/app/AppInterface";
import ViewStack from "@enhavo/app/ViewStack/ViewStack";
import MenuManager from "@enhavo/app/Menu/MenuManager";
import MenuRegistry from "@enhavo/app/Menu/MenuRegistry";
import FrameStorage from "@enhavo/app/ViewStack/FrameStorage";
import ViewRegistry from "@enhavo/app/ViewStack/ViewRegistry";
import DataStorageManager from "@enhavo/app/ViewStack/DataStorageManager";
import StateManager from "@enhavo/app/State/StateManager";
import GlobalDataStorageManager from "@enhavo/app/ViewStack/GlobalDataStorageManager";
import WidgetManager from "@enhavo/app/Toolbar/Widget/WidgetManager";
import WidgetRegistry from "@enhavo/app/Toolbar/Widget/WidgetRegistry";

export class MainApplication extends Application
{
    protected viewStack: ViewStack;
    protected menuManager: MenuManager;
    protected frameStorage: FrameStorage;
    protected viewRegistry: ViewRegistry;
    protected menuRegistry: MenuRegistry;
    protected dataStorageManager: DataStorageManager;
    protected stateManager: StateManager;
    protected globalDataStorageManager: GlobalDataStorageManager;
    protected widgetManager: WidgetManager;
    protected widgetRegistry: WidgetRegistry;

    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new MainApp(
                this.getDataLoader(),
                this.getViewStack(),
                this.getMenuManager(),
                this.getStateManager(),
                this.getDataStorageManager(),
                this.getWidgetManager(),
            );
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
            this.menuManager = new MenuManager(this.getDataLoader().load().menu, this.getMenuRegistry(), this.getGlobalDataStorageManager());
        }
        return this.menuManager;
    }

    public getMenuRegistry(): MenuRegistry
    {
        if(this.menuRegistry == null) {
            this.menuRegistry = new MenuRegistry();
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
        }
        return this.viewRegistry;
    }

    public getStateManager(): StateManager
    {
        if(this.stateManager == null) {
            this.stateManager = new StateManager(this.getViewStack(), this.getEventDispatcher(), this.getGlobalDataStorageManager());
        }
        return this.stateManager;
    }

    public getGlobalDataStorageManager(): GlobalDataStorageManager
    {
        if(this.globalDataStorageManager == null) {
            this.globalDataStorageManager = new GlobalDataStorageManager(this.getEventDispatcher(), this.getDataLoader().load().view_stack.storage);
        }
        return this.globalDataStorageManager;
    }

    public getWidgetRegistry(): WidgetRegistry
    {
        if(this.widgetRegistry == null) {
            this.widgetRegistry = new WidgetRegistry();
        }
        return this.widgetRegistry;
    }

    public getWidgetManager(): WidgetManager
    {
        if(this.widgetManager == null) {
            this.widgetManager = new WidgetManager(this.getDataLoader().load().toolbar.primaryWidgets, this.getDataLoader().load().toolbar.secondaryWidgets, this.getWidgetRegistry());
        }
        return this.widgetManager;
    }
}

let application = new MainApplication();
export default application;