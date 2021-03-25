import FactoryInterface from "@enhavo/core/FactoryInterface";
import ColumnInterface from "@enhavo/app/grid/column/ColumnInterface";

export default interface ColumnFactoryInterface extends FactoryInterface
{
    createFromData(data: object): ColumnInterface;
    createNew(): ColumnInterface
}