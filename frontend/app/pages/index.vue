<template>
    <div>
        <div class="info">
            <h2>Test project</h2>
            <p>
                This is a demo application for managing invoices.
                It is used for testing CRUD functionality, API integration and UI behavior.
            </p>
        </div>

        <div class="btn" @click="navigate">
            Go to invoices
        </div>
    </div>
</template>

<script setup>
const router = useRouter()

const navigate = () => {
    router.push('/invoices')
}
</script>

<style scoped>
.btn {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 12px;
    background: #3b82f6;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    cursor: pointer;
}
</style>
