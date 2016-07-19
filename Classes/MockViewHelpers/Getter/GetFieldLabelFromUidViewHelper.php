<?php
namespace Bithost\PowermailFastexport\MockViewHelpers\Getter;

/***
 *
 * This file is part of the "Powermail FastExport" Extension for TYPO3 CMS.
 *
 *  (c) 2016 Markus Mächler <markus.maechler@bithost.ch>, Bithost GmbH
 *           Esteban Marin <esteban.marin@bithost.ch>, Bithost GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***/

class GetFieldLabelFromUidViewHelper {
    /**
     * fieldRepository
     *
     * @var \In2code\Powermail\Domain\Repository\FieldRepository
     * @inject
     */
    protected $fieldRepository;

    /**
     * Read Label of a field from given UID
     *
     * @param int $uid
     * @return string Label
     */
    public function render($uid) {
        $result = '';
        $field = $this->fieldRepository->findByUid($uid);

        if (method_exists($field, 'getTitle')) {
            $result = $field->getTitle();
        }

        return $result;
    }
}