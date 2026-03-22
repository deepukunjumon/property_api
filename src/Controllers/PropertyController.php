<?php

require_once __DIR__ . '/../Utils/Request.php';
require_once __DIR__ . '/../Utils/Response.php';
require_once __DIR__ . '/../Services/PropertyService.php';

class PropertyController {

    private $service;

    public function __construct() {
        $this->service = new PropertyService();
    }

    /**
     * List of properties
     */
    public function getPropertyList() {
        try {
            $filters = Request::query();

            $limit   = (int)($filters['limit'] ?? 10);
            $offset  = (int)($filters['offset'] ?? 0);
            
            $sortKey = $filters['sort_key'] ?? 'id';
            $sortDir = ($filters['sort_dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

            $data = $this->service->getProperties($filters, $limit, $offset, $sortKey, $sortDir);

            return Response::success($data);

        } catch (\Throwable $e) {
            return Response::error("Something went wrong", 500);
        }
    }

    /**
     * Create a new property
     */
    public function createProperty() {
        try {
            $input = Request::body();

            $required = ['title', 'price', 'location'];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    return Response::error("$field is required", 422);
                }
            }

            if (!is_numeric($input['price'])) {
                return Response::error("price must be a number", 422);
            }

            $data = [
                'title'       => trim($input['title']),
                'price'       => (float)$input['price'],
                'location'    => trim($input['location']),
                'description' => $input['description'] ?? null
            ];

            $id = $this->service->createProperty($data);

            if (!$id) {
                return Response::error("Failed to create property", 500);
            }

            return Response::success([
                'id'      => $id,
                'message' => 'Property created successfully'
            ], 201);

        } catch (\Throwable $e) {
            error_log($e->getMessage());
            return Response::error("Internal Server Error", 500);
        }
    }

    /**
     * Get property details by ID
     */
    public function getPropertyDetails() {

        $id = Request::getArg('id');
        if (!$id || empty($id)) {
            return Response::error("Invalid Property ID", 400);
        }

        $data = $this->service->getPropertyDetails($id);

        if (!$data) {
            return Response::error("Property not found", 404);
        }

        Response::json($data);
    }

    /**
     * Add a review to a property
     */
    public function addReview(int $id) {
        
        $input = Request::body();

        $required = ['rating', 'comment'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                return Response::error("$field is required", 422);
            }
        }

        if (!is_numeric($input['rating']) || $input['rating'] < 1.0 || $input['rating'] > 5.0) {
            return Response::error("rating must be a number between 1.0 and 5.0", 422);
        }

        $data = [
            'rating'  => (float)$input['rating'],
            'comment' => trim($input['comment'])
        ];

        $this->service->addReview($id, $data);

        Response::json(['message' => 'Review added'], 201);
    }
}