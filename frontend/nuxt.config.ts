export default defineNuxtConfig({
    compatibilityDate: '2025-07-15',
    devtools: { enabled: true },

    runtimeConfig: {
        apiBase: process.env.NUXT_API_BASE,

        public: {
            apiBase: process.env.NUXT_PUBLIC_API_BASE
        }
    }
})
