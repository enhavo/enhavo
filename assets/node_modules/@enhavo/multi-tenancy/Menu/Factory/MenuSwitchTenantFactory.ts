import AbstractFactory from "@enhavo/app/Menu/Factory/AbstractFactory";
import MenuDropdown from "@enhavo/app/Menu/Model/MenuDropdown";
import MenuSwitchTenantEventListener from "@enhavo/multi-tenancy/Menu/MenuSwitchTenantEventListener";
import * as _ from "lodash";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import MenuManager from "@enhavo/app/Menu/MenuManager";

export default class MenuSwitchTenantFactory extends AbstractFactory
{
    private readonly eventListener: MenuSwitchTenantEventListener;

    constructor(eventDispatcher: EventDispatcher, menuManager: MenuManager, eventListener: MenuSwitchTenantEventListener)
    {
        super(eventDispatcher, menuManager);
        this.eventListener = eventListener;
    }

    createFromData(data: object): MenuDropdown
    {
        this.eventListener.listen();
        return _.extend(data, this.createNew());
    }

    createNew(): MenuDropdown {
        return new MenuDropdown(this.eventDispatcher, this.menuManager);
    }
}
