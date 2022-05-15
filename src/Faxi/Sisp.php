<?php

namespace Faxi;

// to prevent browser cache the start transaction page
// if user go back and retry the payment it will
// generate a new form and everything will work fine
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

class Sisp
{
    public $posId;
    public $posAuthCode;
    public $lang = "en";

    public $apiBaseUrl = "https://mc.vinti4net.cv/BizMPIOnUsSisp";
    public $transactionPath = "/CardPayment";

    public $data = null;

    function __construct($posId, $posAuthCode, $apiUrl = null)
    {
        $this->posId = $posId;
        $this->posAuthCode = $posAuthCode;

        if(!empty($apiUrl))
            $this->apiBaseUrl = $apiUrl;
    }

    function buyForm($transaction_id, $amount, $callbackUrl)
    {
        $fields = [
            'transactionCode' => 1,
            'posID' => $this->posId,
            'merchantRef' => $transaction_id,
            'merchantSession' => "S" . date('YmdHms'),
            'amount' => $amount,
            'currency' => 132,
            'is3DSec' => 1,
            'urlMerchantResponse' => $callbackUrl,
            'languageMessages' => $this->lang,
            'timeStamp' => date('Y-m-d H:m:s'),
            'fingerprintversion' => '1',
            'entityCode' => '',
            'referenceNumber' => ''
        ];

        $fields['fingerprint'] = self::GerarFingerPrintEnvio(
            $this->posAuthCode, $fields['timeStamp'], $amount,
            $fields['merchantRef'], $fields['merchantSession'], $fields['posID'],
            $fields['currency'], $fields['transactionCode'], '', ''
        );

        $postUrl = $this->apiBaseUrl . $this->transactionPath . "?FingerPrint=" . urlencode($fields["fingerprint"]) . "&TimeStamp=" . urlencode($fields["timeStamp"]) . "&FingerPrintVersion=" . urlencode($fields["fingerprintversion"]); 
        
        return self::generateHtmlForm($postUrl, $fields);
    }

    function phoneRechargeForm($transaction_id, $amount, $phoneNumber, $operatorId, $callbackUrl)
    {
        $fields = [
            'transactionCode' => 3,
            'posID' => $this->posId,
            'merchantRef' => $transaction_id,
            'merchantSession' => "S" . date('YmdHms'),
            'amount' => $amount,
            'currency' => 132,
            'is3DSec' => 1,
            'urlMerchantResponse' => $callbackUrl,
            'languageMessages' => $this->lang,
            'timeStamp' => date('Y-m-d H:m:s'),
            'fingerprintversion' => '1',
            'entityCode' => $operatorId,
            'referenceNumber' => $phoneNumber
        ];

        $fields['fingerprint'] = self::GerarFingerPrintEnvio(
            $this->posAuthCode, $fields['timeStamp'], $amount,
            $fields['merchantRef'], $fields['merchantSession'], $fields['posID'],
            $fields['currency'], $fields['transactionCode'], $fields['entityCode'], $fields['referenceNumber']
        );

        $postUrl = $this->apiBaseUrl . $this->transactionPath . "?FingerPrint=" . urlencode($fields["fingerprint"]) . "&TimeStamp=" . urlencode($fields["timeStamp"]) . "&FingerPrintVersion=" . urlencode($fields["fingerprintversion"]); 
        
        return self::generateHtmlForm($postUrl, $fields);
    }

    function servicePaymentForm($transaction_id, $amount, $reference, $entity, $callbackUrl)
    {
        $fields = [
            'transactionCode' => 2,
            'posID' => $this->posId,
            'merchantRef' => $transaction_id,
            'merchantSession' => "S" . date('YmdHms'),
            'amount' => $amount,
            'currency' => 132,
            'is3DSec' => 1,
            'urlMerchantResponse' => $callbackUrl,
            'languageMessages' => $this->lang,
            'timeStamp' => date('Y-m-d H:m:s'),
            'fingerprintversion' => '1',
            'entityCode' => $entity,
            'referenceNumber' => $reference
        ];

        $fields['fingerprint'] = self::GerarFingerPrintEnvio(
            $this->posAuthCode, $fields['timeStamp'], $amount,
            $fields['merchantRef'], $fields['merchantSession'], $fields['posID'],
            $fields['currency'], $fields['transactionCode'], $fields['entityCode'], $fields['referenceNumber']
        );

        $postUrl = $this->apiBaseUrl . $this->transactionPath . "?FingerPrint=" . urlencode($fields["fingerprint"]) . "&TimeStamp=" . urlencode($fields["timeStamp"]) . "&FingerPrintVersion=" . urlencode($fields["fingerprintversion"]); 
        
        return self::generateHtmlForm($postUrl, $fields);
    }

