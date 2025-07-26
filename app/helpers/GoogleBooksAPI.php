<?php
class GoogleBooksAPI {
    /**
     * Tìm kiếm sách trên Google Books.
     * @param string $query - Tên sách, tác giả, hoặc từ khóa.
     * @return array - Mảng các đối tượng sách.
     */
    public static function search($query, $limit = 3) {
        $api_url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($query) . "&maxResults=" . $limit;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $books = [];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $info = $item['volumeInfo'];
                $books[] = [
                    'title' => $info['title'] ?? 'Không có tiêu đề',
                    'author' => implode(', ', $info['authors'] ?? ['Không rõ tác giả']),
                    'isbn' => self::findIsbn($info['industryIdentifiers'] ?? [])
                ];
            }
        }
        return $books;
    }

    /**
     * Helper để tìm mã ISBN-13.
     */
    private static function findIsbn($identifiers) {
        foreach ($identifiers as $identifier) {
            if ($identifier['type'] === 'ISBN_13') {
                return $identifier['identifier'];
            }
        }
        return null;
    }
}