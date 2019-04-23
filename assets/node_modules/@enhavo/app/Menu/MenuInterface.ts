import { ComponentAwareInterface } from "@enhavo/core/index";

export default interface MenuInterface extends ComponentAwareInterface
{
    children(): Array<MenuInterface>
    unselect(): void;
    select(): void;
    open(): void;
    parent(): MenuInterface;
    setParent(parent: MenuInterface): void;
}