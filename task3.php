<?php

class Sort
{
    private $array;

    public function __construct($string)
    {
        $this->array = $this->prepare(
            $this->toArray($string)
        );
    }

    private function toArray($string)
    {
        return preg_split('/\s+/', $string);
    }

    private function prepare($array)
    {
        $array = array_filter($array, "is_numeric");
        foreach ($array as &$value) {
            $value = (int)$value;
        }

        return array_unique($array);
    }

    public function sort()
    {
        sort($this->array);
        return $this->array;
    }

    public function __toString()
    {
        return sprintf("Результат: %s\n", implode(' ', $this->array));
    }
}

if (isset($argv[1])) {
    $string = new Sort($argv[1]);
    $string->sort();
    echo $string;
} else {
    echo "Введите данные\n";
}

