<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 12/13/17
 * Time: 12:54 PM
 */

namespace pantera\seo\behaviors;

use Closure;
use pantera\seo\components\SlugCache;
use pantera\seo\components\SlugCacheInterface;
use pantera\seo\models\SeoSlug;
use pantera\seo\validators\SlugValidator;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use function call_user_func;
use function is_callable;
use function is_null;

class SlugBehavior extends Behavior
{
    /* @var string|null Атрибут из которого нужно сделать slug */
    public $attribute;
    /* @var string Атрибут в котором будет хранится введенный пользователем slug */
    public $slugAttribute;
    /* @var boolean Если true то {slugAttribute} будет только загружать данные текушего slug */
    public $slugAttributeOnlyLoad = false;
    /* @var ActiveRecord */
    public $owner;
    /**
     * @var boolean Если true будет всегда генерировать новый slug при смене {attribute}
     * работает только если {slugAttribute} пусто или {slugAttributeOnlyLoad} true
     */
    public $alwaysGenerateWhenChangeAttribute = false;
    /**
     * @var string|null|Closure Префикс который будет автоматически добавлен в начало алиаса
     *Возможность передать callback
     */
    public $prefix;
    /* @var null|Closure Колбек вызывается после успешной генерации слуга */
    public $afterGenerate;
    /* @var null|Closure Колбек вызывается после успешного сохранения */
    public $afterSave;
    /* @var bool Указывает что генерация будет происходить после сохранения модели */
    public $generateBeforeSave = false;
    /* @var string|null Slug который нужно сохранить */
    private $_slug;
    /* @var SeoSlug|null Модель с записью о slug для текушей модели */
    private $_slugModel;
    /* @var SlugCacheInterface */
    private $_cache;

    /**
     * Получить объект кеширования
     * @return SlugCacheInterface
     */
    public function getCache(): SlugCacheInterface
    {
        return $this->_cache;
    }

    /**
     * Получить текуший сгенерированый slug
     * @return null|string
     */
    public function getSlug()
    {
        return $this->_slug;
    }

    /**
     * Установить новый slug
     * @param $slug
     */
    public function setSlug($slug)
    {
        $this->_slug = $slug;
    }

    public function init()
    {
        parent::init();
        if (is_null($this->attribute) && is_null($this->slugAttribute)) {
            throw new InvalidConfigException('Параметр {attribute} или {slugAttribute} обязателен');
        }
        if (Yii::$container->has(SlugCacheInterface::class) === false) {
            Yii::$container->setSingleton(SlugCacheInterface::class, new SlugCache());
        }
        $this->_cache = Yii::$container->get(SlugCacheInterface::class);
    }

    public function attach($owner)
    {
        parent::attach($owner);
        if ($this->slugAttribute && $this->owner->hasProperty($this->slugAttribute) === false) {
            throw new InvalidConfigException('Параметр ' . $this->slugAttribute . ' отсутствует в модели');
        }
        if ($this->attribute && $this->owner->hasProperty($this->attribute) === false) {
            throw new InvalidConfigException('Параметр ' . $this->attribute . ' отсутствует в модели');
        }
    }

    public function events()
    {
        $events = [
            ActiveRecord::EVENT_AFTER_INSERT => 'eventSaveCallback',
            ActiveRecord::EVENT_AFTER_UPDATE => 'eventSaveCallback',
            ActiveRecord::EVENT_AFTER_DELETE => 'delete',
            ActiveRecord::EVENT_AFTER_FIND => 'find'
        ];
        if ($this->generateBeforeSave === false) {
            $events[ActiveRecord::EVENT_BEFORE_VALIDATE] = 'assign';
        }
        return $events;
    }

    /**
     * Колбек для собитий сохранения
     */
    public function eventSaveCallback()
    {
        if ($this->generateBeforeSave) {
            $this->owner->refresh();
            $this->assign();
        }
        $this->save();
    }

    /**
     * Поиск записи о slug для текушей модели
     */
    public function find()
    {
        if ($this->owner->isNewRecord === false && SeoSlug::getDb()->getSchema()->getTableSchema(SeoSlug::tableName(), true) !== null) {
            //Сначала попробуем найти запись в кеше
            $this->_slugModel = $this->_cache->get($this->owner);
            if (is_null($this->_slugModel)) {
                $this->_slugModel = SeoSlug::find()
                    ->where([
                        'AND',
                        ['=', 'model', $this->owner->className()],
                        ['=', 'model_id', $this->owner->getPrimaryKey()],
                    ])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                //Если запись найдена положим найденную запись в кеш
                if ($this->_slugModel) {
                    $this->_cache->set($this->owner, $this->_slugModel);
                }
            }
            //Если запись найдена и указано поле в родительской модели то присвоем ему значение этого алиаса
            if ($this->_slugModel && $this->slugAttribute) {
                $this->owner->{$this->slugAttribute} = $this->_slugModel->slug;
            }
        }
    }

