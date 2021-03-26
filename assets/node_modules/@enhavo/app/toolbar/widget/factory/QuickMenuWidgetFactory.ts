import QuickMenuWidget from "@enhavo/app/toolbar/widget/model/QuickMenuWidget";
import AbstractFactory from "@enhavo/app/toolbar/widget/factory/AbstractFactory";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import MenuManager from "@enhavo/app/menu/MenuManager";

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