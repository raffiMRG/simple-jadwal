package Controlers

import (
	"encoding/json"
	"net/http"
	"scrapJadwal/Models"
	"scrapJadwal/Repositories"
	"strings"

	"github.com/gin-gonic/gin"
)

type JurusanController struct {
	JurusanRepo *Repositories.JurusanRepository
}

func NewJurusanController(repo *Repositories.JurusanRepository) *JurusanController {
	return &JurusanController{JurusanRepo: repo}
}

func (c *JurusanController) ScrapeJurusan(ctx *gin.Context) {
	url := "https://cms.unpam.ac.id/api/id/page/kampus-2-unpam-viktor/unit/"
	resp, err := http.Get(url)
	if err != nil {
		ctx.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to fetch data"})
		return
	}
	defer resp.Body.Close()

	var result map[string]interface{}
	if err := json.NewDecoder(resp.Body).Decode(&result); err != nil {
		ctx.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to parse data"})
		return
	}

	data, ok := result["data"].(map[string]interface{})
	if !ok {
		ctx.JSON(http.StatusInternalServerError, gin.H{"error": "Invalid data format"})
		return
	}

	page, ok := data["page"].(map[string]interface{})
	if !ok {
		ctx.JSON(http.StatusInternalServerError, gin.H{"error": "Invalid page format"})
		return
	}

	content, ok := page["content"].(string)
	if !ok {
		ctx.JSON(http.StatusInternalServerError, gin.H{"error": "Content not found"})
		return
	}

	// Extract and clean the list of jurusan from the content
	jurusanList := extractJurusan(content)
	for _, jurusan := range jurusanList {
		// Check if the jurusan already exists in the database
		exists, err := c.JurusanRepo.ExistsByJurusan(jurusan)
		if err != nil {
			ctx.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to check existing data"})
			return
		}
		if exists {
			continue
		}

		newJurusan := &Models.Jurusan{
			Jurusan: jurusan,
			Kampus:  "Unpam Kampus 2 Viktor",
		}
		if err := c.JurusanRepo.SaveJurusan(newJurusan); err != nil {
			ctx.JSON(http.StatusInternalServerError, gin.H{"error": "Failed to save data"})
			return
		}
	}

	ctx.JSON(http.StatusOK, gin.H{"message": "Data scraped and saved successfully"})
}

func extractJurusan(content string) []string {
	// Extract the list of jurusan from the HTML content
	start := strings.Index(content, "<ol>")
	end := strings.Index(content, "</ol>")
	if start == -1 || end == -1 || start >= end {
		return nil
	}

	listHTML := content[start+4 : end] // Skip the opening <ol> tag
	listHTML = strings.ReplaceAll(listHTML, "<li>", "")
	listHTML = strings.ReplaceAll(listHTML, "</li>", "")

	items := strings.Split(listHTML, ";")
	var jurusanList []string
	for _, item := range items {
		cleaned := strings.TrimSpace(item)
		if cleaned != "" {
			jurusanList = append(jurusanList, cleaned)
		}
	}

	return jurusanList
}
