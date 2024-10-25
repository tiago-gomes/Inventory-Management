# Inventory Management API

This API provides a system for efficiently managing product inventory. It allows users to keep track of product details, suppliers, and current stock levels, ensuring that businesses can monitor inventory in real-time, update stock quantities, and handle supply logistics. The API supports essential operations like adding, updating, and deleting products, managing stock levels, and retrieving supplier information. It also includes built-in authentication to secure access and prevent unauthorized use.

## Key Features

- **Product Management**: 
  - Create, update, view, and delete product entries.
  
- **Inventory Tracking**: 
  - Track product quantities in stock.
  - Set thresholds for low stock alerts.
  - Adjust quantities as stock levels change.

- **Supplier Management**: 
  - Manage and retrieve supplier details, including contact information.

- **Authentication & Security**: 
  - Ensure only authorized users can access the API using secure authentication mechanisms.

# Table Structures

## Inventory Table
| Column Name  | Data Type        | Description                      |
|--------------|------------------|----------------------------------|
| id           | BigInteger (PK)   | Unique identifier for inventory  |
| product_id   | BigInteger (FK)   | Reference to the product         |
| quantity     | Integer           | Current quantity in stock        |
| threshold    | Integer           | Minimum stock threshold          |
| created_at   | Timestamp         | Creation date                    |
| updated_at   | Timestamp         | Update date                      |

## Product Table
| Column Name  | Data Type        | Description                      |
|--------------|------------------|----------------------------------|
| id           | BigInteger (PK)   | Unique identifier for product    |
| name         | String            | Name of the product              |
| description  | Text              | Description of the product       |
| price        | Decimal           | Price of the product             |
| supplier_id  | BigInteger (FK)   | Reference to the supplier        |
| created_at   | Timestamp         | Creation date                    |
| updated_at   | Timestamp         | Update date                      |

## Supplier Table
| Column Name  | Data Type        | Description                      |
|--------------|------------------|----------------------------------|
| id           | BigInteger (PK)   | Unique identifier for supplier   |
| name         | String            | Name of the supplier             |
| contact_info | String            | Contact information              |
| created_at   | Timestamp         | Creation date                    |
| updated_at   | Timestamp         | Update date                      |

---

# Task Breakdown

## Main Task 1: Setup Project Structure
- **Sub-task 1.1**: ~~Initialize docker compose~~
- **Sub-task 1.2**: ~~Initialize Laravel Project~~
- **Sub-task 1.3**: ~~Configure environment variables~~
- **Sub-task 1.4**: ~~Install required packages (e.g., Laravel Sanctum for authentication)~~

## Main Task 2: Database Design
- **Sub-task 2.1**: ~~Create Inventory migration table~~
- **Sub-task 2.2**: ~~Create Product migration table~~
- **Sub-task 2.3**: ~~Create Supplier migration table~~

## Main Task 3: Implement Models, Factory and Seeders
- **Sub-task 3.1**: ~~Create Inventory Model, Factory and Seeders~~
- **Sub-task 3.2**: ~~Create Product Model, Factory and Seeders~~
- **Sub-task 3.3**: ~~Create Supplier Model, Factory and Seeders~~
- **Sub-task 3.4**: ~~Register Seeders when we do migrate:fresh --seed~~
  
## Main Task 4: Develop Required Services and Tests
- **Sub-task 4.1**: Create Inventory Services and tests
- **Sub-task 4.2**: Create Product Services and tests
- **Sub-task 4.3**: Create Supplier Services and tests

## Main Task 5: Develop API Endpoints
- **Sub-task 5.1**: Create endpoint to get all products
- **Sub-task 5.2**: Create endpoint to get a specific product
- **Sub-task 5.3**: Create endpoint to create a new product
- **Sub-task 5.4**: Create endpoint to update a product
- **Sub-task 5.5**: Create endpoint to delete a product
- **Sub-task 5.6**: Create endpoint to manage inventory levels (add/remove stock)

## Main Task 6: Implement Authentication
- **Sub-task 6.1**: Set up user authentication (e.g., Laravel Sanctum)
- **Sub-task 6.2**: Create user registration endpoint
- **Sub-task 6.3**: Create user login endpoint
- **Sub-task 6.4**: Protect API endpoints with authentication middleware

## Current Environment

# Application Information

- **Application Name:** inventory
- **Laravel Version:** 11.29.0
- **PHP Version:** 8.3.1
- **Composer Version:** 2.6.6
- **Environment:** local
- **Debug Mode:** ENABLED
- **URL:** localhost
- **Maintenance Mode:** OFF
- **Timezone:** GMT
- **Locale:** en

# Application Drivers

- **Broadcasting:** log
- **Cache:** redis
- **Database:** mysql
- **Logs:** stack / single
- **Mail:** log
- **Queue:** redis
- **Session:** database


