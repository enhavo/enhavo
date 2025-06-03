import {AbstractColumn} from "@enhavo/app/column/model/AbstractColumn";

export class BooleanColumn extends AbstractColumn
{
    property: string;
    sortingProperty: string;
}

export class BooleanColumnData
{
    key: string;
    value: boolean|null
}