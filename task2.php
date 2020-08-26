<?php

class StroopTest
{
    const WORDS = ['red', 'blue', 'green', 'yellow', 'lime', 'magenta', 'black', 'gold', 'gray', 'tomato'];

    private $rows;
    private $cols;

    public function __construct($rows = 5, $cols = 5)
    {
        $maxRows = $this->maxRows();
        if($rows > $maxRows) {
            die(sprintf('Максимальное количество рядов с таким набором слов = %s (введено %s)', $maxRows, $rows));
        }
        $this->rows = $rows;
        $this->cols = $cols;
    }

    private function maxRows()
    {
        return intval(count(self::WORDS) / 2);
    }

    private function Row()
    {
        $row = [];
        $words = self::WORDS;
        $rows = $this->rows;
        while ($rows > 0) {
            $indexes = array_rand($words, 2);
            $row[] = [
                $words[$indexes[0]],
                $words[$indexes[1]]
            ];
            unset($words[$indexes[0]], $words[$indexes[1]]);
            $rows--;
        }

        return $row;
    }

    public function __toString()
    {
        $output = '';
        $cols = $this->cols;
        while ($cols > 0) {
            foreach ($this->Row() as $r) {
                $output .= '<i style="color: ' . $r[0] . '">' . $r[1] . ' </i>';
            }
            $output .= '<br>';
            $cols--;
        }

        return $output;
    }
}

echo new StroopTest(5, 5);

