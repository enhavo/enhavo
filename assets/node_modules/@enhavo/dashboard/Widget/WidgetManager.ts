import * as _ from 'lodash';
import WidgetInterface from "@enhavo/dashboard/Widget/WidgetInterface";
import WidgetRegistry from "@enhavo/dashboard/Widget/WidgetRegistry";

export default class WidgetManager
{
    private widgets: WidgetInterface[];
    private registry: WidgetRegistry;

    constructor(widgets: WidgetInterface[], registry: WidgetRegistry)
    {
        this.registry = registry;
        for (let i in widgets) {
            let widget = registry.getFactory(widgets[i].component).createFromData(widgets[i]);
            _.extend(widgets[i], widget);
        }
        this.widgets = widgets;
    }
}