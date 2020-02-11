import { Registry } from "@enhavo/core";
import ActionFactoryInterface from "./ActionFactoryInterface";
import RegistryInterface from "@enhavo/core/RegistryInterface";

export default class WidgetRegistry extends Registry
{
    getFactory(name: string): ActionFactoryInterface {
        return <ActionFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ActionFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }
}
