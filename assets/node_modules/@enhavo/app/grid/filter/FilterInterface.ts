import { ComponentAwareInterface } from "@enhavo/core/index";

export default interface FilterInterface extends ComponentAwareInterface
{
    key: string;
    label: string;
    active: boolean;

    getValue(): string;
    getKey(): string;
    getLabel(): string;
    setActive(active: boolean): void;
    getActive(): boolean;
    reset(): void
}