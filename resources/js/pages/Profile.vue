<template>
  <Navbar />
  <div :style="layoutStyle">
    <!-- Profil dan Card Saldo -->
    <div :style="topSectionStyle">
      <div :style="imgStyle" style="position: relative;">
        <img
          :src="previewImage || userProfileImage || '/images/profile-pict-holder.svg'"
          alt="Foto Profil"
          style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;"
        />

        <div
          @click="triggerFileInput"
          style="position: absolute; bottom: 10px; right: 10px; background-color: #FFFFFF; border-radius: 50%; padding: 6px; cursor: pointer; box-shadow: 0 0 6px rgba(0, 0, 0, 0.2);"
        >
          <img src="/public/images/edit.png" alt="Edit" style="width: 20px; height: 20px;" />
        </div>

        <input
          type="file"
          accept="image/*"
          ref="fileInputRef"
          @change="handleImageChange"
          style="display: none;"
        />
      </div>

      <div :style="containerStyle">
        <div :style="columnStyle" @mouseover="hoverSaldo = true" @mouseleave="hoverSaldo = false">
          <h2 :style="headingStyle">Saldo DAUR</h2>
          <div :style="[textStatStyle, hoverSaldo ? hoverCardStyle : {}]">
            <a href="/saldo">Rp. 25.000</a>
          </div>
        </div>

        <div :style="columnStyle">
          <h2 :style="headingStyle">Total Sampah</h2>
          <div :style="textStatStyle">15 Kg</div>
        </div>

        <div :style="columnStyle" @mouseover="hoverRiwayat = true" @mouseleave="hoverRiwayat = false">
          <h2 :style="headingStyle">Riwayat</h2>
          <a href="/riwayat">
            <img
              src="/public/images/icon_history.svg"
              alt="Riwayat"
              :style="[iconStyle, hoverRiwayat ? hoverIconStyle : {}]"
            />
          </a>
        </div>
      </div>
    </div>

    <!-- Form Profil -->
    <form style="max-width: 680px; margin: 2rem auto;" @submit.prevent="simpanProfil">
      <div style="margin-bottom: 1rem;">
        <label for="name" :style="labelStyle">Nama</label>
        <input
          id="name"
          type="text"
          placeholder="Nama Lengkap"
          autocomplete="off"
          v-model="nama"
          :style="inputStyle"
        />
      </div>

      <div style="margin-bottom: 1rem;">
        <label for="address" :style="labelStyle">Alamat</label>
        <input
          id="address"
          type="text"
          placeholder="Alamat"
          autocomplete="off"
          v-model="alamat"
          :style="inputStyle"
        />
      </div>

      <div style="margin-bottom: 1rem;">
        <label for="phone" :style="labelStyle">Nomor Telp</label>
        <input
          id="phone"
          type="text"
          placeholder="Nomor Telp"
          autocomplete="off"
          v-model="phone"
          :style="inputStyle"
        />
      </div>

      <div :style="buttonContainerStyle">
        <button
          type="submit"
          @mouseover="isHover = true"
          @mouseleave="isHover = false"
          :style="[buttonStyle, isHover ? buttonHoverStyle : {}]"
        >
          Simpan
        </button>
      </div>
    </form>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import Navbar from '@/components/Navbar.vue'
import { theme } from '@/helpers/theme'
import { useAuthApi } from '@/composables/useAuthApi'
import { useImageApi } from '@/composables/useImageApi'

const { getCurrentUser, updateProfile } = useAuthApi()
const { uploadToCloudinary } = useImageApi()

const fileInputRef = ref<HTMLInputElement | null>(null)
const selectedImage = ref<File | null>(null)
const previewImage = ref<string | null>(null)
const userProfileImage = ref<string | null>(null)

const nama = ref('')
const alamat = ref('')
const phone = ref('')
const userId = ref<number | null>(null)

const hoverSaldo = ref(false)
const hoverRiwayat = ref(false)
const isHover = ref(false)

const triggerFileInput = () => {
    fileInputRef.value?.click()
}

onMounted(async () => {
    const user = await getCurrentUser()
    if (user) {
        nama.value = user.name || ''
        alamat.value = user.address || ''
        phone.value = user.phone || ''
        userProfileImage.value = user.profile_picture || null
        userId.value = user.id
  }
})

const handleImageChange = (event: Event) => {
    const file = (event.target as HTMLInputElement)?.files?.[0]
    if (file) {
        selectedImage.value = file
        previewImage.value = URL.createObjectURL(file)
      }
}

const simpanProfil = async () => {
    let imageUrl = userProfileImage.value

    if (selectedImage.value) {
        const uploadedUrl = await uploadToCloudinary(selectedImage.value)
        if (!uploadedUrl) {
            alert('Gagal upload gambar!')
            return
        }
        imageUrl = uploadedUrl
    }

    const updatedUser = await updateProfile({
        id: userId.value,
        name: nama.value,
        address: alamat.value,
        phone: phone.value,
        profile_picture: imageUrl,
    })

    if (updatedUser) {
        userProfileImage.value = updatedUser.image || imageUrl
        previewImage.value = null
        selectedImage.value = null
        alert('Profil berhasil diperbarui!')
    } else {
        alert('Gagal menyimpan profil.')
    }
}

const hoverCardStyle = {
    transform: 'scale(1.05)',
    transition: '0.2s ease-in-out',
}

const hoverIconStyle = {
    transform: 'scale(1.1)',
    transition: '0.2s ease-in-out',
}

const buttonHoverStyle = {
    backgroundColor: '#2d862d',
    transform: 'scale(1.05)',
}

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
    padding: '2rem',
}

const topSectionStyle = {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    gap: '30px',
    flexWrap: 'wrap',
    marginBottom: '40px',
}

const containerStyle = {
    backgroundColor: theme.colors.whiteElement,
    minHeight: '180px',
    width: '90%',
    maxWidth: '540px',
    display: 'flex',
    justifyContent: 'space-around',
    alignItems: 'center',
    borderRadius: '16px',
    padding: '24px',
    boxShadow: '0 4px 20px rgba(0, 0, 0, 0.1)',
}

const imgStyle = {
    width: '190px',
    height: '190px',
    border: '2px solid #3a9e3a',
    borderRadius: '50%',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
}

const columnStyle = {
    textAlign: 'center',
}

const headingStyle = {
    fontSize: theme.fonts.size.subheading,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    marginBottom: '10px',
}

const textStatStyle = {
    fontSize: theme.fonts.size.subheading,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.primary,
}

const labelStyle = {
    display: 'block',
    fontSize: theme.fonts.size.medium,
    fontWeight: theme.fonts.weight.bold,
    color: theme.colors.darkGrey,
    marginBottom: '10px',
}

const inputStyle = {
    backgroundColor: theme.colors.whiteElement,
    width: '100%',
    border: '1px solid #ccc',
    padding: '10px 16px',
  borderRadius: '999px',
    fontSize: theme.fonts.size.base,
    fontFamily: theme.fonts.family,
    color: theme.colors.darkGrey,
}

const iconStyle = {
    width: '40px',
    height: '40px',
    objectFit: 'contain',
    margin: 'auto',
}

const buttonContainerStyle = {
    display: 'flex',
    justifyContent: 'flex-end',
    marginTop: '20px',
}

const buttonStyle = {
    backgroundColor: theme.colors.primary,
    color: 'white',
    fontWeight: theme.fonts.weight.semibold,
    fontSize: theme.fonts.size.base,
    border: 'none',
    borderRadius: '999px',
    padding: '10px 30px',
    cursor: 'pointer',
    transition: '0.2s ease-in-out',
}
</script>
