import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/app/model/ModelAwareInterface";

export interface ActionInterface extends ComponentAwareInterface, ModelAwareInterface
{
    label: string;
    icon: string;
    key: string;
    execute(): void;
}