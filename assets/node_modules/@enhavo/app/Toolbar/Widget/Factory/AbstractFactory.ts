import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import * as _ from 'lodash';
import WidgetInterface from "@enhavo/app/Toolbar/Widget/WidgetInterface";

export default abstract class AbstractFactory
{
    protected application: ApplicationInterface;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    createFromData(data: object): WidgetInterface
    {
        let Widget = this.createNew();
        Widget = _.extend(data, Widget);
        return Widget;
    }

    abstract createNew(): WidgetInterface;
}