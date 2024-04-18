import Registry from "@enhavo/core/Registry";
import ColumnFactoryInterface from "@enhavo/app/grid/column/ColumnFactoryInterface";
import {RegistryInterface} from "@enhavo/core/RegistryInterface";

export default class ColumnRegistry extends Registry
{
    getFactory(name: string): ColumnFactoryInterface {
        return <ColumnFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ColumnFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }
}
