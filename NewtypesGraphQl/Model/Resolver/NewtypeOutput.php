<?php

declare(strict_types=1);

namespace Vendor\NewtypesGraphQl\Model\Resolver;

use DateTime;
use Exception;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Psr\Log\LoggerInterface;
use Vendor\NewtypesGraphQl\Model\Resolver\DataProvider\Newtypes as NewtypesDataProvider;

class NewtypeOutput implements ResolverInterface
{
    private const DATE_PARAM = 'date';

    /**
     * @param NewtypesDataProvider $newtypesDataProvider
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly NewtypesDataProvider $newtypesDataProvider,
        private readonly LoggerInterface $logger
    ){
    }

    /**
     * Changed: a missing "input" argument is rejected with a GraphQL input error instead of an undefined index
     * warning and a TypeError, which ended as an internal server error. ?array parameters: implicitly nullable
     * parameters are deprecated in PHP 8.4, supported by Magento 2.4.8.
     *
     * @inheritDoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ): array {
        if (!isset($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(__('"input" value should be specified.'));
        }

        $data = $this->cleanInput($args['input']);
        $this->validateInput($data);

        try {
            $result = $this->newtypesDataProvider->getData($data);
        } catch (Exception $e) {
            $this->logger->critical($e);

            throw new GraphQlInputException(
                __('An error occurred while processing your form. Please try again later.')
            );
        }

        return ['newtypes' => $result];
    }

    /**
     * @param string[] $input
     * @return string[]
     */
    public function cleanInput(array $input): array
    {
        $values = [];
        foreach ($input as $field => $value) {
            if (is_array($value)) {
                $cleanValue = $this->cleanInput($value);
            } else {
                // Changed: cast to string, trim() on an int or bool input is a TypeError under strict_types.
                $cleanValue = $value === null ? '' : trim((string)$value);
            }

            $values[$field] = $cleanValue;
        }

        return $values;
    }

    /**
     * @param string[] $input
     * @return void
     * @throws GraphQlInputException
     */
    public function validateInput(array $input): void
    {
        // Changed: a missing date is treated as invalid instead of raising an undefined index warning.
        if (!is_string($input[self::DATE_PARAM] ?? null) || !$this->isValidDate($input[self::DATE_PARAM])) {
            throw new GraphQlInputException(
                __('The Date format is invalid. Verify the Date value and try again.')
            );
        }
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isValidDate(string $value): bool
    {
        if (!empty($value)) {
            try {
                new DateTime($value);

                return true;
            } catch (Exception $e) {
            }
        }

        return false;
    }
}
