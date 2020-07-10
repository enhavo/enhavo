import { Registry } from "@enhavo/core";
import WidgetFactoryInterface from "./WidgetFactoryInterface";
import RegistryInterface from "@enhavo/core/RegistryInterface";

export default class WidgetRegistry extends Registry
{
    getFactory(name: string): WidgetFactoryInterface {
        return <WidgetFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: WidgetFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }
}
