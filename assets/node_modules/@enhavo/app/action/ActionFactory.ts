import {ActionInterface} from "./ActionInterface";
import {ModelFactory} from "@enhavo/app/factory/ModelFactory";

export class ActionFactory extends ModelFactory
{
    createWithData(name: string, data: object): ActionInterface
    {
        return <ActionInterface>super.createWithData(name, data)
    }

    createNew(name: string): ActionInterface
    {
        return <ActionInterface>super.createNew(name);
    }
}