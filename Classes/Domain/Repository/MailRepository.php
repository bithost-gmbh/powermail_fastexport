<?php

namespace Bithost\PowermailFastexport\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

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

class MailRepository extends \In2code\Powermail\Domain\Repository\MailRepository
{
    /**
     * Constructs a new Repository
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function __construct(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        parent::__construct($objectManager);
        $this->objectType = 'In2code\\Powermail\\Domain\\Model\\Mail';
    }

    /**
     * Find all mails in given PID (BE List)
     *
     * @param int $pid
     * @param array $settings TypoScript Config Array
     * @param array $piVars Plugin Variables
     * @return QueryResult
     */
    public function findAllInPidRaw($pid = 0, $settings = [], $piVars = [])
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        // initial filter
        $and = [
            $query->equals('deleted', 0),
            $query->equals('pid', $pid)
        ];

        // filter
        if (isset($piVars['filter'])) {
            foreach ((array)$piVars['filter'] as $field => $value) {

                // Standard Fields
                if (!is_array($value)) {
                    // Fulltext Search
                    if ($field === 'all' && !empty($value)) {
                        $or = [
                            $query->like('sender_name', '%' . $value . '%'),
                            $query->like('sender_mail', '%' . $value . '%'),
                            $query->like('subject', '%' . $value . '%'),
                            $query->like('receiver_mail', '%' . $value . '%'),
                            $query->like('sender_ip', '%' . $value . '%'),
                            $query->like('answers.value', '%' . $value . '%')
                        ];
                        $and[] = $query->logicalOr($or);
                    } elseif ($field === 'form' && !empty($value)) {
                        // Form filter
                        $and[] = $query->equals('form', $value);
                    } elseif ($field === 'start' && !empty($value)) {
                        // Time Filter Start
                        $and[] = $query->greaterThan('crdate', strtotime($value));
                    } elseif ($field === 'stop' && !empty($value)) {
                        // Time Filter Stop
                        $and[] = $query->lessThan('crdate', strtotime($value));
                    } elseif ($field === 'hidden' && !empty($value)) {
                        // Hidden Filter
                        $and[] = $query->equals($field, ($value - 1));
                    } elseif (!empty($value)) {
                        // Other Fields
                        $and[] = $query->like($field, '%' . $value . '%');
                    }
                }

                // Answer Fields
                if (is_array($value)) {
                    foreach ((array)$value as $answerField => $answerValue) {
                        if (empty($answerValue) || $answerField === 'crdate') {
                            continue;
                        }
                        $and[] = $query->equals('answers.field', $answerField);
                        $and[] = $query->like('answers.value', '%' . $answerValue . '%');
                    }
                }
            }
        }

        // create constraint
        $constraint = $query->logicalAnd($and);
        $query->matching($constraint);

        $query->setOrderings($this->getSorting($settings['sortby'], $settings['order'], $piVars));
        return $query->execute(true);
    }
}