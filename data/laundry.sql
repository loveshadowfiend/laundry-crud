-- laundry.Customers definition

CREATE TABLE `Customers` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- laundry.Items definition

CREATE TABLE `Items` (
  `item_id` int NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `weight_limit` int DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- laundry.Employees definition

CREATE TABLE `Employees` (
  `employee_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- laundry.Orders definition

CREATE TABLE `Orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `order_date` date DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `employee_id` int DEFAULT NULL,
  `laundry_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `weight` int DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `Orders_FK` (`employee_id`),
  KEY `Orders_FK_1` (`item_id`),
  KEY `Orders_FK_2` (`customer_id`),
  CONSTRAINT `Orders_FK` FOREIGN KEY (`employee_id`) REFERENCES `Employees` (`employee_id`),
  CONSTRAINT `Orders_FK_1` FOREIGN KEY (`item_id`) REFERENCES `Items` (`item_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `Orders_FK_2` FOREIGN KEY (`customer_id`) REFERENCES `Customers` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;