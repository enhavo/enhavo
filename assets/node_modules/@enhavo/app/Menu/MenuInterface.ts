import { ComponentAwareInterface } from "@enhavo/core/index";

export default interface MenuInterface extends ComponentAwareInterface
{
    clickable: boolean;
    key: string;
    children(): Array<MenuInterface>
    unselect(): void;
    select(): void;
    open(): void;
    parent(): MenuInterface;
    setParent(parent: MenuInterface): void;
    getDescendants(): Array<MenuInterface>
}