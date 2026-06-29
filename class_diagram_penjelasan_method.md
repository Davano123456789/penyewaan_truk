# PENJELASAN METHOD CLASS DIAGRAM - PROYEK PENYEWAAN TRUK

Berikut adalah penjelasan lengkap untuk setiap fungsi/method dari masing-masing Class/Tabel dalam sistem Penyewaan Truk. Format penjelasan ini disesuaikan dengan standar penulisan dokumen skripsi untuk mempermudah pemahaman fungsi kerja sistem.

---

### 1. CLASS: users (Model: User)
Digunakan untuk mengelola data akun pengguna (Admin, Client, dan Sopir).
* **index()** : menampilkan daftar seluruh data pengguna (klien, sopir, dan admin) yang terdaftar di dalam sistem.
* **show(id: int)** : menampilkan detail informasi profil pengguna berdasarkan ID yang dipilih.
* **create()** : menampilkan halaman formulir pendaftaran atau penambahan pengguna baru oleh admin.
* **store(request: Request)** : menyimpan data pengguna baru hasil input formulir ke dalam database.
* **edit(id: int)** : menampilkan halaman formulir ubah data pengguna berdasarkan ID yang dipilih.
* **update(request: Request, id: int)** : memperbarui informasi data pengguna di database berdasarkan ID yang dipilih.
* **destroy(id: int)** : menghapus data pengguna tertentu dari sistem berdasarkan ID yang dipilih.
* **updateProfil(request: Request)** : memperbarui data profil mandiri pengguna yang sedang login saat ini (foto profil, nomor telepon, alamat, dll).

---

### 2. CLASS: armadas (Model: Armada)
Digunakan untuk mengelola armada truk yang disewakan kepada klien.
* **index()** : menampilkan daftar seluruh armada truk beserta status ketersediaan operasionalnya.
* **tambah()** : menampilkan halaman formulir pendaftaran armada truk baru.
* **store(request: Request)** : menyimpan data armada truk baru beserta unggahan gambar ke dalam database.
* **show(id: int)** : menampilkan rincian spesifikasi, foto, dan status ketersediaan armada truk tertentu berdasarkan ID yang dipilih.
* **edit(id: int)** : menampilkan halaman formulir untuk mengubah data spesifikasi armada truk berdasarkan ID yang dipilih.
* **update(request: Request, id: int)** : memperbarui data spesifikasi dan status ketersediaan armada truk di database berdasarkan ID yang dipilih.
* **destroy(id: int)** : menghapus data armada truk tertentu dari sistem berdasarkan ID yang dipilih.

---

### 3. CLASS: parkirs (Model: Parkir)
Digunakan untuk mengelola lokasi pool/depot parkir armada truk.
* **index()** : menampilkan daftar seluruh lokasi depot atau parkir truk yang tersedia.
* **armada(id: int)** : menampilkan daftar armada truk yang saat ini ditempatkan di lokasi parkir tertentu berdasarkan ID parkir.
* **tambah()** : menampilkan halaman formulir penambahan lokasi parkir baru.
* **store(request: Request)** : menyimpan data lokasi parkir baru beserta koordinat geografisnya (latitude & longitude) ke database.
* **show(id: int)** : menampilkan detail informasi alamat dan titik koordinat lokasi parkir tertentu berdasarkan ID yang dipilih.
* **edit(id: int)** : menampilkan halaman formulir untuk mengubah data lokasi parkir berdasarkan ID yang dipilih.
* **update(request: Request, id: int)** : memperbarui informasi data lokasi parkir di database berdasarkan ID yang dipilih.
* **destroy(id: int)** : menghapus data lokasi parkir tertentu dari database berdasarkan ID yang dipilih.

---

### 4. CLASS: keunggulans (Model: Keunggulan)
Digunakan untuk mengelola visualisasi daftar keunggulan jasa sewa pada halaman landing page.
* **index()** : menampilkan daftar poin keunggulan layanan jasa sewa yang dipublikasikan pada halaman utama.
* **tambah()** : menampilkan halaman formulir penambahan data poin keunggulan baru.
* **store(request: Request)** : menyimpan data poin keunggulan baru beserta ikon/gambarnya ke database.
* **show(id: int)** : menampilkan detail informasi poin keunggulan tertentu berdasarkan ID yang dipilih.
* **edit(id: int)** : menampilkan halaman formulir untuk mengubah data poin keunggulan berdasarkan ID yang dipilih.
* **update(request: Request, id: int)** : memperbarui informasi data poin keunggulan di database berdasarkan ID yang dipilih.
* **destroy(id: int)** : menghapus data poin keunggulan tertentu dari database berdasarkan ID yang dipilih.

---

