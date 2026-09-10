import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/framework/model/ModelAwareInterface";

export interface ActionInterface extends ComponentAwareInterface, ModelAwareInterface
{
    label: string;
    icon: string;
    key: string;
    class: string
    execute(): void;
    morph(source: ActionInterface): void;
    mounted(): void;
}
