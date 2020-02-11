import WidgetInterface from "@enhavo/app/Toolbar/Widget/WidgetInterface";
import WidgetRegistry from "@enhavo/app/Toolbar/Widget/WidgetRegistry";
import * as _ from 'lodash';

export default class WidgetManager
{
    private primary: WidgetInterface[];
    private secondary: WidgetInterface[];
    private registry: WidgetRegistry;

    constructor(primary: WidgetInterface[], secondary: WidgetInterface[], registry: WidgetRegistry)
    {
        this.registry = registry;

        for (let i in primary) {
            let action = registry.getFactory(primary[i].component).createFromData(primary[i]);
            _.extend(primary[i], action);
        }
        this.primary = primary;

        for (let i in secondary) {
            let action = registry.getFactory(secondary[i].component).createFromData(secondary[i]);
            _.extend(secondary[i], action);
        }
        this.secondary = secondary;
    }
}