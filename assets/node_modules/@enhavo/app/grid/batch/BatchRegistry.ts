import Registry from "@enhavo/core/Registry";
import BatchFactoryInterface from "@enhavo/app/grid/batch/BatchFactoryInterface";
import {RegistryInterface} from "@enhavo/core/RegistryInterface";

export default class BatchRegistry extends Registry
{
    getFactory(name: string): BatchFactoryInterface {
        return <BatchFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: BatchFactoryInterface): RegistryInterface
    {
        return super.register(name, component, factory);
    }
}
