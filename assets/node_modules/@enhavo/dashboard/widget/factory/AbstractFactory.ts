import * as _ from 'lodash';
import WidgetInterface from "@enhavo/dashboard/widget/WidgetInterface";

export default abstract class AbstractFactory
{
    createFromData(data: object): WidgetInterface
    {
        let widget = this.createNew();
        widget = _.extend(data, widget);
        return widget;
    }

    abstract createNew(): WidgetInterface;
}