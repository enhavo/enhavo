import {MenuManager} from '@enhavo/app/menu/MenuManager';
import {Branding} from '@enhavo/app/model/Branding';
import {ToolbarWidgetManager} from "@enhavo/app/toolbar/ToolbarWidgetManager";
import {ToolbarWidgetInterface} from "@enhavo/app/toolbar/ToolbarWidgetInterface";
import {Router} from "@enhavo/app/routing/Router";
import {FrameStackSubscriber} from "../frame/FrameStackSubscriber";
import {FrameStateManager} from "../frame/FrameStateManager";
import {FrameManager} from "../frame/FrameManager";

export class MainManager
{
    public loading: boolean = true;
    public branding: Branding;
    public primaryToolbarWidgets: ToolbarWidgetInterface[];
    public secondaryToolbarWidgets: ToolbarWidgetInterface[];

    constructor(
        private frameSubscriber: FrameStackSubscriber,
        private frameStageManager: FrameStateManager,
        private frameManager: FrameManager,
        private menuManager: MenuManager,
        private widgetManager: ToolbarWidgetManager,
        private router: Router,
    ) {
    };

    async load()
    {
        this.frameStageManager.subscribe();
        this.frameSubscriber.subscribe();

        await this.frameStageManager.loadState();

        let url = this.router.generate('enhavo_app_admin_api_main');

        const response = await fetch(url);
        const data = await response.json();

        this.primaryToolbarWidgets = this.widgetManager.createToolbarWidgets(data['toolbarWidgetsPrimary']);
        this.secondaryToolbarWidgets = this.widgetManager.createToolbarWidgets(data['toolbarWidgetsSecondary']);

        let menuItems = this.menuManager.createMenuItems(data['menu']);
        this.menuManager.setMenuItems(menuItems);

        let frames = await this.frameManager.getFrames();
        if (frames.length === 0) {
            this.menuManager.start();
        }

        this.loading = false;
    }
}
