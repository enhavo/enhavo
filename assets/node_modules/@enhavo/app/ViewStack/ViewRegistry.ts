import { Registry } from "@enhavo/core";
import ViewFactoryInterface from "./ViewFactoryInterface";
import IframeViewFactory from "@enhavo/app/ViewStack/Factory/IframeViewFactory";
import AjaxViewFactory from "@enhavo/app/ViewStack/Factory/AjaxViewFactory";
import RegistryInterface from '@enhavo/core/RegistryInterface';

export default class ViewRegistry extends Registry
{
    getFactory(name: string): ViewFactoryInterface {
        return <ViewFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ViewFactoryInterface): RegistryInterface {
        return super.register(name, component, factory);
    }

    load() {
        this.register('iframe-view', () => import('@enhavo/app/ViewStack/Components/IframeViewComponent.vue'), new IframeViewFactory());
        this.register('ajax-view', () => import('@enhavo/app/ViewStack/Components/AjaxViewComponent.vue'), new AjaxViewFactory());
    }
}
