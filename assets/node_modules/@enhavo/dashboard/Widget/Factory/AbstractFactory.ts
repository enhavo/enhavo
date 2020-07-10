import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import * as _ from 'lodash';
import WidgetInterface from "@enhavo/dashboard/Widget/WidgetInterface";

export default abstract class AbstractFactory
{
    protected application: ApplicationInterface;

    constructor(application: ApplicationInterface)
    {
        this.application = application;
    }

    createFromData(data: object): WidgetInterface
    {
        let widget = this.createNew();
        widget = _.extend(data, widget);
        return widget;
    }

    abstract createNew(): WidgetInterface;
}