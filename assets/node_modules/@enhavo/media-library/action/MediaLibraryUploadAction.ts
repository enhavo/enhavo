import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {MediaLibraryManager} from '@enhavo/media-library/manager/MediaLibraryManager'
export class MediaLibraryUploadAction extends AbstractAction
{
    constructor(
        private mediaLibraryManager: MediaLibraryManager
    ) {
        super();
    }

    execute(): void
    {
        if (this.mediaLibraryManager.uploadElement) {
            this.mediaLibraryManager.uploadElement.click();
        }
    }
}
