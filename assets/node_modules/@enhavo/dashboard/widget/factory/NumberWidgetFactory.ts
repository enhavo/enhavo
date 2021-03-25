import NumberWidget from "@enhavo/dashboard/widget/model/NumberWidget";
import AbstractFactory from "@enhavo/dashboard/widget/factory/AbstractFactory";

export default class NumberWidgetFactory extends AbstractFactory
{
    createNew(): NumberWidget {
        return new NumberWidget(this.application)
    }
}