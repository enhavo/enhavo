import ViewStack from '@enhavo/app/view-stack/ViewStack';
import {MenuManager} from '@enhavo/app/menu/MenuManager';
import {Branding} from '@enhavo/app/model/Branding';
import StateManager from "@enhavo/app/state/StateManager";
import DataStorageManager from "@enhavo/app/view-stack/DataStorageManager";
import {ToolbarWidgetManager} from "@enhavo/app/toolbar/ToolbarWidgetManager";
import {ToolbarWidgetInterface} from "@enhavo/app/toolbar/ToolbarWidgetInterface";
import {Router} from "@enhavo/app/routing/Router";

export class MainManager
{
    public loading: boolean = true;
    public branding: Branding;
    public menuOpen: boolean = true;
    public menuItems: ToolbarWidgetInterface[];
    public primaryToolbarWidgets: ToolbarWidgetInterface[];
    public secondaryToolbarWidgets: ToolbarWidgetInterface[];

    constructor(
        private viewStack: ViewStack,
        private menuManager: MenuManager,
        private stateManager: StateManager,
        private dataStorageManager: DataStorageManager,
        private widgetManager: ToolbarWidgetManager,
        private router: Router,
    ) {
    };

    async load()
    {
        let url = this.router.generate('enhavo_app_admin_api_main');

        const response = await fetch(url);
        const data = await response.json();

        this.primaryToolbarWidgets = this.widgetManager.createToolbarWidgets(data['toolbarWidgetsPrimary']);
        this.secondaryToolbarWidgets = this.widgetManager.createToolbarWidgets(data['toolbarWidgetsSecondary']);
        this.menuItems = this.menuManager.createMenuItems(data['menu']);

        this.loading = false;
    }

    toogleMenu()
    {
        this.menuOpen = !this.menuOpen;
    }


    clearSelections() {
        for(let item of this.data.items) {
            item.unselect();
        }
    }

    start() {
        if (this.data.items && this.data.items.length > 0) {
            this.clearSelections();
            for(let item of this.data.items) {
                if(item.clickable) {
                    item.select();
                    item.open();
                    return;
                }
            }
        }
    }

    setActive(key: string) {
        this.dataStorage.set('menu-active-key', key);
    }

    getItems(): Array<MenuItemInterface>
    {
        let items = [];
        for (let item of this.data.items) {
            items.push(item);
            for (let descendant of item.getDescendants()) {
                items.push(descendant);
            }
        }
        return items;
    }

    isCustomChange()
    {
        return this.data.customChange;
    }
}
