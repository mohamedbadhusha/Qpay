<?php
class ControllerPaymentQpay extends Controller {
  private $error = array();
 
  public function index() {
    $this->language->load('payment/Qpay');
    $this->document->setTitle('Qpay Payment Method Configuration');
    $this->load->model('setting/setting');
 
    if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
      $this->model_setting_setting->editSetting('Qpay', $this->request->post);
      $this->session->data['success'] = 'Saved.';
      $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }
 
    $this->data['heading_title'] = $this->language->get('heading_title');
    $this->data['entry_text_config_one'] = $this->language->get('text_config_one');
    $this->data['entry_text_config_two'] = $this->language->get('text_config_two');
    $this->data['button_save'] = $this->language->get('text_button_save');
    $this->data['button_cancel'] = $this->language->get('text_button_cancel');
    $this->data['entry_order_status'] = $this->language->get('entry_order_status');
    $this->data['text_enabled'] = $this->language->get('text_enabled');
    $this->data['text_disabled'] = $this->language->get('text_disabled');
    $this->data['entry_status'] = $this->language->get('entry_status');
 
    $this->data['action'] = $this->url->link('payment/Qpay', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
 
    if (isset($this->request->post['text_config_one'])) {
      $this->data['text_config_one'] = $this->request->post['text_config_one'];
    } else {
      $this->data['text_config_one'] = $this->config->get('text_config_one');
    }
        
    if (isset($this->request->post['text_config_two'])) {
      $this->data['text_config_two'] = $this->request->post['text_config_two'];
    } else {
      $this->data['text_config_two'] = $this->config->get('text_config_two');
    }
            
    if (isset($this->request->post['Qpay_status'])) {
      $this->data['Qpay_status'] = $this->request->post['Qpay_status'];
    } else {
      $this->data['Qpay_status'] = $this->config->get('Qpay_status');
    }
        
    if (isset($this->request->post['Qpay_order_status_id'])) {
      $this->data['Qpay_order_status_id'] = $this->request->post['Qpay_order_status_id'];
    } else {
      $this->data['Qpay_order_status_id'] = $this->config->get('Qpay_order_status_id');
    }
 
    $this->load->model('localisation/order_status');
    $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
    $this->template = 'payment/Qpay.tpl';
            
    $this->children = array(
      'common/header',
      'common/footer'
    );
 
    $this->response->setOutput($this->render());
  }
}
