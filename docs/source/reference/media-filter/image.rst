Image Filter
============

The image filter handle cut and resize for images.

+-------------+--------------------------------------------------------------------+
| type        | image                                                              |
+-------------+--------------------------------------------------------------------+
| option      | - width_                                                           |
|             | - height_                                                          |
|             | - maxWidth_                                                        |
|             | - maxHeight_                                                       |
|             | - mode_                                                            |
|             | - format_                                                          |
|             | - jpeg_quality_                                                    |
|             | - png_compression_level_                                           |
|             | - cropWidth_                                                       |
|             | - cropHeight_                                                      |
|             | - cropX_                                                           |
|             | - cropY_                                                           |
+-------------+--------------------------------------------------------------------+
| class       | `Enhavo\\MediaBundle\\Filter\\Filter\\ImageFilter`                 |
+-------------+--------------------------------------------------------------------+


Option
------

width
~~~~~~

**type**: `integer`

Define the width for the image, the image will be resize exactly to this size.
It depends on the height_ and maxHeight_ option if the image will scaled.

height
~~~~~~

**type**: `integer`

Define the height for the image, the image will be resize exactly to this size.
It depends on the width_ and maxWidth_ option if the image will scaled.

maxWidth
~~~~~~~~

**type**: `integer`

Define the max width for the image, the image will only be resize to the value if the max value is reached.

maxHeight
~~~~~~~~~

**type**: `integer`

Define the max height for the image, the image will only be resize to the value if the max value is reached.

mode
~~~~

**type**: `string`

The possible values are `inset` and `outbound`. The default value is `outbound`. The mode is only used if width and height are set.
If the image need to be scaled and the sizes are not fit, the `inset` says that the image will be set into the new size and
you will see the full picture, that means also that you have spaces on the edges. If you set the value
to `outbound` the picture will be cut on the edges and fit to the size.

format
~~~~~~

**type**: `string`

Define the output format. That can be `jpg`, `png` and `gif`. If no format is set, the format will be the same as the input image.

jpeg_quality
~~~~~~~~~~~~

**type**: `integer`

Set the quality if the output format is jpg. The value can be between 1 and 100. The default value is 75.

png_compression_level
~~~~~~~~~~~~~~~~~~~~~

**type**: `integer`

Set the compression level if the output format is png. The value can be between 1 and 9. The default value is 7.

cropWidth
~~~~~~~~~

**type**: `integer`

Set the width of the crop window.

cropHeight
~~~~~~~~~~

**type**: `integer`

Set the height of the crop window.

cropX
~~~~~

**type**: `integer`

Set the x position of the crop window. 0 is the top of the image.

cropY
~~~~~

**type**: `integer`

Set the y position of the crop window. 0 is the left edge of the image.





