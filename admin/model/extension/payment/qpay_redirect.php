<?php

class ModelExtensionPaymentqpayRedirect extends Model {

    public function install() {
        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "qpay_trxn` (
			  `qpay_order_id` int(11) NOT NULL AUTO_INCREMENT,
			  `opencart_order_id` int(11) NOT NULL,
                          `transaction_id` VARCHAR(100) NOT NULL,
                          `status` VARCHAR(10),
                          `status_description` VARCHAR(255),
			  PRIMARY KEY (`qpay_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");

        $this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "qpay_refund_trxn` (
			  `qpay_order_id` int(11) NOT NULL AUTO_INCREMENT,
                          `transaction_id` VARCHAR(100) NOT NULL,
                          `status` VARCHAR(10),
                          `status_description` VARCHAR(255),
                          `opencart_orig_order_id` int(11) NOT NULL,
                          `refund_amount` int(20) NOT NULL,
			  PRIMARY KEY (`qpay_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "qpay_trxn`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "qpay_refund_trxn`");
    }

    public function getTrxnAmount($qpay_order_id) {
        $query = $this->db->query("SELECT TOTAL AS `amount` FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int) $qpay_order_id . "'");
        return $query->row['amount'];
    }

    public function getqpayTrxnId($openCartOrderId) {
        $query = $this->db->query("SELECT `transaction_id` FROM `" . DB_PREFIX . "qpay_trxn` WHERE  `opencart_order_id` ='" . $openCartOrderId . "'");
        return $query->row['transaction_id'];
    }

    public function addRefundqpayTrxn($openCartOrderId, $trxnId, $statusCode, $statusDescription, $refundAmount) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "qpay_refund_trxn`(`transaction_id`, `opencart_orig_order_id`,`status`,`status_description`,`refund_amount`) VALUES ('" . $trxnId . "','" . $openCartOrderId . "','" . $statusCode . "','" . $statusDescription . "','" . $refundAmount . "')");
    }

    public function getOrdercurrency($qpay_order_id) {
        $query = $this->db->query("SELECT currency_code  FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int) $qpay_order_id . "'");
        return $query->row['currency_code'];
    }

    public function getOrderDetails($openCartOrderId) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "qpay_trxn` WHERE `opencart_order_id` = '" . $openCartOrderId . "'");
        return $query->row;
    }

    public function getPrevRefundedAmount($openCartOrderId) {
        $query = $this->db->query("SELECT sum(refund_amount) as refunded_amount FROM `" . DB_PREFIX . "qpay_refund_trxn` WHERE `opencart_orig_order_id` = '" . $openCartOrderId . "' and `status` = '00000'");
        return $query->row['refunded_amount'];
    }

    public function getOpenCartOrderId($qpayTrxnId) {
        $query = $this->db->query("SELECT `opencart_order_id` FROM `" . DB_PREFIX . "qpay_trxn` WHERE  `transaction_id` ='" . $qpayTrxnId . "'");
        return $query->row['opencart_order_id'];
    }

    public function addOrderHistory($openCartOrderId, $order_statusId) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_history`(`order_id`, `order_status_id`, `date_added`) VALUES ('" . $openCartOrderId . "','" . $order_statusId . "', NOW())");
    }

    public function updateqpayTrxn($qpayTrxnId, $status, $ststusDesc) {
        $this->db->query("UPDATE `" . DB_PREFIX . "qpay_trxn` SET `status`='" . $status . "',`status_description`= '" . $ststusDesc . "' WHERE `transaction_id` ='" . $qpayTrxnId . "'");
    }

    public function updateOrderStatus($openCartOrderId, $order_statusId) {
              $this->db->query("UPDATE `" . DB_PREFIX . "order` SET `order_status_id`='" . $order_statusId . "' WHERE `order_id` ='" . $openCartOrderId . "'");
    }

}
