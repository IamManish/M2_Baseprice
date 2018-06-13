<?php
namespace M2\Baseprice\Observer;

class Custompriceupdate implements \Magento\Framework\Event\ObserverInterface
{

    protected $_messageManager;
    
    protected $logger;
    
    /**
     *
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $_curl;
    
    /**
     *
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     */
    public function __construct(\Psr\Log\LoggerInterface $logger, \M2\Baseprice\Observer\Request $curl, \Magento\Framework\Message\ManagerInterface $messageManager)
    {
        $this->logger = $logger;
        $this->_curl = $curl;
        $this->_messageManager = $messageManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $item = $observer->getEvent()->getData('item');
        $item = ($item->getParentItem() ? $item->getParentItem() : $item);
        
        //If Product is simple then only execute it
        if($item->getProductType() != 'simple') {
            return false;
        }
        
        //Prepare Post data
        $data = array(
            'proId' => $item->getSku(),
            'lengthid' => "",
            'widthid' => "",
            "heightid" => "",
            "zoneid" => "",
        );
        
        //Prepare param for CURL request
        $_customOptions = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
        if(array_key_exists('options', $_customOptions)){
            //print_r($_customOptions);
            foreach($_customOptions['options'] as $option){
                //$this->logger->info(print_r($option));
                //$this->logger->debug(print_r($option));
                if(strtolower($option['label']) == 'length') {
                    $data['lengthid'] = $option['value'];
                } else if(strtolower($option['label']) == 'width') {
                    $data['widthid'] = $option['value'];
                } else if(strtolower($option['label']) == 'thickness') {
                    $data['heightid'] = $option['value'];
                } else if(strtolower($option['label']) == 'states') {
                    $data['zoneid'] = $option['value'];
                }
            }
        }
        
        $result = $this->_curl->callPriceRequest($data);
        
        if (is_array($result)) {
            $this->_messageManager->addError($result['message']); // For Error Message
            $item->getQuote()->setHasError(true);
        } else {
            $item->setCustomPrice($result);
            $item->setOriginalCustomPrice($result);
            $item->getProduct()->setIsSuperMode(true);
        }
    }
}