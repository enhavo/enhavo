import FactoryInterface from "@enhavo/core/FactoryInterface";
import FilterInterface from "@enhavo/app/Grid/Filter/FilterInterface";

export default interface FilterFactoryInterface extends FactoryInterface
{
    createFromData(data: object): FilterInterface;
    createNew(): FilterInterface
}