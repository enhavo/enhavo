import { Registry } from "@enhavo/core";
import ViewFactoryInterface from "./ViewFactoryInterface";

export default class ViewRegistry extends Registry
{
    getFactory(name: string): ViewFactoryInterface {
        return <ViewFactoryInterface>super.getFactory(name);
    }
}
