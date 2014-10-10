<?php
namespace Pyrite\PyRest;


abstract class PyRestObject
{
    /**
     * @return array embeddables
     */
    protected static $embeddables = array();
    /**
     * @return array filters
     */
    protected $filters = array();
    /**
     * @return array sorts
     */
    protected $sorts = array();

    private $embedStorage = array();

    public function getId() {
        return $this->id;
    }

    public function getEmbed($name)
    {
        if (array_key_exists($name, $this->embedStorage)) {
            return $this->embedStorage[$name];
        }
        else {
            return null;
        }
    }

    public function __get($name)
    {
        return $this->getEmbed($name);
    }

    public function setEmbed($name, $value)
    {
        $this->embedStorage[$name] = $value;
        return $this;
    }

    public function getEmbeds()
    {
        return $this->embedStorage;
    }

    /**
     * @return array embeddables
     */
    public static function getEmbeddables() {
        return static::initEmbeddables();
    }

    /**
     * @return array filters
     */
    public function getFilters() {
        return $this->filters;
    }
    /**
     * @param array $value
     * @return ObjectREST
     */
    public function setFilters($value) {
        $this->filters = $value;
        return $this;
    }

    /**
     * @return array sorts
     */
    public function getSorts() {
        return $this->sorts;
    }
    /**
     * @param array $value
     * @return ObjectREST
     */
    public function setSorts($value) {
        $this->sorts = $value;
        return $this;
    }

    public function transform()
    {
        $objectData = get_object_vars($this);

        unset($objectData['embeddables']);
        unset($objectData['embedStorage']);
        unset($objectData['filters']);
        unset($objectData['sorts']);

        return $objectData;
    }
}