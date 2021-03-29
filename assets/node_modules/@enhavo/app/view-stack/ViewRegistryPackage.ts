import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import IframeViewFactory from "@enhavo/app/view-stack/Factory/IframeViewFactory";
import AjaxViewFactory from "@enhavo/app/view-stack/Factory/AjaxViewFactory";

export default class ViewRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.register('iframe-view', () => import('@enhavo/app/view-stack/components/IframeViewComponent.vue'), new IframeViewFactory());
        this.register('ajax-view', () => import('@enhavo/app/view-stack/components/AjaxViewComponent.vue'), new AjaxViewFactory());
    }
}