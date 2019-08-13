import { Registry } from "@enhavo/core";
import ModalFactoryInterface from "./ModalFactoryInterface";
import IframeModalFactory from "@enhavo/app/Modal/Factory/IframeModalFactory";
import AjaxFormModalFactory from "@enhavo/app/Modal/Factory/AjaxFormModalFactory";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import RegistryInterface from "@enhavo/core/RegistryInterface";

export default class ModalRegistry extends Registry
{
    getFactory(name: string): ModalFactoryInterface {
        return <ModalFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ModalFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }

    load(application: ApplicationInterface)
    {
        this.register('iframe-modal', () => import('@enhavo/app/Modal/Components/IframeModalComponent.vue'), new IframeModalFactory(application));
        this.register('ajax-form-modal', () => import('@enhavo/app/Modal/Components/AjaxFormModalComponent.vue'), new AjaxFormModalFactory(application));
    }
}
