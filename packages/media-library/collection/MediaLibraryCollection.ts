import {TableCollection} from "@enhavo/app/collection/model/TableCollection";
import {CollectionResourceItem} from "@enhavo/app/collection/CollectionResourceItem";
import {FrameAdded, FrameUpdated} from "@enhavo/app/frame/FrameStack";

export class MediaLibraryCollection extends TableCollection
{
    public view: string = 'thumbnail';
    public multiple: boolean = false;

    init() {
        super.init();

        if (this.isSelectMode()) {
            this.frameManager.on('frame_updated', async (event) => {
                let frame = await this.frameManager.getFrame();
                let updatedFrame = (event as FrameUpdated).frame;
                if (frame && frame.id === updatedFrame.id) {
                    this.multiple = !!updatedFrame.parameters['multiple'];
                }
            }, 100);

            // if frame is keep alive, it can happen that current frame is added again
            this.frameManager.on('frame_added', async (event) => {
                let frame = await this.frameManager.getFrame();
                let updatedFrame = (event as FrameAdded).frame;

                if (frame && frame.id === updatedFrame.id) {
                    this.multiple = !!updatedFrame.parameters['multiple'];
                }
            }, 100);

            this.frameManager.on('update_media_collection', () => {
                this.load();
            })
        }
    }

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
        if (urlParams.has('multiple')) {
            return urlParams.get('multiple') === 'true' || urlParams.get('multiple') === '1';
        }
        return this.multiple;
    }
}
