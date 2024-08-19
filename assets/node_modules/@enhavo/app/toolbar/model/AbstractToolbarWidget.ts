import {ToolbarWidgetInterface} from "@enhavo/app/toolbar/ToolbarWidgetInterface";

export abstract class AbstractToolbarWidget implements ToolbarWidgetInterface
{
    component: string;
    model: string;
}
