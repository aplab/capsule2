<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */
// +---------------------------------------------------------------------------+
// | PHP version 5.4.7                                                         |
// +---------------------------------------------------------------------------+
// | Copyright (c) 2006-2014                                                   |
// +---------------------------------------------------------------------------+
// | 11.05.2014 6:55:12 YEKT 2014                                              |
// | Класс - type_description_here                                             |
// +---------------------------------------------------------------------------+
// | Author: Alexander Polyanin <polyanin@gmail.com>                           |
// +---------------------------------------------------------------------------+
//
// $Id$
/**
 * @package Capsule
 */

namespace App\Cms\Controller;

use App\Cms\Ui\DataModel\DataGrid\DataGrid;
use App\Cms\Ui\SectionManager;
use App\Cms\View\ActionMenuView;
use App\Cms\View\DataGridView;
use App\Cms\View\MainMenuView;
use Capsule\Component\DataStruct\ReturnValue;
use Capsule\Component\Superglobals\Superglobals;
use Capsule\Tools\Tools;
use Capsule\Ui\Toolbar\Button;
use Capsule\Component\Url\Redirect;
use Capsule\I18n\I18n;
use Capsule\Ui\ObjectEditor\Oe;
use Capsule\Core\Fn;
use Capsule\DataModel\DataModel;
use Capsule\User\Env\Env;

/**
 * ReferenceController.php
 *
 * @package Capsule
 * @author Alexander Polyanin <polyanin@gmail.com>
 */
abstract class ReferenceController extends DefaultController
{
    /**
     *
     */
    const SAVE_AND_EXIT = 'saveAndExit';

    /**
     *
     */
    const SAVE_AND_ADD = 'saveAndAdd';

    /**
     *
     */
    const SAVE_AS_NEW = 'saveAsNew';

    /**
     * Класс модуля
     *
     * @var string
     */
    protected $moduleClass;

    /**
     * ReferenceController constructor.
     */
    protected function __construct()
    {
        parent::__construct();
        $this->moduleClass = Fn::cc($this->moduleClass);
    }

    /**
     * (non-PHPdoc)
     * @see \Capsule\Controller\AbstractController::handle()
     */
    public function handle()
    {
        $this->initSections();
        $this->initMainMenu();
        $this->initActionMenu();
        if (method_exists($this, $this->cmd)) {
            $this->{$this->cmd}();
        } else {
            $this->listItems();
        }
        $this->ui->onload->append(new MainMenuView($this->app->registry->mainMenu));
        $this->ui->onload->append(new ActionMenuView($this->app->registry->actionMenu));
        echo $this->ui->html;
    }

    /**
     *
     */
    protected function listItems()
    {
        $p = $this->pagination();
        $filter = $this->app->urlFilter;
        $toolbar = $this->app->registry->actionMenu;
        $toolbar->newMenuItem('New', new \App\Cms\Ui\ActionMenu\Url($filter($this->mod, 'add')));
        $toolbar->newMenuItem(
            'Delete selected',
            new \App\Cms\Ui\ActionMenu\Callback(
                $filter(
                    $this->mod,
                    'CapsuleUiDataGrid.getInstance("capsule-ui-datagrid").del()'
                )
            )
        );
        $c = $this->moduleClass;
        $config = $c::config();
        $title = $config->get('title') ?: 'untitled';
        $this->ui->title->prepend($title . '::List items');
        $data_grid = new DataGrid(
            'capsule-ui-datagrid',
            ($this->moduleClass)::config(),
            new \ArrayIterator(($this->moduleClass)::page($p->currentPage, $p->itemsPerPage))
        );
        $data_grid->itemsPerPageVariants = $p->itemsPerPageVariants;
        $data_grid->pagesNumber = $p->pagesNumber;
        $data_grid->currentPage = $p->currentPage;
        $data_grid->itemsPerPage = $p->itemsPerPage;
        $data_grid->baseUrl = ($this->app->urlFilter)($this->mod);
        SectionManager::getInstance()->content->append(new DataGridView($data_grid));
    }

