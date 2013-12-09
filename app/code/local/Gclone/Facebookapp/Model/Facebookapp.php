<?php

class Gclone_Facebookapp_Model_Facebookapp extends Mage_Core_Model_Abstract {

    /**
     * Whether it is the page for share facebook before deal closed and enable a new deal.
     * @var
     */
    public $appToken;
    public $appId;
    public $myUrl;
    public $appSecret;
    public $shareTime;

    /**
     * Class constructor
     *
     */
    public function _construct() {
        parent::_construct();
        $this->_init('facebookapp/facebookapp');
    }

    /**
     * Initialize facebook sdk functions.
     * Get the application details from database.
     * And give permission to offline access.
     * Using this applications details to get access token.
     *
     */
    public function _init() {

    }

    /**
     * Function for send pdoduct update before deal closed.
     * Get the product details from database.
     * And give check product time and insert the updates to table.
     * After that share the details to facebook.
     *
     */
    public function sendProductupdate() {

        $this->appId = Mage::getStoreConfig('customer/facebook/api_id');

        $this->appSecret = Mage::getStoreConfig('customer/facebook/secret');

        $this->myUrl = Mage::getStoreConfig('customer/facebook/apppath');

        $this->shareTime = Mage::getStoreConfig('customer/facebook/share');

        require 'facebook/facebook.php';

        $facebook = new Facebook(array(
                    'appId' => "$this->appId",
                    'secret' => "$this->appSecret",
                    'cookie' => true,
                ));

        $loginUrl = $facebook->getLoginUrl(array(
                    'canvas' => 1,
                    'fbconnect' => 0,
                    'req_perms' => 'publish_stream,offline_access,manage_pages',
                    'next' => 'APP CANVAS URL IN FACEBOOK',
                    'cancel_url' => 'URL WHERE TO REDIRECT WHEN ACCESS NOT GRANTED',
                ));

        $tokenUrl = "https://graph.facebook.com/oauth/access_token?" .
                "client_id=" . $this->appId .
                "&client_secret=" . $this->appSecret .
                "&grant_type=client_credentials";

        $appToken = file_get_contents($tokenUrl);

        $page = explode('=', $appToken);

        $this->appToken = $page[1];

        $post = array();

        $_productCollection = Mage::getModel('catalog/product')->getCollection();

        $_productCollection->addFieldToFilter(array(array('attribute' => 'Status', 'eq' => '1'),));

        $timeRemaining = array();

        //Getting Admin Time Zone
        $currentTime = Mage_Core_Model_Locale::date(null, null, "en_US", true);

        $model = Mage::getModel('catalog/product'); //getting product model

        foreach ($_productCollection as $_product) {

            $_product = $model->load($_product->getId());

            $currentProduct = $_product->getId();

            $resource = Mage::getSingleton("core/resource");

            $currentdeal = $resource->getConnection('core_write');

            $tprefix = (string) Mage::getConfig()->getTablePrefix();

            $productTodate = $_product->getResource()->formatDate($_product->getspecial_to_date(), false);

            $productFromdate = $_product->getResource()->formatDate($_product->getspecial_from_date(), false);

            $toDate = strtotime($productTodate);

            $fromTime = strtotime($productFromdate);

            if (strtotime(date("Y-m-d", $fromTime)) < strtotime($currentTime)) {

                if (strtotime(date("Y-m-d", $toDate) . ' ' . $_product->getTime()) > strtotime($currentTime)) {

                    $endTime = strtotime(date("Y-m-d", $toDate) . ' ' . $_product->getTime()) - strtotime($currentTime);

                    $shareTime = $this->shareTime * 3600;

                    if ($endTime <= $shareTime) {

                        $resultproductid = $currentdeal->fetchRow("select product_id from " . $tprefix . "product_fb_time where product_id = '$currentProduct'");

                        if (!isset($resultproductid['product_id'])) {

                            $resultproductid = $currentdeal->query("INSERT INTO " . $tprefix . "product_fb_time (product_id,status) VALUES ('$currentProduct','1')");

                            $appToken = $this->appToken;

                            $picture = Mage::helper('catalog/image')->init($_product, 'image');

                            $post = array(
                                'name' => $_product->getName(),
                                'caption' => 'The deal will closed within ' . $this->shareTime . ' hours hurry up',
                                'description' => $_product->getDescription(),
                                'link' => $this->myUrl,
                                'access_token' => $this->appToken,
                                'picture' => "$picture",);

                            $result = $facebook->api('/' . $this->appId . '/feed/', 'post', $post);
                        }
                    }
                }
            }
        }
    }

