import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/app/model/ModelAwareInterface";
import {ModalManager} from "@enhavo/app/modal/ModalManager";

export interface ModalInterface extends ComponentAwareInterface, ModelAwareInterface
{
    modalManager: ModalManager;
    close() :void;
}
