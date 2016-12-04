<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */
// +---------------------------------------------------------------------------+
// | PHP version 5.4.7                                                         |
// +---------------------------------------------------------------------------+
// | Copyright (c) 2006-2014                                                   |
// +---------------------------------------------------------------------------+
// | 07.04.2014 5:40:15 YEKT 2014                                              |
// | Класс - type_description_here                                             |
// +---------------------------------------------------------------------------+
// | Author: Alexander Polyanin <polyanin@gmail.com>                           |
// +---------------------------------------------------------------------------+
//
// $Id$
/**
 * @package Capsule
 */

namespace App\Cms\Ui\DataModel\ObjectEditor\Element;

use Capsule\DataModel\DataModel;
use Capsule\Core\Fn;
use Capsule\DataModel\Config\Properties\FormElement;
use Capsule\User\Env;
/**
 * Bind.php
 *
 * @package Capsule
 * @author Alexander Polyanin <polyanin@gmail.com>
 */
class Bind extends Element
{
    public function __construct(DataModel $object, $name, $settings) {
        parent::__construct($object, $name, $settings);
        $this->data['bind'] = Fn::cc($this->property->bind, $object);
        $class = $this->data['bind'];
        $this->data['options'] = $class::optionsDataList();
        if ($settings instanceof FormElement) {
            $default = $settings->default;
            $this->data['default'] = Env::getInstance()->get($default);
        }
    }
}