import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import EventAction from "@enhavo/app/Action/Model/EventAction";

export default class EventActionFactory extends AbstractFactory
{
    createFromData(data: object): EventAction
    {
        let action = this.createNew();
        let object = <EventAction>data;
        action.component = object.component;
        return action;
    }

    createNew(): EventAction {
        return new EventAction(this.application);
    }
}