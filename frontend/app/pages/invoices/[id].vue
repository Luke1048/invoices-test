<template>
    <div>
        <div v-if="pending">Loading...</div>
        <div v-else-if="error">Error loading invoice</div>

        <div v-else>
            <h1>Invoice {{ invoice.number }}</h1>

            <p><b>Supplier:</b> {{ invoice.supplier_name }}</p>
            <p><b>Amount:</b> {{ invoice.gross_amount }}</p>
            <p><b>Status:</b> {{ invoice.status }}</p>
            <p><b>Due date:</b> {{ invoice.due_date }}</p>
        </div>
    </div>
</template>

<script setup>
const config = useRuntimeConfig()
const route = useRoute()

const { data, error, pending } = await useFetch(
    `http://localhost:8080/api/invoices/${route.params.id}`,
    {
        // baseURL: config.public.apiBase
    }
)

console.log('${route.params.id}:', route.params.id)
// console.log('apiBase:', config.public.apiBase)

const invoice = computed(() => data.value?.data || {})

console.log('invoice: ', invoice)
</script>

<style scoped>
</style>
