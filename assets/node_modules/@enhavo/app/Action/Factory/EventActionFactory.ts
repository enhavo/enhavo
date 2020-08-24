import AbstractFactory from "@enhavo/app/Action/Factory/AbstractFactory";
import EventAction from "@enhavo/app/Action/Model/EventAction";

export default class EventActionFactory extends AbstractFactory
{
    createNew(): EventAction {
        return new EventAction();
    }
}