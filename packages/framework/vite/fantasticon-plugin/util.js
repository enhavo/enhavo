import { watch } from "fs";
import { normalizePath } from "vite";

export function debounced(ms, fn) {
    let timeout;
    return () => {
        if (timeout !== undefined) clearTimeout(timeout);
        timeout = setTimeout(fn, ms);
    };
}

export function watchDebounced(path, fn, ms = 100) {
    return watch(normalizePath(path), debounced(ms, fn));
}
