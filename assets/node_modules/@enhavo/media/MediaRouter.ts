import File from "@enhavo/media/Model/File";
import Router from "@enhavo/core/Router";

export default class MediaRouter
{
    private router: Router;

    constructor(router: Router)
    {
        this.router = router;
    }

    generate(file: File, absolute?: boolean) {
        return this.router.generate('enhavo_media_file_show', {
            id: file.id,
            shortMd5Checksum: file.md5Checksum,
            filename: file.filename
        }, absolute);
    }

    generateFormat(file: File, format: string, absolute?: boolean) {
        return this.router.generate('enhavo_media_file_format', {
            id: file.id,
            format: format,
            shortMd5Checksum: file.md5Checksum,
            filename: file.filename
        }, absolute);
    }

    generateDownload(file: File, absolute?: boolean) {
        return this.router.generate('enhavo_media_file_download', {
            id: file.id,
            shortMd5Checksum: file.md5Checksum,
            filename: file.filename
        }, absolute);
    }
}