import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/framework/model/ModelAwareInterface";

export interface MenuItemInterface extends ComponentAwareInterface, ModelAwareInterface
{
    clickable: boolean;
    key: string;
    children: Array<MenuItemInterface>
    parent: MenuItemInterface;
    active: boolean

    open(): void;
    close(): void;

    getDescendants(): Array<MenuItemInterface>
}
