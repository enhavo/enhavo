import { Registry } from "@enhavo/core";
import WidgetFactoryInterface from "@enhavo/app/Toolbar/Widget/WidgetFactoryInterface";

export default class WidgetRegistry extends Registry
{
    getFactory(name: string): WidgetFactoryInterface {
        return <WidgetFactoryInterface>super.getFactory(name);
    }
}
