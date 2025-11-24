package Controlers

import (
	"fmt"
	"math"
	"net/http"
	"os"
	"strconv"
	"strings"

	"github.com/gin-gonic/gin"
	"gorm.io/gorm"

	"scrapJadwal/Models"
	"scrapJadwal/Repositories"
	"scrapJadwal/helpers"
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

// ================= AUTH =========================
func (c *JadwalController) Register(ctx *gin.Context) {
	var body struct {
		Username string `json:"username" binding:"required"`
		Password string `json:"password" binding:"required"`
		Role     string `json:"role"`
	}

	if err := ctx.ShouldBindJSON(&body); err != nil {
		ctx.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	role := strings.ToLower(body.Role)
	if role == "" {
		role = "user"
	}

	if role == "admin" && os.Getenv("ALLOW_ADMIN_CREATION") != "1" {
		ctx.JSON(http.StatusForbidden, gin.H{"error": "admin creation disabled"})
		return
	}

	hash, _ := helpers.HashPassword(body.Password)

	user := Models.User{
		Username:     body.Username,
		PasswordHash: hash,
		Role:         role,
	}

	if err := c.DB.Create(&user).Error; err != nil {
		ctx.JSON(http.StatusBadRequest, gin.H{"error": "username mungkin sudah digunakan"})
		return
	}

	ctx.JSON(http.StatusCreated, gin.H{"message": "user dibuat", "user": gin.H{"id": user.ID, "username": user.Username, "role": user.Role}})
}

func (c *JadwalController) Login(ctx *gin.Context) {
	var body struct {
		Username string `json:"username" binding:"required"`
		Password string `json:"password" binding:"required"`
	}

	if err := ctx.ShouldBindJSON(&body); err != nil {
		ctx.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	var user Models.User
	if err := c.DB.Where("username = ?", body.Username).First(&user).Error; err != nil {
		ctx.JSON(http.StatusUnauthorized, gin.H{"error": "username atau password salah"})
		return
	}

	if err := helpers.CheckPassword(user.PasswordHash, body.Password); err != nil {
		ctx.JSON(http.StatusUnauthorized, gin.H{"error": "username atau password salah"})
		return
	}

	token, _ := helpers.CreateToken(user.ID, user.Username, user.Role)

	// ctx.JSON(http.StatusOK, gin.H{"access_token": token, "token_type": "bearer", "expires_in": 86400})
	ctx.JSON(http.StatusOK, gin.H{"access_token": token, "username": user.Username, "role": user.Role})
}

func (c *JadwalController) GetUsers(ctx *gin.Context) {
	db := c.DB

	page, _ := strconv.Atoi(ctx.DefaultQuery("page", "1"))
	limit, _ := strconv.Atoi(ctx.DefaultQuery("limit", "10"))
	if page < 1 {
		page = 1
	}
	offset := (page - 1) * limit

	var users []Models.User
	var total int64

	db.Model(&Models.User{}).Count(&total)
	db.Offset(offset).Limit(limit).Order("id DESC").Find(&users)

	ctx.JSON(http.StatusOK, gin.H{
		"page":        page,
		"limit":       limit,
		"total":       total,
		"total_pages": int(math.Ceil(float64(total) / float64(limit))),
		"data":        users,
	})
}

func (c *JadwalController) UpdateUserRole(ctx *gin.Context) {
	db := c.DB
	id := ctx.Param("id")

	var input struct {
		Role string `json:"role" binding:"required"`
	}
	if err := ctx.ShouldBindJSON(&input); err != nil {
		ctx.JSON(http.StatusBadRequest, gin.H{"error": "role is required"})
		return
	}

	var user Models.User
	if err := db.First(&user, id).Error; err != nil {
		ctx.JSON(http.StatusNotFound, gin.H{"error": "user not found"})
		return
	}

	user.Role = input.Role
	db.Save(&user)

	ctx.JSON(http.StatusOK, gin.H{"message": "role updated successfully", "user": user})
}

func (c *JadwalController) DeleteUser(ctx *gin.Context) {
	db := c.DB
	id := ctx.Param("id")

	var user Models.User
	if err := db.First(&user, id).Error; err != nil {
		ctx.JSON(http.StatusNotFound, gin.H{"error": "user not found"})
		return
	}

	db.Delete(&user)
	ctx.JSON(http.StatusOK, gin.H{"message": "user deleted successfully"})
}
