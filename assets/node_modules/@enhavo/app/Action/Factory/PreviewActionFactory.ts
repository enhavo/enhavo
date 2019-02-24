import PreviewAction from "@enhavo/app/Action/Model/PreviewAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";

export default class PreviewActionFactory extends AbstractFactory
{
    createFromData(data: object): PreviewAction
    {
        let action = this.createNew();
        let object = <PreviewAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): PreviewAction {
        return new PreviewAction(this.application);
    }
}