import MediaItem from '@enhavo/media/Type/MediaItem';

export default class ImageCropperConfiguration
{
    openCropper: (item: MediaItem, format: string) => void;
    refresh: (item: MediaItem) => void;
}
