<?php

namespace App\Admin\Extensions\Tools;

class Actions extends \Encore\Admin\Grid\Displayers\Actions
{
    /**
     * @var bool
     */
    protected $allowRestore = false;

    /**
     * Disable delete.
     *
     * @return void.
     */
    public function disableRestore()
    {

        $this->allowRestore = false;
    }

    public function enableRestore()
    {
        $this->allowRestore = false;
    }

    /**
     * Disable delete.
     *
     * @return void.
     */
    public function enableDelete()
    {
        $this->allowDelete = true;
    }

    /**
     * Disable edit.
     *
     * @return void.
     */
    public function enableEdit()
    {
        $this->allowEdit = true;
    }

    protected function restoreAction()
    {
        return <<<EOT
<a href="{$this->getResource()}/{$this->getKey()}/restore">
    <i class="fa fa-undo"></i>
</a>
EOT;
    }
//    protected function editAction()
//    {
//        return parent::editAction();
//    }
//
//    protected function deleteAction()
//    {
//        return parent::deleteAction();
//    }

    /**
     * {@inheritdoc}
     */
    public function display($callback = null)
    {
        if ($callback instanceof \Closure) {
            $callback->call($this, $this);
        }

        $actions = $this->prepends;
        if ($this->allowEdit) {
            array_push($actions, $this->editAction());
        }

        if ($this->allowDelete) {
            array_push($actions, $this->deleteAction());
        }
        if ($this->allowRestore) {
            array_push($actions, $this->restoreAction());
        }


        $actions = array_merge($actions, $this->appends);

        return implode('', $actions);
    }
}