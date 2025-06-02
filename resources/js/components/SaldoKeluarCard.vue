<template>
    <div :style="transactionRow">
        <div :style="transactionIcon">
            <img src="/images/ic-transaction.svg">
        </div>
        <div :style="transactionInfo">
            <p :style="transactionNameInfo">{{ item.nama }}</p>
            <p :style="transactionDate">{{ item.tanggal }}</p>
            <p :style="transactionDetail">Transfer ke: {{ item.metode }}</p>
            <p :style="transactionStatus">Status: {{ item.status }}</p>
        </div>
        <p :style="mutasiAmountMinusStyle">- Rp. {{ item.jumlah.toLocaleString() }}</p>
    </div>
</template>

<script setup>
defineProps({ item: Object })

const transactionRow = { /* ... */ }
const transactionIcon = { /* ... */ }
const transactionInfo = { /* ... */ }
const transactionNameInfo = { /* ... */ }
const transactionDate = { /* ... */ }
const transactionDetail = {
    fontSize: '0.8rem',
    marginTop: '4px',
    color: '#666'
}
const transactionStatus = {
    fontSize: '0.8rem',
    color: '#FF9800', // warna oranye utk status proses
    marginTop: '2px'
}
const mutasiAmountMinusStyle = {
    color: '#F44336',
    fontWeight: 'bold',
    fontSize: '1rem'
}
</script>
