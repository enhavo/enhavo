## Bind icon to unicode

For some cases you need to bind your icon to a unicode, so next time the
font will be generated the unicode is not changed. Use the option
`codepoints` in your `.font.js` file to bind the icon.

```js
// assets/theme/fonts/myfont.font.js

module.exports = {
    // .. other options
    'codepoints': {
        'circle': 0xf101,
        'square': 0xf102,
    }
}
```

With the `--dump` option you will get all codepoints, which are ready to
copy and paste into the `.font.js` file.

```bash
$ yarn webfonts:generate --file assets/theme/fonts/myfont.font.js --dump
```
