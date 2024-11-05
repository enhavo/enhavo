import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {MediaLibraryManager} from "@enhavo/media-library/manager/MediaLibraryManager";

export class MediaLibrarySelectAction extends AbstractAction
{
    constructor(
        private mediaLibraryManager: MediaLibraryManager,
    ) {
        super();
    }

    execute(): void
    {
        this.mediaLibraryManager.select();
    }
}
