import MenuInterface from "@enhavo/app/Menu/MenuInterface";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import MenuManager from "@enhavo/app/Menu/MenuManager";
import MenuAwareApplication from "@enhavo/app/Menu/MenuAwareApplication";

export default abstract class AbstractMenu implements MenuInterface
{
    public component: string;
    public label: string;
    public selected: boolean = false;
    protected application: ApplicationInterface;
    private parentItem: MenuInterface;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    children(): Array<MenuInterface> {
        return [];
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
        return (<MenuAwareApplication>this.application).getMenuManager();
    }
}