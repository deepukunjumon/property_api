<?php

class ReviewRepository {

    private $collection;

    public function __construct() {
        $this->collection = require __DIR__ . '/../../config/mongodb.php';
    }

    public function addReview($propertyId, $data) {
        return $this->collection->insertOne([
            'property_id' => (int)$propertyId,
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);
    }

    public function getReviewsByPropertyId($propertyId) {
        return $this->collection->find(
            ['property_id' => (int)$propertyId]
        )->toArray();
    }
}