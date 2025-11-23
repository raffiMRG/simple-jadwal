package Middleware

import (
	"net/http"

	"github.com/gin-gonic/gin"
)

func AdminOnly() gin.HandlerFunc {
	return func(c *gin.Context) {
		user := c.MustGet("user")
		if user.(map[string]interface{}) != nil {
		}

		if u := c.MustGet("user"); u.(interface{ GetRole() string }).GetRole() != "admin" {
			c.AbortWithStatusJSON(http.StatusForbidden, gin.H{"error": "requires admin role"})
			return
		}
		c.Next()
	}
}
