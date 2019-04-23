import { ComponentAwareInterface } from "@enhavo/core/index";

export default interface ColumnInterface extends ComponentAwareInterface
{
    sortable: boolean;
    directionDesc: boolean;
    key: string;
    property?: string;
}