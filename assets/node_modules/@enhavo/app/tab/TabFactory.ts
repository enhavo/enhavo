import {TabInterface} from "./TabInterface";
import {ModelFactory} from "@enhavo/app/factory/ModelFactory";

export class TabFactory extends ModelFactory
{
    createWithData(name: string, data: object): TabInterface
    {
        return <TabInterface>super.createWithData(name, data)
    }

    createNew(name: string): TabInterface
    {
        return <TabInterface>super.createNew(name);
    }
}