    /**
     * Получение slug
     */
    public function assign()
    {
        if ($this->slugAttributeOnlyLoad === false && $this->slugAttribute && $this->owner->{$this->slugAttribute}) {
            $this->_slug = $this->owner->{$this->slugAttribute};
            //Если есть префикс добави его
            $this->applyPrefix();
        } elseif ($this->attribute && $this->owner->{$this->attribute}) {
            //Генерируем только если нету текушего slug или указана генерация всегда при смене {attribute} или если есть {slugAttribute} и он пустой
            if (is_null($this->_slugModel) || $this->alwaysGenerateWhenChangeAttribute || ($this->slugAttribute && empty($this->owner->{$this->slugAttribute}))) {
                $this->generate();
                if (is_callable($this->afterGenerate)) {
                    call_user_func($this->afterGenerate, $this);
                }
            }
        }
        //Если есть {slugAttribute} то присвоим ему новый slug
        if ($this->slugAttribute) {
            $this->owner->{$this->slugAttribute} = $this->_slug;
        }
    }

    /**
     * Создаём slug
     * @param integer $iteration Номер итерации если алиас уже используется будет в цикле добовлять номер итерации пока не достигнет уникального slug
     */
    protected function generate($iteration = 0)
    {
        $this->_slug = Inflector::slug($this->owner->{$this->attribute} . ($iteration > 0 ? '-' . $iteration : ''));
        //Если есть префикс добави его
        $this->applyPrefix();
        while ($this->validate() === false) {
            $this->generate(++$iteration);
        }
    }

    /**
     * Применение префикса
     */
    protected function applyPrefix()
    {
        if (is_callable($this->prefix)) {
            $prefix = call_user_func($this->prefix, $this);
        } else {
            $prefix = $this->prefix;
        }
        if ($prefix && strpos($this->_slug, $prefix) === false) {
            $this->_slug = $prefix . $this->_slug;
        }
    }

    /**
     * Валидация slug
     * @return boolean
     */
    protected function validate()
    {
        $validator = new SlugValidator();
        return $validator->validateSlug($this->owner, $this->_slug);
    }

    /**
     * Сохранение slug
     * сохранение происходит только если текуший slug отсутствует или новый slug отличается от имеюшегося
     */
    public function save()
    {
        $this->_slug = trim(trim($this->_slug), '/');
        if (is_null($this->_slugModel) || $this->_slugModel->slug !== $this->_slug) {
            //Если уже есть запись с таким slug то обновим её присвоев её максимальный id + 1
            $model = SeoSlug::find()
                ->where([
                    'AND',
                    ['=', 'model', $this->owner->className()],
                    ['=', 'model_id', $this->owner->getPrimaryKey()],
                    ['=', 'slug', $this->_slug],
                ])
                ->one();
            if ($model) {
                $maxId = SeoSlug::find()
                    ->max('id');
                $oldId = $model->id;
                $model->id = ++$maxId;
                $model->save();
                SeoSlug::updateAllCounters(['id' => -1], [
                    'AND',
                    ['>', SeoSlug::tableName() . '.id', $oldId],
                ]);
            } else {
                $model = new SeoSlug();
                $model->model = $this->owner->className();
                $model->model_id = $this->owner->getPrimaryKey();
            }
            $model->slug = $this->_slug;
            $model->save();
            if (is_callable($this->afterSave)) {
                call_user_func($this->afterSave, $this);
            }
        }
    }

    /**
     * Удаление всех записей для текушей модели
     */
    public function delete()
    {
        SeoSlug::deleteAll([
            'AND',
            ['=', 'model', $this->owner->className()],
            ['=', 'model_id', $this->owner->getPrimaryKey()],
        ]);
    }

    /**
     * Сравнение текушего slug модели с переданным
     * @param string $slug Slug которой нужно сравнить
     * @return boolean
     */
    public function slugCompare($slug)
    {
        return $this->owner->{$this->slugAttribute} === trim($slug, '/');
    }

    /**
     * Проверяет есть ли slug у текушей модели
     * @return bool
     */
    public function hasSlug()
    {
        return is_null($this->_slugModel) === false;
    }
}