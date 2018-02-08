<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\Trashed;
use App\Admin\Extensions\Tools\RestoreBatch;
use App\Admin\Extensions\Tools\RestoreAction;
use App\Admin\Extensions\Tools\Release;
use App\Admin\Extensions\ExcelExporter;
use App\Models\ConcertHall;
use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ConcertHallController extends Controller
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

            $content->header(trans('admin/concertHall.indexHead'));
            $content->description(trans('admin/concertHall.indexDescription'));

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

            $content->header(trans('admin/concertHall.editHead'));
            $content->description(trans('admin/concertHall.editDescription'));

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

            $content->header(trans('admin/concertHall.createHead'));
            $content->description(trans('admin/concertHall.createDescription'));

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
        return Admin::grid(ConcertHall::class, function (Grid $grid) {

            if (request('trashed') == 1) {
                $grid->model()->onlyTrashed();
            }

            $grid->paginate(50);

            $grid->id('ID')->sortable();
            $grid->title(trans('admin/concertHall.title'))->editable();
            $grid->column('url')->urlWrapper();

            $grid->rate(trans('admin/concertHall.rate'))->display(function ($rate) {
                $html = "<i class='fa fa-star' style='color:#ff8913'></i>";

                return join('&nbsp;', array_fill(0, min(5, $rate), $html));
            })->sortable()->setAttributes(['width' => '100px']);

            if (request('trashed') != 1) {
                $grid->released(trans('admin.released'))->switch($this->getStates());
            }

            $grid->filter(function (Grid\Filter $filter) {
                $filter->disableIdFilter();

                $filter->where(function ($query) {

                    $query->where('title', 'like', "%{$this->input}%");

                }, trans('admin/concertHall.title'));
                $filter->between('rate', trans('admin/concertHall.rate'));
                $filter->between('created_at', trans('admin.created_at'))->datetime();
                $filter->between('updated_at', trans('admin.updated_at'))->datetime();
            });


            $grid->tools(function ($tools) {

                $tools->append(new Trashed());

                $tools->batch(function (Grid\Tools\BatchActions $batch) {

                    if (request('trashed') == 1) {
                        $batch->disableDelete();
                        $batch->add(trans('admin.restore'), new RestoreBatch());
                    } else {
                        $batch->add(trans('admin.released'), new Release(1));
                        $batch->add(trans('admin.unreleased'), new Release(0));
                    }
                });
            });

            $grid->actions(function ($actions) {
                if($actions->row->trashed()) {
                    $actions->disableDelete();
                    $actions->disableEdit();
                    $actions->append(new RestoreAction($actions->getKey()));
                }
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

        return Admin::form(ConcertHall::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('title', trans('admin/concertHall.title'))->rules('required');
//            $form->text('slug', trans('admin/concertHall.slug'))->rules('required');
            $form->editor('description', trans('admin/concertHall.description'));
            $form->editor('work_time', trans('admin/concertHall.workTime'));

            $form->divide();

            $form->url('url', trans('admin/concertHall.url'));
            $form->text('phone', trans('admin/concertHall.phone'));
            $form->text('address', trans('admin/concertHall.address'));
            $form->text('metro', trans('admin/concertHall.metro'));



            $form->number('rate', trans('admin/concertHall.rate'));
            $form->switch('released', trans('admin.released'))->states($this->getStates());
            $form->divide();

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));

            $form->saving(function ($form) {
                if($form->rate)
                    $form->rate = (float) preg_replace('/([^0-9\.])/', '', $form->rate);
            });
        });
    }

    public function release(Request $request)
    {
        foreach (ConcertHall::find($request->get('ids')) as $tarif) {
            $tarif->released = $request->get('action');
            $tarif->save();
        }
    }

    public function restore(Request $request)
    {
        $restored = ConcertHall::onlyTrashed()->find($request->get('ids'))->each->restore();
        if ($restored && $restored->count()) {
            return response()->json([
                'status'  => true,
                'message' => trans('admin.restore_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin.restore_failed'),
            ]);
        }
    }

    private function getStates()
    {
        return [
            'on'  => ['value' => '1', 'text' => trans('admin.yes'), 'color' => 'primary'],
            'off' => ['value' => '0', 'text' => trans('admin.no'), 'color' => 'default'],
        ];
    }
}
