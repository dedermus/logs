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
use Svr\Core\Models\SystemUsers;
use Svr\Core\Models\SystemUsersToken;
use Svr\Data\Models\DataApplicationsAnimals;
use Svr\Logs\Models\LogsHerriot;
use Svr\Logs\Models\LogsUsers;

class LogsUsersController extends AdminController
{
    /**
     * Экземпляр класса модели
     */
    private LogsUsers $logsHrriot;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->logsUsers = new LogsUsers();
    }

    /**
     * Основной интерфейс.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content): Content
    {
        return $content
            ->header(trans('svr-logs-lang::logs.logs_users_action.title'))
            ->description(trans('svr-logs-lang::logs.logs_users_action.description'))
            ->body($this->grid());
    }

    /**
     * Интерфейс создания новой записи.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content): Content
    {
        return $content
            ->title(trans('svr-logs-lang::logs.logs_users_action.title'))
            ->description(trans('svr-logs-lang::logs.logs_users_action.create'))
            ->body($this->form());
    }

    /**
     * Edit interface.
     *
     * @param string  $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title(trans('svr-logs-lang::logs.logs_users_action.title'))
            ->description(trans('svr-logs-lang::logs.logs_users_action.edit'))
            ->row($this->form()->edit($id));
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
            ->title(trans('svr-logs-lang::logs.logs_users_action.title'))
            ->description(trans('svr-logs-lang::logs.logs_users_action.description'))
            ->body($this->detail($id));
    }

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Logs Herriot';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        $logUsers = $this->logsUsers;
        $grid = new Grid(new LogsUsers());
        $grid->column('log_id', __('svr-logs-lang::logs.logs_users_action.log_id'))
            ->help(__('log_id'))
            ->sortable();
        $grid->column('user_id', __('svr-logs-lang::logs.logs_users_action.user_id'))
            ->link(function ($value){
                return '/admin/core/users/'.$value['user_id'];
            }, '_blank')
            ->help(__('user_id'))
            ->sortable();
        $grid->column('token_id', __('svr-logs-lang::logs.logs_users_action.token_id'))
            ->link(function ($value){
                return '/admin/core/users_tokens/'.$value['token_id'];
            }, '_blank')
            ->help(__('token_id'))
            ->sortable();
        $grid->column('action_module', __('svr-logs-lang::logs.logs_users_action.action_module'))
//            ->link(function ($value){
//                return '/admin/core/modules/'.$value['action_module'];
//            }, '_blank')
            ->help(__('action_module'))
            ->sortable();
        $grid->column(
            'action_method', __('svr-logs-lang::logs.logs_users_action.action_method')
        )
//            ->link(function ($value){
//                return '/admin/core/rights/'.$value['action_method'];
//            }, '_blank')
            ->help(__('action_method'))
            ->sortable();


        $grid->column('created_at', trans('svr-logs-lang::logs.logs_users_action.created_at'))
            ->help(__('created_at'))
            ->display(function ($value) use ($logUsers) {
                return Carbon::parse($value)->timezone(config('app.timezone'))->format(
                    $logUsers->getDateFormat()
                );
            })->sortable();
        $grid->column('updated_at', trans('svr-logs-lang::logs.logs_users_action.updated_at'))
            ->help(__('updated_at'))
            ->display(function ($value) use ($logUsers) {
                return Carbon::parse($value)->timezone(config('app.timezone'))->format(
                    $logUsers->getDateFormat()
                );
            })->sortable();

        $grid->filter(function ($filter)
        {
            $filter->disableIdFilter();
            $filter->equal('log_id', __('svr-logs-lang::logs.logs_users_action.log_id'));
            $filter->equal('user_id', __('svr-logs-lang::logs.logs_users_action.user_id'));
            $filter->equal('token_id', __('svr-logs-lang::logs.logs_users_action.token_id'));
            $filter->in('action_module', __('svr-logs-lang::logs.logs_users_action.action_module'))->select(SystemModules::all()->pluck('module_slug', 'module_slug'));
            $filter->in('action_method', __('svr-logs-lang::logs.logs_users_action.action_method'))->select(SystemModulesActions::all()->pluck('right_action', 'right_action'));
        });

        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            $actions->disableEdit();
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show($this->logsUsers->findOrFail($id));
        $data = $this->logsUsers->find($id)->toArray();
        $show->field('log_id', __('svr-logs-lang::logs.logs_users_action.log_id'));

        $show->field('user_id', trans('svr-logs-lang::logs.logs_users_action.user_id'))
            ->link('/admin/core/users/'.$data['user_id'], '_blank');
        $show->field('token_id', __('svr-logs-lang::logs.logs_users_action.token_id'))
            ->link('/admin/core/users_tokens/'.$data['token_id'], '_blank');
        $show->field(
            'action_module', __('svr-logs-lang::logs.logs_users_action.action_module')
        );
        $show->field(
            'action_method', __('svr-logs-lang::logs.logs_users_action.action_method')
        );
        $show->field(
            'action_data',
            __('svr-logs-lang::logs.logs_users_action.action_data')
        );
        $show->field('created_at', trans('svr-logs-lang::logs.logs_users_action.created_at'));
        $show->field('updated_at', trans('svr-logs-lang::logs.logs_users_action.updated_at'));

        return $show;
    }
}
