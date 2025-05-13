import {File} from '@enhavo/media/model/File';

export class MediaUtil
{
    static isImage(file: File): boolean
    {
        switch (file.mimeType) {
            case 'image/png':
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/gif':
            case 'image/webp':
                return true;
        }

        return false;
    }

    static getIcon(file: File): string
    {
        switch (file.mimeType) {
            case 'video/mpeg':
            case 'video/mp4':
            case 'video/quicktime':
            case 'video/vnd.vivo':
            case 'video/x-msvideo':
            case 'video/x-sgi-movie':
                return 'file_video';
            case 'audio/basic':
            case 'audio/echospeech':
            case 'audio/tsplayer':
            case 'audio/voxware':
            case 'audio/x-aiff':
            case 'audio/x-dspeeh':
            case 'audio/x-midi':
            case 'audio/x-mpeg':
            case 'audio/x-pn-realaudio':
            case 'audio/x-pn-realaudio-plugin':
            case 'audio/x-qt-stream:':
            case 'audio/x-wav':
                return 'file_audio';
            case 'text/plain':
            case 'application/postscript':
            case 'application/rtf':
            case 'application/msword':
            case 'application/vnd.oasis.opendocument.text':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return 'file_text';
            case 'application/pdf':
                return 'file_pdf';
            case 'application/msexcel':
            case 'application/vnd.oasis.opendocument.spreadsheet':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                return 'file_xls';
            case 'application/mspowerpoint':
                return 'file_ppt';
            case 'text/css':
            case 'text/html':
            case 'text/javascript':
            case 'text/xml':
            case 'text/x-php':
            case 'application/json':
            case 'application/xhtml+xml':
            case 'application/xml':
            case 'application/x-httpd-php':
            case 'application/x-javascript':
            case 'application/x-latex':
            case 'application/x-php':
                return 'file_code';
        }

        return 'file_document';
    }
}