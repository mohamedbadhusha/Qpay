<?php

class ControllerExtensionPaymentqpayRedirect extends Controller {

    public function index() {

        $this->load->language('extension/payment/qpay_redirect');

        $data['continue'] = $this->url->link('extension/payment/qpay_redirect/checkout', '', true);

        unset($this->session->data['qpay_redirect']);

        return $this->load->view('extension/payment/qpay_redirect', $data);
    }

    public function checkout() {
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        $this->load->model('extension/payment/qpay_redirect');
        $this->load->model('tool/image');
        $this->load->model('checkout/order');


        $order_status_id = $this->config->get('payment_qpay_redirect_pending_status_id');
        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id);
        $transaction_id = ControllerExtensionPaymentqpayRedirect::addqpayTrxn($this->session->data['order_id']);

        //get order details
        $data = array();
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if ($order_info) {
            $decimal_point = $this->language->get('decimal_point');
            $thousand_point = $this->language->get('thousand_point');

            $data['MessageID'] = 1;
            $data['TransactionID'] = $transaction_id;
            $data['MerchantID'] = trim($this->config->get('payment_qpay_redirect_merchant_id'));
            $data['Language'] = trim($this->config->get('payment_qpay_redirect_language'));
            $data['ItemID'] = trim($this->config->get('payment_qpay_redirect_item_id'));
            $data['ResponseBackURL'] = $this->url->link('extension/payment/qpay_redirect/checkoutReturn');

            $amount = str_replace($decimal_point, '', $this->currency->format($order_info['total'], $order_info['currency_code'], false, true));
            $amount = str_replace($thousand_point, '', $amount);

            $symbol_left = $this->currency->getSymbolLeft($order_info['currency_code']);
            $symbol_right = $this->currency->getSymbolRight($order_info['currency_code']);

            if ($symbol_left) {
                $amount = substr($amount, sizeof($symbol_left));
            }

            if ($symbol_right) {
                $i = strpos($amount, $symbol_right);
                $amount = substr($amount, 0, $i);
            }

            $currencies_file = parse_ini_file("currencies.ini");
            $data['Amount'] = $amount;
            $data['CurrencyISOCode'] = $currencies_file[$order_info['currency_code']];

            $data['SecureHash'] = ControllerExtensionPaymentqpayRedirect::generateSecureHash($data);
            
            $data['RedirectURL'] = $this->config->get('payment_qpay_redirect_URL');
            $this->response->setOutput($this->load->view('extension/payment/qpay_redirect_form', $data));
        }
    }

    public function checkoutReturn() {

        $data = array();
        $this->load->model('checkout/order');
        print_r($_REQUEST);
  
        //get required params from response
        if (isset($_REQUEST['Response_StatusCode'])) {
            $data['Response_StatusCode'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_StatusCode']);
        }
        if (isset($_REQUEST['Response_StatusDescription'])) {
            $data['Response_StatusDescription'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_StatusDescription']);
        }
        if (isset($_REQUEST['Response_Amount'])) {
            $data['Response_Amount'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_Amount']);
        }
        if (isset($_REQUEST['Response_CurrencyISOCode'])) {
            $data['Response_CurrencyISOCode'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_CurrencyISOCode']);
        }
        if (isset($_REQUEST['Response_MerchantID'])) {
            $data['Response_MerchantID'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_MerchantID']);
        }
        if (isset($_REQUEST['Response_TransactionID'])) {
            $data['Response_TransactionID'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_TransactionID']);
        }
        if (isset($_REQUEST['Response_MessageID'])) {
            $data['Response_MessageID'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_MessageID']);
        }


        //get optional params from response
        if (isset($_REQUEST['Response_CardExpiryDate'])) {
            $data['Response_CardExpiryDate'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_CardExpiryDate']);
        }

        if (isset($_REQUEST['Response_CardHolderName'])) {
            $data['Response_CardHolderName'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_CardHolderName']);
        }

        if (isset($_REQUEST['Response_CardNumber'])) {
            $data['Response_CardNumber'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_CardNumber']);
        }

        if (isset($_REQUEST['Response_GatewayStatusCode'])) {
            $data['Response_GatewayStatusCode'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_GatewayStatusCode']);
        }

        if (isset($_REQUEST['Response_GatewayStatusDescription'])) {
            $data['Response_GatewayStatusDescription'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_GatewayStatusDescription']);
        }

        if (isset($_REQUEST['Response_GatewayName'])) {
            $data['Response_GatewayName'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_GatewayName']);
        }

        if (isset($_REQUEST['Response_RRN'])) {
            $data['Response_RRN'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_RRN']);
        }

        if (isset($_REQUEST['Response_ApprovalCode'])) {
            $data['Response_ApprovalCode'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_ApprovalCode']);
        }

        if (isset($_REQUEST['Response_Token'])) {
            $data['Response_Token'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_Token']);
        }

        $success = false;

        // get the secure hash
        $Response_SecureHash = ControllerExtensionPaymentqpayRedirect::checkIfNull($_REQUEST['Response_SecureHash']);
        $secureHash = ControllerExtensionPaymentqpayRedirect::generateSecureHash($data);
       
        if ($secureHash == $Response_SecureHash) {
            $order_status_id = ControllerExtensionPaymentqpayRedirect::getOrderStatusId($data['Response_StatusCode']);
            $this->model_checkout_order->addOrderHistory(ControllerExtensionPaymentqpayRedirect::getOpenCartOrderId($data['Response_TransactionID']), $order_status_id);
            ControllerExtensionPaymentqpayRedirect::updateqpayTrxn($data['Response_TransactionID'], $data['Response_StatusCode'], $data['Response_StatusDescription']);

            if ($data['Response_StatusCode'] == "00000") {
                $success = true;
            }
        } else {
            //Inquiry Message
          
            $url = trim($this->config->get('payment_qpay_redirect_inquiry_URL'));

            $ch = curl_init($url);

            $fields = array();
            $fields['MessageID'] = 2;
            $fields['MerchantID'] = trim($this->config->get('payment_qpay_redirect_merchant_id'));
            $fields['OriginalTransactionID'] = $data['Response_TransactionID'];
            $fields['SecureHash'] = ControllerExtensionPaymentqpayRedirect::generateSecureHash($fields);

            $fields_string = "";
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //for testing
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);

            //Returns the error number for the last cURL operation.
            //Returns the error number or 0 (zero) if no error occurred.
            if (curl_errno($ch) == 0) {
                $result = array();
                foreach (explode("&", $response) as $parameter) {
                    $equalsIndex = strpos($parameter, "=");
                    $name = substr($parameter, 0, $equalsIndex);
                    $value = substr($parameter, $equalsIndex + 1);

                    $result[$name] = $value;
                }
                curl_close($ch);

                $res_data = array();

                //get required params from response
                if (isset($result['Response.MessageStatus'])) {
                    $res_data['Response.MessageStatus'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.MessageStatus']);
                }

                if (isset($result['Response.StatusCode'])) {
                    $res_data['Response.StatusCode'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.StatusCode']);
                }

                if (isset($result['Response.StatusDescription'])) {
                    $res_data['Response.StatusDescription'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.StatusDescription']);
                }

                if (isset($result['Response.Amount'])) {
                    $res_data['Response.Amount'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.Amount']);
                }

                if (isset($result['Response.CurrencyISOCode'])) {
                    $res_data['Response.CurrencyISOCode'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.CurrencyISOCode']);
                }

                if (isset($result['Response.MerchantID'])) {
                    $res_data['Response.MerchantID'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.MerchantID']);
                }

                if (isset($result['Response.TransactionID'])) {
                    $res_data['Response.TransactionID'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.TransactionID']);
                }

                if (isset($result['Response.MessageID'])) {
                    $res_data['Response.MessageID'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.MessageID']);
                }

                if (isset($result['Response.ReversalStatus'])) {
                    $res_data['Response.ReversalStatus'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.ReversalStatus']);
                }


                //get optional params from response
                if (isset($result['Response.GatewayStatusCode'])) {
                    $res_data['Response.GatewayStatusCode'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.GatewayStatusCode']);
                }

                if (isset($result['Response.GatewayStatusDescription'])) {
                    $res_data['Response.GatewayStatusDescription'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.GatewayStatusDescription']);
                }

                if (isset($result['Response.GatewayName'])) {
                    $res_data['Response.GatewayName'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.GatewayName']);
                }

                if (isset($result['Response.RRN'])) {
                    $res_data['Response.RRN'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.RRN']);
                }

                if (isset($result['Response.ApprovalCode'])) {
                    $res_data['Response.ApprovalCode'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.ApprovalCode']);
                }

                if (isset($result['Response.CardExpiryDate'])) {
                    $res_data['Response.CardExpiryDate'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.CardExpiryDate']);
                }

                if (isset($result['Response.CardHolderName'])) {
                    $res_data['Response.CardHolderName'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.CardHolderName']);
                }

                if (isset($result['Response.CardNumber'])) {
                    $res_data['Response.CardNumber'] = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.CardNumber']);
                }


                // get the secure hash
                $res_secure_hash = ControllerExtensionPaymentqpayRedirect::checkIfNull($result['Response.SecureHash']);
                $newSecureHash = ControllerExtensionPaymentqpayRedirect::generateSecureHash($res_data);

                if ($newSecureHash == $res_secure_hash) {
                    $order_statusId = ControllerExtensionPaymentqpayRedirect::getOrderStatusId($res_data['Response.StatusCode']);
                    $this->model_checkout_order->addOrderHistory(ControllerExtensionPaymentqpayRedirect::getOpenCartOrderId($res_data['Response.TransactionID']), $order_statusId);
                    ControllerExtensionPaymentqpayRedirect::updateqpayTrxn($res_data['Response.TransactionID'], $res_data['Response.StatusCode'], $res_data['Response.StatusDescription']);

                    if ($res_data['Response.StatusCode'] == "00000") {
                        $success = true;
                    }
                } else {
                    // do nothing
                }
            } else {
                $success = false;
            }
        }

        if ($success) {
            $this->response->redirect($this->url->link('checkout/success'));
        } else {
            $this->response->redirect($this->url->link('checkout/failure'));
        }
    }

    private function checkIfNull($value) {
        if ($value == null || $value == "null") {
            return null;
        } else {
            return $value;
        }
    }

    //handle order status
    private function getOrderStatusId($status_code) {
        switch ($status_code) {
            case '10008':
                return $order_status_id = $this->config->get('payment_qpay_redirect_canceled_status_id');

            case '00000':
                return $order_status_id = $this->config->get('payment_qpay_redirect_completed_status_id');

            default:
                return $order_status_id = $this->config->get('payment_qpay_redirect_failed_status_id');
        }
    }

    // generate the secure hash
    private function generateSecureHash($array) {
        $authenticationToken = trim($this->config->get('payment_qpay_redirect_authentication_token'));
        ksort($array);
        $orderedString = $authenticationToken;

        // encoding parameters 
        $encoding_parameters = array();
        $encoding_parameters[] = "Response_StatusDescription";
        $encoding_parameters[] = "Response_GatewayStatusDescription";
        $encoding_parameters[] = "Response.StatusDescription";
        $encoding_parameters[] = "Response.GatewayStatusDescription";

        foreach ($array as $k => $param) {
            if (in_array($k, $encoding_parameters)) {
                $orderedString .= urlencode($param);
            } else {
                $orderedString .= $param;
            }
        }
        $secureHash = hash('sha256', $orderedString, false);
        return $secureHash;
    }

    private function addqpayTrxn($openCartOrderId) {
        $trxnId = "OC" . time() . rand(1000, 9999);
        $this->db->query("INSERT INTO `" . DB_PREFIX . "qpay_trxn`(`opencart_order_id`, `transaction_id`) VALUES ('" . $openCartOrderId . "','" . $trxnId . "')");
        return $trxnId;
    }

    private function getOpenCartOrderId($qpayTrxnId) {
        $query = $this->db->query("SELECT `opencart_order_id` FROM `" . DB_PREFIX . "qpay_trxn` WHERE  `transaction_id` ='" . $qpayTrxnId . "'");
        return $query->row['opencart_order_id'];
    }

    private function updateqpayTrxn($qpayTrxnId, $status, $ststusDesc) {
        $this->db->query("UPDATE `" . DB_PREFIX . "qpay_trxn` SET `status`='" . $status . "',`status_description`= '" . $ststusDesc . "' WHERE `transaction_id` ='" . $qpayTrxnId . "'");
    }

}

?>