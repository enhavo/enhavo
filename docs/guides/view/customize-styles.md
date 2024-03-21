## Customize styles

Here is a short guide how you can change the styles in enhavo admin.

### Add custom style file

You can import a custom style file at the top of your entrypoint. Just
add your import statement at the end all other imports. In this example
we add style in `assets/enhavo/styles/custom.scss`

```js
// assets/enhavo/main.ts

import Application from "@enhavo/app/Main/MainApplication";
import ViewRegistryPackage from "./registry/view";
// ... other imports
import "./styles/custom.scss"

// ...
```

### Customize variables

To edit some of the variables in the enhavo admin. Just add a file
`assets/enhavo/styles/custom-vars.scss` and change the var values.

**The defaults vars are:**

```scss
$fontFamily:"IBM Plex Sans";
$fontFamily2:"IBM Plex Sans Condensed";
$fontSize:16px;
$lineHeight:1.4em;
$color1:#00b1db;
$color1b:#0097c7;
$color2:#242733;
$color2b:#1C1F2B;
$color3:#dbc353;
$color4:#fd516b;
$color5:#32de81;
$color6:#dd8888;
$color6b:#cc7777;
$grey1:#f5f5f5;
$grey2:#9da9ad;
$grey3:#77878c;
$grey4:#c2c2c2;
$grey5:#e5e5e5;
$black: #1c353b;
$toolbarHeight:50px;
$collapsedMenuWidth:50px;
$sidebarWidth:230px;
$fontRegular:400;
$fontMedium:500;
$fontSemibold:600;
$fontBold:700;
```
