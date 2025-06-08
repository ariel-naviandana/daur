<template>
    <div :style="layoutStyle">
        <Navbar />
        <div :style="contentStyle" class="p-6">
        <div class="grid grid-cols-2 gap-4 mb-8">
            <InfoCard title="Total Pengguna" :value="totalUser.toLocaleString('id-ID')">
            <template #icon>
                <img src="/public/images/people.png" alt="User Icon" class="w-8 h-8" />
            </template>
            </InfoCard>

            <InfoCard title="Total Transaksi" :value="`Rp${totalTransactionRecycleRp.toLocaleString('id-ID')}`">
            <template #icon>
                <img src="/public/images/money.png" alt="Transaction Icon" class="w-8 h-8" />
            </template>
            </InfoCard>

            <InfoCard title="Total Recycle" :value="totalRecycle.toLocaleString('id-ID')">
            <template #icon>
                <img src="/public/images/trash.png" alt="Report Icon" class="w-8 h-8" />
            </template>
            </InfoCard>

            <InfoCard title="Total Withdraw" :value="`Rp${totalTransactionWithdrawRp.toLocaleString('id-ID')}`">
            <template #icon>
                <img src="/public/images/transfer.png" alt="Report Icon" class="w-8 h-8" />
            </template>
            </InfoCard>
        </div>
        <!-- Aktivitas Recycle -->
            <div :style="headingContainerStyle">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="28"
                    height="28"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    :style="iconStyle"
                >
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    <path d="M9 12h6"></path>
                    <path d="M9 16h6"></path>
                    <path d="M12 8L16 12"></path>
                </svg>
                <h1 :style="headingStyle">Manajemen Recycle</h1>
            </div>
        <RecycleCard
            v-for="(item, index) in history"
            :key="index"
            :item="item"
            @showDetail="openPopup"
            :is-admin="true"
        />
    </div>
    <PopupDetailRecycle
        v-if="selectedItem"
        :isOpen="showPopup"
        :item="selectedItem"
        @close="closePopup"
        @accept="updateStatus('process')"
        @reject="updateStatus('cancel')"
        @done="updateStatus('success')"
        :is-admin="true"
    />
    <PopupNotifikasi
        :isOpen="showNotification"
        :title="notificationTitle"
        :message="notificationMessage"
        @close="showNotification = false"
    />
    </div>
  </template>

  <script lang="ts" setup>
  import Navbar from '@/components/Navbar.vue'
  import InfoCard from '@/components/InfoCard.vue'
  import { theme } from '@/helpers/theme'
  import PopupDetailRecycle from '@/components/PopupDetailRecycle.vue'
  import RecycleCard from '@/components/RecycleCard.vue'
  import {onMounted, ref} from "vue"
  import { useRecycleTransactionApi } from '@/composables/useRecycleTransactionApi'
  import { useUserApi } from '@/composables/useUserApi'
  import { useWalletTransactionApi } from '@/composables/useWalletTransactionApi'
  import {useWalletApi} from "@/composables/useWalletApi"
  import { RecycleTransaction } from '@/interfaces/RecycleTransaction'
  import {WalletTransaction} from "@/interfaces/WalletTransaction"
  import { User } from '@/interfaces/User'
  import {Wallet} from "@/interfaces/Wallet"
  import PopupNotifikasi from '@/components/PopupNotifikasi.vue'

  const users = ref<User[]>([])
  const walletTransactions = ref<WalletTransaction[]>([])
  const { getUsers } = useUserApi()
  const { getWalletTransactions, saveWalletTransaction } = useWalletTransactionApi()
  const {saveWallet} = useWalletApi()
  const totalUser = ref(0)
  const totalRecycle = ref(0)
  const totalTransactionRecycleRp = ref<Number>(0)
  const totalTransactionWithdrawRp = ref<Number>(0)

  const showPopup = ref(false)
  const selectedItem = ref<RecycleTransaction | null>(null)
  const history = ref<RecycleTransaction[]>([])
  const { getRecycleTransactions, saveRecycleTransaction } = useRecycleTransactionApi()

  const showNotification = ref(false)
  const notificationTitle = ref('')
  const notificationMessage = ref('')

  const showAlert = (title: string, message: string) => {
      notificationTitle.value = title
      notificationMessage.value = message
      showNotification.value = true
  }

  const fetchUsers = async () => {
      try {
          users.value = await getUsers()
          totalUser.value = users.value.filter(user => user.role === 'user').length
      } catch (error) {
          console.error('Error fetching users:', error)
          totalUser.value = 0
      }
  }

  const fetchWalletTransactions = async () => {
      try {
          walletTransactions.value = await getWalletTransactions()
          totalTransactionWithdrawRp.value = 0
          for (const transaction of walletTransactions.value) {
              if (transaction.type === 'withdrawal') {
                  if (transaction.status === 'approved')
                      totalTransactionWithdrawRp.value = Number(totalTransactionWithdrawRp.value || 0) + Number(transaction.amount || 0)
              }
          }
      } catch (error) {
          console.error('Error fetching wallet transactions:', error)
          totalTransactionWithdrawRp.value = 0
      }
  }

  const fetchHistory = async () => {
      try {
          history.value = await getRecycleTransactions()
          totalRecycle.value = history.value.length
          totalTransactionRecycleRp.value = 0
          for (const transaction of history.value) {
              if (transaction.status === 'success')
                  totalTransactionRecycleRp.value = Number(totalTransactionRecycleRp.value || 0) + Number(transaction.total_amount || 0)
          }
      } catch (error) {
          console.error('Error fetching history:', error)
          totalRecycle.value = 0
          totalTransactionRecycleRp.value = 0
      }
  }

  onMounted(async () => {
      await Promise.all([
          fetchHistory(),
          fetchUsers(),
          fetchWalletTransactions()
      ])
  })

  const openPopup = (item: RecycleTransaction) => {
      selectedItem.value = item
      showPopup.value = true
  }

  const closePopup = () => {
      showPopup.value = false
      selectedItem.value = null
  }

  const updateStatus = async (newStatus: string) => {
      if (selectedItem.value) {
          try {
              const updatedTransaction = {
                  ...selectedItem.value,
                  status: newStatus
              }
              const success = await saveRecycleTransaction(updatedTransaction)
              if (newStatus === 'success' && success) {
                  const walletTransaction: WalletTransaction = {
                      wallet_id: updatedTransaction.user?.wallet?.id,
                      amount: updatedTransaction.total_amount,
                      type: 'deposit',
                      status: 'approved'
                  }
                  const walletSuccess = await saveWalletTransaction(walletTransaction)

                  if (walletSuccess) {
                      const wallet: Wallet = {
                          id: updatedTransaction.user?.wallet?.id,
                          balance: Number(updatedTransaction.user?.wallet?.balance) + Number(updatedTransaction.total_amount)
                      }
                      await saveWallet(wallet)
                  } else {
                      showAlert('Gagal', 'Gagal memperbarui saldo dompet')
                  }
              }

              if (success) {
                  history.value = history.value.map(item =>
                      item.id === updatedTransaction.id ? updatedTransaction : item
                  )
                  closePopup()
              } else {
                  showAlert('Gagal', 'Gagal memperbarui status transaksi')
              }

              await fetchHistory()
              await fetchWalletTransactions()
          } catch (error) {
              console.error('Error updating transaction status:', error)
              showAlert('Kesalahan', 'Terjadi kesalahan saat memperbarui status')
          }
      }
  }

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

  const headingContainerStyle = {
      display: 'flex',
      alignItems: 'center',
      gap: '12px',
      size: theme.fonts.size.subheading,
      marginBottom: '20px'
  }

  const iconStyle = {
      color: theme.colors.darkGrey
  }
  </script>
