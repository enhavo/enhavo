import PreviewAction from "@enhavo/app/Action/Model/PreviewAction";

export default class PreviewActionFactory
{
    createFromData(data: object): PreviewAction
    {
        let action = this.createNew();
        let object = <PreviewAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): PreviewAction {
        return new PreviewAction()
    }
}