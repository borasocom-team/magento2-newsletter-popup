<?php

/**
 * @Author: Ngo Quang Cuong
 * @Date  :   2017-10-29 21:58:28
 * @Last  Modified by:   https://www.facebook.com/giaphugroupcom
 * @Last  Modified time: 2017-10-29 22:26:47
 */

/**
 * Newsletter subscribe block
 *
 * @author      GiaPhuGroup Ltd. <bestearnmoney87@gmail.com>
 */

namespace PHPCuong\Newsletter\Block;

use Magento\Customer\Model\Session;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutFactory;
use Magento\Newsletter\Model\Subscriber;
use PHPCuong\Newsletter\Helper\Data;

class Subscribe extends \Magento\Framework\View\Element\Template
{

    const NEWSLETTER_POPUP_COOKIE_NAME = 'newsletter_popup';

    protected $customerSession;
    protected $newsletterSubscriber;
    protected $cookieManager;
    protected $helper;
    protected $layoutFactory;

    public function __construct(
        Template\Context $context,
        Session $session,
        Subscriber $subscriber,
        CookieManagerInterface $cookie,
        Data $helper,
        LayoutFactory $layoutFactory,
        array $data = []
    ) {
        $this->customerSession      = $session;
        $this->newsletterSubscriber = $subscriber;
        $this->cookieManager        = $cookie;
        $this->helper               = $helper;
        $this->layoutFactory        = $layoutFactory;

        $customerId                = $this->customerSession->getCustomerId();
        $checkNewsletterSubscriber = false;

        $htmlFromCms               = $this->layoutFactory->create()->createBlock('Magento\Cms\Block\Block')
                                                         ->setBlockId(\PHPCuong\Newsletter\Helper\Data::CMS_BLOCK_ID)
                                                         ->toHtml();
        $this->popUpCookieFullName = \PHPCuong\Newsletter\Block\Subscribe::NEWSLETTER_POPUP_COOKIE_NAME . '_' . hash('md5', $htmlFromCms);

        if (is_numeric($customerId)) {
            $checkNewsletterSubscriber = $this->newsletterSubscriber->loadByCustomerId($customerId);
        }

        if ($checkNewsletterSubscriber && $checkNewsletterSubscriber->isSubscribed() && ! $this->helper->getOnlyCms()) {
            $this->cookieManager->setPublicCookie($this->popUpCookieFullName, 1);
        }

        parent::__construct($context, $data);
    }

    /**
     * Retrieve form action url and set "secure" param to avoid confirm
     * message when we submit form from secure page to unsecure
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('phpcuong_newsletter/subscriber/new', ['_secure' => true]);
    }

    public function isEnabled()
    {
        return $this->helper->getPopupEnabled();
    }

    public function onlyCms()
    {
        return $this->helper->getOnlyCms();
    }

}
