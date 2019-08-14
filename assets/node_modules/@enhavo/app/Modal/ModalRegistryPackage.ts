import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import IframeModalFactory from "@enhavo/app/Modal/Factory/IframeModalFactory";
import AjaxFormModalFactory from "@enhavo/app/Modal/Factory/AjaxFormModalFactory";

export default class ModalRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.register('iframe-modal', () => import('@enhavo/app/Modal/Components/IframeModalComponent.vue'), new IframeModalFactory(application));
        this.register('ajax-form-modal', () => import('@enhavo/app/Modal/Components/AjaxFormModalComponent.vue'), new AjaxFormModalFactory(application));
    }
}