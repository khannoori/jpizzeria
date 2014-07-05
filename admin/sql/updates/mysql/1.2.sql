RENAME TABLE `#__pizza_types` TO `#__pizza_topping_types`;

ALTER TABLE `#__pizza_topping_prices` DROP FOREIGN KEY `FK#__pizza_t725144`;
ALTER TABLE `#__pizza_topping_prices` DROP INDEX `FK#__pizza_t725144`;
ALTER TABLE `#__pizza_topping_prices` DROP `topping_id`;

ALTER TABLE `#__pizza_toppings` ADD `type_id` INT( 11 ) NULL;
ALTER TABLE `#__pizza_toppings` ADD INDEX `FK#__pizza_t725144` (type_id), 
ADD CONSTRAINT `FK#__pizza_t725144` FOREIGN KEY (type_id) REFERENCES `#__pizza_topping_types` (id) ON DELETE SET NULL ON UPDATE SET NULL;