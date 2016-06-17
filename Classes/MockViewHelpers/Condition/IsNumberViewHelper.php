<?php
namespace Bithost\Fastexport\MockViewHelpers\Condition;

class IsNumberViewHelper {
    /**
     * View helper check if given value is number or not
     *
     * @param mixed $val
     *
     * @return bool
     */
    public function render($val = null) {
        return is_numeric($val);
    }
}