import { ref } from 'vue'
import { initializeCsrf } from '../helpers/axios'

export function useCsrf() {
    const isCsrfInitialized = ref(false)

    const initCsrf = async () => {
        if (!isCsrfInitialized.value) {
            await initializeCsrf()
            isCsrfInitialized.value = true
        }
    }

    return { initCsrf }
}
