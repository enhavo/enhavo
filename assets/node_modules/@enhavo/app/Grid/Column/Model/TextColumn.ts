import AbstractColumn from "@enhavo/app/Grid/Column/Model/AbstractColumn";

export default class TextColumn extends AbstractColumn
{
    property: string;
    sortingProperty: string;
    wrap: boolean;
}