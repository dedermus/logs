<?php

namespace Svr\Logs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Svr\Data\Models\DataApplicationsAnimals;

/**
 * Модель: лог отправки данных в хорриот
 *
 * @package App\Models\Logs
 */
class LogsHerriot extends Model
{
    /**
     * Точное название таблицы с учетом схемы
     * @var string
     */
    protected $table								= 'logs.log_herriot_requests';

    /**
     * Первичный ключ таблицы (автоинкремент)
     * @var string
     */
    protected $primaryKey						    = 'log_herriot_requests_id';

    /**
     * Поле даты создания строки
     * @var string
     */
    const CREATED_AT								= 'created_at';

    /**
     * Поле даты обновления строки
     * @var string
     */
    const UPDATED_AT								= 'updated_at';

    /**
     * Значения полей по умолчанию
     * @var array
     */
    protected $attributes                           = [];

    /**
     * @var array|string[]
     */
    protected array $dates
        = [
            'created_at',                   // Дата создания записи
            'updated_at',                   // Дата редактирования записи
        ];

    /**
     * Формат хранения столбцов даты модели.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Поля, которые можно менять сразу массивом
     * @var array
     */
    protected $fillable                     = [

        'application_animal_id',                            // id животного в заявке
        'application_request_herriot',                      // запрос в хорриот при отправке на регистрацию
        'application_response_herriot',                     // ответ от хорриот при отправке на регистрацию
        'application_request_application_herriot',          // запрос в хорриот для проверки статуса регистрации
        'application_response_application_herriot',         // ответ от хорриот для проверки статуса регистрации
        'update_at',                                        // дата обновления записи
    ];

    /**
     * Поля, которые нельзя менять сразу массивом
     * @var array
     */
    protected $guarded = [
        'log_herriot_requests_id',                          // инкремент
    ];

    /**
     * Создать запись
     *
     * @param Request $request
     *
     * @return void
     */
    public function logsHerriotCreate(Request $request): void
    {
        $this->validateRequest($request);
        $this->fill($request->all())->save();
    }

    /**
     * Обновить запись
     * @param Request $request
     *
     * @return void
     */
    public function logsHerriotUpdate(Request $request): void
    {
        $this->validateRequest($request);
        $data = $request->all();
        $id = $data[$this->primaryKey] ?? null;

        if ($id) {
            $modules_data = $this->find($id);
            if ($modules_data) {
                $modules_data->update($data);
            }
        }
    }

    /**
     * Валидация запроса
     *
     * @param Request $request
     *
     * @return void
     */
    private function validateRequest(Request $request): void
    {
        $rules = $this->getValidationRules($request);
        $messages = $this->getValidationMessages();
        $request->validate($rules, $messages);
    }

    /**
     * Получить правила валидации
     *
     * @param Request $request
     *
     * @return array
     */
    private function getValidationRules(Request $request): array
    {
        $id = $request->input($this->primaryKey);

        $dataApplicationsAnimals = new DataApplicationsAnimals();
        return [
            $this->primaryKey              => [
                $request->isMethod('put') ? 'required' : '',
                Rule::exists('.' . $this->getTable(), $this->primaryKey),
            ],
            'application_animal_id' => [
                'required',
                Rule::exists('.' . $dataApplicationsAnimals->getTable(), $dataApplicationsAnimals->primaryKey)],
            'application_request_herriot' => 'nullable|string',
            'application_response_herriot' => 'nullable|string',
            'application_request_application_herriot' => 'nullable|string',
            'application_response_application_herriot' => 'nullable|string',
        ];
    }

    /**
     * Получить сообщения об ошибках валидации
     *
     * @return array
     */
    private function getValidationMessages(): array
    {
        return [
            $this->primaryKey              => trans('svr-core-lang::validation.required'),
            'application_animal_id' => trans('svr-core-lang::validation'),
            'application_request_herriot' => trans('svr-core-lang::validation'),
            'application_response_herriot' => trans('svr-core-lang::validation'),
            'application_request_application_herriot' => trans('svr-core-lang::validation'),
            'application_response_application_herriot' => trans('svr-core-lang::validation'),
        ];
    }
}