    /**
     * Function for send pdoduct update After deal enabled.
     * Get the product details from database.
     * And give check product time and insert the updates to table.
     * After that share the details to facebook.
     *
     */
    public function getCurrentProducts() {

        $this->appId = Mage::getStoreConfig('customer/facebook/api_id');

        $this->appSecret = Mage::getStoreConfig('customer/facebook/secret');

        $this->myUrl = Mage::getStoreConfig('customer/facebook/apppath');

        $this->shareTime = Mage::getStoreConfig('customer/facebook/share');

        require 'facebook/facebook.php';

        $facebook = new Facebook(array(
                    'appId' => "$this->appId",
                    'secret' => "$this->appSecret",
                    'cookie' => true,
                ));

        $loginUrl = $facebook->getLoginUrl(array(
                    'canvas' => 1,
                    'fbconnect' => 0,
                    'req_perms' => 'publish_stream,offline_access,manage_pages',
                    'next' => 'APP CANVAS URL IN FACEBOOK',
                    'cancel_url' => 'URL WHERE TO REDIRECT WHEN ACCESS NOT GRANTED',
                ));

        $tokenUrl = "https://graph.facebook.com/oauth/access_token?" .
                "client_id=" . $this->appId .
                "&client_secret=" . $this->appSecret .
                "&grant_type=client_credentials";

        $appToken = file_get_contents($tokenUrl);

        $page = explode('=', $appToken);

        $this->appToken = $page[1];

        $post = array();

        $timezone = explode(" ", Mage::helper('core')->formatDate(null, 'long', true));

        $currentTime = Mage_Core_Model_Locale::date(null, null, "en_US", true);

        $resource = Mage::getSingleton("core/resource");

        $currentdeal = $resource->getConnection('core_write');

        $tprefix = (string) Mage::getConfig()->getTablePrefix();

        $_productCollection = Mage::getModel('catalog/product')->getCollection();

        $_productCollection->addFieldToFilter(array(array('attribute' => 'Status', 'eq' => '1'),));

        $model = Mage::getModel('catalog/product');

        $cityname = '';

        foreach ($_productCollection as $_product) {

            $_product = $model->load($_product->getId());

            $currentProduct = $_product->getId();

            $cities = $_product->getCity();

            $cities = explode(',', $cities);

            foreach ($cities as $city) {
                $cityId = $city;
            }

            $productTodate = $_product->getResource()->formatDate($_product->getspecial_to_date(), false);

            $productFromdate = $_product->getResource()->formatDate($_product->getspecial_from_date(), false);

            $fromTime = strtotime($productFromdate);

            $cityName = '';

            $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', '562'); // get the cities attribute id 548

            foreach ($attribute->getSource()->getAllOptions(true, true) as $option) {
                if ($cityId == $option['value']) {
                    $cityName = $option['label'];
                }
            }
            if ($fromTime < strtotime($currentTime)) {

                if (strtotime($productTodate . ' ' . $_product->getTime()) > strtotime($currentTime)) {

                    //Query to Checking whether the newsletter had already sent

                    $resultproductid = $currentdeal->fetchRow("select product_id from " . $tprefix . "product_fb_new where product_id = '$currentProduct'");

                    if (!isset($resultproductid['product_id'])) {

                        $resultproductid = $currentdeal->query("INSERT INTO " . $tprefix . "product_fb_new (product_id,status) VALUES ('$currentProduct','1')");

                        $picture = Mage::helper('catalog/image')->init($_product, 'image');

                        $post = array('name' => $_product->getName(),
                            'caption' => 'Today Deal on  ' . $cityName,
                            'description' => $_product->getDescription(),
                            'link' => $this->myUrl,
                            'access_token' => $this->appToken,
                            'picture' => "$picture",);
                        $result = $facebook->api('/' . $this->appId . '/feed/', 'post', $post);
                    }
                }
            }
        }
    }

}

