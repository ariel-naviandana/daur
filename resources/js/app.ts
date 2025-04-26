import './bootstrap';
import { createApp } from 'vue';
import Home from './pages/Home.vue';
import Profile from './pages/Profile.vue';
import RiwayatRecycle from './pages/RiwayatRecycle.vue';
import RiwayatRecycleAdmin from './pages/RiwayatRecycleAdmin.vue';

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
    case 'riwayat-recycle':
      component = RiwayatRecycle;
      break;
    case 'riwayat-recycle-admin':
      component = RiwayatRecycleAdmin;
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
