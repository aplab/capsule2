<?php
/**
 * This file is part of the Capsule package.
 *
 * (c) Alexander Polyanin 2006 <polyanin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 31.10.2016
 * Time: 0:31
 *
 * include js files in the head section
 */
use \Capsule\Tools\Assets\Assets as a;
use \Capsule\Tools\Assets\Js as j;
$assets = new a;
$assets
    ->add(new j('/capsule/vendor/bower_components/jquery/dist/jquery.min.js'))
    ->add(new j('/capsule/vendor/bower_components/moment/min/moment.min.js'))
    ->add(new j('/capsule/vendor/bower_components/jquery-mousewheel/jquery.mousewheel.min.js'))
    ->add(new j('/capsule/vendor/bower_components/bootstrap/dist/js/bootstrap.min.js'))
    ->add(new j('/capsule/vendor/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'))
    ->add(new j('/capsule/vendor/bower_components/jquery-ui/jquery-ui.min.js'))
    ->add(new j('/capsule/vendor/bower_components/js-cookie/src/js.cookie.js'))
    ->add(new j('/capsule/vendor/bower_components/selectize/dist/js/standalone/selectize.min.js'))
    ->add(new j('/capsule/assets/modules/AplAccordionMenu/AplAccordionMenu.js', true))
    ->add(new j('/capsule/assets/modules/Scrollable/CapsuleUiScrollable.js', true))
    ->add(new j('/capsule/assets/cms/modules/CapsuleCmsActionMenu/CapsuleCmsActionMenu.js', true))
    ->add(new j('/capsule/assets/cms/modules/CapsuleCmsDataGrid/CapsuleCmsDataGrid.js', true))
    ->add(new j('/capsule/assets/cms/modules/CapsuleCmsObjectEditor/CapsuleCmsObjectEditor.js', true))
    ->add(new j('/capsule/vendor/bower_components/viewport-units-buggyfill/viewport-units-buggyfill.js'))
    ->add(new j('/capsule/vendor/bower_components/ckeditor/ckeditor.js'))
    ->add(new j('/capsule/vendor/bower_components/ckeditor/adapters/jquery.js'))
    ->add(new j('/capsule/vendor/bower_components/screenfull/dist/screenfull.min.js'))
    ->add(new j('/capsule/vendor/bower_components/clipboard/dist/clipboard.min.js'))
    ->add(new j('/capsule/assets/cms/modules/CapsuleCmsDialog/CapsuleCmsDialog.js', true))
    ->add(new j('/capsule/assets/cms/modules/CapsuleCmsFileUploader/CapsuleCmsFileUploader.js', true))
    ->add(new j('/capsule/assets/cms/modules/CapsuleCmsImageList/CapsuleCmsImageHistory.js', true))
    ->add(new j('/capsule/assets/cms/js/js.js', true));
$assets->putJs();
foreach ($this->js as $item) echo $item;