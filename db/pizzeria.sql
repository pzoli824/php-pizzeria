DROP DATABASE IF EXISTS pizzeria;

/* Database creation with tables */

CREATE DATABASE pizzeria CHARACTER SET utf8 COLLATE utf8_general_ci;

USE pizzeria;

CREATE TABLE Person (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(200) UNIQUE NOT NULL,
  firstname VARCHAR(100) NOT NULL,
  lastname VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  type INT NOT NULL DEFAULT(0)
) ENGINE = InnoDB;

CREATE TABLE Customer (
  id INT PRIMARY KEY AUTO_INCREMENT, 
  balance INT DEFAULT(0),
  person_id INT,
  FOREIGN KEY (person_id) REFERENCES Person(id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE Orders (
    customer_id INT NOT NULL,
    order_date DATETIME NOT NULL,
    paid_date DATETIME,
    PRIMARY KEY (customer_id, order_date),
    FOREIGN KEY (customer_id) REFERENCES Customer(id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE Drink (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    price INT NOT NULL DEFAULT(0)
) ENGINE = InnoDB;

CREATE TABLE Dough (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    price INT NOT NULL DEFAULT(0)
) ENGINE = InnoDB;

CREATE TABLE Sauce (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    price INT NOT NULL DEFAULT(0)
) ENGINE = InnoDB;

CREATE TABLE Topping (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    price INT NOT NULL DEFAULT(0),
    type INT NOT NULL
) ENGINE = InnoDB;

CREATE TABLE Pizzas (
    pizza_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    dough_id INT NOT NULL,
    sauce_id INT,
    FOREIGN KEY (dough_id) REFERENCES Dough(id) ON DELETE CASCADE,
    FOREIGN KEY (sauce_id) REFERENCES Sauce(id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE Toppings (
    pizza_id INT NOT NULL,
    topping_id INT NOT NULL,
    PRIMARY KEY (pizza_id, topping_id),
    FOREIGN KEY (pizza_id) REFERENCES Pizzas(pizza_id) ON DELETE CASCADE,
    FOREIGN KEY (topping_id) REFERENCES Topping(id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE Ordered_Pizzas (
    customer_id INT NOT NULL,
    order_date DATETIME NOT NULL,
    pizza_id INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY(customer_id, order_date, pizza_id),
    FOREIGN KEY (customer_id, order_date) REFERENCES Orders(customer_id, order_date) ON DELETE CASCADE,
    FOREIGN KEY (pizza_id) REFERENCES Pizzas(pizza_id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE Ordered_Drinks (
    customer_id INT NOT NULL,
    order_date DATETIME NOT NULL,
    drink_id INT NOT NULL,
    quantity INT NOT NULL,
    PRIMARY KEY(customer_id, order_date, drink_id),
    FOREIGN KEY (customer_id, order_date) REFERENCES Orders(customer_id, order_date) ON DELETE CASCADE,
    FOREIGN KEY (drink_id) REFERENCES Drink(id) ON DELETE CASCADE
) ENGINE = InnoDB;

/* Inserting data into tables */
/* Insert into Person table, Password help: 10w2HuM.eAPtE = 123qwe */
INSERT INTO 
`person` (`email`,`firstname`,`lastname`, `type`, `password`) 
VALUES
('hkevin@mail.com','Kevin','Horváth', 0, '10w2HuM.eAPtE'),
('tilona@mail.com','Ilona','Tóth', 0, '10w2HuM.eAPtE'),
('vmartin@mail.com','Martin','Varga', 0, '10w2HuM.eAPtE'),
('bdominik@mail.com','Dominik','Balog', 0, '10w2HuM.eAPtE'),
('admin@mail.com', 'Admin', 'Web', 1, '10w2HuM.eAPtE');

/* Insert into Dough table */
INSERT INTO `dough`(`name`, `price`) VALUES ('regular border dough', 1000);
INSERT INTO `dough`(`name`, `price`) VALUES ('dough border with sesame seeds', 1100);
INSERT INTO `dough`(`name`, `price`) VALUES ('dough border filled with 2 type of cheese', 1300);
INSERT INTO `dough`(`name`, `price`) VALUES ('dough border filled with ham and mozzarella cheese', 1300);
INSERT INTO `dough`(`name`, `price`) VALUES ('dough border filled with sausage', 1300);

/* Insert into Sauce table */
INSERT INTO `sauce`(`name`, `price`) VALUES('tomato sauce base', 100);
INSERT INTO `sauce`(`name`, `price`) VALUES('strong tomato sauce based', 150);
INSERT INTO `sauce`(`name`, `price`) VALUES('sour cream base', 150);
INSERT INTO `sauce`(`name`, `price`) VALUES('strong sour cream base', 200);
INSERT INTO `sauce`(`name`, `price`) VALUES('bolognese base', 200);
INSERT INTO `sauce`(`name`, `price`) VALUES('strong bolognese base', 250);
INSERT INTO `sauce`(`name`, `price`) VALUES('cheddar cheese base', 250);
INSERT INTO `sauce`(`name`, `price`) VALUES('strong cheddar cheese base', 300);

/* Insert into Topping table */
/* 
Types:
0: Meats, fishes, eggs
1: Vegetables
2: Cheeses
*/
/* Meats, fishes, eggs */
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('ham', 200, 0);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('bacon', 230, 0);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('chicken liver', 230, 0);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('chicken breast', 250, 0);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('gyros meat', 250, 0);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('sausage', 220, 0);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('salami', 240, 0);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('sardine', 210, 0);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('rib', 210, 0);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('frankfurter', 180, 0);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('boiled eggs', 180, 0);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('fried egg', 180, 0);

/* Vegetables */
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('pineapples', 240, 1);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('pickles', 210, 1);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('hot pepper', 170, 1);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('mushroom', 220, 1);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('corn', 190, 1);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('purple onion', 180, 1);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('paprika', 170, 1);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('tomato', 160, 1);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('pepperoni peppers', 190, 1);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('snake cucumber', 200, 1);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('red onion', 180, 1);

/* Cheeses */
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('mozzarella cheese', 280, 2);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('trappista cheese', 210, 2);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('cheddar cheese', 280, 2);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('feta cheese', 270, 2);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('parmesan cheese', 230, 2);
INSERT INTO `topping`(`name`, `price`, `type`) VALUES('lactose-free cheese', 300, 2);

/* Insert into Drink table */
INSERT INTO `drink`(`name`, `price`) VALUES('Coca-Cola 0,5l', 430);
INSERT INTO `drink`(`name`, `price`) VALUES('Coca-Cola Zero 0,5l', 430);
INSERT INTO `drink`(`name`, `price`) VALUES('Coca-Cola Lemon Zero 0,5l', 430);
INSERT INTO `drink`(`name`, `price`) VALUES('Coca-Cola Cherry Coke 0,5l', 430);
INSERT INTO `drink`(`name`, `price`) VALUES('Fanta Orange 0,5l', 430);
INSERT INTO `drink`(`name`, `price`) VALUES('Sprite 0,5l', 400);
INSERT INTO `drink`(`name`, `price`) VALUES('NaturAqua carbonated mineral water 0,5l', 300);
INSERT INTO `drink`(`name`, `price`) VALUES('NaturAqua carbonated mineral water 0,5l', 300);
INSERT INTO `drink`(`name`, `price`) VALUES('Pepsi Cola 0,5l', 350);

/* Insert into Customer table */
INSERT INTO `customer`(`balance`, `person_id`) 
VALUES
(11000, 1),
(13000, 2),
(7500, 3),
(8200, 4);

/* Insert into Pizzas table */
INSERT INTO `pizzas`(`name`,`dough_id`,`sauce_id`) VALUES('Sausage pizza', 5, 1);
INSERT INTO `pizzas`(`name`,`dough_id`,`sauce_id`) VALUES('Ham pizza', 4, 3);
INSERT INTO `pizzas`(`name`,`dough_id`,`sauce_id`) VALUES('Triple cheese pizza', 4, 4);
INSERT INTO `pizzas`(`name`,`dough_id`,`sauce_id`) VALUES('Beauty of the sea pizza', 2, 5);
INSERT INTO `pizzas`(`name`,`dough_id`,`sauce_id`) VALUES('Ham and egg pizza', 4, 6);
INSERT INTO `pizzas`(`name`,`dough_id`,`sauce_id`) VALUES('Pineapple pizza',2, 7);
INSERT INTO `pizzas`(`name`,`dough_id`,`sauce_id`) VALUES('Corn pizza',3, 4);
INSERT INTO `pizzas`(`name`,`dough_id`,`sauce_id`) VALUES('Gyros pizza',1, 5);
INSERT INTO `pizzas`(`name`,`dough_id`,`sauce_id`) VALUES('Salami pizza', 5, 2);
INSERT INTO `pizzas`(`name`,`dough_id`,`sauce_id`) VALUES('Frankfurt pizza',3, 3);

/* Insert into Topppings table for the existing pizzas */
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(1, 6);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(1, 21);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(1, 25);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(1, 23);

INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(2, 20);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(2, 9);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(2, 22);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(2, 24);

INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(3, 26);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(3, 24);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(3, 25);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(3, 4);

INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(4, 8);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(4, 12);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(4, 11);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(4, 2);

INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(5, 12);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(5, 1);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(5, 2);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(5, 20);

INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(6, 13);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(6, 14);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(6, 20);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(6, 27);

INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(7, 17);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(7, 25);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(7, 3);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(7, 16);

INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(8, 5);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(8, 16);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(8, 28);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(8, 18);

INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(9, 7);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(9, 6);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(9, 2);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(9, 18);

INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(10, 10);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(10, 22);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(10, 19);
INSERT INTO `toppings`(`pizza_id`,`topping_id`) VALUES(10, 14);

/* Insert into Orders */
INSERT INTO `orders`(`customer_id`,`order_date`) VALUES(1, '2022-09-11 11:31:02');
INSERT INTO `orders`(`customer_id`,`order_date`) VALUES(2, '2022-09-18 09:42:41');
INSERT INTO `orders`(`customer_id`,`order_date`, `paid_date`) VALUES(3, '2022-09-17 14:17:15', '2022-09-17 15:10:15');
INSERT INTO `orders`(`customer_id`,`order_date`) VALUES(4, '2022-09-25 19:21:08');

/* Insert into ordered pizzas and ordered drinks */
INSERT INTO `ordered_pizzas`(`customer_id`, `order_date`, `pizza_id`, `quantity`) VALUES(1, '2022-09-11 11:31:02', 1, 2);
INSERT INTO `ordered_drinks`(`customer_id`, `order_date`, `drink_id`, `quantity`) VALUES(1, '2022-09-11 11:31:02', 2, 1);

INSERT INTO `ordered_pizzas`(`customer_id`, `order_date`, `pizza_id`, `quantity`) VALUES(2, '2022-09-18 09:42:41', 3, 1);
INSERT INTO `ordered_pizzas`(`customer_id`, `order_date`, `pizza_id`, `quantity`) VALUES(2, '2022-09-18 09:42:41', 5, 1);
INSERT INTO `ordered_drinks`(`customer_id`, `order_date`, `drink_id`, `quantity`) VALUES(2, '2022-09-18 09:42:41', 3, 1);

INSERT INTO `ordered_pizzas`(`customer_id`, `order_date`, `pizza_id`, `quantity`) VALUES(3, '2022-09-17 14:17:15', 4, 1);
INSERT INTO `ordered_pizzas`(`customer_id`, `order_date`, `pizza_id`, `quantity`) VALUES(3, '2022-09-17 14:17:15', 7, 1);
INSERT INTO `ordered_pizzas`(`customer_id`, `order_date`, `pizza_id`, `quantity`) VALUES(3, '2022-09-17 14:17:15', 9, 1);
INSERT INTO `ordered_drinks`(`customer_id`, `order_date`, `drink_id`, `quantity`) VALUES(3, '2022-09-17 14:17:15', 4, 1);

INSERT INTO `ordered_pizzas`(`customer_id`, `order_date`, `pizza_id`, `quantity`) VALUES(4, '2022-09-25 19:21:08', 10, 3);
INSERT INTO `ordered_drinks`(`customer_id`, `order_date`, `drink_id`, `quantity`) VALUES(4, '2022-09-25 19:21:08', 1, 1);
INSERT INTO `ordered_drinks`(`customer_id`, `order_date`, `drink_id`, `quantity`) VALUES(4, '2022-09-25 19:21:08', 5, 3);

/* --- Creating View tables --- */

/* Return Pizzas with their full price(included sauce,dough,toppings prices in this) */
CREATE OR REPLACE VIEW
pizzas_with_cost
AS
SELECT 
pizza_id, 
name, 
(
(SELECT price from sauce where pizzas.sauce_id = sauce.id) +
(SELECT price from dough where pizzas.dough_id = dough.id) +
(SELECT SUM(topping.price) from toppings
INNER JOIN topping ON toppings.topping_id = topping.id
WHERE toppings.pizza_id = pizzas.pizza_id
GROUP BY pizzas.name)
) as price
from 
pizzas;

/* Return Pizzas with their toppings, and with their dough, sauce */
CREATE OR REPLACE VIEW
pizzas_with_toppings
AS
SELECT 
p.pizza_id, p.name,
p.dough_id, d.name AS dough_name, d.price AS dough_price,
p.sauce_id, s.name AS sauce_name, s.price AS sauce_price,
t.id, t.name AS topping_name, t.price AS topping_price, t.type AS topping_type
FROM 
pizzas AS p
INNER JOIN dough AS d
ON d.id = p.dough_id
INNER JOIN sauce AS s
ON s.id = p.sauce_id
INNER JOIN toppings
ON toppings.pizza_id = p.pizza_id
LEFT OUTER JOIN topping AS t
ON t.id = toppings.topping_id;

/* All the orders including pizzas and drinks */
CREATE OR REPLACE VIEW
orders_with_pizzas_and_drinks
AS
SELECT 
o.customer_id as customer_id, o.order_date, paid_date, 
op.pizza_id, op.quantity as pizza_quantity, 
od.drink_id, od.quantity as drink_quantity
FROM orders as o
LEFT JOIN ordered_pizzas as op ON o.customer_id = op.customer_id AND o.order_date = op.order_date
LEFT JOIN ordered_drinks as od ON o.customer_id = od.customer_id AND o.order_date = od.order_date;

/* Return all the customer accounts with their person account */
CREATE OR REPLACE VIEW
customers_with_persons_account
AS
SELECT 
c.id AS customer_id, p.id AS person_id, p.email, p.lastname, p.firstname, p.password, p.type, c.balance
FROM customer AS c
INNER JOIN person AS p ON c.person_id = p.id;
