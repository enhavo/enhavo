import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import IframeViewFactory from "@enhavo/app/ViewStack/Factory/IframeViewFactory";
import AjaxViewFactory from "@enhavo/app/ViewStack/Factory/AjaxViewFactory";

export default class ViewRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.register('iframe-view', () => import('@enhavo/app/ViewStack/Components/IframeViewComponent.vue'), new IframeViewFactory());
        this.register('ajax-view', () => import('@enhavo/app/ViewStack/Components/AjaxViewComponent.vue'), new AjaxViewFactory());
    }
}