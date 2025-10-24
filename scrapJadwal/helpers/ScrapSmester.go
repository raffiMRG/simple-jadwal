package helpers

import (
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"time"

	models "scrapJadwal/Models"
)

func FetchPage(baseurl, semester string, page int, token string) (*models.ApiResponse, error) {
	url := fmt.Sprintf("%s?page=%d&per_page=500&semester=%s", baseurl, page, semester)
	req, err := http.NewRequest("GET", url, nil)
	if err != nil {
		return nil, err
	}
	req.Header.Set("Authorization", "Bearer "+token)

	client := &http.Client{Timeout: 15 * time.Second}
	resp, err := client.Do(req)
	if err != nil {
		return nil, err
	}
	defer resp.Body.Close()

	if resp.StatusCode != http.StatusOK {
		body, _ := io.ReadAll(resp.Body)
		return nil, fmt.Errorf("status %d: %s", resp.StatusCode, string(body))
	}

	var result models.ApiResponse
	if err := json.NewDecoder(resp.Body).Decode(&result); err != nil {
		return nil, err
	}

	return &result, nil
}
