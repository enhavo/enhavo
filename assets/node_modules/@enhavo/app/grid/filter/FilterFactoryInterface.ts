import FactoryInterface from "@enhavo/core/FactoryInterface";
import FilterInterface from "@enhavo/app/grid/filter/FilterInterface";

export default interface FilterFactoryInterface extends FactoryInterface
{
    createFromData(data: object): FilterInterface;
    createNew(): FilterInterface
}