import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/app/model/ModelAwareInterface";

export interface MenuItemInterface extends ComponentAwareInterface, ModelAwareInterface
{
    clickable: boolean;
    key: string;
    children(): Array<MenuItemInterface>
    open(): void;
    close(): void;
    parent(): MenuItemInterface;
    setParent(parent: MenuItemInterface): void;
    getDescendants(): Array<MenuItemInterface>
    isActive(): boolean
}
