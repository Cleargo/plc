<?php
namespace Cleargo\GiftCardEmailGetTitleDecode\Plugin;

class Template
{
    public function afterGetSubject(\Magento\Email\Model\Template $subject, $result) {
        return htmlspecialchars_decode((string)$result, ENT_QUOTES);
    }
}
?>