import MediaItem from "@enhavo/media/MediaLibrary/MediaItem";

export default class MediaData
{
    items: MediaItem[];
    progress: number;
    dropZone: boolean = false;
    dropZoneActive: boolean = false;
    loading: boolean = false;
    page: number = 1;
    count: number;
    editView: number = null;
    updateRoute: string;
}