<?php

namespace Svr\Logs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Svr\Core\Models\SystemUsers;
use Svr\Core\Models\SystemUsersToken;
use Svr\Data\Models\DataApplicationsAnimals;

/**
 * Модель: Логи действия пользователя
 *
 * @package App\Models\logs
 */
class LogsUsers extends Model
{
    /**
     * Точное название таблицы с учетом схемы
     * @var string
     */
    protected $table								= 'logs.logs_users_actions';


    /**
     * Первичный ключ таблицы (автоинкремент)
     * @var string
     */
    protected $primaryKey						    = 'log_id';


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
        'user_id',                      // Идентификатор пользователя (system.users)
        'token_id',                     // Идентификатор токена (system.tokens)
        'action_module',                // Название модуля в таблице SYSTEM.SYSTEM_MODULES
        'action_method',                // Название метода в таблице SYSTEM.SYSTEM_MODULES_ACTIONS
        'action_data',                  // Данные запроса
        'action_created_at',            // дата создания записи
        'update_at',                    // дата обновления записи
    ];


    /**
     * Поля, которые нельзя менять сразу массивом
     * @var array
     */
    protected $guarded = [
        'log_herriot_requests_id',      // инкремент
    ];


    /**
     * Массив системных скрытых полей
     * @var array
     */
    protected $hidden								= [];

    /**
     * Создать запись
     *
     * @param Request $request
     *
     * @return void
     */
    public function logsUsersCreate(Request $request): void
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
    public function logsUsersUpdate(Request $request): void
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
        $systemUsers = new SystemUsers();
        $systemUsersToken = new SystemUsersToken();
        return [
            $this->primaryKey              => [
                $request->isMethod('put') ? 'required' : '',
                Rule::exists('.' . $this->getTable(), $this->primaryKey),
            ],
            'user_id' => [
                'required',
                Rule::exists('.' . $systemUsers->getTable(), $systemUsers->primaryKey)],
            'token_id' => [
                'required',
                Rule::exists('.' . $systemUsersToken->getTable(), $systemUsersToken->primaryKey)],
            'action_module' => 'required|string|max:32',
            'action_method' => 'required|string|max:64',
            'action_data' => 'nullable|string',
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
            'user_id' => trans('svr-core-lang::validation'),
            'token_id' => trans('svr-core-lang::validation'),
            'action_module' => trans('svr-core-lang::validation'),
            'action_method' => trans('svr-core-lang::validation'),
            'action_data' => trans('svr-core-lang::validation'),
        ];
    }
}
