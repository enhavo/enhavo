import FactoryInterface from "@enhavo/core/FactoryInterface";
import ActionInterface from "./ActionInterface";

export default interface ActionFactoryInterface extends FactoryInterface
{
    createFromData(data: object): ActionInterface;
    createNew(): ActionInterface
}