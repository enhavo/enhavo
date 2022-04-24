import * as _ from 'lodash';
import MenuInterface from "@enhavo/app/menu/MenuInterface";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import MenuManager from "@enhavo/app/menu/MenuManager";

export default abstract class AbstractFactory
{
    protected eventDispatcher: EventDispatcher;
    protected menuManager: MenuManager;

    constructor(eventDispatcher: EventDispatcher, menuManager: MenuManager)
    {
        this.eventDispatcher = eventDispatcher;
        this.menuManager = menuManager;
    }

    createFromData(data: object): MenuInterface
    {
        let menu = this.createNew();
        menu = _.assign(menu, data);
        return menu;
    }

    abstract createNew(): MenuInterface;
}