    function refundForm($transaction_id, $amount, $clearingPeriod, $sisp_transaction_id, $callbackUrl)
    {
        $fields = [
            'transactionCode' => 4,
            'posID' => $this->posId,
            'merchantRef' => $transaction_id,
            'merchantSession' => "S" . date('YmdHms'),
            'amount' => $amount,
            'currency' => 132,
            'is3DSec' => 1,
            'urlMerchantResponse' => $callbackUrl,
            'languageMessages' => $this->lang,
            'timeStamp' => date('Y-m-d H:m:s'),
            'fingerprintversion' => '1',
            'entityCode' => '',
            'referenceNumber' => '',
            'reversal' => 'R',
            'clearingPeriod' => $clearingPeriod,
            'transactionID' => $sisp_transaction_id,
        ];

        $fields['fingerprint'] = self::GerarFingerPrintEnvio(
            $this->posAuthCode, $fields['timeStamp'], $amount,
            $fields['merchantRef'], $fields['merchantSession'], $fields['posID'],
            $fields['currency'], $fields['transactionCode'], '', ''
        );

        $postUrl = $this->apiBaseUrl . $this->transactionPath . "?FingerPrint=" . urlencode($fields["fingerprint"]) . "&TimeStamp=" . urlencode($fields["timeStamp"]) . "&FingerPrintVersion=" . urlencode($fields["fingerprintversion"]); 
        
        return self::generateHtmlForm($postUrl, $fields);
    }

    function onTransactionResult($successCallback, $errorCallback = null, $cancelledCallback = null)
    {
        $successMessageType = array('8', '10', 'P', 'M');

        /*
            8 - buy
            P - payment service
            M - phone recharge
            10 - refund
        */

        if(isset($_POST)) 
        {
            if(isset($_POST["messageType"]) && in_array($_POST["messageType"], $successMessageType))
            {
                $fingerPrintCalculado = self::GerarFingerPrintRespostaBemSucedida(
                    $this->posAuthCode, $_POST["messageType"] , $_POST["merchantRespCP"],
                    $_POST["merchantRespTid"] , $_POST["merchantRespMerchantRef"] , $_POST["merchantRespMerchantSession"] ,
                    $_POST["merchantRespPurchaseAmount"] , $_POST["merchantRespMessageID"] , $_POST["merchantRespPan"] ,
                    $_POST["merchantResp"] , $_POST["merchantRespTimeStamp"] , $_POST["merchantRespReferenceNumber"] ,
                    $_POST["merchantRespEntityCode"] , $_POST["merchantRespClientReceipt"] , trim($_POST["merchantRespAdditionalErrorMessage"]) ,
                    $_POST["merchantRespReloadCode"]
                );

                if($_POST["resultFingerPrint"] == $fingerPrintCalculado)
                {
                    $successCallback($_POST["merchantRespMerchantRef"], $_POST["merchantRespCP"], $_POST["merchantRespTid"]);
                }
                else
                {
                    $errorCallback($_POST["merchantRespMerchantRef"], "resultFingerPrint dont match", "", "");
                }
                
            }
            else if(isset($_POST["messageType"]) && $_POST["messageType"] == "6")
            {
                $errorCallback($_POST["merchantRespMerchantRef"], $_POST["merchantRespErrorDescription"], $_POST["merchantRespErrorDetail"], $_POST["merchantRespAdditionalErrorMessage"]);
            }
            else if(isset($_POST["UserCancelled"]) && $_POST["UserCancelled"] == "true")
            {
                $cancelledCallback();
            }
        }
        
    }

    static function generateHtmlForm($action_url, $fields)
    {
        $form = "<form action='$action_url' method='post'>";

        foreach ($fields as $key => $value) {
            $form .= "<input type='hidden' name='" . $key . "' value='" . $value . "'>";
        }

        $form .= "</form>";

        return $form;
    }

    static function GerarFingerPrintEnvio
        (
            $posAutCode, $timestamp, $amount,
            $merchantRef, $merchantSession, $posID,
            $currency, $transactionCode, $entityCode,
            $referenceNumber
        )
    {
        // REMOVER POSSIVEIS ZEROS A ESQUERDA
        if(!empty($entityCode))
            $entityCode = (int)$entityCode;

        if(!empty($referenceNumber))
            $referenceNumber = (int)$referenceNumber;

        // CONCATENAR OS DADOS PARA O HASH FINAL
        $toHash = base64_encode(hash('sha512', $posAutCode, true)) . $timestamp . ((int)((float)$amount * 1000))
                . $merchantRef . $merchantSession . $posID
                . $currency . $transactionCode . $entityCode . $referenceNumber
            ;

        return base64_encode(hash('sha512', $toHash, true));
    }

    static function GerarFingerPrintRespostaBemSucedida
        (
            $posAutCode, $messageType, $clearingPeriod,
            $transactionID, $merchantReference, $merchantSession,
            $amount, $messageID, $pan,
            $merchantResponse, $timestamp, $reference,
            $entity, $clientReceipt, $additionalErrorMessage,
            $reloadCode
        )
    {
        // REMOVER POSSIVEIS ZEROS A ESQUERDA
        if(!empty($reference))
            $reference = (int)$reference;

        if(!empty($entity))
            $entity = (int)$entity;
        
        // EFETUAR O CALCULO CONFORME A DOCUMENTACAO
        $toHash = base64_encode(hash('sha512', $posAutCode, true)) . $messageType . $clearingPeriod . $transactionID
                . $merchantReference . $merchantSession .
                ((int)((float)$amount * 1000)) . $messageID . $pan .
                $merchantResponse . $timestamp . $reference .
                $entity . $clientReceipt . $additionalErrorMessage .
                $reloadCode
            ;

        return base64_encode(hash('sha512', $toHash, true));
    }

}

?>