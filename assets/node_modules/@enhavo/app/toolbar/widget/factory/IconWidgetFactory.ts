import IconWidget from "@enhavo/app/toolbar/widget/model/IconWidget";
import AbstractFactory from "@enhavo/app/toolbar/widget/factory/AbstractFactory";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import MenuManager from "@enhavo/app/menu/MenuManager";

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