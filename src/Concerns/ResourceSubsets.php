<?php

namespace Devvir\ResourceTools\Concerns;

use Illuminate\Support\Arr;

trait ResourceSubsets
{
    protected array $_only = [];
    protected array $_except = [];

    /**
     * Filter the given data, removing any optional values.
     *
     * @param  array  $data
     * @return array
     */
    protected function filter($data)
    {
        $data = parent::filter($data);

        if ($this->_only) {
            $data = Arr::only($data, $this->_only);
        } elseif ($this->_except) {
            $data = Arr::except($data, $this->_except);
        }

        return $data;
    }

    public function only(string|array $field): self
    {
        $this->_only = is_array($field) ? $field : func_get_args();

        return $this;
    }

    public function except(string|array $field): self
    {
        $this->_except = is_array($field) ? $field : func_get_args();

        return $this;
    }
}
