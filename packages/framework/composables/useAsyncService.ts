import { inject, shallowRef, watch, type InjectionKey, type Ref, type ShallowRef,} from 'vue'

/**
 * Resolves once the given ref holds a non-null value.
 * Must be called synchronously in setup so the watcher is bound to the component.
 */
function untilLoaded<T>(source: Ref<T | null>): Promise<T> {
    return new Promise((resolve) => {
        if (source.value !== null) {
            resolve(source.value)
            return
        }
        const stop = watch(source, (value) => {
            if (value !== null) {
                stop()
                resolve(value)
            }
        })
    })
}

/**
 * Injects an async service and exposes it as a ref that is null until
 * the service has resolved, all dependencies are loaded and `init` has finished.
 *
 * - `init`: optional setup per call site (e.g. `m => m.load()`).
 *           Return `false` to opt out: the ref then stays null.
 * - `dependsOn`: refs from other useAsyncService calls that must be loaded first.
 *
 * Non-null therefore means "initialized".
 * Must be called synchronously inside setup().
 */
export function useAsyncService<T>(
    key: InjectionKey<Promise<T>> | string,
    init?: (service: T) => void | boolean | Promise<void | boolean>,
    dependsOn: Ref<unknown>[] = [],
): ShallowRef<T | null> {
    // inject() must run synchronously in setup
    const promise = inject<Promise<T>>(key)
    if (!promise) {
        throw new Error(`Service "${String(key)}" not provided`)
    }

    const service = shallowRef<T | null>(null)

    // created synchronously so the watchers are stopped on unmount
    const dependencies = Promise.all(dependsOn.map(untilLoaded))

    Promise.all([promise, dependencies])
        .then(async ([s]) => {
            const result = await init?.(s)
            if (result === false) return // opted out, service stays null
            service.value = s
        })
        .catch((e) => console.error(`Failed to load service "${String(key)}"`, e))

    return service
}