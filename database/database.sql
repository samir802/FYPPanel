--
-- Table structure for table api_tokens
--

CREATE TABLE api_tokens (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id int(11) DEFAULT NULL,
  token varchar(255) DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id)
);

--
-- Table structure for table company
--

CREATE TABLE company (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  Company_Name varchar(20) NOT NULL,
  user_id int(11) DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id)
);

--
-- Table structure for table driver
--

CREATE TABLE driver (
  Driver_ID int(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  Driver_Name varchar(25) NOT NULL,
  Phone varchar(10) NOT NULL,
  Email varchar(20) NOT NULL,
  Address varchar(20) NOT NULL,
  Password varchar(150) NOT NULL,
  Vehicle_type varchar(10)
 
);

--
-- Table structure for table orders
--

CREATE TABLE orders (
  OrderId int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  Rented_date datetime NOT NULL,
  Return_Date date NOT NULL,
  status varchar(255) DEFAULT NULL,
  Total_Price int(5) NOT NULL,
  user_id int(11) DEFAULT NULL,
  vehicle_id int(5) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (vehicle_id) REFERENCES vehicles (VehicleID)
);

--
-- Table structure for table rating
--

CREATE TABLE rating (
  RatingId int(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  Comment varchar(100) NOT NULL,
  Rating int(1) NOT NULL,
  user_ID int(5) NOT NULL,
  Order_ID int(5) NOT NULL,
  FOREIGN KEY (Order_ID) REFERENCES orders (OrderId),
  FOREIGN KEY (user_ID) REFERENCES users (id)
);

--
-- Table structure for table vehicles
--

CREATE TABLE vehicles (
  VehicleID int(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  Vehicle_Info varchar(255) NOT NULL,
  VehicleBrand varchar(20) NOT NULL,
  Capacity int(4) NOT NULL,
  Engine_capacity int(7) NOT NULL,
  Fuel_consumption int(5) NOT NULL,
  Driving_method varchar(10) NOT NULL,
  FuelType varchar(11) NOT NULL,
  Price int(5) NOT NULL,
  Vehicle_Image varchar(100) NOT NULL,
  Vehicle_type varchar(10) NOT NULL,
  Company_Id int(5) NOT NULL,
  FOREIGN KEY (Company_Id) REFERENCES company (id)
);