    /**
     *
     */
    protected function add()
    {
        $filter = $this->app->urlFilter;
        $toolbar = $this->app->registry->toolbar;

        $button = new Button;
        $toolbar->add($button, 'save');
        $button->caption = 'Save';
        $button->icon = $this->app->config->icons->cms . '/disk.png';
        $button->action = 'CapsuleUiObjectEditor.getInstance("object_editor").save()';

        $button = clone $button;
        $toolbar->add($button, 'save and exit');
        $button->caption = 'Save and exit';
        $button->icon = $this->app->config->icons->cms . '/disk_go.png';
        $button->action = 'CapsuleUiObjectEditor.getInstance("object_editor").saveAndExit()';

        $button = clone $button;
        $toolbar->add($button, 'exit');
        $button->caption = 'Exit without saving';
        $button->url = $filter($this->mod);
        $button->icon = $this->app->config->icons->cms . '/arrow-return-180.png';
        $button->action = null;

        $button = clone $button;
        $toolbar->add($button, 'save and add new');
        $button->caption = 'Save and add new';
        $button->icon = $this->app->config->icons->cms . '/disk--plus.png';
        $button->action = 'CapsuleUiObjectEditor.getInstance("object_editor").saveAndAdd()';
        $button->url = null;

        $c = $this->moduleClass;
        $config = $c::config();
        $title = $config->get('title') ?: untitled;
        $this->ui->title->prepend($title . '::Add new');

        $class = $this->moduleClass;
        $tmp = $this->createElement($class);
        if ($tmp->status) {
            $oe = new Oe($tmp->item, 'object_editor');
            $this->ui->workplace->append(new View($oe));
            return;
        }
        if (isset(Post::getInstance()->{self::SAVE_AND_EXIT})) {
            Redirect::go($filter($this->mod));
            return;
        }
        if (isset(Post::getInstance()->{self::SAVE_AND_ADD})) {
            Redirect::go($filter($this->mod, 'add'));
            return;
        }
        Redirect::go($filter($this->mod, 'edit', $tmp->item->id));
    }

    /**
     * @param string $class
     * @return ReturnValue
     */
    protected function createElement($class)
    {
        $item = ($class instanceof DataModel) ? $class : new $class;
        $config = $class::config();
        $properties = $config->properties;
        $post = Post::getInstance();
        $ret = new ReturnValue;
        $ret->item = $item;
        foreach ($properties as $name => $property) {
            if ($class::isKey($name)) {
                continue;
            }
            if (!isset($property->formElement)) {
                continue;
            }
            if (!isset($post->$name)) {
                $ret->status = 1;
                return $ret;
            }
            try {
                $item->$name = $post->$name;
            } catch (\Exception $e) {
                $ret->status = 1;
                $this->ui->alert->append(I18n::_($e->getMessage()));
            }
        }
        if ($ret->status) {
            return $ret;
        }
        try {
            $item->store();
        } catch (\Exception $e) {
            $this->ui->alert->append(I18n::_($e->getMessage()));
            $ret->status = 1;
            return $ret;
        }
        $ret->status = 0;
        return $ret;
    }

    /**
     *
     */
    protected function edit()
    {
        $id = reset($this->param);
        $class = $this->moduleClass;
        $item = $class::getElementById($id);
        $filter = $this->app->urlFilter;
        if (!($item instanceof $class)) {
            $msg = 'Object not found';
            $this->ui->alert->append(I18n::_($msg));
            Redirect::go($filter($this->mod));
            return;
        }
        $toolbar = $this->app->registry->toolbar;

        $button = new Button;
        $toolbar->add($button, 'save');
        $button->caption = 'Save';
        $button->icon = $this->app->config->icons->cms . '/disk.png';
        $button->action = 'CapsuleUiObjectEditor.getInstance("object_editor").save()';

        $button = clone $button;
        $toolbar->add($button, 'save and exit');
        $button->caption = 'Save and exit';
        $button->icon = $this->app->config->icons->cms . '/disk_go.png';
        $button->action = 'CapsuleUiObjectEditor.getInstance("object_editor").saveAndExit()';

        $button = clone $button;
        $toolbar->add($button, 'exit');
        $button->caption = 'Exit without saving';
        $button->url = $filter($this->mod);
        $button->icon = $this->app->config->icons->cms . '/arrow-return-180.png';
        $button->action = null;

        $button = clone $button;
        $toolbar->add($button, 'save and add new');
        $button->caption = 'Save and add new';
        $button->icon = $this->app->config->icons->cms . '/disk--plus.png';
        $button->action = 'CapsuleUiObjectEditor.getInstance("object_editor").saveAndAdd()';
        $button->url = null;

        $button = clone $button;
        $toolbar->add($button, 'save as new');
        $button->caption = 'Save as new';
        $button->icon = $this->app->config->icons->cms . '/documents.png';
        $button->action = 'CapsuleUiObjectEditor.getInstance("object_editor").saveAsNew()';
        $button->url = null;

        $c = $this->moduleClass;
        $config = $c::config();
        $title = $config->get('title') ?: untitled;
        $this->ui->title->prepend($title . '::Edit');

        if (isset(Post::getInstance()->{self::SAVE_AS_NEW})) {
            $tmp = $this->copyItem($item);
            if (!$tmp->status) {
                Redirect::go($filter($this->mod, 'edit', $tmp->item->id));
                return;
            }
        }
        $tmp = $this->updateItem($item);
        if ($tmp->status) {
            $oe = new Oe($item, 'object_editor');
            $this->ui->workplace->append(new View($oe));
            return;
        }
        if (isset(Post::getInstance()->{self::SAVE_AND_EXIT})) {
            Redirect::go($filter($this->mod));
            return;
        }
        if (isset(Post::getInstance()->{self::SAVE_AND_ADD})) {
            Redirect::go($filter($this->mod, 'add'));
            return;
        }
        $oe = new Oe($item, 'object_editor');
        $this->ui->workplace->append(new View($oe));
    }

