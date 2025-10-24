package Config

import (
	"fmt"
	"log"

	"gorm.io/driver/mysql"
	"gorm.io/gorm"

	"scrapJadwal/Models"
)

// Variabel global DB
var DB *gorm.DB

func InitDB(dsn string) {
	var err error
	DB, err = gorm.Open(mysql.Open(dsn), &gorm.Config{})
	if err != nil {
		log.Fatalf("Gagal koneksi ke database: %v", err)
	}

	fmt.Println("✅ Koneksi database berhasil")

	DB.AutoMigrate(&Models.JadwalKuliah{})
}
