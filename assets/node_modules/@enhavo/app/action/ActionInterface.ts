import { ComponentAwareInterface } from "@enhavo/core/index";

export default interface ActionInterface extends ComponentAwareInterface
{
    execute(): void;
}