    /**
     * @param unknown $item
     * @param bool|string $deep
     * @return ReturnValue
     */
    protected function copyItem($item, $deep = false)
    {
        $copy = clone $item;
        $tmp = $this->createElement($copy);
        return $tmp;
    }

    /**
     * @param string $class
     * @return ReturnValue
     */
    protected function updateItem($item)
    {
        $config = $item::config();
        $properties = $config->properties;
        $post = Post::getInstance();
        $ret = new ReturnValue;
        $ret->item = $item;
        foreach ($properties as $name => $property) {
            if ($item::isKey($name)) {
                continue;
            }
            if (!isset($property->formElement)) {
                continue;
            }
            if (!isset($post->$name)) {
                $ret->status = 1;
                return $ret;
            }
            try {
                $item->$name = $post->$name;
            } catch (\Exception $e) {
                $ret->status = 1;
                $this->ui->alert->append(I18n::_($e->getMessage()));
            }
        }
        if (isset($ret->status) && $ret->status) {
            return $ret;
        }
        try {
            $item->store();
        } catch (\Exception $e) {
            $this->ui->alert->append(I18n::_($e->getMessage()));
            $ret->status = 1;
            return $ret;
        }
        $ret->status = 0;
        return $ret;
    }

    /**
     *
     */
    protected function del()
    {
        $post = Post::getInstance();
        $tmp = $post->get(__FUNCTION__, null);
        if (!$tmp) {
            return;
        }
        if (!is_array($tmp)) {
            $tmp[] = $tmp;
        }
        $ids = array();
        foreach ($tmp as $id) {
            if (ctype_digit($id)) {
                $ids[] = $id;
            }
        }
        if (empty($ids)) {
            return;
        }
        $class = $this->moduleClass;
        $class::del($ids);
        $filter = $this->app->urlFilter;
        Redirect::go($filter($this->mod));
    }

    /**
     * @return ReturnValue
     */
    protected function pagination()
    {
        $post = (new Superglobals())->post;
        $default_items_per_page = $this->app->config->defaultItemsPerPage;
        $items_per_page_variants = $this->app->config->itemsPerPageVariants;
        // init items per page
        $current_items_per_page = $this->env->get('itemsPerPage', $default_items_per_page);
        if (!in_array($current_items_per_page, $items_per_page_variants)) {
            $current_items_per_page = $default_items_per_page;
        }
        if (in_array($post->itemsPerPage, $items_per_page_variants)) {
            $current_items_per_page = $post->itemsPerPage;
            $this->env->set('itemsPerPage', $current_items_per_page);
        }
        $pages = $this->pages($current_items_per_page);
        // init current page
        $default_page = 1;
        $current_page = $this->env->get('currentPage', $default_page);
        if (!in_array($current_page, $pages)) {
            $current_page = $default_page;
        }
        if (in_array($post->pageNumber, $pages)) {
            $current_page = $post->pageNumber;
            $this->env->set('currentPage', $current_page);
        }
        $prev_page = $current_page - 1;
        if (!$prev_page) {
            $prev_page = null;
        }
        $next_page = $current_page + 1;
        $pages_number = sizeof($pages);
        if ($next_page > $pages_number) {
            $next_page = null;
        }
        $ret = new ReturnValue();
        $ret->previousPage = $prev_page;
        $ret->currentPage = $current_page;
        $ret->nextPage = $next_page;
        $ret->itemsPerPage = $current_items_per_page;
        $ret->pagesNumber = $pages_number;
        $ret->itemsPerPageVariants = $items_per_page_variants;
        return $ret;
    }

    /**
     * @param $current_items_per_page
     * @return mixed
     */
    protected function pages($current_items_per_page)
    {
        $class = $this->moduleClass;
        return $class::pages($current_items_per_page);
    }
}