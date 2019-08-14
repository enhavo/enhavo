import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import UrlFactory from "@enhavo/app/Grid/Batch/Factory/UrlFactory";
import ModalFactory from "@enhavo/app/Grid/Batch/Factory/ModalFactory";

export default class BatchRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.register('batch-url', null, new UrlFactory(application));
        this.register('batch-modal', null, new ModalFactory(application));
    }
}