## Create new icon font

To create a new icon font, you need to only create a js font file (must
end with `.font.js`), e.g. `myfont.font.js`. Define relative to this
file where your icons are stored and give it a font name

```js
// assets/theme/fonts/myfont.font.js

module.exports = {
    'files': [
        '../icons/*.svg'
    ],
    'fontName': 'myfont-icons',
    'classPrefix': 'icon-',
    'baseSelector': '.icon',
    'types': ['eot', 'woff', 'woff2', 'ttf', 'svg'],
    'fileName': 'fonts/[fontname].[hash].[ext]',
};
```

Now put your svg icons in the defined directory.

    theme
    ├── fonts
    │   ├── myfont.font.js
    ├── icons
    │   ├── circle.svg
    │   └── square.svg
    └── base.js

To include the font to your project, you need to import it to your
entrypoint.

```js
// assets/theme/base.js

import '.fonts/myfont.font.js'
```

If you build your assets with webpack, the font and css file will be
stored automatically to the build directory. Now you can use the icon
font inside your html.

```html
<span class="icon icon-circle"></span>
```

To get a html preview of all your icons you can generate the it with
following command

```bash
$ yarn webfonts:generate --file assets/theme/fonts/myfont.font.js
```
