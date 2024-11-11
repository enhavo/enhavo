import {AbstractColumn} from "@enhavo/app/column/model/AbstractColumn";

export class StateColumn extends AbstractColumn
{
    property: string;
    sortingProperty: string;
    wrap: boolean;
}