import {assetPath} from "@enhavo/app/vite/fantasticon-plugin/paths.js";

// escape import . meta . hot to prevent vite replacement during build
export const hmrTemplate = (event, id, config, base) => `
(function() {
  var link = document.createElement('link');
  link.setAttribute('data-id', '${id}');
  link.setAttribute("href", "${base}${assetPath(config, "css")}?${Date.now()}");
  link.setAttribute("rel", "stylesheet");
  link.setAttribute("type", "text/css");
  var head = document.getElementsByTagName('head')[0];
  head.appendChild(link);
})()

import.${"meta"}.hot && import.${"meta"}.hot.on("${event}", () => {
  const link = document.querySelector("link[data-id='${id}']");
  const href = link.href.slice(0, link.href.indexOf("?")) + "?" + Date.now();
  link.setAttribute("href", href);
});`;

export const hmrLinkTag = (config, base, id) => ({
    tag: "link", attrs: {
        rel: "stylesheet", type: "text/css", href: `${base}${assetPath(config, "css")}?${Date.now()}`, "data-id": id,
    }, injectTo: "head",
});
