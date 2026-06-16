<template>
    <div>
        <h1>Invoices</h1>

        <div v-if="pending">
            Loading...
        </div>

        <div v-else-if="error">
            Error loading invoices
        </div>

        <div v-else>
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>Number</th>
                        <th>Supplier</th>
                        <th>Gross amount</th>
                        <th>Status</th>
                        <th>Due date</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr
                        v-for="invoice in invoices"
                        :key="invoice.id"
                        class="row"
                        @click="goToInvoice(invoice.id)"
                    >
                        <td>{{ invoice.number }}</td>
                        <td>{{ invoice.supplier_name }}</td>
                        <td>{{ invoice.gross_amount }}</td>
                        <td>{{ invoice.status }}</td>
                        <td>{{ invoice.due_date }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
const config = useRuntimeConfig()

const { data, error, pending } = await useFetch('/api/invoices', {
    baseURL: config.public.apiBase
})

const invoices = computed(() => data.value?.data || [])
</script>

<style scoped>
.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

th, td {
    border: 1px solid #e5e7eb;
    padding: 10px 12px;
    text-align: left;
    white-space: nowrap;
}

th {
    background: #f3f4f6;
    font-weight: 600;
}

.row {
    cursor: pointer;
}

tbody tr:nth-child(even) {
    background-color: #f9fafb;
}

tbody tr:hover {
    background: #f1f5f9;
}
</style>
