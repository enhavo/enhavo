import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import OpenAction from "@enhavo/app/Action/Model/OpenAction";
import View from "@enhavo/app/View/View";

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