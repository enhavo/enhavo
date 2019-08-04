import MenuManager from "@enhavo/app/Menu/MenuManager";
import MenuRegistry from "@enhavo/app/Menu/MenuRegistry";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default interface MenuAwareApplication extends ApplicationInterface
{
    getMenuManager(): MenuManager;
    getMenuRegistry(): MenuRegistry;
}