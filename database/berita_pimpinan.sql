-- ======================================================
-- Tabel Berita & Pimpinan untuk BENGPUSKOMLEKAD
-- Jalankan di phpMyAdmin atau MySQL CLI
-- ======================================================

-- Tabel Berita
CREATE TABLE IF NOT EXISTS berita_db (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(500) NOT NULL,
    kategori VARCHAR(100) DEFAULT 'Umum',
    tanggal DATE NOT NULL,
    gambar VARCHAR(500) DEFAULT NULL,
    isi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Pimpinan
CREATE TABLE IF NOT EXISTS pimpinan_db (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(300) NOT NULL,
    masa_jabatan VARCHAR(200) NOT NULL,
    gambar VARCHAR(500) DEFAULT NULL,
    is_current TINYINT(1) DEFAULT 0,
    urutan INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================================
-- SEED DATA BERITA (dari data hardcode sebelumnya)
-- ======================================================
INSERT IGNORE INTO berita_db (id, judul, kategori, tanggal, gambar, isi) VALUES
(1, 'Drone Interceptor', 'LITBANG', '2026-06-12', 'assets/images/elektronika.jpeg',
 'BENGPUSKOMLEKAD sebagai unsur pelaksana pusat kecabangan berpartisipasi dalam gelar manuver lapangan yang merupakan bagian dari program Pendidikan Komponen Cadangan. Kegiatan ini merupakan bagian dari upaya modernisasi alutsista dan pengembangan teknologi pertahanan nasional.'),
(2, 'Modernisasi Fasilitas Bengkel Elektronika dengan Peralatan Terkini', 'Teknologi', '2026-06-08', 'assets/images/gedung-bengpus.jpeg',
 'Investasi besar dalam peralatan modern untuk mendukung pemeliharaan sistem elektronika pertahanan generasi terbaru. BENGPUSKOMLEKAD terus berkomitmen untuk meningkatkan kapabilitas teknisnya demi mendukung kesiapan operasional TNI AD.'),
(3, 'Penandatanganan MoU dengan Industri Pertahanan Australia', 'Kerjasama', '2026-06-01', 'assets/images/sumga.jpeg',
 'Kerjasama strategis dengan mitra internasional untuk bertukar ilmu pengetahuan seputar Teknologi. MoU ini membuka peluang transfer teknologi dan peningkatan kompetensi personel BENGPUSKOMLEKAD dalam bidang pemeliharaan sistem komunikasi modern.');

-- ======================================================
-- SEED DATA PIMPINAN (dari data hardcode sebelumnya)
-- ======================================================
INSERT IGNORE INTO pimpinan_db (id, nama, masa_jabatan, gambar, is_current, urutan) VALUES
(1,  'Kolonel Cke Setyo Budi Nugroho, S.Sos.',         '2025 - Sekarang', 'assets/images/kabeng.jpeg',          1, 100),
(2,  'Kolonel Cke Muh. Hatta, M.P.M., MCap.Mgt.',      '2023 - 2025',     'assets/images/kabeng-hatta.jpg',     0, 90),
(3,  'Kolonel Chb Moch. Sholeh, SH., M.M.',             '2023 - 2023',     'assets/images/kabeng-sholeh.jpg',    0, 80),
(4,  'Kolonel Chb Umang Arfan Latsusmintarto, S.Si',    '2022 - 2023',     'assets/images/kabeng-umang.jpg',     0, 70),
(5,  'Kolonel Chb Anang Murtioso, S.Si.',               '2020 - 2022',     NULL,                                  0, 60),
(6,  'Kolonel Chb Try Haryono, S.sos., M.M.',           '2019 - 2020',     'assets/images/kabeng-haryono.jpg',   0, 55),
(7,  'Kolonel Cke Ir.Agus Budi Prayitno',               '2018 - 2019',     'assets/images/kabeng-prayitno.jpg',  0, 50),
(8,  'Kolonel Chb Drs. Leo Yunaidy Wibisono, M.A.P.',  '2016 - 2018',     'assets/images/kabeng-leo.jpg',       0, 45),
(9,  'Kolonel Chb Zakaria',                              '2015 - 2016',     'assets/images/kabeng-zakaria.jpg',   0, 40),
(10, 'Kolonel Chb Totok',                               '2014 - 2015',     'assets/images/kabeng-totok.jpg',     0, 35),
(11, 'Kolonel Chb Sasmito Yupitoro, S.T.',              '2011 - 2014',     'assets/images/kabeng-sasmito.jpg',   0, 30),
(12, 'Kolonel Chb Harijono, S.T.',                      '2006 - 2011',     'assets/images/kabeng-harijono.jpg',  0, 25),
(13, 'Kolonel Chb Sumarno',                              '2003 - 2006',     'assets/images/kabeng-sumarno.jpg',   0, 20),
(14, 'Kolonel Chb E. Supribadio. TE',                   '1997 - 2003',     'assets/images/kabeng-supribadio.jpg',0, 18),
(15, 'Kolonel Chb Wiyono',                               '1991 - 1997',     'assets/images/kabeng-wiyono.jpg',    0, 16),
(16, 'Kolonel Chb Widoyo',                               '1987 - 1991',     'assets/images/kabeng-widoyo.jpg',    0, 14),
(17, 'Kolonel Chb Priyambodo',                           '1980 - 1987',     'assets/images/kabeng-priyambodo.jpg',0, 12),
(18, 'Kolonel Chb R. Karnoto (alm)',                     '1972 - 1980',     'assets/images/kabeng-karnoto.jpg',   0, 10),
(19, 'Letnan Kolonel Chb Harmono (alm)',                 '1970 - 1972',     'assets/images/kabeng-harmono.jpg',   0, 8),
(20, 'Letnan Kolonel Chb Drondio (alm)',                 '1966 - 1970',     'assets/images/kabeng-drondio.jpg',   0, 6),
(21, 'Letnan Kolonel Chb Poedjadi (alm)',                '1961 - 1966',     'assets/images/kabeng-poedjadi.jpg',  0, 4),
(22, 'Letnan Satu Chb Harjadi (alm)',                    '1950 - 1961',     'assets/images/kabeng-harjadi.jpg',   0, 2);
