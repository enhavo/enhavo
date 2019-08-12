import FactoryInterface from "@enhavo/core/FactoryInterface";
import BatchInterface from "@enhavo/app/Grid/Batch/BatchInterface";

export default interface BatchFactoryInterface extends FactoryInterface
{
    createFromData(data: object): BatchInterface;
    createNew(): BatchInterface
}