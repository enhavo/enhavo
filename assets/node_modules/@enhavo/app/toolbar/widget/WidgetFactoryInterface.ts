import FactoryInterface from "@enhavo/core/FactoryInterface";
import WidgetInterface from "@enhavo/app/toolbar/widget/WidgetInterface";

export default interface ActionFactoryInterface extends FactoryInterface
{
    createFromData(data: object): WidgetInterface;
    createNew(): WidgetInterface
}