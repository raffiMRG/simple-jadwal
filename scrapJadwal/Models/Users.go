package Models

import "time"

type User struct {
	ID           uint   `gorm:"primaryKey" json:"id"`
	Username     string `gorm:"uniqueIndex;size:100" json:"username"`
	PasswordHash string `gorm:"size:255" json:"-"`
	Role         string `gorm:"size:50;default:user" json:"role"`
	CreatedAt    time.Time
	UpdatedAt    time.Time
}
