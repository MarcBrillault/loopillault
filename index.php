<?php

require_once __DIR__ . '/vendor/autoload.php';

class Candidat
{
    public $nom;
    public $slug;
    public $score;
    public $credit;
    public $points;

    public function getScore()
    {
        return substr_count($this->score, '0');
    }

    public function getNom()
    {
        return preg_replace('# #', '<br>', $this->nom, 1);
    }

    public function getPoints()
    {
        $points = [];
        foreach (str_split($this->score) as $score) {
            $points[] = new Point($score === '0');
        }

        return $points;
    }
}

class Point
{
    /** @var bool */
    public $hasPoint;

    public function __construct($hasPoint = false)
    {
        if ($hasPoint) {
            $this->hasPoint = true;
        }
    }
}

$candidats = [
    candidat("Jean-Luc Mélenchon", "melenchon", "000000X", "Thomas Coex @TomCOEX"),
    candidat("Philippe Poutou", "poutou", "000X00X", "Delphine Goldsztejn"),
    candidat("Marine Le Pen", "lepen", "0000XXX"),
    candidat("Nathalie Arthaud", "arthaud", "000XXXX", "Victoria Viennet"),
    candidat("Yannick Jadot", "jadot", "0XX0X0X", "Thomas Coex @TomCOEX"),
    candidat("Anasse Kazib", "kazib", "000XXXX", "Mathieu Génon"),
    candidat("Nicolas Dupont-Aignan", "nda", "00XXXXX"),
    candidat("François Asselineau", "asselineau", "00XXXXX", "Ugo Amez @Ugo_Amez"),
    candidat("Anne Hidalgo", "hidalgo", "0XX0XXX"),
    candidat("Alexandre Langlois", "langlois", "0XXX0XX"),
    candidat("Fabien Roussel", "roussel", "0XXXX0X"),
    candidat("Christiane Taubira", "taubira", "00XXXXX"),
    candidat("Jean Lassalle", "lassalle", "0XXXXXX"),
    candidat("Antoine Martinez", "martinez", "0XXXXXX"),
    candidat("Valérie Pécresse", "pecresse", "0XXXXXX", "Sarah Meyssonnier @SarahMeyss"),
    candidat("Gaspard Koenig", "koenig", "XXXXXXX"),
    candidat("Emmanuel Macron", "macron", "XXXXXXX", "Sarah Meyssonnier @SarahMeyss"),
    candidat("Hélène Thouy", "thouy", "XXXXXXX", "Parti animaliste"),
    candidat("Marie Cau", "cau", "XXXXXXX", "François Lo Presti"),
    candidat("Clara Egger", "egger", "XXXXXXX", "Neyer Crismar Valeriano Rodriguez"),
    candidat("Éric Zemmour", "zemmour", "XXXXXXX", "Éric Piermont"), // Éric Piermont pour la photo avec la main sur la tête
];

$data = [
    'candidats' => $candidats,
];

if (isset($_GET['candidat'])) {
    $data['candidat'] = getCandidat($_GET['candidat']);
}

$loader = new \Twig\Loader\FilesystemLoader('.');
$twig = new \Twig\Environment($loader);
$template = $twig->load('template.twig');
echo $template->render($data);

function candidat($nom, $slug, $score, $credit = null)
{
    assert(strlen($score) === 7);
    $c = new Candidat();
    $c->nom = $nom;
    $c->slug = $slug;
    $c->score = $score;
    $c->credit = $credit;

    return $c;
}

function getCandidat($slug)
{
    global $candidats;

    foreach ($candidats as $candidat) {
        if ($candidat->slug === $slug) {
            return $candidat;
        }
    }

    throw new Exception(sprintf('Candidat %s not found', $slug));
}
