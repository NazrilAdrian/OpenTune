<p align="center">
  <img src="./Banner.png" alt="OpenTune Banner" width="800">
</p>

# OpenTune 🎵

**OpenTune** adalah aplikasi web pemutar musik yang memungkinkan pengguna untuk mengunggah, mengelola, mencari, dan memutar lagu secara online. Aplikasi ini dirancang dengan konsep sederhana dan modern agar pengguna dapat mengatur koleksi musik pribadi serta membuat playlist sesuai kebutuhan.

## 📌 Tentang OpenTune

OpenTune dikembangkan sebagai proyek aplikasi berbasis web menggunakan **Laravel**. Sistem ini menyediakan fitur pengelolaan lagu, artis, album, genre, serta playlist.

Pengguna dapat mengunggah lagu secara individual maupun dalam bentuk album, kemudian mengelola koleksi musik tersebut melalui halaman **Your Song** dan **Your Album**. Pengguna juga dapat mencari lagu dan membuat playlist pribadi.

Selain pengguna biasa, OpenTune memiliki **Admin** yang bertanggung jawab dalam pengelolaan data dan konten yang terdapat di dalam sistem.

## ✨ Fitur Utama

### 🎧 Music Management

* Upload lagu
* Upload album dalam bentuk ZIP
* Memutar lagu
* Mencari lagu
* Mengedit metadata lagu
* Menghapus lagu
* Mengelola artis
* Mengelola album
* Mengelola genre

### 📚 Personal Music Library

* **Your Song** — menampilkan lagu yang diunggah oleh pengguna
* **Your Album** — menampilkan album yang dibuat atau diunggah oleh pengguna
* Edit dan delete lagu atau album milik pengguna

### 🎶 Playlist

* Membuat playlist
* Mencari playlist
* Menambahkan lagu ke playlist
* Menghapus lagu dari playlist
* Mengatur urutan lagu dalam playlist
* Memutar lagu dari playlist

### 🔎 Search & Discovery

* Pencarian lagu berdasarkan keyword
* Daily Recommendations
* Recently Uploaded
* Top This Month
* Eksplorasi lagu, artis, dan album

### 👤 User & Admin

* Login dan autentikasi pengguna
* Role **User** dan **Admin**
* Admin dapat mengelola data pengguna dan konten musik

## 🛠️ Teknologi

* **Laravel**
* **PHP**
* **MySQL**
* **HTML**
* **CSS**
* **JavaScript**
* **Git & GitHub**

## 🗂️ Struktur Modul

| Modul          | Penanggung Jawab        |
| -------------- | ----------------------- |
| User & Profile | Nazril Adrian           |
| Song & Artist  | Syahid Ahmad Yasin      |
| Playlist       | Rafli Rizqi Fadillah    |
| Admin & Genre  | Nazla Arina Nurfia Sofa |

## 🔄 Gambaran Sistem

```text
Login
  │
  ├── User
  │    │
  │    └── Main Menu
  │         ├── Home
  │         ├── Search
  │         ├── Your Song
  │         ├── Your Album
  │         ├── Playlist
  │         ├── Upload Song
  │         ├── Upload Album
  │         └── Profile
  │
  └── Admin
       │
       └── Admin Menu
            ├── Dashboard
            ├── User Management
            ├── Song Management
            ├── Album Management
            ├── Artist Management
            └── Genre Management
```

## 🗄️ Database

OpenTune menggunakan database relasional dengan beberapa tabel utama:

* `users`
* `artists`
* `albums`
* `genres`
* `songs`
* `playlists`
* `playlist_songs`

### Relasi Database

```text
users 1:N songs
users 1:N albums
users 1:N playlists
artists 1:N songs
artists 1:N albums
albums 1:N songs
genres 1:N songs
playlists 1:N playlist_songs
songs 1:N playlist_songs
```

## 🎓 Tujuan Pengembangan

OpenTune dikembangkan sebagai **tugas untuk mata kuliah Framework Pemrograman Web**.

Proyek ini bertujuan untuk menerapkan penggunaan framework dalam pengembangan aplikasi web serta mengimplementasikan berbagai konsep pengembangan web, seperti:

* Framework Laravel
* CRUD
* Autentikasi dan role pengguna
* Relasi database
* Pengelolaan data musik
* Upload dan pengelolaan file
* Pembuatan playlist
* Pencarian data
* Integrasi antara frontend dan backend

## 👥 Team

| Nama                    | Modul          |
| ----------------------- | -------------- |
| Nazril Adrian           | User & Profile |
| Syahid Ahmad Yasin      | Song & Artist  |
| Rafli Rizqi Fadillah    | Playlist       |
| Nazla Arina Nurfia Sofa | Admin & Genre  |

---

<p align="center">
  <b>OpenTune — Your Music, Your Collection, Your Playlist. 🎵</b>
</p>
