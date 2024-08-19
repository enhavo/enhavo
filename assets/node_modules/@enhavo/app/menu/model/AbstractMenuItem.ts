import {MenuItemInterface} from "@enhavo/app/menu/MenuItemInterface";

export abstract class AbstractMenuItem implements MenuItemInterface
{
    public model: string;
    public component: string;
    public label: string;
    public selected: boolean = false;
    public key: string;
    public clickable: boolean = false;
    protected parentItem: MenuItemInterface;

    children(): Array<MenuItemInterface> {
        return [];
    }

    getDescendants(): Array<MenuItemInterface> {
        let descendants = [];
        for (let child of this.children()) {
            descendants.push(child);
            for (let descendant of child.getDescendants()) {
                descendants.push(descendant);
            }
        }
        return descendants;
    }

    unselect(): void {
        this.selected = false;
        for (let child of this.children()) {
            child.unselect();
        }
    }

    select(): void {
        if (this.parent()) {
            this.parent().select();
        }
        this.selected = true;
    }

    open(): void {}

    parent(): MenuItemInterface {
        return this.parentItem;
    }

    setParent(parent: MenuItemInterface): MenuItemInterface {
        return (this.parentItem = parent);
    }

    abstract isActive(): boolean;
}
