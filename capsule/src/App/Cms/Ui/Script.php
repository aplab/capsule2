<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */
// +---------------------------------------------------------------------------+
// | PHP version 5.4.7                                                         |
// +---------------------------------------------------------------------------+
// | Copyright (c) 2006-2013                                                   |
// +---------------------------------------------------------------------------+
// | 24.10.2013 0:48:28 YEKT 2013                                             |
// | Класс - type_description_here                                             |
// +---------------------------------------------------------------------------+
// | Author: Alexander Polyanin <polyanin@gmail.com>                           |
// +---------------------------------------------------------------------------+
//
// $Id$
/**
 * @package Capsule
 */

namespace App\Cms\Ui;

/**
 * Script.php
 *
 * @package Capsule
 * @author Alexander Polyanin <polyanin@gmail.com>
 */
/**
 * Class Script
 * @package App\Cms\Ui
 */
class Script
{
    /**
     * @var
     */
    private $src;

    /**
     * Script constructor.
     * @param $src
     */
    public function __construct($src)
    {
        $this->src = $src;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return'<script src="' . $this->src . '"></script>';
    }
}