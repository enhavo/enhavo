<?php

namespace Enhavo\Bundle\MediaBundle\Documentation;

use Enhavo\Bundle\ApiBundle\Documentation\DescriberInterface;
use Enhavo\Bundle\ApiBundle\Documentation\Model\Documentation;

class MediaFileDescriber implements DescriberInterface
{
    public function describe(Documentation $documentation, array $options = []): void
    {
        if (!$documentation->components()->hasSchema('MediaFile')) {
            $fileSchema = $documentation->components()->schema('MediaFile');
            $fileSchema->object()
                ->property('id', 'integer')->description('File ID')->end()
                ->property('token', 'string')->description('File token')->end()
                ->property('filename', 'string')->description('Original filename with extension')->end()
                ->property('basename', 'string')->description('Filename without extension')->end()
                ->property('extension', 'string')->description('File extension')->end()
                ->property('mimeType', 'string')->description('MIME type')->end()
                ->property('checksum', 'string')->description('File checksum')->end()
                ->property('shortChecksum', 'string')->description('Short file checksum')->end()
            ;
        }
    }
}
