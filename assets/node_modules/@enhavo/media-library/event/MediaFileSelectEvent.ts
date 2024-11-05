import {Event} from "@enhavo/app/frame/FrameEventDispatcher";
import {File} from "@enhavo/media/model/File";

export class MediaFileSelectEvent extends Event
{
    constructor(
        public files: File[],
        public fullName: string,
        public parent: string,
    )
    {
        super('media_file_select');
    }
}
