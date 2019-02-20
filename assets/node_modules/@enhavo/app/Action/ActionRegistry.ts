import { Registry } from "@enhavo/core";
import ActionFactoryInterface from "./ActionFactoryInterface";

export default class ActionRegistry extends Registry
{
    getFactory(name: string): ActionFactoryInterface {
        return <ActionFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ActionFactoryInterface): void {
        return super.register(name, component, factory);
    }
}
