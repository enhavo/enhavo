import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/framework/model/ModelAwareInterface";

export interface TabInterface extends ComponentAwareInterface, ModelAwareInterface
{
    label: string;
    key: string;
    active: boolean;
    error: boolean;

    morph(source: TabInterface): void;
    update(parameters: object): void;
}
