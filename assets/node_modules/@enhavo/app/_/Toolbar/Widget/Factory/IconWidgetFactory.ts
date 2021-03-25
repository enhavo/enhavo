import IconWidget from "@enhavo/app/Toolbar/Widget/Model/IconWidget";
import AbstractFactory from "@enhavo/app/Toolbar/Widget/Factory/AbstractFactory";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import MenuManager from "@enhavo/app/Menu/MenuManager";

export default class IconWidgetFactory extends AbstractFactory
{
    private readonly eventDispatcher: EventDispatcher;
    private readonly menuManager: MenuManager;

    constructor(eventDispatcher: EventDispatcher, menuManager: MenuManager) {
        super();
        this.eventDispatcher = eventDispatcher;
        this.menuManager = menuManager;
    }

    createNew(): IconWidget {
        return new IconWidget(this.eventDispatcher, this.menuManager);
    }
}