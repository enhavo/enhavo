import {ToolbarWidgetInterface} from "./ToolbarWidgetInterface";
import {ModelFactory} from "@enhavo/app/factory/ModelFactory";

export class ToolbarWidgetFactory extends ModelFactory
{
    createWithData(name: string, data: object): ToolbarWidgetInterface
    {
        return <ToolbarWidgetInterface>super.createWithData(name, data)
    }

    createNew(name: string): ToolbarWidgetInterface
    {
        return <ToolbarWidgetInterface>super.createNew(name);
    }
}
