<?php

namespace RZ\OpeningHours\Helpers;

use RZ\OpeningHours\Exceptions\InvalidFormatterByLocale;

trait DataTrait
{
    /** @var mixed|null */
    protected $data = null;

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return self
     */
    protected function setOptions($options)
    {
        $this->options = array_merge($this->getDefaultOptions(), $options);
        return $this;
    }

    /**
     * @param string $locale
     * @return mixed
     * @throws InvalidFormatterByLocale
     */
    protected function getFormatter(string $locale)
    {
        if (isset(static::$formatters[$locale])) {
            $formatterClass = static::$formatters[$locale];
            return new $formatterClass();
        }
        throw InvalidFormatterByLocale::invalidFormatter($locale);
    }
}
