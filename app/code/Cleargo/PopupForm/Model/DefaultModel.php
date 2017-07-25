<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cleargo\PopupForm\Model;

/**
 * Implementation of \Zend_Captcha
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class DefaultModel extends \Magento\Captcha\Model\DefaultModel
{
    public function isCorrect($word)
    {
        $storedWord = $this->getWord();
        if (!$word || !$storedWord) {
            return false;
        }

        if (!$this->isCaseSensitive()) {
            $storedWord = strtolower($storedWord);
            $word = strtolower($word);
        }
        return $word === $storedWord;
    }
}