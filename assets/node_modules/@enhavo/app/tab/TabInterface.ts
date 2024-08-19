import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/app/model/ModelAwareInterface";

export interface TabInterface extends ComponentAwareInterface, ModelAwareInterface
{
    label: string;
    key: string;
    active: boolean;
    error: boolean;
}