### 5. CLASS: mitra_kerjas (Model: Mitra)
Digunakan untuk mengelola daftar logo perusahaan mitra kerja sama yang ditampilkan di website.
* **index()** : menampilkan daftar seluruh logo dan nama perusahaan mitra kerja sama yang terdaftar.
* **tambah()** : menampilkan halaman formulir pendaftaran mitra kerja baru.
* **store(request: Request)** : menyimpan data logo dan nama mitra kerja baru ke database.
* **show(id: int)** : menampilkan detail informasi mitra kerja tertentu berdasarkan ID yang dipilih.
* **edit(id: int)** : menampilkan halaman formulir untuk mengubah data kemitraan berdasarkan ID yang dipilih.
* **update(request: Request, id: int)** : memperbarui informasi data mitra kerja di database berdasarkan ID yang dipilih.
* **destroy(id: int)** : menghapus data kemitraan tertentu dari database berdasarkan ID yang dipilih.

---

### 6. CLASS: penyewaans (Model: Penyewaan)
Digunakan untuk mengelola transaksi utama penyewaan armada truk.
* **index(request: Request)** : menampilkan daftar keseluruhan transaksi penyewaan truk di dashboard admin atau riwayat transaksi sewa klien.
* **show(id: int)** : menampilkan rincian detail dari satu transaksi penyewaan berdasarkan ID transaksi.
* **cetakInvoice(id: int)** : menghasilkan dan mengunduh berkas invoice resmi berformat PDF untuk transaksi penyewaan berdasarkan ID.
* **konfirmasiPembayaran(id: int)** : melakukan verifikasi dan menyetujui bukti pembayaran transaksi penyewaan berdasarkan ID.
* **tolakPembayaran(request: Request, id: int)** : menolak bukti pembayaran transaksi penyewaan dengan menyertakan alasan penolakan berdasarkan ID.
* **destroy(id: int)** : menghapus atau membatalkan data transaksi penyewaan tertentu dari database berdasarkan ID.
* **indexPembatalan()** : menampilkan daftar pengajuan pembatalan sewa dari klien yang membutuhkan persetujuan/proses admin.
* **prosesPembatalan(request: Request, id: int)** : memproses persetujuan pembatalan penyewaan serta menentukan nominal pengembalian dana (refund) berdasarkan ID.
* **showPembayaran(penyewaanId: int)** : menampilkan halaman formulir unggah bukti pembayaran untuk penyewaan tertentu berdasarkan ID.
* **storePembayaran(request: Request, penyewaanId: int)** : menyimpan data transaksi pembayaran beserta berkas bukti transfer dari klien berdasarkan ID penyewaan.
* **riwayatPembayaran()** : menampilkan daftar riwayat seluruh transaksi pembayaran yang pernah dilakukan oleh klien yang sedang aktif.

---

### 7. CLASS: keranjangs (Model: Keranjang)
Digunakan untuk mengelola draft pemesanan sewa truk milik klien sebelum masuk tahap checkout transaksi.
* **index()** : menampilkan daftar pemesanan armada truk aktif milik klien yang sedang disimpan di keranjang sewa.
* **tambah()** : menampilkan formulir pemesanan awal untuk memilih rute, armada truk, dan tanggal penyewaan.
* **store(request: Request)** : menyimpan data pilihan armada sewa baru ke dalam keranjang pemesanan klien.
* **destroy(id: int)** : menghapus item pemesanan armada tertentu dari daftar keranjang klien berdasarkan ID.

---

### 8. CLASS: pembayarans (Model: Pembayaran)
Digunakan untuk mengelola pencatatan transaksi pembayaran uang sewa.
* **index()** : menampilkan daftar seluruh data verifikasi pembayaran sewa yang masuk ke sistem.
* **store(request: Request)** : mencatat data transaksi pembayaran baru ke dalam database secara otomatis.
* **show(id: int)** : menampilkan detail transaksi pembayaran tertentu berdasarkan ID yang dipilih.

---

### 9. CLASS: penugasan_sopirs (Model: PenugasanSopir)
Digunakan oleh admin untuk memantau pengerjaan pengantaran armada sewa oleh sopir.
* **index()** : menampilkan daftar seluruh tugas pengantaran barang yang aktif maupun riwayat tugas bagi para sopir.
* **show(id: int)** : menampilkan rincian instruksi tugas pengantaran barang spesifik berdasarkan ID.
* **store(request: Request)** : membuat dan menugaskan pengantaran barang baru untuk sopir tertentu oleh admin.
* **updateStatus(request: Request, id: int)** : memperbarui status perjalanan pengerjaan tugas pengantaran barang berdasarkan ID tugas yang sedang diproses sopir.
* **uploadBuktiSelesai(request: Request, id: int)** : menyimpan berkas foto bukti fisik pengantaran selesai yang diunggah oleh sopir berdasarkan ID penugasan.

---

### 10. CLASS: notifikasis (Model: Notifikasi)
Digunakan untuk mengelola pesan notifikasi real-time di sistem.
* **index()** : menampilkan daftar seluruh notifikasi masuk untuk pengguna yang sedang login.
* **markAsRead(id: int)** : menandai status satu notifikasi tertentu sebagai telah dibaca berdasarkan ID notifikasi.
* **markAllAsRead()** : menandai seluruh notifikasi yang dimiliki pengguna saat ini sebagai telah dibaca secara massal.
