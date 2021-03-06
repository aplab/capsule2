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
 * Time: 23:30
 */

namespace App\Cms\Ui\MainMenu;


class Callback extends Action
{
    protected $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    public function JsonSerialize()
    {
        return [
            'type' => self::TYPE_CALLBACK,
            'callback' => $this->callback
        ];
    }
}