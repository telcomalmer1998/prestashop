<?php

class tamaraprestashopCallbackModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {
        parent::initContent();
        $this->setTemplate('module:tamaraprestashop/views/templates/front/payment_infos.tpl');
    }

    public function postProcess()
    {
        $sql = 'SELECT `id_order`  FROM `' . _DB_PREFIX_ . 'tamara` WHERE `order_id`="'.$_GET['orderId'].'"';
        $id_order = Db::getInstance()->getValue($sql);
        $this->context->smarty->assign(['id_order' => $id_order,
        'status' => $_GET['paymentStatus']
        ]);

        $mode = Tools::getValue('mode', Configuration::get('mode'));
    $prod = "https://api.tamara.co/";
    $sandbox = "https://api-sandbox.tamara.co/";

        if(isset($_GET['paymentStatus']) && $_GET['paymentStatus'] == 'approved') {
            PrestaShopLogger::addLog("11111111111111");

            $endpoint = "";
        if ( $mode == 1){
            $endpoint .= $sandbox . "orders/" . $_GET['orderId'] . "/authorise";
          } elseif($mode == 2) {
            $endpoint .= $prod . "orders/" . $_GET['orderId'] . "/authorise";
          } else {
            $endpoint .= $prod . "orders/" . $_GET['orderId'] . "/authorise";
          }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer '.Tools::getValue('api_token', Configuration::get('api_token')),
            )
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);
        PrestaShopLogger::addLog("first CONDITION");

        $updateOrders = 'UPDATE ' . _DB_PREFIX_ . 'orders SET current_state = 2 WHERE id_order ='.$id_order;
        Db::getInstance()->execute($updateOrders);
        } elseif (($_GET['paymentStatus'] == 'declined') || ($_GET['paymentStatus'] == 'expired') || ($_GET['paymentStatus'] == 'canceled')) {
            PrestaShopLogger::addLog("2nd CONDITION ");
        $updateOrders = 'UPDATE ' . _DB_PREFIX_ . 'orders SET current_state = 8 WHERE id_order ="'.$id_order.'"';
        Db::getInstance()->execute($updateOrders);
        return true;
       }
        $this->setTemplate('module:tamaraprestashop/views/templates/front/payment_infos.tpl');
    }
}
