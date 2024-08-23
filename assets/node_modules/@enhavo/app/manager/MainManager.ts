import {FrameStack} from '@enhavo/app/frame/FrameStack';
import {MenuManager} from '@enhavo/app/menu/MenuManager';
import {Branding} from '@enhavo/app/model/Branding';
import {ToolbarWidgetManager} from "@enhavo/app/toolbar/ToolbarWidgetManager";
import {ToolbarWidgetInterface} from "@enhavo/app/toolbar/ToolbarWidgetInterface";
import {Router} from "@enhavo/app/routing/Router";
import {FrameStackSubscriber} from "../frame/FrameStackSubscriber";

export class MainManager
{
    public loading: boolean = true;
    public branding: Branding;
    public primaryToolbarWidgets: ToolbarWidgetInterface[];
    public secondaryToolbarWidgets: ToolbarWidgetInterface[];

    constructor(
        private frameSubscriber: FrameStackSubscriber,
        private menuManager: MenuManager,
        private widgetManager: ToolbarWidgetManager,
        private router: Router,
    ) {
    };

    async load()
    {
        this.frameSubscriber.subscribe();

        let url = this.router.generate('enhavo_app_admin_api_main');

        const response = await fetch(url);
        const data = await response.json();

        this.primaryToolbarWidgets = this.widgetManager.createToolbarWidgets(data['toolbarWidgetsPrimary']);
        this.secondaryToolbarWidgets = this.widgetManager.createToolbarWidgets(data['toolbarWidgetsSecondary']);

        let menuItems = this.menuManager.createMenuItems(data['menu']);
        this.menuManager.setMenuItems(menuItems);

        this.loading = false;
    }

    isCustomChange()
    {
        return this.data.customChange;
    }
}
