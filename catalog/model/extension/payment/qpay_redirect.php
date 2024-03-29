<?php
class ModelExtensionPaymentqpayRedirect extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/smartroute_redirect');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('smartroute_redirect_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

		if ($this->config->get('smartroute_redirect_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('smartroute_redirect_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'smartroute_redirect',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_smartroute_redirect_sort_order')
			);
		}

		return $method_data;
	}


}