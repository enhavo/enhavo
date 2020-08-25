import { Registry } from "@enhavo/core";
import ModalFactoryInterface from "./ModalFactoryInterface";

export default class ModalRegistry extends Registry
{
    getFactory(name: string): ModalFactoryInterface {
        return <ModalFactoryInterface>super.getFactory(name);
    }
}
