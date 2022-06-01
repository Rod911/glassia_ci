-- 2021-12-16 00:17:54
CREATE TABLE `payment_receipts` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`bill_no` INT NOT NULL,
	`date` DATE NOT NULL,
	`received_amt` FLOAT(10, 2) NOT NULL,
	`created_date` DATE NOT NULL,
	PRIMARY KEY (`id`)
);
-- 2021-12-28 22:11:59
ALTER TABLE `invoice_particulars`
ADD `hsn` VARCHAR(100) NOT NULL
AFTER `name`;
-- 2022-06-01 20:34:04
CREATE TABLE `customer_receipts` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`customer` VARCHAR(250) NOT NULL,
	`amount` FLOAT(10, 2) NOT NULL,
	`receipt_date` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;
-- 
ALTER TABLE `customer_receipts`
ADD `payment_date` DATE NOT NULL
AFTER `receipt_date`;
--