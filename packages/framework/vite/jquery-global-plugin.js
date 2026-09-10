export function jqueryGlobalPlugin(libs) {
    return {
        name: 'global-jquery',
        transform(code, id) {
            for (const lib of libs) {
                if (id.includes(lib)) {
                    return `import ___jq from 'jquery'; if(typeof window !== 'undefined') { window.jQuery = window.jQuery || ___jq; }\n${code}`;
                }
            }
        }
    }
}
