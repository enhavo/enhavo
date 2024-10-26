import {File} from '@enhavo/media/model/File';

export interface FileUrlResolverInterface
{
    resolve(file: File, format?: string, absolute?: boolean): string;
}
