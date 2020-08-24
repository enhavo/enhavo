import QuickMenuWidget from "@enhavo/app/Toolbar/Widget/Model/QuickMenuWidget";
import AbstractFactory from "@enhavo/app/Toolbar/Widget/Factory/AbstractFactory";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import MenuManager from "@enhavo/app/Menu/MenuManager";

export default class SearchWidgetFactory extends AbstractFactory
{
    private readonly eventDispatcher: EventDispatcher;
    private readonly menuManager: MenuManager;

    constructor(eventDispatcher: EventDispatcher, menuManager: MenuManager) {
        super();
        this.eventDispatcher = eventDispatcher;
        this.menuManager = menuManager;
    }

    createNew(): QuickMenuWidget {
        return new QuickMenuWidget(this.eventDispatcher, this.menuManager);
    }
}