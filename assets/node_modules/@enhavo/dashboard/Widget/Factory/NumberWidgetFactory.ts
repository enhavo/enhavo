import NumberWidget from "@enhavo/dashboard/Widget/Model/NumberWidget";
import AbstractFactory from "@enhavo/dashboard/Widget/Factory/AbstractFactory";

export default class NumberWidgetFactory extends AbstractFactory
{
    createNew(): NumberWidget {
        return new NumberWidget(this.application)
    }
}