import { Registry } from "@enhavo/core";
import BatchFactoryInterface from "@enhavo/app/Grid/Batch/BatchFactoryInterface";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import UrlFactory from "@enhavo/app/Grid/Batch/Factory/UrlFactory";
import ModalFactory from "@enhavo/app/Grid/Batch/Factory/ModalFactory";
import RegistryInterface from "@enhavo/core/RegistryInterface";

export default class BatchRegistry extends Registry
{
    getFactory(name: string): BatchFactoryInterface {
        return <BatchFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: BatchFactoryInterface): RegistryInterface
    {
        return super.register(name, component, factory);
    }

    load(application: ApplicationInterface)
    {
        this.register('batch-url', null, new UrlFactory(application));
        this.register('batch-modal', null, new ModalFactory(application));
    }
}
