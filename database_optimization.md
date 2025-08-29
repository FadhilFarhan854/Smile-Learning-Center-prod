# Database Optimization - Manual Index Creation

Untuk meningkatkan performa aplikasi, jalankan query SQL berikut di database production:

## Index untuk tabel absens
```sql
-- Index untuk query berdasarkan siswa_id dan tanggal_absen (paling sering digunakan)
CREATE INDEX idx_absens_siswa_tanggal ON absens(siswa_id, tanggal_absen);

-- Index untuk query berdasarkan tanggal_absen saja
CREATE INDEX idx_absens_tanggal ON absens(tanggal_absen);

-- Index untuk query berdasarkan siswa_id saja
CREATE INDEX idx_absens_siswa ON absens(siswa_id);
```

## Index untuk tabel orders
```sql
-- Index untuk query berdasarkan siswa_id, bulan, dan tahun (paling sering digunakan)
CREATE INDEX idx_orders_siswa_bulan_tahun ON orders(siswa_id, bulan, tahun);

-- Index untuk query berdasarkan bulan dan tahun
CREATE INDEX idx_orders_bulan_tahun ON orders(bulan, tahun);

-- Index untuk query berdasarkan kategori
CREATE INDEX idx_orders_kategori ON orders(kategori);

-- Index untuk query berdasarkan modul_id
CREATE INDEX idx_orders_modul ON orders(modul_id);

-- Index untuk query berdasarkan siswa_id, bulan, tahun, dan kategori
CREATE INDEX idx_orders_siswa_bulan_tahun_kategori ON orders(siswa_id, bulan, tahun, kategori);
```

## Index untuk tabel siswas (jika belum ada)
```sql
-- Index untuk query berdasarkan kelas_id
CREATE INDEX idx_siswas_kelas ON siswas(kelas_id);

-- Index untuk query berdasarkan tanggal_masuk
CREATE INDEX idx_siswas_tanggal_masuk ON siswas(tanggal_masuk);

-- Index untuk query berdasarkan tanggal_lulus
CREATE INDEX idx_siswas_tanggal_lulus ON siswas(tanggal_lulus);

-- Index untuk search berdasarkan nama
CREATE INDEX idx_siswas_nama ON siswas(nama);
```

## Index untuk tabel kelas (jika belum ada)
```sql
-- Index untuk query berdasarkan unit_id
CREATE INDEX idx_kelas_unit ON kelas(unit_id);
```

## Index untuk tabel spps
```sql
-- Index untuk query berdasarkan siswa_id
CREATE INDEX idx_spps_siswa ON spps(siswa_id);

-- Index untuk query berdasarkan bulan dan tahun
CREATE INDEX idx_spps_bulan_tahun ON spps(bulan, tahun);

-- Index untuk query berdasarkan status
CREATE INDEX idx_spps_status ON spps(status);
```

## Index untuk tabel moduls
```sql
-- Index untuk query berdasarkan status
CREATE INDEX idx_moduls_status ON moduls(status);
```

Jalankan query ini di database production untuk meningkatkan performa secara signifikan.
