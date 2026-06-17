<template>
    <div>
        <div v-if="pending">
            Loading...
        </div>

        <div v-else-if="error">
            Error loading invoice
        </div>

        <div v-else>
            <h1>Invoice {{ invoice.number }}</h1>

            <table class="invoice-table">
                <tbody>
                <tr>
                    <th>ID</th>
                    <td>{{ invoice.id }}</td>
                </tr>

                <tr>
                    <th>Number</th>
                    <td>{{ invoice.number }}</td>
                </tr>

                <tr>
                    <th>Supplier name</th>
                    <td>{{ invoice.supplier_name ?? '—' }}</td>
                </tr>

                <tr>
                    <th>Supplier tax ID</th>
                    <td>{{ invoice.supplier_tax_id ?? '—' }}</td>
                </tr>

                <tr>
                    <th>Net amount</th>
                    <td>{{ invoice.net_amount }}</td>
                </tr>

                <tr>
                    <th>VAT amount</th>
                    <td>{{ invoice.vat_amount }}</td>
                </tr>

                <tr>
                    <th>Gross amount</th>
                    <td>{{ invoice.gross_amount }}</td>
                </tr>

                <tr>
                    <th>Currency</th>
                    <td>{{ invoice.currency }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <span :class="statusClass(invoice.status)">
                            {{ invoice.status }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <th>Issue date</th>
                    <td>{{ invoice.issue_date }}</td>
                </tr>

                <tr>
                    <th>Due date</th>
                    <td>{{ invoice.due_date }}</td>
                </tr>

                <tr>
                    <th>Created at</th>
                    <td>{{ invoice.created_at }}</td>
                </tr>

                <tr>
                    <th>Updated at</th>
                    <td>{{ invoice.updated_at }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
const config = useRuntimeConfig()
const router = useRouter()
const route = useRoute()

const apiBase = import.meta.server
    ? config.apiBase
    : config.public.apiBase

const { data, error, pending } = await useFetch(
    `/api/invoices/${route.params.id}`,
    {
        baseURL: apiBase,
    }
)

const invoice = computed(() => data.value?.data || {})

const statusClass = (status) => {
    return {
        'badge': true,
        'badge-pending': status === 'pending',
        'badge-approved': status === 'approved',
        'badge-rejected': status === 'rejected',
    }
}
</script>

<style scoped>
.invoice-table th {
    width: 220px;
    background: #f3f4f6;
    font-weight: 600;
    text-align: left;
    vertical-align: top;
}

.invoice-table td {
    text-align: left;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-pending {
    background: #fef3c7;
    color: #92400e;
}

.badge-approved {
    background: #d1fae5;
    color: #065f46;
}

.badge-rejected {
    background: #fee2e2;
    color: #991b1b;
}
</style>
