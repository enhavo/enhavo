import FactoryInterface from "@enhavo/core/FactoryInterface";
import WidgetInterface from "./WidgetInterface";

export default interface WidgetFactoryInterface extends FactoryInterface
{
    createFromData(data: object): WidgetInterface;
    createNew(): WidgetInterface
}