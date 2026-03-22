<?php

require_once __DIR__ . '/../Repositories/PropertyRepository.php';
require_once __DIR__ . '/../Repositories/ReviewRepository.php';

class PropertyService {

    private $propertyRepo;
    private $reviewRepo;

    public function __construct() {
        $this->propertyRepo = new PropertyRepository();
        $this->reviewRepo = new ReviewRepository();
    }

    /**
     * Get a list of properties with optional filters
     *
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @param string $sortKey
     * @param string $sortDir
     * 
     * @return array
     */
    public function getProperties(array $filters, int $limit, int $offset, string $sortKey, string $sortDir) {
        return $this->propertyRepo->getAll($filters, $limit, $offset, $sortKey, $sortDir);
    }

    /**
     * Create a new property
     *
     * @param array $data
     * @return int|false
     */
    public function createProperty(array $data) {
        return $this->propertyRepo->create($data);
    }

    /**
     * Get property details by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getPropertyDetails(int $id) {
        $property = $this->propertyRepo->find($id);

        if (!$property) return null;

        $reviews = $this->reviewRepo->getReviewsByPropertyId($id);

        $property['reviews'] = $reviews;

        return $property;
    }

    /**
     * Add a review to a property
     *
     * @param int $propertyId
     * @param array $data
     * 
     * @return bool
     */
    public function addReview(int $propertyId, array $data) {
        return $this->reviewRepo->addReview($propertyId, $data);
    }
}