<?php 
	class Tamda_Export_IndexController extends Mage_Core_Controller_Front_Action {
		public function customerAction() {
			$customer_id = Mage::app()->getRequest()->getParam('customer_id');
			$customer = Mage::getModel('customer/customer')->load($customer_id);
			$status = array(
				'Approved' => 1,
				'Pending' => 2,
				'Rejected' => 0
			);
			$data = array();
			if ($customer->getEntityId()) {
				$data['insert'] = array(
					"customer_id" => $customer->getEntityId(),	 
					"customer_group_id" => $customer->getGroupId(),
					"store_id" => 1,
					"fullname" => $customer->getFirstname() . ' ' . $customer->getLastname(),
					"firstname" => (string)$customer->getFirstname(),
					"lastname" => (string)$customer->getLastname(),
					"email" => $customer->getEmail(),
					"password" => $customer->getPasswordHash(),
					"address_id" => (string)$customer->getDefaultShipping(),
					"ip" => (string)$customer->getRegistrationRemoteIp(),
					"status" => (string)$status[$customer->getCustomerStatus()],
					"approved" => (string)$status[$customer->getCustomerStatus()],
					"safe" => (string)$status[$customer->getCustomerStatus()],
					"date_added" => $customer->getCreatedAt()
				);

				if ($addresses = $customer->getAddresses()) {
					$data['address'] = array();
					foreach ($addresses as $address) {
						$data['address'][] = array(
							"address_id" => (string)$address->getEntityId(),
							"customer_id" => $customer->getEntityId(),
							"fullname" => $address->getFirstname() . ' ' . $customer->getLastname(),
							"firstname" => (string)$address->getFirstname(),
							"lastname" => (string)$address->getLastname(),
							"telephone" => (string)$address->getTelephone(),
							"company" => (string)$address->getCompany(),
							"city" => (string)$address->getCity(),
							"postcode" => (string)$address->getPostcode(),
							"address" => (string)$address->getStreet()[0],
							"country" => 'Czech Republic',
							"zone" => (string)$address->getRegion(),
						);

						if ($address->getEntityId() == $data['insert']['address_id']) {
							$data['insert']['telephone'] = (string)$address->getTelephone();
						}
					}
				}

				$data['ip'] = array();
				if ($downline = $customer->getDownline()) {
					$data['ip'] = explode(',', $downline);
				}
			}
			
			$this->getResponse()->setHeader('Content-type', 'application/json');
			$this->getResponse()->setBody(json_encode($data));
			/*foreach ($customer->getAddresses() as $address) {
				var_dump($address->getFirstname());
			}*/
			/*$product_id = Mage::app()->getRequest()->getParam('product_id');
			$product = Mage::getModel('catalog/product')->load($product_id); 
			$insert = array(
				"category_id" => "", 
				"manufacturer_id" => "",
				"name" => $product->getName(),
				"slug" => $product->getUrlKey(),
				"description" => $product->getDescription(),
				"meta_title" => $product->getMetaTitle(),
				"meta_description" => $product->getMetaDescription(), 
				"meta_keyword" => $product->getMetaKeyword(), 
				"image" => "",
				"model" => "",
				"sku" => $product->getSku(),
				"upc" => "",
				"ean" => $product->getSEan(),
				"ks_krt" => "",
				"jan" => "",
				"isbn" => "",
				"mpn" => "",
				"qf" => "",
				"stock_status_id" => "",
				"quantity" => "",
				"price" => $product->getPrice(),
				"special_price" => $product->getSpecialPrice(),
				"special_price_date_start" => "",
				"special_price_date_stop" => "",
				"minimum" => "",
				"points" => "",
				"shipping" => "",
				"subtract" => "",
				"location" => "",
				"expiry_date" => "",
				"tax_class_id" => "",
				"width" => "",
				"height" => "",
				"weight_class_id" => 1,
				"weight" => $product->getCanNang(),
				"length_class_id" => "",
				"length" => "",
				"is_new" => "",
				"is_new_date_start" => $product->getNewsFromDate()news_from_date,
				"is_new_date_stop" => news_to_date,
				"viewed" => "",
				"viewed_weekly" => "",
				"viewed_monthly" => "",
				"ordered_weekly" => "",
				"ordered_monthly" => "",
				"date_available" => "",
				"status" => $product->getStatus(),
				"sort_order" => "",
			);*/
		}

		public function noteAction() {
			$note_id = Mage::app()->getRequest()->getParam('note_id');
			$note = Mage::getModel('customernotes/notes')->load($note_id);
			$data = array();
			if ($note->getNoteId()) {
				$data['insert'] = array(
					"customer_history_id" => $note->getNoteId(),
					"customer_id" => $note->getCustomerId(),
					"user_id" => $note->getUserId(),
					"comment" => $note->getNote(),
					"date_added" => $note->getCreatedTime(),
				);
			}

			$this->getResponse()->setHeader('Content-type', 'application/json');
			$this->getResponse()->setBody(json_encode($data));
		}
	}
?>