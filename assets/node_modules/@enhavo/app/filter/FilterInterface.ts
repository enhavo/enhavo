import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/app/model/ModelAwareInterface";


export interface FilterInterface extends ComponentAwareInterface, ModelAwareInterface
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