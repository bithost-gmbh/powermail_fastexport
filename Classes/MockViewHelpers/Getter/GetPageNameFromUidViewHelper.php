<?php
namespace Bithost\PowermailFastexport\MockViewHelpers\Getter;

class GetPageNameFromUidViewHelper {

	/**
	 * pageRepository
	 *
	 * @var \In2code\Powermail\Domain\Repository\PageRepository
	 * @inject
	 */
	protected $pageRepository;

	/**
	 * View helper check if given value is array or not
	 *
	 * @param int $uid PID
	 * @return string Page Name
	 */
	public function render($uid = 0) {
		return $this->pageRepository->getPageNameFromUid($uid);
	}

}