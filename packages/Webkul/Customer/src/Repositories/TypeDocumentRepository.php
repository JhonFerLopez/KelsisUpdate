<?php

namespace Webkul\Customer\Repositories;

use Webkul\Core\Eloquent\Repository;

class TypeDocumentRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Customer\Contracts\TypeDocument';
    }

    /**
     * Returns guest group.
     *
     * @return object
     */
    public function getCustomerTypeDocument()
    {
        static $customerTypeDocument;

        if ($customerTypeDocument) {
            return $customerTypeDocument;
        }

        return $customerTypeDocument = $this->findOneByField('prefijo', 'name');
    }
}
