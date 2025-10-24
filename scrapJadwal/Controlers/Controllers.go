package Controlers

import (
	"fmt"
	"math"
	"net/http"
	"strconv"

	"github.com/gin-gonic/gin"
	"gorm.io/gorm"

	"scrapJadwal/Models"
	"scrapJadwal/Repositories"
)

type JadwalController struct {
	DB      *gorm.DB
	Token   string
	baseurl string
}

func NewJadwalController(db *gorm.DB, baseurl, token string) *JadwalController {
	return &JadwalController{baseurl: baseurl, DB: db, Token: token}
}

func (c *JadwalController) SfrapJadwal() {
	for i := 1; i <= 8; i++ {
		semester := fmt.Sprintf("%02d", i)
		Repositories.ScrapeSemester(c.baseurl, semester, c.Token, c.DB)
	}
}

// func (c *JadwalController) GetJadwalKuliah(ctx *gin.Context) {
// 	var jadwal []Models.JadwalKuliah

// 	// Ambil parameter filter dari query string
// 	namaDosen := ctx.Query("nama_dosen")
// 	namaMK := ctx.Query("nama_mata_kuliah")
// 	ruang := ctx.Query("id_ruang")
// 	semester := ctx.Query("semester")
// 	hari := ctx.Query("nama_hari")

// 	q := c.DB.Model(&Models.JadwalKuliah{})

// 	if namaDosen != "" {
// 		q = q.Where("nama_dosen LIKE ?", "%"+namaDosen+"%")
// 	}
// 	if namaMK != "" {
// 		q = q.Where("nama_mata_kuliah LIKE ?", "%"+namaMK+"%")
// 	}
// 	if ruang != "" {
// 		q = q.Where("id_ruang LIKE ?", "%"+ruang+"%")
// 	}
// 	if semester != "" {
// 		q = q.Where("semester = ?", semester)
// 	}
// 	if hari != "" {
// 		q = q.Where("nama_hari = ?", hari)
// 	}

// 	q.Order("nama_hari, ket_jam").Find(&jadwal)
// 	ctx.JSON(http.StatusOK, jadwal)
// }

func (c *JadwalController) GetJadwalKuliah(ctx *gin.Context) {
	var jadwal []Models.JadwalKuliah

	// Ambil parameter filter dari query string
	namaDosen := ctx.Query("nama_dosen")
	namaMK := ctx.Query("nama_mata_kuliah")
	ruang := ctx.Query("id_ruang")
	semester := ctx.Query("semester")
	hari := ctx.Query("nama_hari")

	// Ambil parameter pagination
	page, _ := strconv.Atoi(ctx.DefaultQuery("page", "1"))
	limit, _ := strconv.Atoi(ctx.DefaultQuery("limit", "20")) // default 20 per halaman
	offset := (page - 1) * limit

	q := c.DB.Model(&Models.JadwalKuliah{})

	if namaDosen != "" {
		q = q.Where("nama_dosen LIKE ?", "%"+namaDosen+"%")
	}
	if namaMK != "" {
		q = q.Where("nama_mata_kuliah LIKE ?", "%"+namaMK+"%")
	}
	if ruang != "" {
		q = q.Where("id_ruang LIKE ?", "%"+ruang+"%")
	}
	if semester != "" {
		q = q.Where("semester = ?", semester)
	}
	if hari != "" {
		q = q.Where("nama_hari = ?", hari)
	}

	var total int64
	q.Count(&total) // hitung total data

	q.Order("nama_hari, ket_jam").Limit(limit).Offset(offset).Find(&jadwal)

	ctx.JSON(http.StatusOK, gin.H{
		"page":  page,
		"limit": limit,
		"total": total,
		"pages": int(math.Ceil(float64(total) / float64(limit))),
		"data":  jadwal,
	})
}
