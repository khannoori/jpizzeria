ALTER TABLE `#__pizza_toppings` 
   DROP FOREIGN KEY `FK#__pizza_t725144`;
ALTER TABLE `#__pizza_topping_prices` 
   DROP FOREIGN KEY `FK#__pizza_t426526`;
ALTER TABLE `#__pizza_topping_prices` 
   DROP FOREIGN KEY `FK#__pizza_t699734`;
ALTER TABLE `#__pizza_contents` 
   DROP FOREIGN KEY `FK#__pizza_c246399`;
ALTER TABLE `#__pizza_contents` 
   DROP FOREIGN KEY `FK#__pizza_c212391`;
ALTER TABLE `#__pizza_item_prices` 
   DROP FOREIGN KEY `FK#__pizza_i8087`;
ALTER TABLE `#__pizza_item_prices` 
   DROP FOREIGN KEY `FK#__pizza_i272697`;
ALTER TABLE `#__pizza_orders` 
   DROP FOREIGN KEY `FK#__pizza_o259668`;
ALTER TABLE `#__pizza_order_line` 
   DROP FOREIGN KEY `FK#__pizza_o287275`;
ALTER TABLE `#__pizza_order_line` 
   DROP FOREIGN KEY `FK#__pizza_o516626`;
ALTER TABLE `#__pizza_order_line_extras` 
   DROP FOREIGN KEY `FK#__pizza_o102954`;
ALTER TABLE `#__pizza_order_line_extras` 
   DROP FOREIGN KEY `FK#__pizza_o544651`;
ALTER TABLE `#__pizza_order_line_minus` 
   DROP FOREIGN KEY `FK#__pizza_o102444`;
ALTER TABLE `#__pizza_order_line_minus` 
   DROP FOREIGN KEY `FK#__pizza_o544561`;
ALTER TABLE `#__pizza_reviews` 
   DROP FOREIGN KEY `FK#__pizza_r109304`;
ALTER TABLE `#__pizza_reviews` 
   DROP FOREIGN KEY `FK#__pizza_r868625`;
ALTER TABLE `#__pizza_ratings` 
   DROP FOREIGN KEY `FK#__pizza_r464913`;
ALTER TABLE `#__pizza_ratings` 
   DROP FOREIGN KEY `FK#__pizza_r224235`;

DROP TABLE IF EXISTS `#__pizza_toppings`;
DROP TABLE IF EXISTS `#__pizza_topping_prices`;
DROP TABLE IF EXISTS `#__pizza_sizes`;
DROP TABLE IF EXISTS `#__pizza_topping_types`;
DROP TABLE IF EXISTS `#__pizza_items`;
DROP TABLE IF EXISTS `#__pizza_contents`;
DROP TABLE IF EXISTS `#__pizza_item_prices`;
DROP TABLE IF EXISTS `#__pizza_orders`;
DROP TABLE IF EXISTS `#__pizza_order_line`;
DROP TABLE IF EXISTS `#__pizza_order_line_extras`;
DROP TABLE IF EXISTS `#__pizza_order_line_minus`;
DROP TABLE IF EXISTS `#__pizza_reviews`;
DROP TABLE IF EXISTS `#__pizza_ratings`;