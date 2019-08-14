import { ComponentAwareInterface } from "@enhavo/core/index";

export default interface ModalInterface extends ComponentAwareInterface
{
    init(): void;
}