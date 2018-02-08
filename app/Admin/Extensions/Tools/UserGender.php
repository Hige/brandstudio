<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class UserGender extends AbstractTool
{
    public function script()
    {
        $url = url(Request::fullUrlWithQuery(['gender' => '_gender_']));

        return <<<EOT

$('input:radio.user-gender').change(function () {

    var url = "$url".replace('_gender_', $(this).val());

    $.pjax({container:'#pjax-container', url: url });

});

EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $options = [
            'all'   => trans('admin.all'),
            'm'     => trans('admin.males'),
            'f'     => trans('admin.females'),
        ];

        $icon = [
            'all'   => '',
            'm'     => 'fa fa-male',
            'f'     => 'fa fa-female',
        ];

        return view('admin.tools.gender', compact('options'), compact('icon'));
    }
}
