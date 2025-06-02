<template>
    <div>
        <div v-for="(item, index) in permintaan" :key="index" class="card">
            <p><strong>{{ item.nama }}</strong></p>
            <p>{{ item.metode }}</p>
            <p>Rp. {{ item.jumlah.toLocaleString() }}</p>
            <div class="actions">
                <button @click="setujui(index)">Setujui</button>
                <button @click="tolak(index)">Tolak</button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const permintaan = ref([
    { nama: 'Walter White', metode: 'Dana 0812...', jumlah: 43000 },
    // data dummy lainnya
])

function setujui(index) {
    // implementasi logika menyetujui
    console.log('Setujui:', permintaan.value[index])
}

function tolak(index) {
    // implementasi logika menolak
    console.log('Tolak:', permintaan.value[index])
}
</script>

<style scoped>
.actions {
    margin-top: 10px;
}
button {
    margin-right: 10px;
    padding: 5px 10px;
}
</style>
