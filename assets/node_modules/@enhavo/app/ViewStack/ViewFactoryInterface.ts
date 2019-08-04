import FactoryInterface from "@enhavo/core/FactoryInterface";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";

export default interface ViewFactoryInterface extends FactoryInterface
{
    createFromData(data: object): ViewInterface;
    createNew(): ViewInterface
}