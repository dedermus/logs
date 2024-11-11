<?php

namespace Svr\Logs\Controllers;

use OpenAdminCore\Admin\Form;
use Svr\Data\Models\DataApplicationsAnimals;
use Svr\Logs\Models\LogsHerriot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use OpenAdminCore\Admin\Facades\Admin;
use OpenAdminCore\Admin\Controllers\AdminController;
use OpenAdminCore\Admin\Grid;
use OpenAdminCore\Admin\Show;
use OpenAdminCore\Admin\Layout\Content;

class LogsHerriotController extends AdminController
{
    /**
     * Экземпляр класса модели
     * @var LogsHerriot
     */
    private LogsHerriot $logsHerriot;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->logsHerriot = new LogsHerriot();
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
            ->header(trans('svr-logs-lang::logs.log_herriot_requests.title'))
            ->description(trans('svr-logs-lang::logs.log_herriot_requests.description'))
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
            ->title(trans('svr-logs-lang::logs.log_herriot_requests.title'))
            ->description(trans('svr-logs-lang::logs.log_herriot_requests.create'))
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
            ->title(trans('svr-logs-lang::logs.log_herriot_requests.title'))
            ->description(trans('svr-logs-lang::logs.log_herriot_requests.edit'))
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
            ->title(trans('svr-logs-lang::logs.log_herriot_requests.title'))
            ->description(trans('svr-logs-lang::logs.log_herriot_requests.description'))
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
        $logHerriot = $this->logsHerriot;
        $grid = new Grid(new LogsHerriot());
        $grid->column('log_herriot_requests_id', __('svr-logs-lang::logs.log_herriot_requests.log_herriot_requests_id'))
            ->help(__('log_herriot_requests_id'))
            ->sortable();
        $grid->column('application_animal_id', __('svr-logs-lang::logs.log_herriot_requests.application_animal_id'))
            ->display(function ($application_animal_id) {
                $data = DataApplicationsAnimals::pluck('application_animal_id', 'application_animal_id');
                return ($data->get($application_animal_id, '') !== '') ? "($application_animal_id) $data[$application_animal_id]" : 'not found';
            })
            ->link(function ($value){
                return '/admin/data/svr_applications/'.$value['application_animal_id'];
            }, '_blank')
            ->help(__('application_animal_id'))
            ->sortable();
        $grid->column('application_request_herriot', __('svr-logs-lang::logs.log_herriot_requests.application_request_herriot'))
            ->help(__('application_request_herriot'))
            ->sortable();
        $grid->column(
            'application_response_herriot', __('svr-logs-lang::logs.log_herriot_requests.application_response_herriot')
        )
            ->help(__('application_response_herriot'))
            ->sortable();

        $grid->column(
            'application_request_application_herriot', __('svr-logs-lang::logs.log_herriot_requests.application_request_application_herriot')
        )
            ->help(__('application_request_application_herriot'))
            ->sortable();
        $grid->column(
            'application_response_application_herriot',
            __('svr-logs-lang::logs.log_herriot_requests.application_response_application_herriot')
        )
            ->help(__('application_response_application_herriot'))
            ->sortable();
        $grid->column('created_at', trans('svr-logs-lang::logs.log_herriot_requests.created_at'))
            ->help(__('created_at'))
            ->display(function ($value) use ($logHerriot) {
                return Carbon::parse($value)->timezone(config('app.timezone'))->format(
                    $logHerriot->getDateFormat()
                );
            })->sortable();
        $grid->column('updated_at', trans('svr-logs-lang::logs.log_herriot_requests.updated_at'))
            ->help(__('updated_at'))
            ->display(function ($value) use ($logHerriot) {
                return Carbon::parse($value)->timezone(config('app.timezone'))->format(
                    $logHerriot->getDateFormat()
                );
            })->sortable();

