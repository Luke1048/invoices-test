<template>
    <div>
        <div v-if="pending">Loading...</div>

        <div v-else-if="error">Error loading invoice</div>

        <div v-else>
            <h1>Edit Invoice {{ invoice.number }}</h1>

            <form class="form" @submit.prevent="onSubmit">
                <div class="field">
                    <label>Net amount</label>
                    <input
                        type="number"
                        step="0.01"
                        v-model="form.net_amount"
                        :disabled="isLocked"
                    />
                    <span class="error">{{ errors.net_amount }}</span>
                </div>

                <div class="field">
                    <label>VAT amount</label>
                    <input
                        type="number"
                        step="0.01"
                        v-model="form.vat_amount"
                        :disabled="isLocked"
                    />
                    <span class="error">{{ errors.vat_amount }}</span>
                </div>

                <div class="field">
                    <label>Due date</label>
                    <input
                        type="date"
                        v-model="form.due_date"
                        :disabled="isLocked"
                    />
                    <span class="error">{{ errors.due_date }}</span>
                </div>

                <div class="field">
                    <label>Gross</label>
                    <input :value="grossAmount" disabled />
                </div>

                <button type="submit" :disabled="isLocked">
                    Save
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, computed, watch } from 'vue'
import { z } from 'zod'

const config = useRuntimeConfig()
const route = useRoute()
const router = useRouter()

const apiBase = import.meta.server
    ? config.apiBase
    : config.public.apiBase

const { data, pending, error } = await useFetch(
    `/api/invoices/${route.params.id}`,
    { baseURL: apiBase }
)

const invoice = computed(() => data.value?.data || {})

const isLocked = computed(() => invoice.value.status !== 'pending')

const form = reactive({
    net_amount: 0,
    vat_amount: 0,
    due_date: '',
})

watch(
    () => invoice.value?.id,
    () => {
        if (!invoice.value?.id) return

        form.net_amount = Number(invoice.value.net_amount || 0)
        form.vat_amount = Number(invoice.value.vat_amount || 0)
        form.due_date = invoice.value.due_date || ''
    },
    { immediate: true }
)

const errors = reactive({})

const schema = z.object({
    net_amount: z.coerce.number().min(0),
    vat_amount: z.coerce.number().min(0),
    due_date: z.string().min(1),
})

const grossAmount = computed(() => {
    return (
        Number(form.net_amount || 0) +
        Number(form.vat_amount || 0)
    ).toFixed(2)
})

const onSubmit = async () => {
    if (isLocked.value) return

    errors.net_amount = ''
    errors.vat_amount = ''
    errors.due_date = ''

    const result = schema.safeParse(form)

    if (!result.success) {
        const err = result.error.flatten().fieldErrors

        errors.net_amount = err.net_amount?.[0]
        errors.vat_amount = err.vat_amount?.[0]
        errors.due_date = err.due_date?.[0]

        return
    }

    await $fetch(`/api/invoices/${route.params.id}`, {
        baseURL: apiBase,
        method: 'put',
        body: {
            ...result.data,
            gross_amount: Number(grossAmount.value),
        },
    })

    router.push(`/invoices/${route.params.id}`)
}
</script>

<style scoped>
.form {
    max-width: 500px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.field {
    display: flex;
    flex-direction: column;
}

input {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.error {
    color: #dc2626;
    font-size: 12px;
}

button {
    padding: 10px;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
}

button:disabled {
    opacity: 0.6;
    pointer-events: none;
}
</style>
