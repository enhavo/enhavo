import ViewStack from '@enhavo/app/view-stack/ViewStack';
import ViewInterface from '@enhavo/app/view-stack/ViewInterface';
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
    private defaultTitle: string = ''

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
        this.defaultTitle = document.title;

        this.componentRegistry.registerStore('mainApp', this);
        this.data = this.componentRegistry.registerData(this.data);

        this.widgetManager.init();
        this.menuManager.init();
        this.viewStack.init();
        this.subscribe();

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

    private subscribe()
    {
        this.viewStack.getDispatcher().on('removed', async (event) => {
            window.setTimeout(() => {
                this.updateTitle()
            }, 200);
        });

        this.viewStack.getDispatcher().on('updated', async (event) => {
            this.updateTitle()
        });

        this.viewStack.getDispatcher().on('loaded', async (event) => {
            this.updateTitle()
        });
    }

    private updateTitle()
    {
        for (let view of this.viewStack.getViews()) {
            if (view.focus) {
                document.title = view.label != '' && view.label != null ? view.label : this.defaultTitle;
            }
        }
    }
}