        $grid->filter(function ($filter)
        {
            $filter->disableIdFilter();
            $filter->equal('log_herriot_requests_id', __('svr-logs-lang::logs.log_herriot_requests_id'));
            $filter->where(function ($query)
            {
                $query->whereRaw("application_animal_id IN (SELECT application_animal_id FROM data.data_applications_animals WHERE animal_id = {$this->input})");
            }, 'Идентификатор животного', 'animal_id');
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
        $show = new Show($this->logsHerriot->findOrFail($id));
        $data = $this->logsHerriot->find($id)->toArray();
        $show->field('log_herriot_requests_id', __('svr-logs-lang::logs.log_herriot_requests.log_herriot_requests_id'));

        $show->field('application_animal_id', trans('svr-logs-lang::logs.log_herriot_requests.application_animal_id'))
            ->link('/admin/data/svr_applications/'.$data['application_animal_id'], '_blank');


        $show->field('application_request_herriot', __('svr-logs-lang::logs.log_herriot_requests.application_request_herriot'));
        $show->field(
            'application_response_herriot', __('svr-logs-lang::logs.log_herriot_requests.application_response_herriot')
        );
        $show->field(
            'application_request_application_herriot', __('svr-logs-lang::logs.log_herriot_requests.application_request_application_herriot')
        );
        $show->field(
            'application_response_application_herriot',
            __('svr-logs-lang::logs.log_herriot_requests.application_response_application_herriot')
        );
        $show->field('created_at', trans('svr-logs-lang::logs.log_herriot_requests.created_at'));
        $show->field('updated_at', trans('svr-logs-lang::logs.log_herriot_requests.updated_at'));

        return $show;
    }

    /**
     * Форма для создания/редактирования
     *
     * @return Form
     */
    protected function form():Form
    {
        $model = $this->logsHerriot;
        $form = new Form($this->logsHerriot);

        // 	Инкремент
        $form->display('log_herriot_requests_id', trans('svr-logs-lang::logs.log_herriot_requests.log_herriot_requests_id'))
            ->help(__('log_herriot_requests_id'));

        // Инкремент
        $form->hidden('log_herriot_requests_id', trans('svr-logs-lang::logs.log_herriot_requests.log_herriot_requests_id'))
            ->help(__('log_herriot_requests_id'));
        // id животного в заявке
        $form->select('application_animal_id', trans('svr-logs-lang::logs.log_herriot_requests.application_animal_id'))
            ->required()
            ->options(function($application_animal_id){
                return DataApplicationsAnimals::pluck('application_animal_id', 'application_animal_id');
            })
            ->help(__('application_animal_id'));

        // Запрос в хорриот при отправке на регистрацию
        $form->textarea('application_request_herriot', trans('svr-logs-lang::logs.log_herriot_requests.application_request_herriot'))
            ->help(__('application_request_herriot'));
        // Ответ от хорриот при отправке на регистрацию
        $form->textarea('application_response_herriot', trans('svr-logs-lang::logs.log_herriot_requests.application_response_herriot'))
            ->help(__('application_response_herriot'));
        // Запрос в хорриот для проверки статуса регистрации
        $form->textarea('application_request_application_herriot', trans('svr-logs-lang::logs.log_herriot_requests.application_request_application_herriot'))
            ->help(__('application_request_application_herriot'));
        // Ответ от хорриот для проверки статуса регистрации
        $form->textarea('application_response_application_herriot', trans('svr-logs-lang::logs.log_herriot_requests.application_response_application_herriot'))
            ->help(__('application_response_application_herriot'));
        // Дата создания записи
        $form->datetime('created_at', trans('svr-logs-lang::logs.log_herriot_requests.created_at'))
            ->disable()
            ->help(__('created_at'));
        // Дата удаления записи
        $form->datetime('updated_at', trans('svr-logs-lang::logs.log_herriot_requests.updated_at'))
            ->disable()
            ->help(__('updated_at'));

        $form->saving(function (Form $form) use ($model)
        {
            // создается текущая страница формы.
            if ($form->isCreating())
            {
                $model->logsHerriotCreate(request());
            }
            // обновляется текущая страница формы.
            if ($form->isEditing())
            {
                $model->logsHerriotUpdate(request());
            }
        });

        return $form;
    }
}
