<template>
    <div :style="layoutStyle">
        <Navbar />
        <div :style="contentStyle" class="p-6">
        <div class="grid grid-cols-2 gap-4 mb-8">
            <InfoCard title="Total Pengguna" value="1.234">
            <template #icon>
                <img src="/public/images/people.png" alt="User Icon" class="w-8 h-8" />
            </template>
            </InfoCard>

            <InfoCard title="Total Transaksi" value="Rp 25.000.000">
            <template #icon>
                <img src="/public/images/money.png" alt="Transaction Icon" class="w-8 h-8" />
            </template>
            </InfoCard>

            <InfoCard title="Total Sampah" value="87 Kg">
            <template #icon>
                <img src="/public/images/trash.png" alt="Report Icon" class="w-8 h-8" />
            </template>
            </InfoCard>

            <InfoCard title="Total Saldo Ditransfer" value="Rp 3.400.000">
            <template #icon>
                <img src="/public/images/transfer.png" alt="Report Icon" class="w-8 h-8" />
            </template>
            </InfoCard>
        </div>
        <!-- Aktivitas Recycle -->
        <div>
          <h2 :style="headingStyle" class="mb-4">Aktivitas recycle terbaru</h2>
          <RecycleList :items="recycleData" />
        </div>
    </div>
    </div>
  </template>

  <script lang="ts" setup>
  import Navbar from '../components/Navbar.vue'
  import InfoCard from '../components/InfoCard.vue'
  import RecycleList from '../components/RecycleList.vue'
  import { theme } from '@/helpers/theme'

  const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
  }

  const contentStyle = {
    color: theme.colors.darkGrey,
    paddingTop: '24px',
    maxWidth: '1200px',
    margin: '0 auto',
  }

  const headingStyle = {
    fontSize: theme.fonts.size.subheading,
    fontWeight: theme.fonts.weight.bold,
  }

  const recycleData = [
    { name: 'Rudy Tabootie', date: '3 Januari 2025, 19:12', material: 'Campuran', weight: '4 Kg', status: 'Diproses' },
    { name: 'Peter Griffin', date: '3 Januari 2025, 19:12', material: 'Kertas', weight: '2 Kg', status: 'Dibatalkan' },
    { name: 'Walter White', date: '3 Januari 2025, 19:12', material: 'Kaca', weight: '2 Kg', status: 'Diproses' },
    { name: 'Rudy Tabootie', date: '3 Januari 2025, 19:12', material: 'Besi', weight: '4 Kg', status: 'Sukses' },
    { name: 'Sussie White', date: '3 Januari 2025, 19:12', material: 'Plastik', weight: '500 g', status: 'Sukses' },
  ]

  </script>
