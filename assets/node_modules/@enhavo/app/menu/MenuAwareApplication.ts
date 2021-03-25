import MenuManager from "@enhavo/app/menu/MenuManager";
import MenuRegistry from "@enhavo/app/menu/MenuRegistry";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default interface MenuAwareApplication extends ApplicationInterface
{
    getMenuManager(): MenuManager;
    getMenuRegistry(): MenuRegistry;
}