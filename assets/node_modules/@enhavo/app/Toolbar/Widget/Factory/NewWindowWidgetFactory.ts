import NewWindowWidget from "@enhavo/app/Toolbar/Widget/Model/NewWindowWidget";
import AbstractFactory from "@enhavo/app/Toolbar/Widget/Factory/AbstractFactory";

export default class NewWindowWidgetFactory extends AbstractFactory
{
    createNew(): NewWindowWidget {
        return new NewWindowWidget()
    }
}