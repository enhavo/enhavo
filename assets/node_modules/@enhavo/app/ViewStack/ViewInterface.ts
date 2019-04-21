import { ComponentAwareInterface } from "@enhavo/core/index";

export default interface ViewInterface extends ComponentAwareInterface
{
    id: number;
    label: string;
    children: ViewInterface[];
    parent: ViewInterface;
    loaded: boolean;
    width: string;
    minimize: boolean;
    priority: number;
    removed: boolean;
    position: number;
    url: string;
    customMinimized: boolean;

    finish(): void;
}