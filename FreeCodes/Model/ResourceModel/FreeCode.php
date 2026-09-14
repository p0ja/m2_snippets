<?php

declare(strict_types=1);

namespace Vendor\FreeCodes\Model\ResourceModel;

use Magento\Framework\DB\Sql\Expression;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * The pool is read and updated with two queries; no model or collection is needed for that.
 */
class FreeCode extends AbstractDb
{
    public const TABLE_NAME = 'vendor_free_code';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'code_id');
    }

    /**
     * A free code, or null when the pool is empty
     *
     * @return array{code_id: string, code: string}|null
     */
    public function findFreeCode(): ?array
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ['code_id', 'code'])
            ->where('claimed_at IS NULL')
            ->order('code_id')
            ->limit(1);

        return $connection->fetchRow($select) ?: null;
    }

    /**
     * Mark the code as claimed if it is still free
     *
     * Two visitors can read the same free code at the same time. The condition "claimed_at IS NULL" in the UPDATE
     * lets only one of them change the row; the other gets 0 affected rows and has to look for another code.
     *
     * @param int $codeId
     * @return bool True when this call claimed the code.
     */
    public function claim(int $codeId): bool
    {
        $affectedRows = $this->getConnection()->update(
            $this->getMainTable(),
            ['claimed_at' => new Expression('CURRENT_TIMESTAMP')],
            ['code_id = ?' => $codeId, 'claimed_at IS NULL']
        );

        return $affectedRows === 1;
    }
}
