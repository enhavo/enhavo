## Format

File formats are variants of media files. In order to keep the original
file untouched, we use formats to change the content of files and save
them separate.

To create a format we apply filters on the original
file. If we want to use a set of different filters, we chain them
together.

Enhavo comes already with a set of filters, that covers most use
cases. That includes resizing, compression and preview images out of
pdfs and videos. For a full list of all filters check
[reference](/reference/media-filter/index)

Define formats under `enhavo_media.formats` config.

```yaml
# config/packages/enhavo_media.yaml

enhavo_media:
    formats:
        # single type
        header:
            type: image
            width: 800
            height: 350
            format: jpg
        # chained types
        hero:
            -
                type: image
                max_width: 1920
            -
                type: image_compression
```
