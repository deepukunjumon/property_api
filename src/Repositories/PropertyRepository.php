<?php

require_once __DIR__ . '/../../config/database.php';

class PropertyRepository {

    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    /**
     * {@inheritDoc}
     */
    public function getAll($filters, $limit, $offset, $sortKey, $sortDir) {

        $fieldsArray = ['id', 'title', 'description', 'price', 'location', 'created_at'];
        $fields = implode(', ', $fieldsArray);

        $sql = "SELECT {$fields} FROM properties WHERE 1=1";
        $params = [];

        if (isset($filters['location'])) {
            $sql .= " AND location LIKE :location";
            $params['location'] = '%' . $filters['location'] . '%';
        }

        if (isset($filters['min_price'])) {
            $sql .= " AND price >= :min_price";
            $params['min_price'] = (float) $filters['min_price'];
        }

        if (isset($filters['max_price'])) {
            $sql .= " AND price <= :max_price";
            $params['max_price'] = (float) $filters['max_price'];
        }

        $allowedSortKeys = ['id', 'price', 'created_at'];
        if (!in_array($sortKey, $allowedSortKeys)) {
            $sortKey = 'id';
        }

        $sortDir = strtolower($sortDir) === 'desc' ? 'DESC' : 'ASC';

        $sql .= " ORDER BY {$sortKey} {$sortDir} LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            if (is_float($value)) {
                $stmt->bindValue(":$key", $value);
            } else {
                $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
            }
        }

        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * {@inheritdoc}
     */
    public function create($data) {

        $sql = "INSERT INTO properties (title, description, price, location) 
                VALUES (:title, :description, :price, :location)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $data['description'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':price', (float) $data['price']);
        $stmt->bindValue(':location', $data['location'], PDO::PARAM_STR);

        $result = $stmt->execute();

        if ($result) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM properties WHERE id = :id");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}