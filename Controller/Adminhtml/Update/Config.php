<?php

namespace Mgroup\ShippingRate\Controller\Adminhtml\Update;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
use Mgroup\ShippingRate\Helper\Data;
use mysql_xdevapi\Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class Config
 * @package Mgroup\ShippingRate\Controller\Adminhtml\Update
 */
class Config extends Action
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var CollectionFactory
     */
    protected $productCollection;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Config constructor.
     * @param Context $context
     * @param CollectionFactory $productCollection
     * @param ProductRepositoryInterface $productFactory
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollection,
        ProductRepositoryInterface $productRepository,
        Data $helper
    )
    {
        $this->productCollection = $productCollection;
        $this->helper = $helper;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    /**
     * execute js file data for all store & customer group
     * then redirect back to the system page
     */
    public function execute()
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/an6.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);

        try{
            $productCollection = $this->productCollection->create()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('am_shipping_peritem', array('null' => true))
                ->load();

            $configRate = $this->helper->getConfigShippingRate();

            foreach ($productCollection as $product) {
                $weight = $product->getWeight();
                $logger->info($product->getId());
//                if ($product->getId() == 956) {
//                    foreach (preg_split("/((\r?\n)|(\r\n?))/", $configRate) as $line) {
//                        $data = $this->multiExplode(array("-", ":"), $line);
//                        $logger->info("dooo");
//                        $logger->info((int)$data[0]);
//                        $logger->info(!is_null($data[0]));
//
//                        $logger->info((int)$data[1]);
//                        $logger->info(!empty($data[1]));
//
//                        $logger->info((int)$data[2]);
//
//                        if (!is_null($data[0]) && !is_null($data[1])) {
//                            if ($weight >= (int)$data[0] && $weight <= (int)$data[1] && !empty($data[2])) {
//                                $logger->info("test");
//                                $product->setAmShippingPeritemConfig((int)$data[2]);
//                                $this->productRepository->save($product);
//                            }
//                        } elseif (!empty($data[0]) && empty($data[1]) && $weight >= (int)$data[0]) {
//                            $product->setAmShippingPeritemConfig((int)$data[2]);
//                            $this->productRepository->save($product);
////                        $product->save();
//                        }
//                    }
//                }
            }
            $this->messageManager->addSuccessMessage(__('Update Config success.'));
        } catch (Exception $e){
            $this->messageManager->addErrorMessage(__('Update Config error.'));
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }

    /**
     * @param $delimiters
     * @param $string
     * @return array
     */
    function multiExplode($delimiters, $string)
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return $launch;
    }
}