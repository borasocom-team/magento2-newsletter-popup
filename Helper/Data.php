<?php

namespace PHPCuong\Newsletter\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper{

    const POPUP_ENABLED = 'newsletter/popup/enabled';
    const ONLY_CMS = 'newsletter/popup/only_cms';

    const CMS_BLOCK_ID = 'newsletter.popup.upper.content';

    public function getPopupEnabled()
    {
        return $this->scopeConfig->getValue(
            self::POPUP_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function getOnlyCms()
    {
        return $this->scopeConfig->getValue(
            self::ONLY_CMS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}