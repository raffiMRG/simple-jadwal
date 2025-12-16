package Repositories

import (
	"scrapJadwal/Models"

	"gorm.io/gorm"
)

type JurusanRepository struct {
	DB *gorm.DB
}

func NewJurusanRepository(db *gorm.DB) *JurusanRepository {
	return &JurusanRepository{DB: db}
}

func (r *JurusanRepository) SaveJurusan(jurusan *Models.Jurusan) error {
	return r.DB.Create(jurusan).Error
}

func (r *JurusanRepository) ExistsByJurusan(jurusan string) (bool, error) {
	var count int64
	err := r.DB.Model(&Models.Jurusan{}).Where("jurusan = ?", jurusan).Count(&count).Error
	if err != nil {
		return false, err
	}
	return count > 0, nil
}
