-- 2021-12-16 00:17:54
CREATE TABLE `payment_receipts` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`bill_no` INT NOT NULL,
	`date` DATE NOT NULL,
	`received_amt` FLOAT(10, 2) NOT NULL,
	`created_date` DATE NOT NULL,
	PRIMARY KEY (`id`)
);
-- 