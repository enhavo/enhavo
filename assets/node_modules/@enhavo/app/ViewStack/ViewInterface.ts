import { ComponentAwareInterface } from "@enhavo/core/index";

export default interface ViewInterface extends ComponentAwareInterface {
    id: number;
    name: string;
    children: ViewInterface[];
    parent: ViewInterface;
    loaded: boolean;
    width: number;

    finish(): void;
}