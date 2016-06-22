<?php

namespace Bithost\PowermailFastexport\Domain\Repository;

class AnswerRepository extends \In2code\Powermail\Domain\Repository\AnswerRepository
{
    public function findByMailUidsRaw(array $mailUids) {
        $query = $this->createQuery();

        $query->matching(
            $query->in('mail', $mailUids)
        );

        return $query->execute(true);
    }
}