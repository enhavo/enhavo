## Special

Any page can be annotated as a special page over the admin. A special page is a unique page, which is somehow referenced by the system or your code.
Normally if you want to generate an url to the page within the code.

### Config

In `specials` you can configure all available special pages.

```yaml
# config/packages/enhavo_page.yaml
enhavo_page:
    specials:
        # used by system
        error_default:
            label: page.label.error_page_default
            translation_domain: EnhavoPageBundle
        error_403:
            label: page.label.error_page_403
            translation_domain: EnhavoPageBundle
        error_404:
            label: page.label.error_page_404
            translation_domain: EnhavoPageBundle
        # define your special pages
        privacy:
            label: Privacy
```

### Routing

All annotated special pages are passed as a route to the `theme` group. So when `theme` routes are loaded,
you can generate an url over the router or use the build in twig functions `page_special_url` and `page_special_exists`.

```twig
<a href="{{ path('enhavo_page_page_special_privacy') }}">Privacy</a>

{% if page_special_exists('privacy') %}
    <a href="{{ page_special_url('privacy') }}">Privacy</a>
{% endif %}
```

`page_special_url` will return the string `#` if the no page was annotated with this key. 


Because they are passed as routes, you can also use the router inside vue to generate an url, if theme routes were loaded.

```vue
<template>
    <a :href="privacyUrl">Privacy</a>
</template>

<script setup lang="ts">
import {Router} from "@enhavo/app/routing/Router";

const router = inject<Router>('router');

let privacyUrl = '#';
if (router.hasRoute('enhavo_page_page_special_privacy')) {
    privacyUrl = router.generate('enhavo_page_page_special_privacy')
}
</script>
```

### Endpoint

If your endpoint has the `ViewEndpointType` as a parent, you can use the option `page_specials` to pass all
special pages as normalized data.

```php
namespace App\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\AppBundle\Endpoint\Type\ViewEndpointType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MyEndpointType extends AbstractEndpointType
{
    public static function getParentType(): ?string
    {
        return ViewEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'page_specials' => true, // [!code ++]
        ]);
    }
}
```

A json representation would look like this:

```json
{
    "page_specials": {
        "imprint": {
            "url": "/imprint",
            "label": "Imprint"
        }
    }
}
```

If you want to pass it for all endpoints inside an area, you use the area options.

```yaml 
enhavo_app:
    area:
        theme:
            firewall: main
            options:
                page_specials: true // [!code ++]
```


### Error pages

If a page is annotated with `error_default`, `error_403` or `error_404` then this page will be shown instead of the default error.

