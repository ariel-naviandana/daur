import './bootstrap';
import { createApp } from 'vue';
import Home from './pages/Home.vue';
import Profile from './pages/Profile.vue';
import RiwayatRecycle from './pages/RiwayatRecycle.vue';
import Artikel from './pages/Artikel.vue';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';



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
    case 'artikel':
      component = Artikel;
      break;
    case 'login':
      component = Login;
      break;
    case 'register':
      component = Register;
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
