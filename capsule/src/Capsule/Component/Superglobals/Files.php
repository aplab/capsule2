<?php
/**
 * This file is part of the Capsule package.
 *
 * (c) Alexander Polyanin 2006 <polyanin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 22.11.2016
 * Time: 23:50
 */

namespace Capsule\Component\Superglobals;


use Capsule\Component\HttpRequest\DataSet;

/**
 * Class Files
 * @package Capsule\Component\Superglobals
 */
class Files extends DataSet
{
    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function get($name, $default = null)
    {
        return array_key_exists($name, $_FILES) ? $_FILES[$name] : $default;
    }
}