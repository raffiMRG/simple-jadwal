package Repositories

import (
	"fmt"
	"log"
	"time"

	// "scrapJadwal/models"
	"scrapJadwal/helpers"

	"gorm.io/gorm"
)

func ScrapeSemester(baseurl, semester string, token string, db *gorm.DB) {
	fmt.Printf("Scraping semester %s...\n", semester)
	page := 1

	for {
		data, err := helpers.FetchPage(baseurl, semester, page, token)
		if err != nil {
			log.Printf("Gagal ambil semester %s halaman %d: %v", semester, page, err)
			break
		}

		// Tambahkan info semester
		for i := range data.Data {
			data.Data[i].Semester = semester
		}

		// Simpan ke DB
		if len(data.Data) > 0 {
			db.Create(&data.Data)
			fmt.Printf("✅ Semester %s - halaman %d: tersimpan %d data\n", semester, page, len(data.Data))
		}

		if data.CurrentPage >= data.LastPage {
			break
		}
		page++
		time.Sleep(1 * time.Second) // delay biar gak dianggap spam
	}
}
