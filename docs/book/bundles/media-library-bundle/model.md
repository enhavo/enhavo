## Model 

The media library introduce a new entity  `Item` representing a single entry within the media library.
An item refers to a containing file. If you want to use an item from the media library, the containing file will
be duplicated and then refers back to its item to track the usage. This newly created file can be used like
any other media file.

Here is an example of an media library item with two duplicated files.

![image](/images/media-library-uml.png)


### Factory

The `enhavo_media.file.factory` service will now use the media library factory and comes with the `createFromItem` function.

```php
$item = $itemRepository->find($id);
$file = $fileFactory->createFromItem($item);
$book->setCover($file);
```

