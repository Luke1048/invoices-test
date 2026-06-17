<template>
    <div>
        <h1>Invoices</h1>

        <div class="controls">
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
                    @click="changePage(page + 1)"
                >
                    Next
                </button>
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
    gap: 16px;
    align-items: center;
    margin-top: 20px;
}
</style>
