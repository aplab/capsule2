<?php
/**
 * This file is part of the Capsule package.
 *
 * (c) Alexander Polyanin 2006 <polyanin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 04.11.2016
 * Time: 23:38
 */

namespace App\Cms\Ui\MainMenu;


class IconBs extends Icon
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}