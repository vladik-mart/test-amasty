<?php
include('simple_html_dom.php');

class TerrikonSerieaParser
{
    private $domParser;

    const ROOT_URL = 'https://terrikon.com';
    private $archiveUrl = self::ROOT_URL . '/football/italy/championship/archive';
    private $links = [];

    public function __construct()
    {
        $this->domParser = new simple_html_dom();
        $this->links = $this->scoreboardLinks();
    }

    private function scoreboardLinks()
    {
        $links = [];
        $this->domParser->load_file($this->archiveUrl);
        $parsedLinks = $this->domParser->find('.maincol .news a');
        foreach ($parsedLinks as $link) {
            $links[$link->plaintext] = self::ROOT_URL . $link->href;
        }

        return $links;
    }

    public function scoreboardPositions($clubName)
    {
        $results = [];

        foreach ($this->links as $season => $link) {
            $results[$season] = $this->scoreboardPosition($clubName, $link);
        }

        return $results;
    }

    public function scoreboardPosition($clubName, $link)
    {
        $result = new stdClass();
        $result->clubName = $clubName;
        $result->position = null;
        $this->domParser->load_file($link);

        foreach ($this->domParser->find('table.colored tr') as $row) {
            $parsedClubName = $row->find('td', 1)->plaintext;
            if ($parsedClubName == $clubName) {
                $result->position = intval($row->find('td', 0)->plaintext);
                return $result;
            }
        }

        return $result;
    }
}

if (isset($_POST['name'])){
    $parser = new TerrikonSerieaParser();
    $results = $parser->scoreboardPositions($_POST['name']);

    foreach ($results as $season => $result) {
        $html .= sprintf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $_POST['name'], $season, $result->position ?? 'Отсутствует в серии A');
    }

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PHP Parser</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/form.css" rel="stylesheet">
</head>
<body class="text-center">
<main role="main" class="container">

    <div class="col-sm-12">
        <form action="" method="post">

            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Название клуба из Серии А" value="<?php echo $_POST['name'] ?? ''; ?>" name="name">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Показать результаты</button>
                </div>
            </div>

        </form>
    </div>

    <?php if(isset($_POST['name'])): ?>
        <div class="col-sm-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Название клуба</th>
                    <th scope="col">Сезон</th>
                    <th scope="col">Место</th>
                </tr>
                </thead>
                <tbody>
                <?php echo $html; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</main>

</body>
</html>
