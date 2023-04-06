<?php
class TamaraPrestashopWebhookModuleFrontController extends ModuleFrontController
{

  public function postProcess()
  {
    PrestaShopLogger::addLog("Webhook Running");
    $mode = Tools::getValue('mode', Configuration::get('mode'));
    $prod = "https://api.tamara.co/";
    $order_status = '';
    $order_id = '';
$sandbox = "https://api-sandbox.tamara.co/";
    $endpoint = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     PrestaShopLogger::addLog("post");
    $raw_payload = file_get_contents('php://input');
        $payload = json_decode($raw_payload, true);

      //  $sql = 'SELECT `id_order`  FROM `' . _DB_PREFIX_ . 'tamara` WHERE `order_id`="'.$payload['order_id'].'"';
       // $id_order = Db::getInstance()->getValue($sql);
        if (is_array($payload)) {
            PrestaShopLogger::addLog(json_encode($payload));
            foreach($payload as $k => $v){
                PrestaShopLogger::addLog($k);
                if($k == 'order_id'){
                $order_id .= $v;
                }
            if($k == 'order_status') {
                $order_status .= $v;
                break ;
            }
            }
            PrestaShopLogger::addLog("order status is       .....".$order_status);
            if ($order_status == 'approved'){
                PrestaShopLogger::addLog('approvvveeeeed');
                PrestaShopLogger::addLog("mode is sssssss ".$mode);
                PrestaShopLogger::addLog("sandboxxxx ".$sandbox);
                 PrestaShopLogger::addLog("prodddd    " .$prod);
                PrestaShopLogger::addLog("order isdddddd  ".$order_id);
             if ( $mode == 1){
                $endpoint .= $sandbox . "orders/" . $order_id . "/authorise";
              } elseif($mode == 2) {
                $endpoint .= $prod . "orders/" . $order_id . "/authorise";
              } else {
                $endpoint .= $prod . "orders/" . $order_id . "/authorise";
              }
                PrestaShopLogger::addLog("before cuRLLLLLL");
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
            $result = curl_exec($ch);
            PrestaShopLogger::addLog("afterrr RESSS");

            curl_close($ch);
            PrestaShopLogger::addLog('resultddddddd');
            PrestaShopLogger::addLog("typf oof resuult". gettype($result));
               $sql = 'SELECT `id_order`  FROM `' . _DB_PREFIX_ . 'tamara` WHERE `order_id`="'.$order_id.'"';
        $id_order = Db::getInstance()->getValue($sql);
            $updateOrders = 'UPDATE ' . _DB_PREFIX_ . 'orders SET current_state = 2 WHERE id_order ='.$id_order;
            Db::getInstance()->execute($updateOrders);

            }elseif (($order_status == 'canceled') || ($order_status == 'declined') || ($order_status == 'expired')){
            $sql = 'SELECT `id_order`  FROM `' . _DB_PREFIX_ . 'tamara` WHERE `order_id`="'.$order_id.     '"';
       PrestaShopLogger::addLog("sql is .".$sql);
            $id_order = Db::getInstance()->getValue($sql);
            PrestaShopLogger::addLog("id order is ".$id_order);

                     $updateOrders = 'UPDATE ' . _DB_PREFIX_ . 'orders SET current_state = 8 WHERE id_order ="'.$id_order.'"';
             PrestaShopLogger::addLog("update is ".$updateOrders);
            Db::getInstance()->execute($updateOrders);
            return true;
            }else{
             PrestaShopLogger::addLog("none!!!!! ".$order_status);
            }
        }
    } else{
    PrestaShopLogger::addLog($_SERVER['REQUEST_METHOD']);
    }
  }
}