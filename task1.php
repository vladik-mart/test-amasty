<?php

class First
{
    public function getClassname()
    {
        return static::class;
    }

    public function getLetter()
    {
        return "A";
    }
}

class Second extends First
{
    public function getLetter()
    {
        return "B";
    }
}

$first = new First();
$second = new Second();

echo $first->getClassname() . '<br>';
echo $second->getClassname() . '<br>';
echo $first->getLetter() . '<br>';
echo $second->getLetter();