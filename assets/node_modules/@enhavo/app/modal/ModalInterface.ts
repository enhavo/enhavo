import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/app/model/ModelAwareInterface";

export interface ModalInterface extends ComponentAwareInterface, ModelAwareInterface
{
    init(): void;
}