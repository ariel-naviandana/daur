import './bootstrap';
import { createApp } from 'vue';
import Home from './pages/Home.vue';
import Profile from './pages/Profile.vue';
import RiwayatRecycle from './pages/RiwayatRecycle.vue';
import Chat from './pages/Chat.vue';

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
      case 'chat':
          component = Chat;
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
