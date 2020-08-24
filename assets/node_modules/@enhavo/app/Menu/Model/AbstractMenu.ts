import MenuInterface from "@enhavo/app/Menu/MenuInterface";
import MenuManager from "@enhavo/app/Menu/MenuManager";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";

export default abstract class AbstractMenu implements MenuInterface
{
    private parentItem: MenuInterface;
    public component: string;
    public label: string;
    public selected: boolean = false;
    public key: string;
    public clickable: boolean = false;
    protected eventDispatcher: EventDispatcher;
    protected menuManager: MenuManager;

    constructor(eventDispatcher: EventDispatcher, menuManager: MenuManager)
    {
        this.eventDispatcher = eventDispatcher;
        this.menuManager = menuManager;
    }

    children(): Array<MenuInterface> {
        return [];
    }

    getDescendants(): Array<MenuInterface>
    {
        let descendants = [];
        for(let child of this.children()) {
            descendants.push(child);
            for(let descendant of child.getDescendants()) {
                descendants.push(descendant);
            }
        }
        return descendants;
    }

    unselect(): void {
        this.selected = false;
        for(let child of this.children()) {
            child.unselect();
        }
    }

    select(): void {
        if(this.parent()) {
            this.parent().select();
        }
        this.selected = true;
    }

    open(): void {

    }

    parent(): MenuInterface {
        return this.parentItem;
    }

    setParent(parent: MenuInterface): MenuInterface {
        return this.parentItem = parent;
    }

    protected getManager(): MenuManager
    {
        return this.menuManager;
    }
}
