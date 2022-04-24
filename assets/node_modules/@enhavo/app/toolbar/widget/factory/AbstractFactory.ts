import * as _ from 'lodash';
import WidgetInterface from "@enhavo/app/toolbar/widget/WidgetInterface";

export default abstract class AbstractFactory
{
    createFromData(data: object): WidgetInterface
    {
        let Widget = this.createNew();
        Widget = _.extend(Widget, data);
        return Widget;
    }

    abstract createNew(): WidgetInterface;
}