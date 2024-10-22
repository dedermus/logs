<?php

namespace Svr\Logs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель: лог отправки данных в хорриот
 *
 * @package App\Models\Logs
 */
class LogsHerriot extends Model
{
    use HasFactory;


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
     * Поле даты обновления строки
     * @var string
     */
    const UPDATED_AT                                = 'update_at';

    /**
     * Поле даты создания строки
     * @var string
     */
    const CREATED_AT                                = null;


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
        'log_herriot_requests_id',                          // инкремент
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
        'log_herriot_requests_id'
    ];


    /**
     * Массив системных скрытых полей
     * @var array
     */
    protected $hidden								= [];

}
