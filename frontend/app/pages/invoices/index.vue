<template>
    <div>
        <h1>Invoices</h1>

        <div v-if="hasInvoices" class="controls">
            <label>
                Per page:
                <select
                    :value="perPage"
                    @change="changePerPage($event.target.value)"
                >
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </label>
        </div>

        <div v-if="pending">
            Loading...
        </div>

        <div v-else-if="error">
            Error loading invoices
        </div>

        <div v-else>
            <div v-if="!hasInvoices">
                No invoices found
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

                <div class="pagination">
                    <button
                        :disabled="page <= 1"
                        @click="changePage(page - 1)"
                    >
                        Previous
                    </button>

                    <span>
                Page {{ page }} of {{ lastPage }}
            </span>

                    <button
                        :disabled="page >= lastPage"
                        @click="changePage(page + 1)">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const config = useRuntimeConfig()
const router = useRouter()
const route = useRoute()

const page = computed(() => Number(route.query.page || 1))
const perPage = computed(() => Number(route.query.per_page || 10))

const apiBase = import.meta.server
    ? config.apiBase
    : config.public.apiBase

const { data, pending, error } = await useFetch('/api/invoices', {
    baseURL: apiBase,
    query: {
        page,
        per_page: perPage
    },
    watch: [page, perPage]
})

const invoices = computed(() => data.value?.data || [])

const hasInvoices = computed(() => invoices.value.length > 0)

const lastPage = computed(() => data.value?.meta?.last_page || 1)

const changePage = (newPage) => {
    router.push({
        query: {
            ...route.query,
            page: newPage
        }
    })
}

const changePerPage = (newPerPage) => {
    router.push({
        query: {
            page: 1,
            per_page: newPerPage
        }
    })
}

const goToInvoice = (id) => {
    router.push(`/invoices/${id}`)
}
</script>

<style scoped>
.controls {
    margin-bottom: 16px;
}

.controls select {
    margin-left: 8px;
    padding: 4px 8px;
}

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

th,
td {
    border: 1px solid #e5e7eb;
    padding: 10px 12px;
    text-align: left;
}

th {
    background: #f3f4f6;
}

.row {
    cursor: pointer;
}

tbody tr:hover {
    background: #f1f5f9;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    margin-top: 20px;
    padding: 10px;
}

.pagination button {
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    background: white;
    color: #111827;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.pagination button:hover {
    background: #f3f4f6;
}

.pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f9fafb;
}

.pagination span {
    font-size: 14px;
    color: #374151;
    font-weight: 500;
}

.controls select {
    margin-left: 8px;
    padding: 6px 28px 6px 10px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: white;
    font-size: 14px;
    color: #111827;
    cursor: pointer;
    outline: none;

    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;

    background-image: linear-gradient(45deg, transparent 50%, #6b7280 50%),
    linear-gradient(135deg, #6b7280 50%, transparent 50%);
    background-position: calc(100% - 14px) calc(50% - 3px),
    calc(100% - 9px) calc(50% - 3px);
    background-size: 5px 5px, 5px 5px;
    background-repeat: no-repeat;
}

.controls select:hover {
    border-color: #9ca3af;
}

.controls select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
}
</style>
