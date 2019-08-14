import { Registry } from "@enhavo/core";
import ColumnFactoryInterface from "@enhavo/app/Grid/Column/ColumnFactoryInterface";
import RegistryInterface from "@enhavo/core/RegistryInterface";

export default class ColumnRegistry extends Registry
{
    getFactory(name: string): ColumnFactoryInterface {
        return <ColumnFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ColumnFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }
}
