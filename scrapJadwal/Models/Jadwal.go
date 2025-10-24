package Models

// Struktur data API
type ApiResponse struct {
	CurrentPage int            `json:"current_page"`
	Data        []JadwalKuliah `json:"data"`
	LastPage    int            `json:"last_page"`
}

type JadwalKuliah struct {
	ID             uint   `gorm:"primaryKey"`
	IdMataKuliah   string `json:"id_mata_kuliah"`
	IdKelas        string `json:"id_kelas"`
	TglMulaiKuliah string `json:"tgl_mulai_kuliah"`
	TglAkhirKuliah string `json:"tgl_akhir_kuliah"`
	IdRuang        string `json:"id_ruang"`
	IdPeriode      string `json:"id_periode"`
	NamaMataKuliah string `json:"nama_mata_kuliah"`
	NamaDosen      string `json:"nama_dosen"`
	NamaHari       string `json:"nama_hari"`
	KetJam         string `json:"ket_jam"`
	Semester       string `json:"semester"` // tambahan manual
}
