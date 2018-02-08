<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class Trashed extends AbstractTool
{
    protected function script()
    {
        $url  = Request::fullUrlWithQuery(['trashed' => '_trashed_']);

        return <<<EOT

$('.grid-status').click(function () {
    var status = $(this).find('input')[0].checked ? 0 : 1;
    $.pjax({container:'#pjax-container', url: "$url".replace('_trashed_', status) });
});

EOT;

    }

    public function render()
    {
        Admin::script($this->script());

        $trashed = (Request::get('trashed') == 1) ? 'active' : '';
        $checked = (Request::get('trashed') == 1) ? 'checked' : '';

        $trashedLabel = trans('admin.trashed');

        return <<<EOT

<a class="btn btn-bitbucket btn-sm grid-status $trashed">
    <input type="hidden" $checked />
    <i class="fa fa-trash"></i> $trashedLabel
</a>

EOT;
    }
}