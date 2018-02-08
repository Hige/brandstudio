<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\Trashed;
use App\Admin\Extensions\Tools\RestoreBatch;
use App\Admin\Extensions\Tools\RestoreAction;
use App\Admin\Extensions\Tools\Release;
use App\Admin\Extensions\ExcelExporter;
use App\Models\ConcertHall;
use App\Models\Event;
use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class EventController extends Controller
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

            $content->header(trans('admin/event.indexHead'));
            $content->description(trans('admin/event.indexDescription'));

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

            $content->header(trans('admin/event.editHead'));
            $content->description(trans('admin/event.editDescription'));

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

            $content->header(trans('admin/event.createHead'));
            $content->description(trans('admin/event.createDescription'));

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
        return Admin::grid(Event::class, function (Grid $grid) {

            if (request('trashed') == 1) {
                $grid->model()->onlyTrashed();
            }

            $grid->paginate(50);

            $grid->model()->with('concertHall')->orderBy('ID', 'asc');

            $grid->id('ID')->sortable();
            $grid->title(trans('admin/event.title'))->editable();
            $grid->start(trans('admin/event.start'))->editable('datetime');
            $grid->rate(trans('admin/event.rate'))->display(function ($rate) {
                $html = "<i class='fa fa-star' style='color:#ff8913'></i>";

                return join('&nbsp;', array_fill(0, min(5, $rate), $html));
            })->sortable()->setAttributes(['width' => '100px']);

            $grid->concert_hall_id(trans('admin/event.concertHall'))->display(function ($value) {
                return $this->concert_hall['id'];
            })
                ->editable('select', $this->getConcertHall())->display(function ($value) {
                    return "<span class=\"label label-success\">$value</span>";
                });

            if (request('trashed') != 1) {
                $grid->released(trans('admin.released'))->switch($this->getStates());
            }

            $grid->filter(function (Grid\Filter $filter) {
                $filter->disableIdFilter();

                $filter->where(function ($query) {

                    $query->where('title', 'like', "%{$this->input}%");

                }, trans('admin/event.title'));
                $filter->between('rate', trans('admin/event.rate'));
                $filter->in('concert_hall_id', trans('admin/event.concertHall'))->multipleSelect($this->getConcertHall());
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

        return Admin::form(Event::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('title', trans('admin/event.title'))->rules('required');
//            $form->text('slug', trans('admin/event.slug'))->rules('required');

            $form->editor('description', trans('admin/event.description'));

            $form->divide();


            $form->number('rate', trans('admin/event.rate'));
            $form->select('concert_hall_id', trans('admin/event.concertHall'))->options($this->getConcertHall());
            $form->datetime('start', trans('admin/event.start'));
            $form->datetime('end', trans('admin/event.end'));
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
        foreach (Event::find($request->get('ids')) as $tarif) {
            $tarif->released = $request->get('action');
            $tarif->save();
        }
    }

    public function restore(Request $request)
    {
        $restored = Event::onlyTrashed()->find($request->get('ids'))->each->restore();
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

    public function getConcertHall()
    {
        return ConcertHall::select()->where('released', '1')->orderBy('id', 'asc')->pluck('title', 'id');
    }
}
