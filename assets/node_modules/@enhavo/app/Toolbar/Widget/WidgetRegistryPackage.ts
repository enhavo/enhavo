import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import IconWidgetFactory from "@enhavo/app/Toolbar/Widget/Factory/IconWidgetFactory";
import QuickMenuWidgetFactory from "@enhavo/app/Toolbar/Widget/Factory/QuickMenuWidgetFactory";

export default class WidgetRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.register('icon-widget', () => import('@enhavo/app/Toolbar/Widget/Components/IconWidgetComponent.vue'), new IconWidgetFactory(application));
        this.register('quick-menu-widget', () => import('@enhavo/app/Toolbar/Widget/Components/QuickMenuWidgetComponent.vue'), new QuickMenuWidgetFactory(application));
    }
}