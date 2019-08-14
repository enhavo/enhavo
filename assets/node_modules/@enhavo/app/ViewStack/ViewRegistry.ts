import { Registry } from "@enhavo/core";
import ViewFactoryInterface from "./ViewFactoryInterface";
import RegistryInterface from '@enhavo/core/RegistryInterface';

export default class ViewRegistry extends Registry
{
    getFactory(name: string): ViewFactoryInterface {
        return <ViewFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ViewFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }
}
