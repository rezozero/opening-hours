<?php

namespace Lib\OpeningHours\Helpers;

use Lib\OpeningHours\FormatterInterface;

trait DataTrait
{
    /** @var mixed */
    protected $data = null;

    public function getData()
    {
        return $this->data;
    }

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

    protected function setOptions ($options)
    {
         $this->options = array_merge($this->getDefaultOptions(), $options);
    }

    protected function getFormatter(string $locale)
    {
        if (isset(static::$formatters[$locale])) {
            $formatterClass = static::$formatters[$locale];
            return new $formatterClass();
        }
        throw new FormatterNotFound('No formatter was found for locale ' . $locale);
    }
}
