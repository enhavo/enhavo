import * as _ from 'lodash';
import MenuInterface from "@enhavo/app/Menu/MenuInterface";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import MenuManager from "@enhavo/app/Menu/MenuManager";

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
        menu = _.extend(data, menu);
        return menu;
    }

    abstract createNew(): MenuInterface;
}