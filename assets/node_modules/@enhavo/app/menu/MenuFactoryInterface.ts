import FactoryInterface from "@enhavo/core/FactoryInterface";
import MenuInterface from "@enhavo/app/Menu/MenuInterface";

export default interface MenuFactoryInterface extends FactoryInterface
{
    createFromData(data: object): MenuInterface;
    createNew(): MenuInterface
}