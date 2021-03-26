import AbstractColumn from "@enhavo/app/grid/column/model/AbstractColumn";

export default class TextColumn extends AbstractColumn
{
    property: string;
    sortingProperty: string;
    wrap: boolean;
    whitespace: string;
}