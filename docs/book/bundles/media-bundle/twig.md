## Twig

The media bundle provide the `media_url` function to get the image url.
The first parameter is the normalized file, the second the name of the format or null, if you want to
use the original file. With third parameter tells if url is absolute or the path is absolute or relative.

```twig
<!-- url to original file -->
<img src="{{ media_url(file) }}">

<!-- url to the format header -->
<img src="{{ media_url(file, "header") }}">

<!-- absolute url to the original file  -->
<img src="{{ media_url(file, null, constant('Symfony\\Component\\Routing\\Generator::ABSOLUTE_URL')) }}">
```

Further functions are `media_filename`, `media_parameter` or `media_is_picture`

```twig
<!-- print the filename -->
<p>{{ media_filename(file) }}</p>

<!-- print the alt parameter -->
<img alt="{{ media_parameter(file, "alt") }}" src="..." />

<!-- check if the file is a picture -->
{% if media_is_picture(file) %}<img src="..." />{% else %}<a href="..." />{% endif %s}
```