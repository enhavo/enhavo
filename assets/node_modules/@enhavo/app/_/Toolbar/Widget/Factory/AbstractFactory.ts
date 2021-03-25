import * as _ from 'lodash';
import WidgetInterface from "@enhavo/app/Toolbar/Widget/WidgetInterface";

export default abstract class AbstractFactory
{
    createFromData(data: object): WidgetInterface
    {
        let Widget = this.createNew();
        Widget = _.extend(data, Widget);
        return Widget;
    }

    abstract createNew(): WidgetInterface;
}