ALTER TABLE `#__users` ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS `#__pizza_toppings` (
  id      int(11) NOT NULL AUTO_INCREMENT, 
  name    varchar(50), 
  extra   tinyint(1),
  type_id int( 11 ) NOT NULL,
  PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS `#__pizza_topping_prices` (
  id         int(11) NOT NULL AUTO_INCREMENT, 
  price      decimal(5, 2),
  size_id    int(11), 
  type_id    int(11), 
  PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS `#__pizza_sizes` (
  id   int(11) NOT NULL AUTO_INCREMENT, 
  name varchar(50), 
  PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS `#__pizza_topping_types` (
  id   int(11) NOT NULL AUTO_INCREMENT, 
  name varchar(50), 
  PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS `#__pizza_items` (
  id                int(11) NOT NULL AUTO_INCREMENT, 
  number            varchar(20), 
  name              varchar(50), 
  description       varchar(255), 
  image             varchar(50), 
  creation_time     timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  modification_time timestamp NULL, 
  state		        tinyint(1) NOT NULL, 
  featured          tinyint(1) NOT NULL, 
  catid				int(11) NOT NULL,
  ordering			int(11) NOT NULL,
  PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS `#__pizza_contents` (
  item_id    int(11) NOT NULL, 
  topping_id int(11) NOT NULL, 
  PRIMARY KEY (item_id, 
  topping_id));
CREATE TABLE IF NOT EXISTS `#__pizza_item_prices` (
  id      int(11) NOT NULL AUTO_INCREMENT, 
  price   decimal(5, 2), 
  item_id int(11), 
  size_id int(11), 
  PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS `#__pizza_orders` (
  id           int(11) NOT NULL AUTO_INCREMENT, 
  ordered_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  due_time     timestamp NULL, 
  hits         int(11), 
  status       tinyint(1), 
  visible      tinyint(1), 
  type         tinyint(1), 
  customer_id  int(11), 
  PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS `#__pizza_order_line` (
  id       		 int(11) NOT NULL AUTO_INCREMENT, 
  item_price_id  int(11), 
  order_id 		 int(11), 
  PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS `#__pizza_order_line_extras` (
  order_line_id int(11) NOT NULL, 
  topping_id    int(11) NOT NULL, 
  PRIMARY KEY (order_line_id, 
  topping_id));
CREATE TABLE IF NOT EXISTS `#__pizza_order_line_minus` (
  order_line_id int(11) NOT NULL, 
  topping_id    int(11) NOT NULL, 
  PRIMARY KEY (order_line_id, 
  topping_id));  
CREATE TABLE IF NOT EXISTS `#__pizza_reviews` (
  id            int(11) NOT NULL AUTO_INCREMENT, 
  creation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  review        tinytext, 
  customer_id   int(11), 
  item_id       int(11), 
  PRIMARY KEY (id));
CREATE TABLE IF NOT EXISTS `#__pizza_ratings` (
  vote        tinyint(2), 
  customer_id int(11), 
  item_id     int(11));
  
ALTER TABLE `#__pizza_toppings` 
   ADD INDEX `FK#__pizza_t725144` (type_id), 
   ADD CONSTRAINT `FK#__pizza_t725144` 
   FOREIGN KEY (type_id) 
   REFERENCES `#__pizza_topping_types` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_topping_prices` 
   ADD INDEX `FK#__pizza_t426526` (size_id), 
   ADD CONSTRAINT `FK#__pizza_t426526` 
   FOREIGN KEY (size_id) 
   REFERENCES `#__pizza_sizes` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_topping_prices` 
   ADD INDEX `FK#__pizza_t699734` (type_id), 
   ADD CONSTRAINT `FK#__pizza_t699734` 
   FOREIGN KEY (type_id) 
   REFERENCES `#__pizza_topping_types` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_contents` 
   ADD INDEX `FK#__pizza_c246399` (topping_id), 
   ADD CONSTRAINT `FK#__pizza_c246399` 
   FOREIGN KEY (topping_id) 
   REFERENCES `#__pizza_toppings` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_contents` 
   ADD INDEX `FK#__pizza_c212391` (item_id), 
   ADD CONSTRAINT `FK#__pizza_c212391` 
   FOREIGN KEY (item_id) 
   REFERENCES `#__pizza_items` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_item_prices` 
   ADD INDEX `FK#__pizza_i8087` (item_id), 
   ADD CONSTRAINT `FK#__pizza_i8087` 
   FOREIGN KEY (item_id) 
   REFERENCES `#__pizza_items` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_item_prices` 
   ADD INDEX `FK#__pizza_i272697` (size_id), 
   ADD CONSTRAINT `FK#__pizza_i272697` 
   FOREIGN KEY (size_id) 
   REFERENCES `#__pizza_sizes` (id)
   ON UPDATE Cascade
   ON DELETE Cascade;
ALTER TABLE `#__pizza_orders` 
   ADD INDEX `FK#__pizza_o259668` (customer_id), 
   ADD CONSTRAINT `FK#__pizza_o259668` 
   FOREIGN KEY (customer_id) 
   REFERENCES `#__users` (id) 
   ON UPDATE Cascade 
   ON DELETE Set null;
ALTER TABLE `#__pizza_order_line` 
   ADD INDEX `FK#__pizza_o287275` (order_id), 
   ADD CONSTRAINT `FK#__pizza_o287275` 
   FOREIGN KEY (order_id) 
   REFERENCES `#__pizza_orders` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_order_line` 
   ADD INDEX `FK#__pizza_o516626` (item_price_id), 
   ADD CONSTRAINT `FK#__pizza_o516626` 
   FOREIGN KEY (item_price_id) 
   REFERENCES `#__pizza_item_prices` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_order_line_extras` 
   ADD INDEX `FK#__pizza_o102954` (topping_id), 
   ADD CONSTRAINT `FK#__pizza_o102954` 
   FOREIGN KEY (topping_id) 
   REFERENCES `#__pizza_toppings` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_order_line_extras` 
   ADD INDEX `FK#__pizza_o544651` (order_line_id), 
   ADD CONSTRAINT `FK#__pizza_o544651` 
   FOREIGN KEY (order_line_id) 
   REFERENCES `#__pizza_order_line` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_order_line_minus` 
   ADD INDEX `FK#__pizza_o102444` (topping_id), 
   ADD CONSTRAINT `FK#__pizza_o102444` 
   FOREIGN KEY (topping_id) 
   REFERENCES `#__pizza_toppings` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_order_line_minus` 
   ADD INDEX `FK#__pizza_o544561` (order_line_id), 
   ADD CONSTRAINT `FK#__pizza_o544561` 
   FOREIGN KEY (order_line_id) 
   REFERENCES `#__pizza_order_line` (id) 
   ON UPDATE Cascade 
   ON DELETE Cascade;
ALTER TABLE `#__pizza_reviews` 
   ADD INDEX `FK#__pizza_r109304` (item_id), 
   ADD CONSTRAINT `FK#__pizza_r109304` 
   FOREIGN KEY (item_id) 
   REFERENCES `#__pizza_items` (id) 
   ON UPDATE Cascade 
   ON DELETE Set null;
ALTER TABLE `#__pizza_reviews` 
   ADD INDEX `FK#__pizza_r868625` (customer_id), 
   ADD CONSTRAINT `FK#__pizza_r868625` 
   FOREIGN KEY (customer_id) 
   REFERENCES `#__users` (id) 
   ON UPDATE Cascade 
   ON DELETE Set null;
ALTER TABLE `#__pizza_ratings` 
   ADD INDEX `FK#__pizza_r464913` (item_id), 
   ADD CONSTRAINT `FK#__pizza_r464913` 
   FOREIGN KEY (item_id) 
   REFERENCES `#__pizza_items` (id) 
   ON UPDATE Cascade 
   ON DELETE Set null;
ALTER TABLE `#__pizza_ratings` 
   ADD INDEX `FK#__pizza_r224235` (customer_id), 
   ADD CONSTRAINT `FK#__pizza_r224235` 
   FOREIGN KEY (customer_id) 
   REFERENCES `#__users` (id) 
   ON UPDATE Cascade 
   ON DELETE Set null;