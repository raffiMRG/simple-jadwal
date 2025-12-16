package Models

type Jurusan struct {
	ID      uint   `gorm:"primaryKey;autoIncrement"`
	Jurusan string `gorm:"not null"`
	Kampus  string `gorm:"not null"`
}
