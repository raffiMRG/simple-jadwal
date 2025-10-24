package main

import (
	"fmt"
	"log"
	"os"
	"scrapJadwal/Config"
	"scrapJadwal/Controlers"
	"scrapJadwal/Models"
	"time"

	"github.com/gin-contrib/cors"
	"github.com/gin-gonic/gin"
	"github.com/joho/godotenv"
)

func main() {
	var err error
	// Load .env file
	err = godotenv.Load()
	if err != nil {
		log.Fatal("Error loading .env file")
	}

	// load config from env
	dsn := os.Getenv("DATABASE_DSN")
	if dsn == "" {
		log.Fatal("DATABASE_DSN env required")
	}

	// load config from env
	token := os.Getenv("BEARER_TOKEN")
	if token == "" {
		log.Fatal("BEARER_TOKEN env required")
	}

	// load config from env
	baseurl := os.Getenv("SRC_URL")
	if token == "" {
		log.Fatal("SRC_URL env required")
	}

	// load config from env
	port := os.Getenv("PORT")
	if token == "" {
		log.Fatal("PORT env required")
	}

	// init DB
	Config.InitDB(dsn)

	// migrasi
	db := Config.DB
	db.AutoMigrate(Models.JadwalKuliah{})

	// dependency injection ke controller
	// jadwalController := Controllers.NewJadwalController(db)
	jadwalController := Controlers.NewJadwalController(db, baseurl, token)

	r := gin.Default()

	// Atur middleware CORS
	r.Use(cors.New(cors.Config{
		// AllowOrigins:     []string{"http://localhost:3000"}, // sesuaikan origin frontend kamu
		AllowAllOrigins:  true,
		AllowMethods:     []string{"GET", "POST", "PUT", "PATCH", "DELETE"},
		AllowHeaders:     []string{"Origin", "Content-Type", "Accept", "Authorization"},
		ExposeHeaders:    []string{"Content-Length"},
		AllowCredentials: true,
		MaxAge:           12 * time.Hour,
	}))

	r.Static("/static", "./static")
	// r.GET("/api/jadwal-kuliah", jadwalController.SfrapJadwal)
	r.GET("/api/jadwal-kuliah", jadwalController.GetJadwalKuliah)
	// r.LoadHTMLGlob("templates/*")
	// r.GET("/", func(c *gin.Context) {
	// 	c.HTML(http.StatusOK, "index.html", nil)
	// })

	r.Run(":" + port)

	fmt.Println("Selesai scraping semua semester ✅")
}
