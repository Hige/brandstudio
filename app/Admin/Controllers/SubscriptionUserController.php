<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\ExcelExporter;
use App\Models\Event;
use App\Models\ConcertHall;
use App\Models\SubscriptionUser;
use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;

class SubscriptionUserController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('admin/subscriptionUser.indexHead'));
            $content->description(trans('admin/subscriptionUser.indexDescription'));

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin/subscriptionUser.editHead'));
            $content->description(trans('admin/subscriptionUser.editDescription'));

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('admin/subscriptionUser.createHead'));
            $content->description(trans('admin/subscriptionUser.createDescription'));

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(SubscriptionUser::class, function (Grid $grid) {

            $grid->paginate(50);

            $grid->model()->with('event')->orderBy('ID', 'desc');

            $grid->id('ID')->sortable();
            $grid->name(trans('admin/subscriptionUser.name'))->setAttributes(['width' => '150px']);

//            $grid->birthday(trans('admin/subscriptionUser.birthday'))->sortable();
            $grid->birthday(trans('admin/subscriptionUser.birthday'))->display(function($date){
                if($date) {
                    return Carbon::parse($date)->toDateString();
                } else {
                    return '';
                }
            })->sortable();

            $grid->email(trans('admin/subscriptionUser.email'))->setAttributes(['width' => '150px']);
            $grid->phone(trans('admin/subscriptionUser.phone'))->setAttributes(['width' => '120px']);
            $grid->gender(trans('admin/subscriptionUser.gender'))->display(function($gender){
                if($gender == 'm') {
                    return "<i class=\"fa fa-mars\" title=\"". trans('admin.male') ."\"></i>";
                } else if($gender == 'f') {
                    return "<i class=\"fa fa-venus\" title=\"". trans('admin.female') ."\"></i>";
                } else {
                    return "<i class=\"fa fa-circle-thin\" title=\"". trans('admin.none') ."\"></i>";
                }
            })->setAttributes(['width' => '20px']);;

            $grid->event()->title(trans('admin/subscriptionUser.event'));
            $grid->event()->concert_hall(trans('admin/subscriptionUser.concertHall'))->get('title');
            $grid->created_at(trans('admin.created_at'))->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->disableIdFilter();

                $filter->where(function ($query) {

                    $query->where('name', 'like', "%{$this->input}%");

                }, trans('admin/subscriptionUser.name'));
                $filter->like('phone', trans('admin/subscriptionUser.email'));
                $filter->like('phone', trans('admin/subscriptionUser.phone'));
//                $filter->like('gender', trans('admin/subscriptionUser.gender'));
                $filter->in('gender', trans('admin/subscriptionUser.gender'))->multipleSelect($this->getGender());
                $filter->between('birthday', trans('admin/subscriptionUser.birthday'));
                $filter->in('event_id', trans('admin/subscriptionUser.event'))->multipleSelect($this->getEvents());
                $filter->between('created_at', trans('admin.created_at'))->datetime();
                $filter->between('updated_at', trans('admin.updated_at'))->datetime();
            });

            $grid->exporter(new ExcelExporter());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {

        return Admin::form(SubscriptionUser::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('name', trans('admin/subscriptionUser.name'))->rules('required');
            $form->email(trans('admin/subscriptionUser.email'));
            $form->text('phone', trans('admin/subscriptionUser.phone'));
            $form->select('gender', trans('admin/subscriptionUser.gender'))->options($this->getGender());
            $form->date('birthday', trans('admin/subscriptionUser.birthday'));
            $form->select('event_id', trans('admin/subscriptionUser.event'))->options($this->getEvents());

            $form->divide();

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));

            $form->saving(function ($form) {
                if($form->rate)
                    $form->rate = (float) preg_replace('/([^0-9\.])/', '', $form->rate);
            });
        });
    }

    private function getGender()
    {
        return [
            'm'  => trans('admin.male'),
            'f'  => trans('admin.female'),
            'null' => trans('admin.none'),
        ];

    }

    public function getEvents()
    {
        return Event::select()->where('released', '1')->orderBy('id', 'asc')->pluck('title', 'id');
    }
}
