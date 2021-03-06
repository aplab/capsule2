<?php
/**
 * This file is part of the Capsule package.
 *
 * (c) Alexander Polyanin 2006 <polyanin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 11.12.2016
 * Time: 3:22
 */
$prefix = 'capsule-cms-object-editor' ?>
<div class="<?=$prefix?>-element <?=$prefix?>-element-image">
    <label class="<?=$prefix?>-label" for="<?=$prefix?>-element-<?=$this->id?>"><?=$this->property->name?></label>
    <div class="<?=$prefix?>-value">
        <div class="input-group">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button">
                    <span class="fa fa-upload"></span>
                </button>
            </span>
            <input type="text" class="form-control" placeholder=""
                   autocorrect="off" autocapitalize="none" autocomplete="off"
                   name="<?=$this->property->name?>"
                   id="<?=$prefix?>-element-<?=$this->id?>"
                   value="<?=\Capsule\Component\Utf8String::hsc($this->value)?>">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button">
                    <span class="fa fa-star"></span>
                </button>
                <button class="btn btn-default" type="button">
                    <span class="fa fa-history"></span>
                </button>
            </span>
        </div>
        <div class="thumbnail"><div class="preview"></div></div>
    </div>
</div>
