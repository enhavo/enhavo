import DataLoader from '@enhavo/app/DataLoader';
import AppInterface from "@enhavo/app/AppInterface";
import ViewApp from "@enhavo/app/ViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import WidgetManager from "@enhavo/dashboard/Widget/WidgetManager";

export default class DashboardApp extends ViewApp implements AppInterface
{
    private widgetManager: WidgetManager;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View, widgetManager: WidgetManager)
    {
        super(loader, eventDispatcher, view);
        this.widgetManager = widgetManager;
    }
}