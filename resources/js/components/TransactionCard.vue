<template>
    <div :style="cardStyle">
        <div>
            <p :style="nameStyle">{{ data.name }}</p>
            <p :style="metaStyle">{{ data.date }}<br />{{ data.bank }}</p>
        </div>
        <div :style="rightStyle">
            <p :style="amountStyle">Rp. {{ data.amount.toLocaleString() }}</p>
            <div v-if="isRequest">
                <button :style="setujuStyle">Setujui</button>
                <button :style="tolakStyle">Tolak</button>
            </div>
            <p v-else :style="statusStyle">Sukses</p>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    data: { id: number, name: string, amount: number, date: string, bank: string }
    isRequest: boolean
}>()

const cardStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    background: 'white',
    borderRadius: '16px',
    padding: '1rem 1.5rem',
    boxShadow: '0 2px 8px rgba(0,0,0,0.05)',
}

const nameStyle = {
    fontWeight: 'bold',
    fontSize: '16px',
}

const metaStyle = {
    fontSize: '12px',
    color: '#666',
}

const amountStyle = {
    fontWeight: 'bold',
    fontSize: '16px',
    color: '#4CAF50',
    marginBottom: '5px'
}

const statusStyle = {
    color: 'white',
    background: '#4CAF50',
    padding: '5px 10px',
    borderRadius: '10px',
    fontSize: '12px',
    textAlign: 'center'
}

const setujuStyle = {
    background: '#4CAF50',
    color: 'white',
    border: 'none',
    borderRadius: '10px',
    padding: '6px 12px',
    marginRight: '10px',
    cursor: 'pointer'
}

const tolakStyle = {
    background: '#F44336',
    color: 'white',
    border: 'none',
    borderRadius: '10px',
    padding: '6px 12px',
    cursor: 'pointer'
}

const rightStyle = {
    textAlign: 'right'
}
</script>
