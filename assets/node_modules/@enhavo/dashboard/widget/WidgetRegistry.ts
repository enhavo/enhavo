import Registry from "@enhavo/core/Registry";
import WidgetFactoryInterface from "./WidgetFactoryInterface";

export default class WidgetRegistry extends Registry
{
    getFactory(name: string): WidgetFactoryInterface {
        return <WidgetFactoryInterface>super.getFactory(name);
    }
}
