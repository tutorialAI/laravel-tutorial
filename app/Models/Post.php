<?php

namespace App\Models;

use App\Models\Scopes\AncientScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Post extends Model
{
    use HasFactory, Prunable;

    // Изменение дефолтных названий для колонок created_at и updated_at
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';

    /**
     * Первичный ключ таблицы БД.
     *
     * @var string
     */
    protected $primaryKey = 'post_id';

    /**
     * Указывает, что идентификаторы модели являются автоинкрементными.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Следует ли обрабатывать временные метки модели.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Формат хранения столбцов даты модели.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    protected $fillable = [
        'title',
        'description'
    ];

    // связь один ко многим: один пользователь у нескольких записей
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->withDefault(
                ['name' => 'Guest Author'] // можно передать параметры по умолчанию
            ); // вернет пустую модель User, если к модели Post не привязан ни один user.
        // это нужно чтобы не делать постоянные проверки на null
    }
}
