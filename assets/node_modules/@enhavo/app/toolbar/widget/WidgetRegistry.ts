import Registry from "@enhavo/core/Registry";
import WidgetFactoryInterface from "@enhavo/app/toolbar/widget/WidgetFactoryInterface";

export default class WidgetRegistry extends Registry
{
    getFactory(name: string): WidgetFactoryInterface {
        return <WidgetFactoryInterface>super.getFactory(name);
    }
}
