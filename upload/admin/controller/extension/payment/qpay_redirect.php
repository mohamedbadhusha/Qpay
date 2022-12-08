<?php

class ControllerExtensionPaymentSmartRouteRedirect extends Controller {

    public function index() {
        $this->load->language('extension/payment/qpay_redirect');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('payment_qpay_redirect', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/qpay_redirect', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/payment/qpay_redirect', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['payment_qpay_redirect_URL'])) {
            $data['payment_qpay_redirect_URL'] = $this->request->post['payment_qpay_redirect_URL'];
        } else {
            $data['payment_qpay_redirect_URL'] = $this->config->get('payment_qpay_redirect_URL');
        }
        
        if (isset($this->request->post['payment_qpay_redirect_refund_URL'])) {
            $data['payment_qpay_redirect_refund_URL'] = $this->request->post['payment_qpay_redirect_refund_URL'];
        } else {
            $data['payment_qpay_redirect_refund_URL'] = $this->config->get('payment_qpay_redirect_refund_URL');
        }
        
        if (isset($this->request->post['payment_qpay_redirect_inquiry_URL'])) {
            $data['payment_qpay_redirect_inquiry_URL'] = $this->request->post['payment_qpay_redirect_inquiry_URL'];
        } else {
            $data['payment_qpay_redirect_inquiry_URL'] = $this->config->get('payment_qpay_redirect_inquiry_URL');
        }

        if (isset($this->request->post['payment_qpay_redirect_merchant_id'])) {
            $data['payment_qpay_redirect_merchant_id'] = $this->request->post['payment_qpay_redirect_merchant_id'];
        } else {
            $data['payment_qpay_redirect_merchant_id'] = $this->config->get('payment_qpay_redirect_merchant_id');
        }

        if (isset($this->request->post['payment_qpay_redirect_language'])) {
            $data['payment_qpay_redirect_language'] = $this->request->post['payment_qpay_redirect_language'];
        } else {
            $data['payment_qpay_redirect_language'] = $this->config->get('payment_qpay_redirect_language');
        }

        if (isset($this->request->post['payment_qpay_redirect_item_id'])) {
            $data['payment_qpay_redirect_item_id'] = $this->request->post['payment_qpay_redirect_item_id'];
        } else {
            $data['payment_qpay_redirect_item_id'] = $this->config->get('payment_qpay_redirect_item_id');
        }

        if (isset($this->request->post['payment_qpay_redirect_authentication_token'])) {
            $data['payment_qpay_redirect_authentication_token'] = $this->request->post['payment_qpay_redirect_authentication_token'];
        } else {
            $data['payment_qpay_redirect_authentication_token'] = $this->config->get('payment_qpay_redirect_authentication_token');
        }

        if (isset($this->request->post['payment_qpay_redirect_status'])) {
            $data['payment_qpay_redirect_status'] = $this->request->post['payment_qpay_redirect_status'];
        } else {
            $data['payment_qpay_redirect_status'] = $this->config->get('payment_qpay_redirect_status');
        }

        if (isset($this->request->post['payment_qpay_redirect_sort_order'])) {
            $data['payment_qpay_redirect_sort_order'] = $this->request->post['payment_qpay_redirect_sort_order'];
        } else {
            $data['payment_qpay_redirect_sort_order'] = $this->config->get('payment_qpay_redirect_sort_order');
        }

        if (isset($this->request->post['payment_qpay_redirect_pending_status_id'])) {
            $data['payment_qpay_redirect_pending_status_id'] = $this->request->post['payment_qpay_redirect_pending_status_id'];
        } else {
            $data['payment_qpay_redirect_pending_status_id'] = $this->config->get('payment_qpay_redirect_pending_status_id');
        }

        if (isset($this->request->post['payment_qpay_redirect_completed_status_id'])) {
            $data['payment_qpay_redirect_completed_status_id'] = $this->request->post['payment_qpay_redirect_completed_status_id'];
        } else {
            $data['payment_qpay_redirect_completed_status_id'] = $this->config->get('payment_qpay_redirect_completed_status_id');
        }
        if (isset($this->request->post['payment_qpay_redirect_refunded_status_id'])) {
            $data['payment_qpay_redirect_refunded_status_id'] = $this->request->post['payment_qpay_redirect_refunded_status_id'];
        } else {
            $data['payment_qpay_redirect_refunded_status_id'] = $this->config->get('payment_qpay_redirect_refunded_status_id');
        }
        if (isset($this->request->post['payment_qpay_redirect_failed_status_id'])) {
            $data['payment_qpay_redirect_failed_status_id'] = $this->request->post['payment_qpay_redirect_failed_status_id'];
        } else {
            $data['payment_qpay_redirect_failed_status_id'] = $this->config->get('payment_qpay_redirect_failed_status_id');
        }

