<?php

/*
 * This file is part of the I18n Routing Bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\BenatEspina\I18nRoutingBundle\Repository;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface ResourceRepository
{
    public function getTranslatableBy($locale, array $criteria);
    public function getTranslationBy($locale, array $criteria);
}
