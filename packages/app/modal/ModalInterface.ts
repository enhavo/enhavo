import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/framework/model/ModelAwareInterface";
import {ModalManager} from "@enhavo/app/modal/ModalManager";

export interface ModalInterface extends ComponentAwareInterface, ModelAwareInterface
{
    modalManager: ModalManager;
    close() :void;
}
