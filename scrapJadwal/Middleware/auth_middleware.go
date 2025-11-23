package Middleware

import (
	"net/http"
	"scrapJadwal/Models"
	"scrapJadwal/helpers"

	"github.com/gin-gonic/gin"
	"gorm.io/gorm"
)

type Middleware struct {
	DB *gorm.DB
}

func NewMiddleware(db *gorm.DB) *Middleware {
	return &Middleware{DB: db}
}

func (config *Middleware) Auth() gin.HandlerFunc {
	return func(c *gin.Context) {
		header := c.GetHeader("Authorization")
		if header == "" {
			c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "token required"})
			return
		}

		token := header[len("Bearer "):]

		claims, err := helpers.ParseToken(token)
		if err != nil {
			c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "invalid token"})
			return
		}

		var user Models.User
		config.DB.First(&user, claims["sub"])
		c.Set("user", user)

		c.Next()
	}
}
