import {AbstractBatch} from "@enhavo/app/batch/model/AbstractBatch";
import {MediaLibraryManager} from "@enhavo/media-library/manager/MediaLibraryManager";

export class MediaLibrarySelectBatch extends AbstractBatch
{
    public constructor(
        private mediaLibraryManager: MediaLibraryManager,
    ) {
        super();
    }

    async execute(ids: number[]): Promise<boolean>
    {
        this.mediaLibraryManager.select();
        return false;
    }
}
