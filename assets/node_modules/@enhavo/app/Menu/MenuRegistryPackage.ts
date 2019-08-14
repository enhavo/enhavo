import RegistryPackage from "@enhavo/core/RegistryPackage";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import MenuItemFactory from "@enhavo/app/Menu/Factory/MenuItemFactory";
import MenuListFactory from "@enhavo/app/Menu/Factory/MenuListFactory";
import MenuDropdownFactory from "@enhavo/app/Menu/Factory/MenuDropdownFactory";

export default class MenuRegistryPackage extends RegistryPackage
{
    constructor(application: ApplicationInterface) {
        super();
        this.register('menu-item', () => import('@enhavo/app/Menu/Components/MenuItemComponent.vue'), new MenuItemFactory(application));
        this.register('menu-list', () => import('@enhavo/app/Menu/Components/MenuListComponent.vue'), new MenuListFactory(application));
        this.register('menu-dropdown', () => import('@enhavo/app/Menu/Components/MenuDropdownComponent.vue'), new MenuDropdownFactory(application));
    }
}