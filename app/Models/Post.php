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

    /*
     * Периодическое удаление (pruning) старых записей
     * Также нужно в App/Console/Kernel.php в методе schedule указать переодичность выполнения
     * Или запускать команду из консоли: php artisan model:prune --pretend
     * (флаг не запускает команду, показывает данные которых это будет затронуто)
     * */
    public function prunable()
    {
        return static::where('creation_date', '>', now()->year . '-01-01');
    }

    /**
     * Этот метод будет вызван перед удалением модели.
     * Этот метод может быть полезен для удаления любых дополнительных ресурсов, связанных с моделью,
     * таких как хранимые файлы, до того, как модель будет окончательно удалена из базы данных:
     *
     * @return void
     */
    protected function pruning()
    {
        //
    }

    /*
    * Применение глобальных диапазонов
     * Чтобы игнорировать глбальный дипазон в контроллере
     * Post::withoutGlobalScope(AncientScope::class),
     * игнорировать всех Post::withoutGlobalScopes()
    */
    protected static function booted()
    {
        static::addGlobalScope(new AncientScope);
    }

    /**
     * Локальный диапозон, можно применять только к определенному запросу
     * Диапазон запроса, включающий посты только определенного пользователя
     * В контроллере используется: Post::ForUser(2)
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', '=', $userId);
    }
}
