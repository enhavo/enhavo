import { ComponentAwareInterface } from "@enhavo/core/index";

export default interface ActionInterface extends ComponentAwareInterface
{
    getValue(): string;
    getKey(): string;
    reset(): void
}