<?php

declare(strict_types=1);

namespace Vendor\FreeCodes\Controller\Code;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Vendor\FreeCodes\Api\ClaimFreeCodeInterface;

/**
 * POST freecodes/code/claim, called by view/frontend/web/js/claim-code.js.
 *
 * The code is handed out here and not while the widget renders: the widget HTML is stored in the full page cache,
 * so a code rendered into it would be shown to every visitor. POST requests are never cached, and for
 * HttpPostActionInterface actions Magento validates the form key itself (CSRF protection).
 */
class Claim implements HttpPostActionInterface
{
    /**
     * Constructor
     *
     * @param RequestInterface $request
     * @param JsonFactory $jsonFactory
     * @param ClaimFreeCodeInterface $claimFreeCode
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly JsonFactory $jsonFactory,
        private readonly ClaimFreeCodeInterface $claimFreeCode
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(): Json
    {
        $result = $this->jsonFactory->create();

        if (!$this->request->isXmlHttpRequest()) {
            return $result->setHttpResponseCode(400)->setData(['message' => __('Invalid request.')]);
        }

        $code = $this->claimFreeCode->execute();
        if ($code === null) {
            return $result->setData(['code' => null, 'message' => __('Sorry, there are no codes left.')]);
        }

        return $result->setData(['code' => $code, 'message' => '']);
    }
}
