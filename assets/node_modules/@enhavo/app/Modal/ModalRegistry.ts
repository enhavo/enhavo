import { Registry } from "@enhavo/core";
import ModalFactoryInterface from "./ModalFactoryInterface";
import RegistryInterface from "@enhavo/core/RegistryInterface";

export default class ModalRegistry extends Registry
{
    getFactory(name: string): ModalFactoryInterface {
        return <ModalFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ModalFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }
}
