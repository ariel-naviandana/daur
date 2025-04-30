<template>
    <div :style="layoutStyle">
    <Navbar />
    <div :style="mainContentStyle">
        <div class="home-page" :style="homePageStyle">
            <section class="hero">
                <img src="/public/images/hero-image.png" alt="Hero Image" :style="heroImgStyle" />
            </section>

            <section class="mission" :style="missionStyle">
                <div class="mission-img">
                    <img src="/public/images/recycle-image.png" alt="Recycle" :style="missionImgStyle" />
                </div>
                <div class="mission-text" :style="missionTextStyle">
                    <h2 :style="missionHeadingStyle">Meningkatkan Kesadaran, Mewujudkan Perubahan</h2>
                    <p :style="missionParagraphStyle">
                        Misi kami adalah meningkatkan kesadaran akan pentingnya daur ulang dan menyediakan solusi mudah bagi
                        masyarakat untuk mendaur ulang sampah dengan benar. Dengan teknologi dan inovasi, kami menghadirkan cara baru
                        dalam pengelolaan limbah.
                    </p>
                </div>
            </section>

            <section class="title_center" :style="titleCenterStyle">
                <h2 :style="titleHeadingStyle">Cara Mengikuti</h2>
                <div :style="containerStyle">
                    <div class="card" :style="cardStyle">
                        <div class="badge" :style="badgeStyle">1</div>
                        <div class="card-body" :style="cardBodyStyle">
                            <img :style="cardImgStyle" src="/public/images/langkah1.svg" alt="Pilih dan Kemas" />
                            <div class="label" :style="labelStyle">Pilah dan Kemas</div>
                        </div>
                    </div>
                    <div class="card" :style="cardStyle">
                        <div class="badge" :style="badgeStyle">2</div>
                        <div class="card-body" :style="cardBodyStyle">
                            <img :style="cardImgStyle" src="/public/images/langkah2.svg" alt="Buat Transaksi" />
                            <div class="label" :style="labelStyle">Buat Transaksi</div>
                        </div>
                    </div>
                    <div class="card" :style="cardStyle">
                        <div class="badge" :style="badgeStyle">3</div>
                        <div class="card-body" :style="cardBodyStyle">
                            <img :style="cardImgStyle" src="/public/images/langkah3.svg" alt="Kirim Sampah" />
                            <div class="label" :style="labelStyle">Kirim Sampah</div>
                        </div>
                    </div>
                    <div class="card" :style="cardStyle">
                        <div class="badge" :style="badgeStyle">4</div>
                        <div class="card-body" :style="cardBodyStyle">
                            <img :style="cardImgStyle" src="/public/images/langkah4.svg" alt="Sampah Terkelola" />
                            <div class="label" :style="labelStyle">Sampah Terkelola</div>
                        </div>
                    </div>
                </div>
                <button class="send-button" :style="sendButtonStyle" @click="goToRecycle">Kirim Sampah</button>
            </section>
            <h2 :style="titleHeadingStyle">Jenis Sampah</h2>
            <CategoryList @category-clicked="openPopup"/>
        </div>
    </div>
        <PopupDetailSampah
            :isOpen="isPopupOpen"
            :selectedCategory="selectedCategory"
            @close="closePopup"
            @add="handleAddItem"
        />
    </div>
</template>

<script lang="ts" setup>
import Navbar from '../components/Navbar.vue'
import { theme } from '@/config/theme'
import CategoryList from "@/components/CategoryList.vue";
import {ref} from "vue";
import PopupDetailSampah from "@/components/PopupDetailSampah.vue";

const goToRecycle = () => {
    window.location.href = '/recycle';
};

const layoutStyle = {
    backgroundColor: theme.colors.whiteBg,
    minHeight: '100vh',
    fontFamily: theme.fonts.family,
}

const mainContentStyle = {
    maxWidth: '1200px',
    margin: '0 auto',
    padding: '24px',
    overflowX: 'hidden',
}

const titleCenterStyle = {
    textAlign: 'center',
    padding: '40px'
}

const titleHeadingStyle = {
    fontSize: theme.fonts.size.heading,
    fontWeight: 'bold',
    textAlign: 'center'
}

const heroImgStyle = {
    width: '1440px',
    height: 'auto'
}

const missionStyle = {
    display: 'flex',
    paddingTop: '50px',
    paddingLeft: '50px',
    paddingRight: '50px',
    alignItems: 'center'
}

const missionImgStyle = {
    width: '150vh',
    borderRadius: '10px'
}

const missionTextStyle = {
    paddingLeft: '20px'
}

const missionHeadingStyle = {
    marginLeft: '40px',
    fontSize: theme.fonts.size.heading,
    color: '#222222',
    fontWeight: 'bold'
}

const missionParagraphStyle = {
    marginTop: '10px',
    marginLeft: '40px',
    fontSize: theme.fonts.size.medium,
    color: '#222222'
}

const containerStyle = {
    marginTop: '20px',
    display: 'flex',
    justifyContent: 'center',
    gap: '50px',
    flexWrap: 'wrap'
}

const cardStyle = {
    position: 'relative',
    width: '160px',
    height: '160px',
    borderRadius: '20px',
    boxShadow: '0 2px 8px rgba(0, 0, 0, 0.1)',
    background: '#fff',
    textAlign: 'center',
    fontFamily: 'sans-serif',
    boxSizing: 'border-box'
}

const cardBodyStyle = {
    margin: '10px 0',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center'
}

const cardImgStyle = {
    height: '80px',
    marginTop: '26px',
    marginBottom: '6px',
}

const badgeStyle = {
    position: 'absolute',
    top: '0',
    left: '0',
    width: '40px',
    height: '40px',
    background: '#4CAF50',
    color: 'white',
    borderTopLeftRadius: '20px',
    borderBottomRightRadius: '20px',
    fontSize: theme.fonts.size.medium,
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center'
}

const labelStyle = {
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.regular,
    color: theme.colors.darkGrey,
}

const sendButtonStyle = {
    padding: '10px 60px',
    borderRadius: '30px',
    border: 'none',
    marginTop: '24px',
    backgroundColor: theme.colors.primary,
    fontSize: theme.fonts.size.base,
    fontWeight: theme.fonts.weight.semibold,
    color: theme.colors.whiteElement,
    cursor: 'pointer',
    position: 'relative',
    zIndex: 10,
}

const trashTypesStyle = {
    display: 'flex',
    gap: '40px',
    justifyContent: 'center',
    marginBottom: '60px'
}

const smallCardStyle = {
    width: '100px',
    height: '100px',
    padding: '16px',
    borderRadius: '24px',
    boxShadow: '0 2px 8px rgba(0, 0, 0, 0.1)',
    background: '#fff',
    textAlign: 'center',
    boxSizing: 'border-box',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center'
}

const smallCardImgStyle = {
    height: '40px',
    marginTop: '14px',
    marginBottom: '6px',
}

const labelJenisStyle = {
    fontSize: '24px',
    fontWeight: 500,
    color: '#222222',
    marginTop: '16px'
}

const isPopupOpen = ref(false)
const selectedCategory = ref<{ id: number; name: string; icon: string } | null>(null)

const openPopup = (category: { id: number; name: string; icon: string }) => {
    selectedCategory.value = category
    isPopupOpen.value = true
}

const closePopup = () => {
    isPopupOpen.value = false
}

const handleAddItem = (item: { type: string; quantity: number; pricePerKg: number }) => {
    window.location.href = '/recycle'
    closePopup()
}
</script>

<style scoped>

</style>
