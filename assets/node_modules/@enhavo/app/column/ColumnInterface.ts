import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/app/model/ModelAwareInterface";

export interface ColumnInterface extends ComponentAwareInterface, ModelAwareInterface
{
    sortable: boolean;
    directionDesc: boolean;
    key: string;
    property?: string;
    sortingProperty?: string;
    condition: string;
    display: boolean;
    width: number;
    label: string;
}