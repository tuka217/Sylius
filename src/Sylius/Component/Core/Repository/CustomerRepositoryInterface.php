<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Core\Repository;

use Pagerfanta\Pagerfanta;
use Sylius\Component\Customer\Repository\CustomerRepositoryInterface as BaseCustomerRepositoryInterface;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
interface CustomerRepositoryInterface extends BaseCustomerRepositoryInterface
{
    /**
     * @param array $criteria
     * @param array $sorting
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator(array $criteria = null, array $sorting = null);
}
