import {ToolbarWidgetInterface} from "./ToolbarWidgetInterface";
import {ToolbarWidgetFactory} from "./ToolbarWidgetFactory";

export class ToolbarWidgetManager
{
    constructor(
        private factory: ToolbarWidgetFactory,
    )
    {
    }

    createToolbarWidgets(widgets: object[]): ToolbarWidgetInterface[]
    {
        let data = [];
        for (let i in widgets) {
            data.push(this.createToolbarWidget(widgets[i]));
        }
        return data;
    }

    createToolbarWidget(widget: object): ToolbarWidgetInterface
    {
        if (!widget.hasOwnProperty('model')) {
            throw 'The action data needs a "model" property!';
        }

        return this.factory.createWithData(widget['model'], widget);
    }
}
