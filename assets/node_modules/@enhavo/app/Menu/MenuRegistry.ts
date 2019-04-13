import { Registry } from "@enhavo/core";
import MenuFactoryInterface from "@enhavo/app/Menu/MenuFactoryInterface";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import MenuListFactory from "@enhavo/app/Menu/Factory/MenuListFactory";
import MenuItemFactory from "@enhavo/app/Menu/Factory/MenuItemFactory";
import MenuDropdownFactory from "@enhavo/app/Menu/Factory/MenuDropdownFactory";

export default class MenuRegistry extends Registry
{
    getFactory(name: string): MenuFactoryInterface {
        return <MenuFactoryInterface>super.getFactory(name);
    }

    register(name: string, component: object, factory: MenuFactoryInterface): void {
        return super.register(name, component, factory);
    }

    load(application: ApplicationInterface)
    {
        this.register('menu-item', () => import('@enhavo/app/Menu/Components/MenuItemComponent.vue'), new MenuItemFactory(application));
        this.register('menu-list', () => import('@enhavo/app/Menu/Components/MenuListComponent.vue'), new MenuListFactory(application));
        this.register('menu-dropdown', () => import('@enhavo/app/Menu/Components/MenuDropdownComponent.vue'), new MenuDropdownFactory(application));
    }
}
