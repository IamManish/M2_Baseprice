<?php
namespace M2\Baseprice\Observer;

class SalesOrderBeforeSaveObserver implements \Magento\Framework\Event\ObserverInterface
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
        
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        
        if($order->canShip()) {
            
            $items = $order->getAllItems();
            foreach ($items as $item) {
                $item = ($item->getParentItem() ? $item->getParentItem() : $item);
                
                //If Product is simple then only execute it
                if($item->getProductType() != 'simple') {
                    return false;
                }
                
                $state = null;              
                $options = $item->getProductOptions();
                
                if (isset($options['options']) && !empty($options['options'])) {
                    foreach ($options['options'] as $option) {
                        if(strtolower($option['label']) == "states") {
                            $state = strtolower($option['value']);
                        }                        
                    }
                } 
            }
            
            if(is_null($state)) {
                return $this;
            }
            
            if (strtolower($order->getShippingAddress()->getRegion()) != $state) {
                throw new \Magento\Framework\Exception\LocalizedException(__('We cannot place this order. Please choose the state selected on product detail page.'));
            } 
        }
        
        return $this;
        
        
    }
}