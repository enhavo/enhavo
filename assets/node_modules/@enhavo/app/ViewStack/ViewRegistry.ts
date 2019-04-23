import { Registry } from "@enhavo/core";
import ViewFactoryInterface from "./ViewFactoryInterface";
import IframeViewFactory from "@enhavo/app/ViewStack/Factory/IframeViewFactory";
import AjaxViewFactory from "@enhavo/app/ViewStack/Factory/AjaxViewFactory";
import IframeViewComponent from './Components/IframeViewComponent.vue'
import AjaxViewComponent from './Components/AjaxViewComponent.vue'

export default class ViewRegistry extends Registry
{
    getFactory(name: string): ViewFactoryInterface {
        return <ViewFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: ViewFactoryInterface): void {
        return super.register(name, component, factory);
    }

    load() {
        this.register('iframe-view', IframeViewComponent, new IframeViewFactory());
        this.register('ajax-view', AjaxViewComponent, new AjaxViewFactory());
    }
}
