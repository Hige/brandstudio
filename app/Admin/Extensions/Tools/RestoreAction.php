<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;



class RestoreAction
{

    /**
     * @var
     */
    protected $id;

    /**
     * Resource path of the grid.
     *
     * @var
     */
    protected $resourcePath;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Get current resource uri.
     *
     * @param string $path
     *
     * @return string
     */
    public function getResource($path = null)
    {
        if (!empty($path)) {
            $this->resourcePath = $path;

            return $this;
        }

        if (!empty($this->resourcePath)) {
            return $this->resourcePath;
        }

        return app('request')->getPathInfo();
    }

    public function script()
    {
        $restoreConfirm = trans('admin.restore_confirm');
        $confirm = trans('admin.confirm');
        $cancel = trans('admin.cancel');

        return <<<SCRIPT

$('.grid-row-restore').unbind('click').click(function() {

    var id = [$(this).data('id')];

    swal({
      title: "$restoreConfirm",
      type: "success",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "$confirm",
      closeOnConfirm: false,
      cancelButtonText: "$cancel"
    },
    function(){
        $.ajax({
            method: 'post',
            url: '{$this->getResource()}/restore',
            data: {
                _token:LA.token,
                ids: id
            },
            success: function (data) {
                $.pjax.reload('#pjax-container');

                if (typeof data === 'object') {
                    if (data.status) {
                        swal(data.message, '', 'success');
                    } else {
                        swal(data.message, '', 'error');
                    }
                }
            }
        });
    });
});

SCRIPT;

    }

    protected function render()
    {
        Admin::script($this->script());

        return <<<EOT
<a href="javascript:void(0);" data-id="{$this->id}" class="grid-row-restore">
    <i class="fa fa-undo"></i>
</a>
EOT;
        return "<a class='btn btn-xs btn-success fa fa-check grid-check-row' data-id='{$this->id}'></a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}
