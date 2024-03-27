import { ComponentAwareInterface } from "@enhavo/core/index";

export default interface ActionInterface extends ComponentAwareInterface
{
    label: string;
    icon: string;
    key: string;
    execute(): void;
}