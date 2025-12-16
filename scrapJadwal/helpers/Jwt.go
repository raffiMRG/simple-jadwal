package helpers

import (
	"fmt"
	"os"
	"time"

	"github.com/golang-jwt/jwt/v4"
)

func CreateToken(id uint, username, role string) (string, error) {
	claims := jwt.MapClaims{
		"sub":      id,
		"username": username,
		"role":     role,
		"exp":      time.Now().Add(24 * time.Hour).Unix(),
	}

	t := jwt.NewWithClaims(jwt.SigningMethodHS256, claims)
	secret := os.Getenv("JWT_SECRET")
	if secret == "" {
		secret = "nyari-apa-lu-kuda"
	}
	return t.SignedString([]byte(secret))
}

func ParseToken(tokenString string) (jwt.MapClaims, error) {
	claims := jwt.MapClaims{}

	secret := os.Getenv("JWT_SECRET")
	if secret == "" {
		secret = "nyari-apa-lu-kuda"
	}

	token, err := jwt.ParseWithClaims(tokenString, claims,
		func(token *jwt.Token) (interface{}, error) {
			return []byte(secret), nil
		})

	if err != nil || !token.Valid {
		return nil, fmt.Errorf("invalid token")
	}

	return claims, nil
}
