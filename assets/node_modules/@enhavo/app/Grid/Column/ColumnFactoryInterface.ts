import FactoryInterface from "@enhavo/core/FactoryInterface";
import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface";

export default interface ColumnFactoryInterface extends FactoryInterface
{
    createFromData(data: object): ColumnInterface;
    createNew(): ColumnInterface
}