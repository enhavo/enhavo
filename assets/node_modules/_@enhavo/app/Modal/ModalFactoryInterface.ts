import FactoryInterface from "@enhavo/core/FactoryInterface";
import ModalInterface from "./ModalInterface";

export default interface ModalFactoryInterface extends FactoryInterface
{
    createFromData(data: object): ModalInterface;
    createNew(): ModalInterface
}