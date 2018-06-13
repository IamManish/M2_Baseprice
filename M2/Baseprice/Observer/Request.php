<?php
namespace M2\Baseprice\Observer;

class Request
{

    protected $logger;

    /**
     *
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $_curl;
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    
    /**
     * Recipient email config path
     */
    const XML_PATH_URL = 'baseprice/general/url';

    /**
     *
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     */
    public function __construct(\Psr\Log\LoggerInterface $logger, \Magento\Framework\HTTP\Client\Curl $curl, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->logger = $logger;
        $this->_curl = $curl;
        $this->scopeConfig = $scopeConfig;
    }
    
    /**
     * Sample function returning config value
     **/
    
    public function getPriceUrl() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        
        return $this->scopeConfig->getValue(self::XML_PATH_URL, $storeScope);
        
        
    }

    public function callPriceRequest($params)
    {
        $url = $this->getPriceUrl();
        
        try {
           
            // if the method is post
            $this->_curl->post($url, $params);
            
            // response will contain the output in form of JSON string
            $response = $this->_curl->getBody();
            
            if($this->_curl->getStatus() == 200) {
                $result = json_decode($response);
                $price = (int)$result->discount;
                
                if($price > 0) {
                    return $price;
                } else {
                    return array(
                        "status" => $this->_curl->getStatus(),
                        "error" => 1,
                        "message" => "Sorry, something went wrong. Coundn't fetch price data from Api. Cannot add the product to shopping cart."
                    );
                }
            } else {
                return array(
                    "status" => $this->_curl->getStatus(),
                    "error" => 1,
                    "message" => "Sorry, something went wrong. Coundn't fetch price data from Api. Cannot add the product to shopping cart."
                );
            }
        } catch (\Exception $e) {
            return array(
                "status" => $this->_curl->getStatus(),
                "error" => 1,
                "message" => $e->getMessage()
            );
        }
        
        // $this->logger->info(($price->price));
        // $this->logger->debug($price->price);
    }
}