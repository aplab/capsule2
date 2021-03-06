<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */
// +---------------------------------------------------------------------------+
// | PHP version 5.4.7                                                         |
// +---------------------------------------------------------------------------+
// | Copyright (c) 2006-2014                                                   |
// +---------------------------------------------------------------------------+
// | 08.03.2014 3:32:18 YEKT 2014                                              |
// | Класс - type_description_here                                             |
// +---------------------------------------------------------------------------+
// | Author: Alexander Polyanin <polyanin@gmail.com>                           |
// +---------------------------------------------------------------------------+
//
// $Id$
/**
 * @package Capsule
 */

namespace App\Cms\Controller\Component;
use App\Cms\Controller\ReferenceController;
use App\Cms\Ui\ActionMenu\Url;
use Capsule\Component\Url\Redirect;
use Capsule\Tools\Tools;

/**
 * Named.php
 *
 * @package Capsule
 * @author Alexander Polyanin <polyanin@gmail.com>
 */
class SystemSettings extends Preferences
{
    protected $moduleClass = '\\App\\Cms\\Component\\SystemSettings';
}