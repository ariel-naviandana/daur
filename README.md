<h1 align="center">
    <img width="400px" src="https://github.com/user-attachments/assets/5c5cd5db-64f8-45f4-a5d7-2890c3d52bcc">
</h1>

# DAUR - Aplikasi Web Pengelolaan Sampah
<p allign="justify">
DAUR adalah aplikasi web yang dirancang untuk membantu masyarakat dalam mengelola sampah secara digital. Aplikasi ini memungkinkan pengguna untuk memilah sampah, mengajukan layanan daur ulang (pick-up atau self drop), terhubung dengan bank sampah terdekat, serta memperoleh saldo dari hasil daur ulang. DAUR juga menyediakan fitur edukasi tentang jenis-jenis sampah dan memfasilitasi komunikasi antara pengguna dan pengelola sampah.
</p>

## CATATAN
Pada tahap development, setiap anggota melakukan push ke branch masing-masing (ariel, ivan, farhah, satria), lalu akan di merge di branch main. Commit history selengkapnya dapat dilihat pada masing-masing branch.

## Tautan video
<a>https://youtu.be/stQZfjQeYaw</a>

## Cara instalasi dan menjalankan aplikasi
### Cara 1
1. Akses tautan https://daur-seven.vercel.app/ 
2. Melakukan Register & Login (dapat dilakukan menggunakan dummy)

### Cara 2
Alternatif jika tidak dapat diakses : 
1. Clone repository github https://github.com/ariel-naviandana/daur/
2. Pindah ke branch final
2. Buat file .env
3. Jalankan perintah
  ```php artisan migrate --seed```
4. Jalankan perintah
  ```php artisan serve```
5. Buka terminal baru dan jalankan
```npm run dev```
  
## Struktur proyek

