<?php

namespace Svr\Logs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель: Логи действия пользователя
 *
 * @package App\Models\logs
 */
class LogsUsers extends Model
{
    use HasFactory;


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
     * Поле даты обновления строки
     * @var string
     */
    const UPDATED_AT                                = 'update_at';

    /**
     * Поле даты создания строки
     * @var string
     */
    const CREATED_AT                                = 'action_created_at';


    /**
     * Значения полей по умолчанию
     * @var array
     */
    protected $attributes                           = [];


    /**
     * Поля, которые можно менять сразу массивом
     * @var array
     */
    protected $fillable                     = [
        'log_herriot_requests_id',      // инкремент
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
        'log_id'
    ];


    /**
     * Массив системных скрытых полей
     * @var array
     */
    protected $hidden								= [];

}
