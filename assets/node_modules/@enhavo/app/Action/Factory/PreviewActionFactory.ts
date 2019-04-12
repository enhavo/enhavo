import PreviewAction from "@enhavo/app/Action/Model/PreviewAction";
import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import * as _ from 'lodash';

export default class PreviewActionFactory extends AbstractFactory
{
    createFromData(data: object): PreviewAction
    {
        let action = this.createNew();
        action = _.extend(data, action);
        action.loadListener();
        return action;
    }

    createNew(): PreviewAction {
        return new PreviewAction(this.application);
    }
}