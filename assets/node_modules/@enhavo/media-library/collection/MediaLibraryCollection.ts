import {TableCollection} from "@enhavo/app/collection/model/TableCollection";
import {CollectionResourceItem} from "@enhavo/app/collection/CollectionResourceItem";

export class MediaLibraryCollection extends TableCollection
{
    public view: string = 'thumbnail';

    async open(row: CollectionResourceItem)
    {
        if (!this.isSelectMode()) {
            return await super.open(row);
        }

        this.changeSelect(row, !row.selected);
    }

    changeSelect(row: CollectionResourceItem, value: boolean)
    {
        if (this.isSelectMode() && !this.isMultipleSelection()) {
            if (row.selected) {
                super.changeSelect(row, false);
            } else {
                super.changeSelectAll(false);
                super.changeSelect(row, true);
            }
            return;
        }

        super.changeSelect(row, value);
    }

    public changeSelectAll(value: boolean): void
    {
        if (this.isSelectMode() && !this.isMultipleSelection() && value) {
            this.selectedAll = value;
            return;
        }

        super.changeSelectAll(value);
    }

    public resetIds()
    {
        super.changeSelectAll(false);
    }

    public isSelectMode()
    {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('mode') === 'select';
    }

    private isMultipleSelection()
    {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('multiple') === 'true' || urlParams.get('multiple') === '1';
    }
}
