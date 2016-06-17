<?php
namespace Bithost\PowermailFastexport\MockViewHelpers\Getter;

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