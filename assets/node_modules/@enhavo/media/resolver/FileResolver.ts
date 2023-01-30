import {FileUrlResolverInterface} from "@enhavo/media/resolver/FileUrlResolverInterface";
import {File} from '@enhavo/media/model/File';

export class FileResolver implements FileUrlResolverInterface
{
    resolve(file: File, format: string = null): string
    {
        if (format) {
            return '/file/format/'+file.id+'/'+format+'/'+file.md5Checksum.substring(0,6)+'/'+file.filename;
        }
        return '/file/show/'+file.id+'/'+file.md5Checksum.substring(0,6)+'/'+file.filename;
    }
}
