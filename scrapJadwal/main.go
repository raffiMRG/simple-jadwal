package main

import (
	"fmt"
	"log"
	"os"
	"scrapJadwal/Config"
	"scrapJadwal/Controlers"
	"scrapJadwal/Middleware"
	"scrapJadwal/Models"
	"scrapJadwal/Repositories"
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
	// token := os.Getenv("BEARER_TOKEN")
	// if token == "" {
	// 	log.Fatal("BEARER_TOKEN env required")
	// }

	// load config from env
	baseurl := os.Getenv("SRC_URL")
	if baseurl == "" {
		log.Fatal("SRC_URL env required")
	}

	// load config from env
	port := os.Getenv("PORT")
	if port == "" {
		log.Fatal("PORT env required")
	}

	// init DB
	Config.InitDB(dsn)

	// migrasi
	db := Config.DB
	db.AutoMigrate(&Models.JadwalKuliah{}, &Models.User{}, &Models.Jurusan{})

	// dependency injection ke controller
	// jadwalController := Controllers.NewJadwalController(db)
	Controller := Controlers.NewJadwalController(db, baseurl)
	Middleware := Middleware.NewMiddleware(db)
	jurusanRepo := Repositories.NewJurusanRepository(db)
	jurusanController := Controlers.NewJurusanController(jurusanRepo)

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
	// r.GET("/api/jadwal-kuliah", Controller.GetJadwalKuliah)	<-- dimasukin ke protected route
	// r.LoadHTMLGlob("templates/*")
	// r.GET("/", func(c *gin.Context) {
	// 	c.HTML(http.StatusOK, "index.html", nil)
	// })

	r.POST("/register", Controller.Register)
	r.POST("/login", Controller.Login)

	api := r.Group("/api", Middleware.Auth())
	{
		api.GET("/jadwal-kuliah", Controller.GetJadwalKuliah)

		// api.GET("/profile", Controller.Profile)
		// api.GET("/admin", middleware.AdminOnly(), controller.AdminOnlyPage)

		api.GET("/users", Controller.GetUsers)
		api.PUT("/users/:id/role", Controller.UpdateUserRole)
		api.DELETE("/users/:id", Controller.DeleteUser)

		api.POST("/scraping", Controller.SfrapJadwal)
		api.POST("/scrape-jurusan", jurusanController.ScrapeJurusan)
	}

	r.Run(":" + port)

	fmt.Println("Selesai scraping semua semester ✅")
}
