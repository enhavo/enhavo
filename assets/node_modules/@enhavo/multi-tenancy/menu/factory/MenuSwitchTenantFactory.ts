import AbstractFactory from "@enhavo/app/menu/factory/AbstractFactory";
import MenuDropdown from "@enhavo/app/menu/model/MenuDropdown";
import MenuSwitchTenantEventListener from "@enhavo/multi-tenancy/menu/MenuSwitchTenantEventListener";
import * as _ from "lodash";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import MenuManager from "@enhavo/app/menu/MenuManager";

export default class MenuSwitchTenantFactory extends AbstractFactory
{
    private readonly eventListener: MenuSwitchTenantEventListener;

    constructor(eventDispatcher: EventDispatcher, menuManager: MenuManager, eventListener: MenuSwitchTenantEventListener)
    {
        super(eventDispatcher, menuManager);
        this.eventListener = eventListener;
    }

    createNew(): MenuDropdown {
        this.eventListener.listen();
        return new MenuDropdown(this.eventDispatcher, this.menuManager);
    }
}
