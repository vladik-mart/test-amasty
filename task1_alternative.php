<?php

class First
{
    protected $letter = "A";

    public function getClassname()
    {
        return static::class;
    }

    public function getLetter()
    {
        echo $this->letter;
    }
}

class Second extends First
{
    protected $letter = "B";
}

$first = new First();
$second = new Second();

echo $first->getClassname() . '<br>';
echo $second->getClassname() . '<br>';
echo $first->getLetter() . '<br>';
echo $second->getLetter();