```
📦app
 ┣ 📂Http
 ┃ ┣ 📂Controllers
 ┃ ┃ ┣ 📜AdminPageController.php
 ┃ ┃ ┣ 📜ArticleController.php
 ┃ ┃ ┣ 📜AuthController.php
 ┃ ┃ ┣ 📜BankController.php
 ┃ ┃ ┣ 📜CategoryController.php
 ┃ ┃ ┣ 📜ChatController.php
 ┃ ┃ ┣ 📜Controller.php
 ┃ ┃ ┣ 📜PageController.php
 ┃ ┃ ┣ 📜RecycleTransactionController.php
 ┃ ┃ ┣ 📜RecycleTransactionItemController.php
 ┃ ┃ ┣ 📜UserController.php
 ┃ ┃ ┣ 📜WalletController.php
 ┃ ┃ ┣ 📜WalletTransactionController.php
 ┃ ┃ ┗ 📜WasteTypeController.php
 ┃ ┗ 📂Middleware
 ┃ ┃ ┣ 📜AuthMiddleware.php
 ┃ ┃ ┣ 📜ForceHttps.php
 ┃ ┃ ┣ 📜NoCacheMiddleware.php
 ┃ ┃ ┗ 📜RedirectToHome.php
 ┣ 📂Models
 ┃ ┣ 📜Article.php
 ┃ ┣ 📜Bank.php
 ┃ ┣ 📜Category.php
 ┃ ┣ 📜Chat.php
 ┃ ┣ 📜RecycleTransaction.php
 ┃ ┣ 📜RecycleTransactionItem.php
 ┃ ┣ 📜User.php
 ┃ ┣ 📜Wallet.php
 ┃ ┣ 📜WalletTransaction.php
 ┃ ┗ 📜WasteType.php
 ┗ 📂Providers
 ┃ ┗ 📜AppServiceProvider.php
📦public
 ┣ 📂build
 ┃ ┣ 📂assets
 ┃ ┗ 📜manifest.json
 ┣ 📂images
 ┃ ┣ 📜artikel2.jpg
 ┃ ┣ 📜Artikel3.png
 ┃ ┣ 📜artikel4.jpg
 ┃ ┣ 📜artikel5.jpg
 ┃ ┣ 📜back-btn.svg
 ┃ ┣ 📜camera-icon.svg
 ┃ ┣ 📜edit.png
 ┃ ┣ 📜hero-image.png
 ┃ ┣ 📜ic-transaction.svg
 ┃ ┣ 📜icon-upload.svg
 ┃ ┣ 📜icon_article.svg
 ┃ ┣ 📜icon_history.svg
 ┃ ┣ 📜icon_plus.svg
 ┃ ┣ 📜ic_chat.svg
 ┃ ┣ 📜ic_jenis_aluminium.svg
 ┃ ┣ 📜ic_jenis_alumunium.svg
 ┃ ┣ 📜ic_jenis_besi.svg
 ┃ ┣ 📜ic_jenis_botol_kaca.svg
 ┃ ┣ 📜ic_jenis_botol_plastik.svg
 ┃ ┣ 📜ic_jenis_kardus.svg
 ┃ ┣ 📜ic_jenis_kertas.svg
 ┃ ┣ 📜ic_manajemen_user.svg
 ┃ ┣ 📜ic_search.svg
 ┃ ┣ 📜image.png
 ┃ ┣ 📜langkah1.svg
 ┃ ┣ 📜langkah2.svg
 ┃ ┣ 📜langkah3.svg
 ┃ ┣ 📜langkah4.svg
 ┃ ┣ 📜list-icon.png
 ┃ ┣ 📜logo-daur.png
 ┃ ┣ 📜marker-icon-2x.png
 ┃ ┣ 📜marker-icon.png
 ┃ ┣ 📜marker-shadow.png
 ┃ ┣ 📜money-in.svg
 ┃ ┣ 📜money-out.svg
 ┃ ┣ 📜money.png
 ┃ ┣ 📜people.png
 ┃ ┣ 📜profile-icon.svg
 ┃ ┣ 📜profile-pict-holder.svg
 ┃ ┣ 📜recycle-image.png
 ┃ ┣ 📜send-icon.svg
 ┃ ┣ 📜transfer.png
 ┃ ┣ 📜trash.png
 ┃ ┣ 📜user-icon.png
 ┃ ┗ 📜withdraw-icon.svg
 ┣ 📜.htaccess
 ┣ 📜favicon.ico
 ┣ 📜index.php
 ┗ 📜robots.txt
📦resources
 ┣ 📂css
 ┃ ┗ 📜app.css
 ┣ 📂js
 ┃ ┣ 📂components
 ┃ ┃ ┣ 📜ArtikelCard.vue
 ┃ ┃ ┣ 📜BankCard.vue
 ┃ ┃ ┣ 📜CategoryCard.vue
 ┃ ┃ ┣ 📜CategoryList.vue
 ┃ ┃ ┣ 📜ChatList.vue
 ┃ ┃ ┣ 📜ChatWindow.vue
 ┃ ┃ ┣ 📜FormArticle.vue
 ┃ ┃ ┣ 📜InfoCard.vue
 ┃ ┃ ┣ 📜ManajemenArtikelCard.vue
 ┃ ┃ ┣ 📜ManajemenCategory.vue
 ┃ ┃ ┣ 📜ManajemenWasteType.vue
 ┃ ┃ ┣ 📜MessageInput.vue
 ┃ ┃ ┣ 📜MutasiSaldoCard.vue
 ┃ ┃ ┣ 📜Navbar.vue
 ┃ ┃ ┣ 📜PermintaanPenarikanCard.vue
 ┃ ┃ ┣ 📜PopupConfirm.vue
 ┃ ┃ ┣ 📜PopupDelete.vue
 ┃ ┃ ┣ 📜PopupDeleteArticle.vue
 ┃ ┃ ┣ 📜PopupDetailRecycle.vue
 ┃ ┃ ┣ 📜PopupDetailSampah.vue
 ┃ ┃ ┣ 📜PopupEdit.vue
 ┃ ┃ ┣ 📜PopupEditArticle.vue
 ┃ ┃ ┣ 📜PopupEditRole.vue
 ┃ ┃ ┣ 📜PopupFormBank.vue
 ┃ ┃ ┣ 📜PopupFormCategory.vue
 ┃ ┃ ┣ 📜PopupFormWasteType.vue
 ┃ ┃ ┣ 📜PopupNotifikasi.vue
 ┃ ┃ ┣ 📜PopupSimpanProfile.vue
 ┃ ┃ ┣ 📜PopupWithdrawal.vue
 ┃ ┃ ┣ 📜ProfileIcon.vue
 ┃ ┃ ┣ 📜RecycleCard.vue
 ┃ ┃ ┣ 📜SaldoCard.vue
 ┃ ┃ ┣ 📜SaldoKeluarCard.vue
 ┃ ┃ ┣ 📜SaldoMasukCard.vue
 ┃ ┃ ┣ 📜TransactionCard.vue
 ┃ ┃ ┣ 📜UserStatCard.vue
 ┃ ┃ ┗ 📜WasteTypeCard.vue
 ┃ ┣ 📂composables
 ┃ ┃ ┣ 📜useArticleApi.ts
 ┃ ┃ ┣ 📜useAuthApi.ts
 ┃ ┃ ┣ 📜useBankApi.ts
 ┃ ┃ ┣ 📜useCategoryApi.ts
 ┃ ┃ ┣ 📜useChatApi.ts
 ┃ ┃ ┣ 📜useCsrf.ts
 ┃ ┃ ┣ 📜useImageApi.ts
 ┃ ┃ ┣ 📜useRecycleTransactionApi.ts
 ┃ ┃ ┣ 📜useRecycleTransactionItemApi.ts
 ┃ ┃ ┣ 📜useUserApi.ts
 ┃ ┃ ┣ 📜useWalletApi.ts
 ┃ ┃ ┣ 📜useWalletTransactionApi.ts
 ┃ ┃ ┗ 📜useWasteTypeApi.ts
 ┃ ┣ 📂helpers
 ┃ ┃ ┣ 📜axios.ts
 ┃ ┃ ┗ 📜theme.ts
 ┃ ┣ 📂interfaces
 ┃ ┃ ┣ 📜Article.ts
 ┃ ┃ ┣ 📜Bank.ts
 ┃ ┃ ┣ 📜Category.ts
 ┃ ┃ ┣ 📜Chat.ts
 ┃ ┃ ┣ 📜RecycleTransaction.ts
 ┃ ┃ ┣ 📜RecycleTransactionItem.ts
 ┃ ┃ ┣ 📜User.ts
 ┃ ┃ ┣ 📜Wallet.ts
 ┃ ┃ ┣ 📜WalletTransaction.ts
 ┃ ┃ ┗ 📜WasteType.ts
 ┃ ┣ 📂pages
 ┃ ┃ ┣ 📜Artikel.vue
 ┃ ┃ ┣ 📜Chat.vue
 ┃ ┃ ┣ 📜Home.vue
 ┃ ┃ ┣ 📜HomeAdmin.vue
 ┃ ┃ ┣ 📜Login.vue
 ┃ ┃ ┣ 📜ManajemenArtikel.vue
 ┃ ┃ ┣ 📜ManajemenBank.vue
 ┃ ┃ ┣ 📜ManajemenRecycle.vue
 ┃ ┃ ┣ 📜ManajemenSaldo.vue
 ┃ ┃ ┣ 📜ManajemenSampah.vue
 ┃ ┃ ┣ 📜ManajemenUser.vue
 ┃ ┃ ┣ 📜Profile.vue
 ┃ ┃ ┣ 📜Recycle.vue
 ┃ ┃ ┣ 📜Register.vue
 ┃ ┃ ┣ 📜RiwayatRecycle.vue
 ┃ ┃ ┗ 📜Saldo.vue
 ┃ ┣ 📂stores
 ┃ ┃ ┗ 📜auth.ts
 ┃ ┣ 📜app.ts
 ┃ ┗ 📜bootstrap.js
 ┗ 📂views
 ┃ ┣ 📜admin-users.blade.php
 ┃ ┣ 📜artikel.blade.php
 ┃ ┣ 📜chat.blade.php
 ┃ ┣ 📜home-admin.blade.php
 ┃ ┣ 📜home.blade.php
 ┃ ┣ 📜login.blade.php
 ┃ ┣ 📜manajemen-artikel.blade.php
 ┃ ┣ 📜manajemen-bank.blade.php
 ┃ ┣ 📜manajemen-recycle.blade.php
 ┃ ┣ 📜manajemen-saldo.blade.php
 ┃ ┣ 📜manajemen-sampah.blade.php
 ┃ ┣ 📜master.blade.php
 ┃ ┣ 📜profile.blade.php
 ┃ ┣ 📜recycle.blade.php
 ┃ ┣ 📜register.blade.php
 ┃ ┣ 📜riwayat-recycle.blade.php
 ┃ ┗ 📜saldo.blade.php
📦routes
 ┣ 📜console.php
 ┗ 📜web.php
📜.env
📜composer.json
📜package.json
📜tsconfig.js
📜vercel.json
📜vite.config.js
```
