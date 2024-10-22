<?php

namespace Svr\Logs\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use OpenAdminCore\Admin\Facades\Admin;
use OpenAdminCore\Admin\Controllers\AdminController;
use OpenAdminCore\Admin\Grid;
use OpenAdminCore\Admin\Show;
use OpenAdminCore\Admin\Layout\Content;
use Svr\Core\Models\SystemModules;
use Svr\Core\Models\SystemModulesActions;
use Svr\Logs\Models\LogsUsers;

class LogsUsersController extends AdminController
{
    protected $model;
    protected $model_obj;
    protected $title;
    protected $trans;
    protected $all_columns_obj;

    public function __construct()
    {
        $this->model = new LogsUsers();
        $this->model_obj = new $this->model;
        $this->trans = 'log.';
        $this->title = trans($this->trans.'logs_users_actions');
        $this->all_columns_obj = Schema::getColumns($this->model->getTable());
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return Admin::content(function (Content $content) {
            $content->header($this->title);
            $content->description(trans('admin.description'));
            $content->body($this->grid());
        });
    }

    /**
     * Show interface.
     *
     * @param string $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->title($this->title)
            ->description(trans('admin.show'))
            ->body($this->detail($id));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        $grid = new Grid($this->model_obj);

        $grid->fixColumns(-1);

        $grid->filter(function ($filter)
        {
            $filter->disableIdFilter();
            $filter->equal('log_id', __($this->trans.'log_id'));
            $filter->equal('user_id', __($this->trans.'user_id'));
            $filter->equal('token_id', __($this->trans.'token_id'));
            $filter->in('action_module', __($this->trans.'action_module'))->select(SystemModules::all()->pluck('module_slug', 'module_slug'));
            $filter->in('action_method', __($this->trans.'action_method'))->select(SystemModulesActions::all()->pluck('right_action', 'right_action'));

        });

        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableEdit();
        });

        foreach ($this->all_columns_obj as $key => $value) {
            $value_name = $value['name'];
            $value_label = $value_name;
            $trans = trans($this->trans . $value_name);

            match ($value_name) {
                // Индивидуальные настройки для отображения колонок:company_created_at, update_at, company_id
                'log_id' => $grid->column($value_name, 'ID')->sortable(),
                $this->model_obj->getCreatedAtColumn(), $this->model_obj->getUpdatedAtColumn() => $grid
                    ->column($value_name, $value_label)
                    ->display(function ($value) {return Carbon::parse($value);})
                    ->xx_datetime()
                    ->help($trans),

                // Отображение остальных колонок
                default => $grid->column($value_name, $value_label)->help($trans),
            };
        }

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(LogsUsers::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
        });

        foreach ($this->all_columns_obj as $key => $value) {
            $value_name = $value['name'];
            $value_label = $value_name;
            $trans = trans(strtolower($this->trans . $value_name));
            match ($value_name) {
                // Индивидуальные настройки для отображения полей:created_at, update_at
                $this->model_obj->getUpdatedAtColumn(), $this->model_obj->getCreatedAtColumn() => $show
                    ->field($value_name, $value_label)
                    ->xx_datetime()
                    ->xx_help(msg:$trans),
                // Отображение остальных полей
                default => $show
                    ->field($value_name, $value_label)
                    ->xx_help(msg:$trans),
            };
        }
        return $show;
    }
}
