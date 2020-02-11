import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import DropdownWidgetFactory from "@enhavo/app/Toolbar/Widget/Factory/DropdownWidgetFactory";
import QuickMenuWidgetFactory from "@enhavo/app/Toolbar/Widget/Factory/QuickMenuWidgetFactory";
import SearchWidgetFactory from "@enhavo/app/Toolbar/Widget/Factory/SearchWidgetFactory";

export default class WidgetRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.register('dropdown-widget', () => import('@enhavo/app/Toolbar/Widget/Components/DropdownWidgetComponent.vue'), new DropdownWidgetFactory(application));
        this.register('quick-menu-widget', () => import('@enhavo/app/Toolbar/Widget/Components/QuickMenuWidgetComponent.vue'), new QuickMenuWidgetFactory(application));
        this.register('search-widget', () => import('@enhavo/app/Toolbar/Widget/Components/SearchWidgetComponent.vue'), new SearchWidgetFactory(application));
    }
}