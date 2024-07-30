import {AbstractColumn} from "@enhavo/app/column/model/AbstractColumn";
export class TextColumn extends AbstractColumn
{
    property: string;
    sortingProperty: string;
    wrap: boolean;
    whitespace: string;
}
