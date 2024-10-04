import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/app/model/ModelAwareInterface";
import {Action} from "@hotwired/stimulus/dist/types/core/action";

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
