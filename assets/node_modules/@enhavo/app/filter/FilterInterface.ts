import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/app/model/ModelAwareInterface";

export interface FilterInterface extends ComponentAwareInterface, ModelAwareInterface
{
    key: string;
    label: string;
    active: boolean;
    value: any;
    initialValue: any;

    reset(): void
}
