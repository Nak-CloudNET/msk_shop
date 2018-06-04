/* 11/05/2018 By Kaoly */

ALTER TABLE `erp_return_purchases`
ADD COLUMN `customer_id`  int(11) NULL AFTER `shipping`,
ADD COLUMN `sale_id`  int(11) NULL AFTER `customer_id`;