        if (isset($this->request->post['payment_qpay_redirect_canceled_status_id'])) {
            $data['payment_qpay_redirect_canceled_status_id'] = $this->request->post['payment_qpay_redirect_canceled_status_id'];
        } else {
            $data['payment_qpay_redirect_canceled_status_id'] = $this->config->get('payment_qpay_redirect_canceled_status_id');
        }
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/qpay_redirect', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/qpay_redirect')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function order() {
        
        if ($this->config->get('payment_qpay_redirect_status')) {
            $this->load->language('extension/payment/qpay_redirect');

            if (isset($this->request->get['order_id'])) {
                $order_id = $this->request->get['order_id'];
            } else {
                $order_id = 0;
            }

            //get order details 
            $decimal_point = $this->language->get('decimal_point');
            $thousand_point = $this->language->get('thousand_point');
            $orderInfo = ControllerExtensionPaymentSmartRouteRedirect::getOrderDetails($this->request->get['order_id']);
            $currency_code = ControllerExtensionPaymentSmartRouteRedirect::getOrdercurrency($order_id);
            $decimal_place = $this->currency->getDecimalPlace($currency_code);
            $trxnTotalAmount = ControllerExtensionPaymentSmartRouteRedirect::getTrxnAmount($order_id);
            $trxnTotalAmount = str_replace($decimal_point, '', $this->currency->format($trxnTotalAmount, $currency_code, false, true));
            $trxnTotalAmount = str_replace($thousand_point, '', $trxnTotalAmount);
            $symbol_left = $this->currency->getSymbolLeft($currency_code);
            $symbol_right = $this->currency->getSymbolRight($currency_code);
            if ($symbol_left) {
                $trxnTotalAmount = substr($trxnTotalAmount, sizeof($symbol_left));
            }

            if ($symbol_right) {
                $i = strpos($trxnTotalAmount, $symbol_right);
                $trxnTotalAmount = substr($trxnTotalAmount, 0, $i);
            }

            $prevRefundedAmount = ControllerExtensionPaymentSmartRouteRedirect::getPrevRefundedAmount($order_id);
            $available_amount = $trxnTotalAmount - $prevRefundedAmount;

            for ($i = 0; $i < $decimal_place; $i++) {
                $available_amount = $available_amount / 10;
            }

            for ($i = 0; $i < $decimal_place; $i++) {
                $prevRefundedAmount = $prevRefundedAmount / 10;
            }

            $data['user_token'] = $this->session->data['user_token'];
            $data['order_id'] = $this->request->get['order_id'];
            $data['status'] = $orderInfo['status'];
            $data['status_desc'] = $orderInfo['status_description'];
            $data['transaction_id'] = $orderInfo['transaction_id'];
            $data['amount'] = $available_amount;
            $data['prev_refund_amount'] = $this->currency->format($prevRefundedAmount, $currency_code, false, true);
            $data['refundAction'] = $this->url->link('extension/payment/qpay_redirect/refund', 'user_token=' . $this->session->data['user_token'], true);
            $data['decimal_place'] = $decimal_place;
            unset($this->session->data['qpay_redirect']);

            return $this->load->view('extension/payment/qpay_order', $data);
        }
    }

    public function refund() {
        $this->load->language('extension/payment/qpay_redirect');

        $order_id = $_REQUEST['order_id'];
        $refund_amount = $_REQUEST['refund_amount'];

        $url = trim($this->config->get('payment_qpay_redirect_refund_URL'));
        
        $ch = curl_init($url);

        //prepare the request parameters
        $fields = array();
        $fields['MessageID'] = 4;
        $fields['MerchantID'] = trim($this->config->get('payment_qpay_redirect_merchant_id'));
        $fields['TransactionID'] = "OCR" . time() . rand(1000, 9999);
        $fields['OriginalTransactionID'] = ControllerExtensionPaymentSmartRouteRedirect::getSmartRouteTrxnId($order_id);

        $currencies_file = parse_ini_file(DIR_CATALOG . 'controller/extension/payment/currencies.ini');
        $currency_code = ControllerExtensionPaymentSmartRouteRedirect::getOrdercurrency($order_id);
        $decimal_point = $this->language->get('decimal_point');
        $thousand_point = $this->language->get('thousand_point');
        $amount = str_replace($decimal_point, '', $this->currency->format(number_format($refund_amount, 2), $currency_code, false, true));
        $amount = str_replace($thousand_point, '', $amount);

        $symbol_left = $this->currency->getSymbolLeft($currency_code);
        $symbol_right = $this->currency->getSymbolRight($currency_code);

        if ($symbol_left) {
            $amount = substr($amount, sizeof($symbol_left));
        }

        if ($symbol_right) {
            $i = strpos($amount, $symbol_right);
            $amount = substr($amount, 0, $i);
        }
        $fields['CurrencyISOCode'] = $currencies_file[$currency_code];
        $fields['Amount'] = $amount;
        $fields['SecureHash'] = ControllerExtensionPaymentSmartRouteRedirect::generateSecureHash($fields);

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
        
        //curl_getinfo — Get information regarding a specific transfer
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        //Returns the error number for the last cURL operation.
        //Returns the error number or 0 (zero) if no error occurred.
        if (curl_errno($ch) == 0 && $httpcode != 500) {
            
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
            if (isset($result['Response.OriginalTransactionID'])) {
                $res_data['Response.OriginalTransactionID'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($result['Response.OriginalTransactionID']);
            }

            if (isset($result['Response.StatusCode'])) {
                $res_data['Response.StatusCode'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($result['Response.StatusCode']);
            }

            if (isset($result['Response.StatusDescription'])) {
                $res_data['Response.StatusDescription'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($result['Response.StatusDescription']);
            }

            if (isset($result['Response.Amount'])) {
                $res_data['Response.Amount'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($result['Response.Amount']);
            }

            if (isset($result['Response.CurrencyISOCode'])) {
                $res_data['Response.CurrencyISOCode'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($result['Response.CurrencyISOCode']);
            }

            if (isset($result['Response.MerchantID'])) {
                $res_data['Response.MerchantID'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($result['Response.MerchantID']);
            }

            if (isset($result['Response.TransactionID'])) {
                $res_data['Response.TransactionID'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($result['Response.TransactionID']);
            }

            if (isset($result['Response.MessageID'])) {
                $res_data['Response.MessageID'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($result['Response.MessageID']);
            }

            //get optional params from response
            if (isset($result['Response.RRN'])) {
                $res_data['Response.RRN'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($result['Response.RRN']);
            }

            // get the secure hash
            $success = false;
            $res_secure_hash = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($result['Response.SecureHash']);
            $newSecureHash = ControllerExtensionPaymentSmartRouteRedirect::generateSecureHash($res_data);

            //test the securehash
            if ($newSecureHash == $res_secure_hash) {
                //$order_statusId = $this->config->get('payment_qpay_redirect_refunded_status_id');

                if ($res_data['Response.StatusCode'] == "00000") {
                    $order_statusId = $this->config->get('payment_qpay_redirect_refunded_status_id');
                    ControllerExtensionPaymentSmartRouteRedirect::addOrderHistory(ControllerExtensionPaymentSmartRouteRedirect::getOpenCartOrderId($res_data['Response.OriginalTransactionID']), $order_statusId);
                    ControllerExtensionPaymentSmartRouteRedirect::addRefundSmartRouteTrxn($order_id, $fields['TransactionID'], $res_data['Response.StatusCode'], $res_data['Response.StatusDescription'], $amount);
                    ControllerExtensionPaymentSmartRouteRedirect::updateOrderStatus($order_id, $order_statusId);
                    $success = true;
                } else if ($res_data['Response.StatusCode'] == "00023" || $res_data['Response.StatusCode'] == "00024" || $res_data['Response.StatusCode'] == "00028" || $res_data['Response.StatusCode'] == "00061") {
                    $order_statusId = $this->config->get('payment_qpay_redirect_failed_status_id');
                    ControllerExtensionPaymentSmartRouteRedirect::addOrderHistory(ControllerExtensionPaymentSmartRouteRedirect::getOpenCartOrderId($fields['OriginalTransactionID']), $order_statusId);
                    ControllerExtensionPaymentSmartRouteRedirect::updateOrderStatus($order_id, $order_statusId);
                }
            } else {
                //Inquiry Message
                $url = trim($this->config->get('payment_qpay_redirect_inquiry_URL'));
                
                $ch = curl_init($url);

                $params = array();
                $params['MessageID'] = 2;
                $params['MerchantID'] = trim($this->config->get('payment_qpay_redirect_merchant_id'));
                $params['OriginalTransactionID'] = $res_data['Response.TransactionID'];
                $params['SecureHash'] = ControllerExtensionPaymentSmartRouteRedirect::generateSecureHash($params);

                $params_string = "";
                foreach ($params as $key => $value) {
                    $params_string .= $key . '=' . $value . '&';
                }

                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                //for testing
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $execute = curl_exec($ch);
                
                //curl_getinfo — Get information regarding a specific transfer
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                
                //Returns the error number for the last cURL operation.
                //Returns the error number or 0 (zero) if no error occurred.
                if (curl_errno($ch) == 0 && $httpcode != 500) {

                    $response = array();
                    foreach (explode("&", $execute) as $parameter) {
                        $equalsIndex = strpos($parameter, "=");
                        $name = substr($parameter, 0, $equalsIndex);
                        $value = substr($parameter, $equalsIndex + 1);

                        $response[$name] = $value;
                    }
                    curl_close($ch);

                    $data = array();

                    //get required params from response
                    if (isset($response['Response.MessageStatus'])) {
                        $data['Response.MessageStatus'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.MessageStatus']);
                    }

                    if (isset($response['Response.StatusCode'])) {
                        $data['Response.StatusCode'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.StatusCode']);
                    }

                    if (isset($response['Response.StatusDescription'])) {
                        $data['Response.StatusDescription'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.StatusDescription']);
                    }

                    if (isset($response['Response.Amount'])) {
                        $data['Response.Amount'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.Amount']);
                    }


                    if (isset($response['Response.CurrencyISOCode'])) {
                        $data['Response.CurrencyISOCode'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.CurrencyISOCode']);
                    }

                    if (isset($response['Response.MerchantID'])) {
                        $data['Response.MerchantID'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.MerchantID']);
                    }

                    if (isset($response['Response.TransactionID'])) {
                        $data['Response.TransactionID'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.TransactionID']);
                    }

                    if (isset($response['Response.MessageID'])) {
                        $data['Response.MessageID'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.MessageID']);
                    }

                    if (isset($response['Response.ReversalStatus'])) {
                        $data['Response.ReversalStatus'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.ReversalStatus']);
                    }


                    //get optional params from response
                    if (isset($response['Response.GatewayStatusCode'])) {
                        $data['Response.GatewayStatusCode'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.GatewayStatusCode']);
                    }

                    if (isset($response['Response.GatewayStatusDescription'])) {
                        $data['Response.GatewayStatusDescription'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.GatewayStatusDescription']);
                    }

                    if (isset($response['Response.GatewayName'])) {
                        $data['Response.GatewayName'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.GatewayName']);
                    }

                    if (isset($response['Response.RRN'])) {
                        $data['Response.RRN'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.RRN']);
                    }

                    if (isset($response['Response.ApprovalCode'])) {
                        $data['Response.ApprovalCode'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.ApprovalCode']);
                    }

                    if (isset($response['Response.CardExpiryDate'])) {
                        $data['Response.CardExpiryDate'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.CardExpiryDate']);
                    }

                    if (isset($response['Response.CardHolderName'])) {
                        $data['Response.CardHolderName'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.CardHolderName']);
                    }

                    if (isset($response['Response.CardNumber'])) {
                        $data['Response.CardNumber'] = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.CardNumber']);
                    }

                    // get the secure hash
                    $response_secure_hash = ControllerExtensionPaymentSmartRouteRedirect::checkIfNull($response['Response.SecureHash']);
                    $secureHash = ControllerExtensionPaymentSmartRouteRedirect::generateSecureHash($data);

                    if ($secureHash == $response_secure_hash) {
                        if ($data['Response.StatusCode'] == "00000") {
                            ControllerExtensionPaymentSmartRouteRedirect::addOrderHistory(ControllerExtensionPaymentSmartRouteRedirect::getOpenCartOrderId($fields['OriginalTransactionID']), $this->config->get('payment_qpay_redirect_refunded_status_id'));
                            ControllerExtensionPaymentSmartRouteRedirect::addRefundSmartRouteTrxn($order_id, $data['Response.TransactionID'], $data['Response.StatusCode'], $res_data['Response.StatusDescription'], $amount);
                            ControllerExtensionPaymentSmartRouteRedirect::updateOrderStatus($order_id, $this->config->get('payment_qpay_redirect_refunded_status_id'));
                            $success = true;
                        } else if ($data['Response.StatusCode'] == "00023" || $data['Response.StatusCode'] == "00024" || $data['Response.StatusCode'] == "00028" || $data['Response.StatusCode'] == "00061") {
                            ControllerExtensionPaymentSmartRouteRedirect::addOrderHistory(ControllerExtensionPaymentSmartRouteRedirect::getOpenCartOrderId($fields['OriginalTransactionID']), $this->config->get('payment_qpay_redirect_failed_status_id'));
                            ControllerExtensionPaymentSmartRouteRedirect::updateOrderStatus($order_id, $this->config->get('payment_qpay_redirect_failed_status_id'));
                        }
                    } else {
                        // do nothing
                    }
                } else {
                   $success = false; 
                }
            }
         // end of inquiry
        } else {
            $success = false;
        } // end of curl_errno
        
        if ($success) {
            $data['text_success'] = $this->language->get('text_refund_success');
            $data['text_error'] = '';
        } else {
            $data['text_error'] = $this->language->get('text_error');
            $data['text_success'] = '';
        }
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_orders'),
            'href' => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] , true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_orders_info'),
            'href' => $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . "&order_id=".$order_id , true)
        );

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');


        $this->response->setOutput($this->load->view('extension/payment/qpay_redirect_refund', $data));
    }

    public function install() {
        $this->load->model('extension/payment/qpay_redirect');
        $this->model_extension_payment_qpay_redirect->install();
    }

    public function uninstall() {
//        $this->load->model('extension/payment/qpay_redirect');
//        $this->model_extension_payment_qpay_redirect->uninstall();
    }

    private function getTrxnAmount($qpay_order_id) {
        $this->load->model('extension/payment/qpay_redirect');
        return $this->model_extension_payment_qpay_redirect->getTrxnAmount($qpay_order_id);
    }

    private function getSmartRouteTrxnId($openCartOrderId) {
        $this->load->model('extension/payment/qpay_redirect');
        return $this->model_extension_payment_qpay_redirect->getSmartRouteTrxnId($openCartOrderId);
    }

    private function addRefundSmartRouteTrxn($openCartOrderId, $smartRouteTrxnId, $status, $statusDesc, $amount) {
        $this->load->model('extension/payment/qpay_redirect');
        return $this->model_extension_payment_qpay_redirect->addRefundSmartRouteTrxn($openCartOrderId, $smartRouteTrxnId, $status, $statusDesc, $amount);
    }

    private function getOrdercurrency($openCartOrderId) {
        $this->load->model('extension/payment/qpay_redirect');
        return $this->model_extension_payment_qpay_redirect->getOrdercurrency($openCartOrderId);
    }

    private function getOrderDetails($openCartOrderId) {
        $this->load->model('extension/payment/qpay_redirect');
        return $this->model_extension_payment_qpay_redirect->getOrderDetails($openCartOrderId);
    }

    private function getPrevRefundedAmount($openCartOrderId) {
        $this->load->model('extension/payment/qpay_redirect');
        return $this->model_extension_payment_qpay_redirect->getPrevRefundedAmount($openCartOrderId);
    }

    private function getOpenCartOrderId($smartRouteTrxnId) {
        $this->load->model('extension/payment/qpay_redirect');
        return $this->model_extension_payment_qpay_redirect->getOpenCartOrderId($smartRouteTrxnId);
    }

    private function addOrderHistory($openCartOrderId, $order_statusId) {
        $this->load->model('extension/payment/qpay_redirect');
        $this->model_extension_payment_qpay_redirect->addOrderHistory($openCartOrderId, $order_statusId);
    }

    private function updateSmartRouteTrxn($smartRouteTrxnId, $status, $ststusDesc) {
        $this->load->model('extension/payment/qpay_redirect');
        $this->model_extension_payment_qpay_redirect->updateSmartRouteTrxn($smartRouteTrxnId, $status, $ststusDesc);
    }

    private function updateOrderStatus($openCartOrderId, $order_statusId) {
        $this->load->model('extension/payment/qpay_redirect');
        $this->model_extension_payment_qpay_redirect->updateOrderStatus($openCartOrderId, $order_statusId);
    }

    // generate the secure hash
    private function generateSecureHash($array) {
        $authenticationToken = trim($this->config->get('payment_qpay_redirect_authentication_token'));
        ksort($array);
        $orderedString = $authenticationToken;

        // encoding parameters 
        $encoding_parameters = array();
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

    private function checkIfNull($value) {
        if ($value == null || $value == "null") {
            return null;
        } else {
            return $value;
        }
    }

}
