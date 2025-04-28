<template>
    <div :style="layoutStyle">
        <Navbar />
        <div :style="mainContentStyle">
            <div :style="categoryListContainerStyle">
                <button :style="navigationButtonStyle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div :style="categoryListStyle">
                    <button v-for="category in categories" :key="category.id" :style="categoryItemStyle">
                        <div :style="{ ...categoryIconStyle, backgroundColor: category.bgColor }" v-html="category.icon"></div>
                        <span :style="categoryLabelStyle">{{ category.name }}</span>
                    </button>
                </div>

                <button :style="navigationButtonStyle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>

            <div :style="cartContainerStyle">
                <h2 :style="sectionTitleStyle">
                    <span :style="cartIconStyle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M9 22C9.55228 22 10 21.5523 10 21C10 20.4477 9.55228 20 9 20C8.44772 20 8 20.4477 8 21C8 21.5523 8.44772 22 9 22Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M20 22C20.5523 22 21 21.5523 21 21C21 20.4477 20.5523 20 20 20C19.4477 20 19 20.4477 19 21C19 21.5523 19.4477 22 20 22Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M1 1H5L7.68 14.39C7.77144 14.8504 8.02191 15.264 8.38755 15.5583C8.75318 15.8526 9.2107 16.009 9.68 16H19.4C19.8693 16.009 20.3268 15.8526 20.6925 15.5583C21.0581 15.264 21.3086 14.8504 21.4 14.39L23 6H6" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                    Keranjang
                </h2>

                <div :style="cartItemsContainerStyle">
                    <div v-for="(item, index) in cartItems" :key="index" :style="cartItemStyle">
                        <div :style="itemLeftStyle">
                            <div :style="{ ...itemIconStyle, color: item.iconColor }" v-html="icons[item.icon]"></div>
                            <div :style="itemDetailsStyle">
                                <span :style="itemNameStyle">{{ item.name }}</span>
                                <div :style="weightContainerStyle">
                                    <button :style="minusButtonStyle" @click="updateWeight(index, -1)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                    <div :style="weightBadgeStyle">{{ item.weight }} kg</div>
                                    <button :style="plusButtonStyle" @click="updateWeight(index, 1)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <span :style="priceStyle">Rp{{ item.price.toLocaleString('id-ID') }}</span>
                    </div>
                </div>

                <div :style="totalContainerStyle">
                    <div :style="totalRowStyle">
                        <span>Berat</span>
                        <span>{{ totalWeight }} kg</span>
                    </div>
                    <div :style="totalRowStyle">
                        <span>Total</span>
                        <span :style="totalPriceStyle">Rp{{ totalPrice.toLocaleString('id-ID') }}</span>
                    </div>
                </div>
            </div>

            <div :style="bookingContainerStyle">
                <h2 :style="sectionTitleStyle">
                    <span :style="bookingIconStyle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M19 4H5C3.89543 4 3 4.89543 3 6V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V6C21 4.89543 20.1046 4 19 4Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M16 2V6M8 2V6M3 10H21" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                    Booking
                </h2>

                <div :style="pickupTypeStyle">
                    <button :style="{ ...pickupButtonStyle, backgroundColor: isPickup ? theme.colors.primary : 'white', color: isPickup ? 'white' : theme.colors.darkGrey }" @click="isPickup = true">Pick-up</button>
                    <button :style="{ ...pickupButtonStyle, backgroundColor: !isPickup ? theme.colors.primary : 'white', color: !isPickup ? 'white' : theme.colors.darkGrey }" @click="isPickup = false">Drop-off</button>
                </div>

                <form @submit.prevent="handleSubmit" :style="formStyle">
                    <div :style="formRowStyle">
                        <div :style="{ ...formGroupStyle, flex: 2 }">
                            <label :style="labelStyle">
                                {{ isPickup ? 'Alamat Penjemputan' : 'Alamat Drop-off' }}
                            </label>
                            <input
                                v-model="address"
                                type="text"
                                :style="inputStyle"
                                :placeholder="isPickup ? 'Masukkan alamat lengkap penjemputan' : 'Masukkan alamat lengkap drop-off'"
                                required
                            />
                        </div>

                        <div :style="{ ...formGroupStyle, flex: 1 }">
                            <label :style="labelStyle">Waktu Penjemputan</label>
                            <input
                                v-model="pickupTime"
                                type="time"
                                :style="inputStyle"
                                required
                            />
                        </div>
                    </div>

                    <div :style="formGroupStyle">
                        <label :style="labelStyle">Catatan</label>
                        <textarea
                            v-model="note"
                            :style="textareaStyle"
                            placeholder="Tambahkan catatan untuk driver"
                        ></textarea>
                    </div>

                    <button type="submit" :style="submitButtonStyle">Konfirmasi</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { theme } from '@/config/theme'
