import ViewStack from '@enhavo/app/view-stack/ViewStack';
import MenuManager from '@enhavo/app/menu/MenuManager';
import Branding from '@enhavo/app/main/Branding';
import StateManager from "@enhavo/app/state/StateManager";
import DataStorageManager from "@enhavo/app/view-stack/DataStorageManager";
import WidgetManager from "@enhavo/app/toolbar/widget/WidgetManager";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class MainApp
{
    public data: any;

    private readonly viewStack: ViewStack;
    private readonly menuManager: MenuManager;
    private readonly stateManager: StateManager;
    private readonly dataStorageManager: DataStorageManager;
    private readonly widgetManager: WidgetManager;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(
        brandingData: Branding,
        viewStack: ViewStack,
        menuManager: MenuManager,
        stateManager: StateManager,
        dataStorageManager: DataStorageManager,
        widgetManager: WidgetManager,
        componentRegistry: ComponentRegistryInterface
    ) {
        this.data = {
            branding: brandingData
        };

        this.viewStack = viewStack;
        this.dataStorageManager = dataStorageManager;
        this.menuManager = menuManager;
        this.stateManager = stateManager;
        this.widgetManager = widgetManager;
        this.componentRegistry = componentRegistry;
    }

    init() {
        this.componentRegistry.registerStore('mainApp', this);
        this.data = this.componentRegistry.registerData(this.data);

        this.widgetManager.init();
        this.menuManager.init();
        this.viewStack.init();

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