import "./bootstrap";
import { createApp } from "vue";
import Home from "./pages/Home.vue";
import Profile from "./pages/Profile.vue";
import RiwayatRecycle from "./pages/RiwayatRecycle.vue";
import Saldo from "./pages/Saldo.vue";

const el = document.getElementById("app");

if (el) {
    const page = el.dataset.page;

    let component;

    switch (page) {
        case "home":
            component = Home;
            break;
        case "profile":
            component = Profile;
            break;
        case "riwayat-recycle":
            component = RiwayatRecycle;
            break;
        case "saldo":
            component = Saldo;
            break;
        default:
            console.error("Unknown page component:", page);
            break;
    }

    if (component) {
        createApp(component).mount("#app");
        console.log(`Mounted ${page} component`);
    }
}
