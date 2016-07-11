<?php

namespace Bithost\PowermailFastexport\Domain\Repository;

class AnswerRepository extends \In2code\Powermail\Domain\Repository\AnswerRepository
{
    /**
     * Constructs a new Repository
     *
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function __construct(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
        parent::__construct($objectManager);
        $this->objectType = 'In2code\\Powermail\\Domain\\Model\\Answer';
    }

    public function findByMailUidsRaw(array $mailUids) {
        $query = $this->createQuery();

        $query->matching(
            $query->in('mail', $mailUids)
        );

        return $query->execute(true);
    }
}