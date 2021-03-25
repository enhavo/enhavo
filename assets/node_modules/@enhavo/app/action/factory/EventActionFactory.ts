import AbstractFactory from "@enhavo/app/action/factory/AbstractFactory";
import EventAction from "@enhavo/app/action/model/EventAction";

export default class EventActionFactory extends AbstractFactory
{
    createNew(): EventAction {
        return new EventAction();
    }
}