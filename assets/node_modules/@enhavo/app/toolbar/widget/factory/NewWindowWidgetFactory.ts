import NewWindowWidget from "@enhavo/app/toolbar/widget/model/NewWindowWidget";
import AbstractFactory from "@enhavo/app/toolbar/widget/factory/AbstractFactory";

export default class NewWindowWidgetFactory extends AbstractFactory
{
    createNew(): NewWindowWidget {
        return new NewWindowWidget()
    }
}