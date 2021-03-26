import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import OpenAction from "@enhavo/app/action/model/OpenAction";
import View from "@enhavo/app/view/View";

export default class OpenActionFactory extends AbstractFactory
{
    private readonly view: View;

    constructor(view: View) {
        super();
        this.view = view;
    }

    createNew(): OpenAction {
        return new OpenAction(this.view)
    }
}