<?php 

class ControllerPaymentRayPay extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('payment/raypay');
		$this->load->model('setting/setting');

		$this->document->title = $this->language->get('heading_title');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

			$this->model_setting_setting->editSetting('raypay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('extension/payment'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['entry_user_id'] = $this->language->get('entry_user_id');
        $this->data['entry_marketing_id'] = $this->language->get('entry_marketing_id');
        $this->data['entry_sandbox'] = $this->language->get('entry_sandbox');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['help_encryption'] = $this->language->get('help_encryption');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];

		$this->document->breadcrumbs = array();

		$this->document->breadcrumbs[] = array(

			'href' => $this->url->https('common/home'),
			'text' => $this->language->get('text_home'),
			'separator' => false
		);

		$this->document->breadcrumbs[] = array(

			'href' => $this->url->https('extension/payment'),
			'text' => $this->language->get('text_payment'),
			'separator' => ' :: '
		);

		$this->document->breadcrumbs[] = array(

			'href' => $this->url->https('payment/raypay'),
			'text' => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->https('payment/raypay');
		$this->data['cancel'] = $this->url->https('extension/payment');

		if (isset($this->request->post['raypay_user_id'])) {

			$this->data['raypay_user_id'] = $this->request->post['raypay_user_id'];

		} else {

			$this->data['raypay_user_id'] = $this->config->get('raypay_user_id');
		}

        if (isset($this->request->post['raypay_marketing_id'])) {

            $this->data['raypay_marketing_id'] = $this->request->post['raypay_marketing_id'];

        } else {

            $this->data['raypay_marketing_id'] = $this->config->get('raypay_marketing_id');
        }

        if (isset($this->request->post['raypay_sandbox'])) {

            $this->data['raypay_sandbox'] = $this->request->post['raypay_sandbox'];

        } else {

            $this->data['raypay_sandbox'] = $this->config->get('raypay_sandbox');
        }

		if (isset($this->request->post['raypay_order_status_id'])) {

			$this->data['raypay_order_status_id'] = $this->request->post['raypay_order_status_id'];

		} else {

			$this->data['raypay_order_status_id'] = $this->config->get('raypay_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['raypay_status'])) {

			$this->data['raypay_status'] = $this->request->post['raypay_status'];

		} else {

			$this->data['raypay_status'] = $this->config->get('raypay_status');
		}

		if (isset($this->request->post['raypay_sort_order'])) {

			$this->data['raypay_sort_order'] = $this->request->post['raypay_sort_order'];

		} else {

			$this->data['raypay_sort_order'] = $this->config->get('raypay_sort_order');
		}

		$this->id = 'content';
		$this->template = 'payment/raypay.tpl';
		$this->layout = 'common/layout';

		$this->render();
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'payment/raypay')) {

			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!@$this->request->post['raypay_user_id']) {

			$this->error['warning'] = $this->language->get('error_user_id');
		}
        if (!@$this->request->post['raypay_marketing_id']) {

            $this->error['warning'] = $this->language->get('error_marketing_id');
        }

		if (!$this->error) {

			return true;

		} else {

			return false;
		}
	}
}