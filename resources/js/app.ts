import './bootstrap';
import { createApp } from 'vue';
import Home from './pages/Home.vue';
import Profile from './pages/Profile.vue';
import Chat from './pages/Chat.vue';
import Recycle from './pages/Recycle.vue';
import RiwayatRecycle from './pages/RiwayatRecycle.vue';
import ManajemenRecycle from "./pages/ManajemenRecycle.vue";

const el = document.getElementById('app');

if (el) {
  const page = el.dataset.page;

  let component;

  switch (page) {
    case 'home':
      component = Home;
      break;
    case 'profile':
      component = Profile;
      break;
    case 'chat':
      component = Chat;
      break;
    case 'recycle':
      component = Recycle;
      break;
    case 'riwayat-recycle':
        component = RiwayatRecycle;
        break;
    case 'manajemen-recycle':
        component = ManajemenRecycle;
        break;
    default:
      console.error('Unknown page component:', page);
      break;
  }

  if (component) {
    createApp(component).mount('#app');
    console.log(`Mounted ${page} component`);
  }
}
