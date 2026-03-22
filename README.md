## Property API

### Requirements

- PHP 8.1+
- MySQL 8.0
- MongoDB

### Setup

1. **Clone the repository**

   ```
   git clone https://github.com/deepukunjumon/property_api.git
   cd property-api
   ```

2. **Install PHP dependencies**:

   ```
   composer install
   ```

3. **Set up MySQL database**:
   - Ensure MySQL is running on localhost with default port 3306
   - Create the database and table by running the schema:
     ```
     mysql -u root -p < schema.sql
     ```

4. **Set up MongoDB**:
   - Ensure MongoDB is running on localhost:27017
   - The application will automatically create the `property_db` database and `reviews` collection as needed

### Running Locally

1. **Start the development server**:

   ```
   php -S localhost:8000 -t public
   ```

2. **Access the API**:
   - The API will be available at `http://localhost:8000`
   - Available endpoints:
     - `GET /properties` - Get list of properties
     - `POST /properties` - Create a new property
     - `GET /properties/{id}` - Get property details
     - `POST /properties/{id}/reviews` - Add a review to a property