import Navbar from '../components/Navbar.vue'

const icons = {
    kertas: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
    plastik: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 21C16.4183 21 20 17.4183 20 13C20 8.58172 16.4183 5 12 5C7.58172 5 4 8.58172 4 13C4 17.4183 7.58172 21 12 21Z" stroke="currentColor" stroke-width="2"/></svg>`,
    kaca: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 2H16L17 7H7L8 2Z" stroke="currentColor" stroke-width="2"/><path d="M7 7V20C7 20.5523 7.44772 21 8 21H16C16.5523 21 17 20.5523 17 20V7" stroke="currentColor" stroke-width="2"/></svg>`,
    besi: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 15C15.866 15 19 11.866 19 8C19 4.13401 15.866 1 12 1C8.13401 1 5 4.13401 5 8C5 11.866 8.13401 15 12 15Z" stroke="currentColor" stroke-width="2"/><path d="M12 15V23M8 19H16" stroke="currentColor" stroke-width="2"/></svg>`,
    aluminium: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="2" width="14" height="20" rx="2" stroke="currentColor" stroke-width="2"/><path d="M12 18H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>`,
    kardus: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 8V21H3V8M21 8L12 3L3 8M21 8L12 13L3 8" stroke="currentColor" stroke-width="2"/></svg>`
}

const categories = [
    { id: 1, name: 'Kertas', icon: icons.kertas, bgColor: '#FFF3E0', iconColor: '#FF9800' },
    { id: 2, name: 'Plastik', icon: icons.plastik, bgColor: '#E3F2FD', iconColor: '#2196F3' },
    { id: 3, name: 'Kaca', icon: icons.kaca, bgColor: '#E8F5E9', iconColor: '#4CAF50' },
    { id: 4, name: 'Besi', icon: icons.besi, bgColor: '#FFEBEE', iconColor: '#F44336' },
    { id: 5, name: 'Aluminium', icon: icons.aluminium, bgColor: '#F3E5F5', iconColor: '#9C27B0' },
    { id: 6, name: 'Kardus', icon: icons.kardus, bgColor: '#FFF3E0', iconColor: '#FF9800' }
]

const cartItems = ref([
    { name: 'Koran', weight: 5, price: 15000, icon: 'kertas', iconColor: '#FF9800' },
    { name: 'Gelas Kaca', weight: 7, price: 21000, icon: 'kaca', iconColor: '#4CAF50' },
    { name: 'Botol Plastik', weight: 9, price: 20000, icon: 'plastik', iconColor: '#2196F3' }
])

const isPickup = ref(true)
const address = ref('')
const pickupTime = ref('')
const note = ref('')

const totalWeight = computed(() => cartItems.value.reduce((sum, item) => sum + item.weight, 0))
const totalPrice = computed(() => cartItems.value.reduce((sum, item) => sum + item.price, 0))

const updateWeight = (index: number, change: number) => {
    const newWeight = cartItems.value[index].weight + change
    if (newWeight > 0) {
        cartItems.value[index].weight = newWeight
        cartItems.value[index].price = newWeight * 3000
    }
}

const handleSubmit = () => {
    console.log({
        items: cartItems.value,
        totalWeight: totalWeight.value,
        totalPrice: totalPrice.value,
        isPickup: isPickup.value,
        address: address.value,
        pickupTime: pickupTime.value,
        note: note.value
    })
}

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family
}

const mainContentStyle = {
    maxWidth: '1200px',
    margin: '0 auto',
    padding: '24px'
}

const cartContainerStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '12px',
    padding: '24px',
    marginTop: '24px'
}

const sectionTitleStyle = {
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    margin: 0,
    display: 'flex',
    alignItems: 'center',
    gap: '12px'
}

const cartIconStyle = {
    color: theme.colors.darkGrey
}

const cartItemsContainerStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '16px',
    marginTop: '20px'
}

const cartItemStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: '16px',
    backgroundColor: '#F9FAFB',
    borderRadius: '8px'
}

const itemLeftStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '12px'
}

const itemIconStyle = {
    width: '40px',
    height: '40px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    borderRadius: '8px',
    backgroundColor: '#F3F4F6'
}

const itemDetailsStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '8px'
}

const itemNameStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium
}

const weightContainerStyle = {
    display: 'flex',
    alignItems: 'center',
    gap: '4px'
}

const weightBadgeStyle = {
    padding: '4px 8px',
    fontSize: theme.fonts.size.small,
    color: theme.colors.darkGrey,
    minWidth: '50px',
    textAlign: 'center'
}

const weightButtonStyle = {
    width: '24px',
    height: '24px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '0',
    borderRadius: '50%',
    border: 'none',
    cursor: 'pointer',
    color: 'white'
}

const priceStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.semibold,
    color: theme.colors.primary
}

const totalContainerStyle = {
    marginTop: '20px',
    padding: '16px',
    backgroundColor: '#F9FAFB',
    borderRadius: '8px'
}

const totalRowStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    marginBottom: '8px',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium
}

const totalPriceStyle = {
    color: theme.colors.primary,
    fontWeight: theme.fonts.weight.bold
}

const bookingContainerStyle = {
    backgroundColor: theme.colors.whiteElement,
    borderRadius: '12px',
    padding: '24px',
    marginTop: '24px'
}

const bookingIconStyle = {
    color: theme.colors.darkGrey
}

const pickupTypeStyle = {
    display: 'flex',
    gap: '12px',
    marginTop: '20px'
}

const pickupButtonStyle = {
    flex: 1,
    padding: '12px',
    borderRadius: '8px',
    border: '1px solid #E5E7EB',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    cursor: 'pointer'
}

const formRowStyle = {
    display: 'flex',
    gap: '16px',
    width: '100%'
}

const formStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '20px',
    marginTop: '24px'
}

const formGroupStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '8px'
}

const inputStyle = {
    padding: '12px',
    borderRadius: '8px',
    border: '1px solid #E5E7EB',
    fontSize: theme.fonts.size.base,
    fontFamily: theme.fonts.family,
    width: '100%',
    boxSizing: 'border-box'
}

const labelStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    color: theme.colors.darkGrey
}

const textareaStyle = {
    padding: '12px',
    borderRadius: '8px',
    border: '1px solid #E5E7EB',
    fontSize: theme.fonts.size.base,
    fontFamily: theme.fonts.family,
    minHeight: '100px',
    resize: 'vertical'
}

const submitButtonStyle = {
    padding: '12px',
    borderRadius: '8px',
    border: 'none',
    backgroundColor: theme.colors.primary,
    color: 'white',
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.medium,
    cursor: 'pointer'
}

const categoryListContainerStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    gap: '16px',
    padding: '16px 0'
}

const navigationButtonStyle = {
    width: '40px',
    height: '40px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    border: 'none',
    borderRadius: '50%',
    backgroundColor: 'white',
    color: theme.colors.darkGrey,
    cursor: 'pointer',
    boxShadow: '0 1px 3px rgba(0, 0, 0, 0.1)',
    flexShrink: 0
}

const categoryListStyle = {
    display: 'flex',
    justifyContent: 'space-between',
    gap: '16px',
    overflowX: 'auto',
    padding: '8px 4px',
    margin: '0 -4px'
}

const categoryItemStyle = {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    margin: '0 10px',
    gap: '8px',
    padding: '16px',
    border: 'none',
    background: 'white',
    cursor: 'pointer',
    borderRadius: '16px',
    boxShadow: '0 2px 4px rgba(0, 0, 0, 0.05)',
    minWidth: '100px'
}

const categoryIconStyle = {
    width: '40px',
    height: '40px',
    borderRadius: '12px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center'
}

const categoryLabelStyle = {
    fontSize: '0.875rem',
    color: theme.colors.darkGrey,
    fontWeight: theme.fonts.weight.medium
}

const minusButtonStyle = {
    ...weightButtonStyle,
    backgroundColor: theme.colors.red
}

const plusButtonStyle = {
    ...weightButtonStyle,
    backgroundColor: theme.colors.primary
}
</script>

<style scoped>
::-webkit-scrollbar {
    display: none;
}
</style>
