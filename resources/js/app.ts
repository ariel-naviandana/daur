import './bootstrap'
import { createApp } from 'vue'
import Home from './pages/Home.vue'
import Profile from './pages/Profile.vue'
import Chat from './pages/Chat.vue'
import Recycle from './pages/Recycle.vue'
import RiwayatRecycle from './pages/RiwayatRecycle.vue'
import ManajemenRecycle from "./pages/ManajemenRecycle.vue"
import UserManagement from "./pages/ManajemenUser.vue"
import Saldo from "./pages/Saldo.vue"
import Artikel from "./pages/Artikel.vue"
import Login from "./pages/Login.vue"
import Register from "./pages/Register.vue"
import HomeAdmin from "./pages/HomeAdmin.vue"
import ManajemenSaldo from "./pages/ManajemenSaldo.vue"
import ManajemenArtikel from "./pages/ManajemenArtikel.vue"
import ManajemenSampah from "./pages/ManajemenSampah.vue"
import ManajemenBank from "./pages/ManajemenBank.vue"

const el = document.getElementById('app')

if (el) {
  const page = el.dataset.page

  let component

  switch (page) {
    case 'home':
      component = Home
      break
    case 'profile':
      component = Profile
      break
    case 'chat':
      component = Chat
      break
    case 'recycle':
      component = Recycle
      break
    case 'riwayat-recycle':
        component = RiwayatRecycle
        break
    case 'manajemen-recycle':
        component = ManajemenRecycle
        break
    case 'manajemen-sampah':
        component = ManajemenSampah
        break
    case 'admin-users':
        component = UserManagement
        break
    case "saldo":
        component = Saldo
        break
    case 'artikel':
        component = Artikel
        break
    case 'login':
        component = Login
        break
    case 'register':
        component = Register
        break
    case 'home-admin':
        component = HomeAdmin
        break
    case 'manajemen-artikel':
        component = ManajemenArtikel
        break
    case 'manajemen-saldo':
        component = ManajemenSaldo
        break
    case 'manajemen-bank':
        component = ManajemenBank
        break
    default:
      console.error('Unknown page component:', page)
      break
  }

  if (component) {
    createApp(component).mount('#app')
    console.log(`Mounted ${page} component`)
  }
}
