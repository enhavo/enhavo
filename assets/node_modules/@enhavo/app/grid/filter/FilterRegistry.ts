import Registry from "@enhavo/core/Registry";
import FilterFactoryInterface from "@enhavo/app/grid/filter/FilterFactoryInterface";
import {RegistryInterface} from "@enhavo/core/RegistryInterface";

export default class FilterRegistry extends Registry
{
    getFactory(name: string): FilterFactoryInterface {
        return <FilterFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: FilterFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }
}
