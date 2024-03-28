import Registry from "@enhavo/core/Registry";
import ActionFactoryInterface from "./ActionFactoryInterface";
import {RegistryInterface} from "@enhavo/core/RegistryInterface";

export default class ActionRegistry extends Registry
{
    getFactory(name: string): ActionFactoryInterface {
        return <ActionFactoryInterface>super.getFactory(name);
    }
}
