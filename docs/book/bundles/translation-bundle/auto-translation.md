## Auto translation

Auto translation allows you to automatically translate your content using external translation services
like DeepL or Claude. The system translates all properties marked as translatable and supports auto translation.

### Clients

To enable auto translation, you need to configure at least one translation client.
You can provide context to the translation client to improve translation quality.
The context is passed along with each translation request.

```yaml
# config/packages/enhavo_translation.yaml
enhavo_translation:
    translation_client:
        context:
            provider: Enhavo\Bundle\TranslationBundle\Client\ConfigContextProvider
            text: 'This is a website about cooking recipes. Use informal language.'
            files:
                - 'translations/context.txt'
        client: Enhavo\Bundle\TranslationBundle\Client\DeeplTranslationClient
        deepl:
            api_key: '%env(DEEPL_API_KEY)%'
            glossary_id: 'your-glossary-id'
        claude:
            api_key: '%env(CLAUDE_API_KEY)%'
            model: 'claude-haiku-4-5-20251001'
            timeout: 600
            max_tokens: 4096
        chain:
            clients:
                - Enhavo\Bundle\TranslationBundle\Client\ClaudeTranslationClient
                - Enhavo\Bundle\TranslationBundle\Client\UrlTranslationClient
        url:
            domains:
                - 'example.com'
```


### Endpoint and Action

To allow users to trigger auto translation from the admin interface, you need to add
a route with the `translate_resource` endpoint and a `translate` action to the input configuration.

```yaml
# config/routes/admin_api/article.yaml
app_admin_api_article_translate_resource:
    path: /article/translate/resource
    defaults:
        _expose: admin_api
        _endpoint:
            type: translate_resource
            resource: app.article
```

```yaml
# config/resources/article.yaml
enhavo_resource:
    inputs:
        enhavo_article.article:
            actions:
                translate:
                    type: translate
                    route: app_admin_api_article_translate_resource
```

This adds a translate button to the resource form. When clicked, it saves the resource
and translates all translatable properties into every configured locale.

### Console command

You can also trigger auto translation from the command line.

```bash
bin/console translation:auto-translate <resource> <id> <locale>
```

For example:

```bash
bin/console translation:auto-translate app.article 5 de
```
