import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import IconWidgetFactory from "@enhavo/app/toolbar/widget/factory/IconWidgetFactory";
import NewWindowWidgetFactory from "@enhavo/app/toolbar/widget/factory/NewWindowWidgetFactory";
import QuickMenuWidgetFactory from "@enhavo/app/toolbar/widget/factory/QuickMenuWidgetFactory";

export default class WidgetRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.register('icon-widget', () => import('@enhavo/app/toolbar/widget/components/IconWidgetComponent.vue'), new IconWidgetFactory(application));
        this.register('quick-menu-widget', () => import('@enhavo/app/toolbar/widget/components/QuickMenuWidgetComponent.vue'), new QuickMenuWidgetFactory(application));
        this.register('new-window-widget', () => import('@enhavo/app/toolbar/widget/components/IconWidgetComponent.vue'), new NewWindowWidgetFactory(application));
    }
}