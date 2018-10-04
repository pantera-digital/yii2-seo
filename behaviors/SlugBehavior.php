<?php
/**
 * Created by PhpStorm.
 * User: singletonn
 * Date: 12/13/17
 * Time: 12:54 PM
 */

namespace pantera\seo\behaviors;

use pantera\seo\components\SlugCache;
use pantera\seo\components\SlugCacheInterface;
use pantera\seo\models\SeoSlug;
use pantera\seo\validators\SlugValidator;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
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
    /* @var string|null Префикс который будет автоматически добавлен в начало алиаса */
    public $prefix;
    /* @var string|null Slug который нужно сохранить */
    private $slug;
    /* @var SeoSlug|null Модель с записью о slug для текушей модели */
    private $slugModel;
    /* @var SlugCacheInterface */
    private $cache;

    public function init()
    {
        parent::init();
        if (is_null($this->attribute) && is_null($this->slugAttribute)) {
            throw new InvalidConfigException('Параметр {attribute} или {slugAttribute} обязателен');
        }
        if (Yii::$container->has(SlugCacheInterface::class) === false) {
            Yii::$container->setSingleton(SlugCacheInterface::class, new SlugCache());
        }
        $this->cache = Yii::$container->get(SlugCacheInterface::class);
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
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'assign',
            ActiveRecord::EVENT_AFTER_INSERT => 'save',
            ActiveRecord::EVENT_AFTER_UPDATE => 'save',
            ActiveRecord::EVENT_AFTER_DELETE => 'delete',
            ActiveRecord::EVENT_AFTER_FIND => 'find'
        ];
    }

    /**
     * Поиск записи о slug для текушей модели
     */
    public function find()
    {
        if ($this->owner->isNewRecord === false && SeoSlug::getDb()->getSchema()->getTableSchema(SeoSlug::tableName(), true) !== null) {
            //Сначала попробуем найти запись в кеше
            $this->slugModel = $this->cache->get($this->owner);
            if (is_null($this->slugModel)) {
                $this->slugModel = SeoSlug::find()
                    ->where([
                        'AND',
                        ['=', 'model', $this->owner->className()],
                        ['=', 'model_id', $this->owner->getPrimaryKey()],
                    ])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
                //Если запись найдена положим найденную запись в кеш
                if ($this->slugModel) {
                    $this->cache->set($this->owner, $this->slugModel);
                }
            }
            //Если запись найдена и указано поле в родительской модели то присвоем ему значение этого алиаса
            if ($this->slugModel && $this->slugAttribute) {
                $this->owner->{$this->slugAttribute} = $this->slugModel->slug;
            }
        }
    }

    /**
     * Получение slug
     */
    public function assign()
    {
        if ($this->slugAttributeOnlyLoad === false && $this->slugAttribute && $this->owner->{$this->slugAttribute}) {
            $this->slug = $this->owner->{$this->slugAttribute};
            //Если есть префикс добави его
            $this->applyPrefix();
        } elseif ($this->attribute && $this->owner->{$this->attribute}) {
            //Генерируем только если нету текушего slug или указана генерация всегда при смене {attribute} или если есть {slugAttribute} и он пустой
            if (is_null($this->slugModel) || $this->alwaysGenerateWhenChangeAttribute || ($this->slugAttribute && empty($this->owner->{$this->slugAttribute}))) {
                $this->generate();
            }
        }
        //Если есть {slugAttribute} то присвоим ему новый slug
        if ($this->slugAttribute) {
            $this->owner->{$this->slugAttribute} = $this->slug;
        }
    }

    /**
     * Создаём slug
     * @param integer $iteration Номер итерации если алиас уже используется будет в цикле добовлять номер итерации пока не достигнет уникального slug
     */
    private function generate($iteration = 0)
    {
        $this->slug = Inflector::slug($this->owner->{$this->attribute} . ($iteration > 0 ? '-' . $iteration : ''));
        //Если есть префикс добави его
        $this->applyPrefix();
        while ($this->validate() === false) {
            $this->generate(++$iteration);
        }
    }

    /**
     * Применение префикса
     */
    private function applyPrefix()
    {
        if ($this->prefix && strpos($this->slug, $this->prefix) === false) {
            $this->slug = $this->prefix . $this->slug;
        }
    }

    /**
     * Валидация slug
     * @return boolean
     */
    private function validate()
    {
        $validator = new SlugValidator();
        return $validator->validateSlug($this->owner, $this->slug);
    }

    /**
     * Сохранение slug
     * сохранение происходит только если текуший slug отсутствует или новый slug отличается от имеюшегося
     */
    public function save()
    {
        $this->slug = trim(trim($this->slug), '/');
        if (is_null($this->slugModel) || $this->slugModel->slug !== $this->slug) {
            //Если уже есть запись с таким slug то обновим её присвоев её максимальный id + 1
            $model = SeoSlug::find()
                ->where([
                    'AND',
                    ['=', 'model', $this->owner->className()],
                    ['=', 'model_id', $this->owner->getPrimaryKey()],
                    ['=', 'slug', $this->slug],
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
            $model->slug = $this->slug;
            $model->save();
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
        return is_null($this->slugModel) === false;
    }
}