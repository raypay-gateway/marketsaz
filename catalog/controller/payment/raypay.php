<?php

class ControllerPaymentRayPay extends Controller
{
	protected function index()
	{
		$this->id = 'payment';

		$this->load->language('payment/raypay');
		$this->load->model('checkout/order');
		$this->load->library('encryption');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		// if ($this->currency->getCode() != 'RLS') {

		// 	$this->currency->set('RLS');
		// }

		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['return'] = $this->url->https('checkout/success');
		$this->data['cancel_return'] = $this->url->https('checkout/payment');

		$this->data['back'] = $this->url->https('checkout/payment');
		$amount =  @$this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], false);
		
		if($order_info['currency'] != "RLS" && $order_info['currency'] != "IRR" && $this->currency->getCode() != 'RLS'&& $this->currency->getCode() != 'IRR'){
			$amount = $amount * 10;
		}
		$subUrl = 'payment/raypay/callback&order_id='.$order_info['order_id'] . '&';
		$redirect = $this->url->http($subUrl);
		$redirectUrl = str_replace('&amp;', '&', $redirect);
		$invoice_id             = round(microtime(true) * 1000);
		$user_id = $this->config->get('raypay_user_id');
		$acceptor_code = $this->config->get('raypay_acceptor_code');
		$description = 'پرداخت  فروشگاه مارکت ساز با شماره سفارش ' . $order_info['order_id'];

		if (extension_loaded('curl')) {
			$params = array(
				'amount'       => strval($amount),
				'invoiceID'    => strval($invoice_id),
				'userID'       => $user_id,
				'redirectUrl'  => $redirectUrl,
				'factorNumber' => strval($order_info['order_id']),
				'acceptorCode' => $acceptor_code,
				'comment'      => $description
			);

			$result = $this->common('https://api.raypay.ir/raypay/api/v1/Payment/getPaymentTokenWithUserID', $params);
			$result = json_decode($result);

			if (isset($result->Data) && $result->StatusCode == 200) {
				$access_token = $result->Data->Accesstoken;
				$terminal_id  = $result->Data->TerminalID;
				$this->data['action'] = 'https://mabna.shaparak.ir:8080/Pay';
				$this->data['token'] = $access_token;
				$this->data['terminal_id'] = $terminal_id;

			} else {

				$code = isset($result->StatusCode) ? $result->StatusCode : 'Undefined';
				$message = isset($result->Message) ? $result->Message : $this->language->get('error_undefined');

				$this->data['error_warning'] = $this->language->get('error_request') . '<br/><br/>' . $this->language->get('error_code') . $code . '<br/>' . $this->language->get('error_message') . $message;
			}

		} else {

			$this->data['error_warning'] = $this->language->get('error_curl');
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'payment/raypay.tpl')) {

			$this->template = $this->config->get('config_template') . 'payment/raypay.tpl';

		} else {

			$this->template = 'marketsaz/template/payment/raypay.tpl';
		}

		$this->response->setOutput($this->render());
	}

	public function callback()
	{
		$this->data['error_warning'] = false;

		$this->load->language('payment/raypay');
		$this->load->model('checkout/order');

		if ($this->request->get['?invoiceID'] && $this->request->get['order_id']) {

			$order_id = $this->request->get['order_id'];
			$invoice_id = $this->request->get['?invoiceID'];
			$order_info = $this->model_checkout_order->getOrder($order_id);
			$params = array(
				'order_id' => $order_id
			);

			$result = $this->common('https://api.raypay.ir/raypay/api/v1/Payment/checkInvoice?pInvoiceID=' . $invoice_id, $params);
			$result = json_decode($result);

			if ( $result->StatusCode != 200) {
				$code = isset($result->StatusCode) ? $result->StatusCode : 'Undefined';
				$message = isset($result->Message) ? $result->Message : $this->language->get('error_undefined');

				$this->data['error_warning'] = $this->language->get('error_verify') . '<br/><br/>' . $this->language->get('error_code') . $code . '<br/>' . $this->language->get('error_message') . $message;

			} else {

				if (isset($result->Data) && $result->Data->State == 1) {
					$this->model_checkout_order->confirm($order_info['order_id'], $this->config->get('raypay_order_status_id'), $invoice_id);

				}
				else{
					$this->data['error_warning'] = $this->language->get('error_payment');
				}

			}
		}
					 else {
						 $this->data['error_warning'] = $this->language->get('error_data');

					}

		if ($this->data['error_warning'] && $this->data['error_warning'] != false) {

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {

				$this->data['base'] = HTTP_SERVER;

			} else {

				$this->data['base'] = HTTPS_SERVER;
			}

			$this->data['error_title'] = $this->language->get('error_title');
			$this->data['error_wait'] = sprintf($this->language->get('error_wait'), $this->url->https('checkout/cart'));
			$this->data['continue'] = $this->url->https('checkout/cart');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'payment/raypay_failure.tpl')) {

				$this->template = $this->config->get('config_template') . 'payment/raypay_failure.tpl';

			} else {

				$this->template = 'marketsaz/template/payment/raypay_failure.tpl';
			}

			$this->render();

		} else {

			$this->redirect($this->url->https('checkout/success'));
		}
	}

	function common($url, $params)
	{
		$options = array('Content-Type: application/json');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$options );
		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}
}
?>
