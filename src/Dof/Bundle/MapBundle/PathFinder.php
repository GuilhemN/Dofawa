<?php
namespace Dof\Bundle\MapBundle;

class PathFinder
{
    public function getCouts() {
        return $this->couts;
    }
    public function casesAdjacentes($case) { // Renvoi la liste des cases adjacentes
        list($x, $y) = explode(',', $case);
        $casesAdjacentes = array();
        if(isset($this->map[$x+1][$y+1], $this->map[$x+1][$y], $this->map[$x][$y+1]) AND $this->map[$x+1][$y+1] == "franchissable" AND $this->map[$x+1][$y] == "franchissable" AND $this->map[$x][$y+1] == "franchissable")        // X+1; Y+1
            $casesAdjacentes[] = array($x+1, $y+1);
        if(isset($this->map[$x-1][$y-1], $this->map[$x-1][$y], $this->map[$x][$y-1]) AND $this->map[$x-1][$y-1] == "franchissable" AND $this->map[$x-1][$y] == "franchissable" AND $this->map[$x][$y-1] == "franchissable")        // X-1;    Y-1
            $casesAdjacentes[] = array($x-1, $y-1);
        if(isset($this->map[$x-1][$y+1], $this->map[$x-1][$y], $this->map[$x][$y+1]) AND $this->map[$x-1][$y+1] == "franchissable" AND $this->map[$x-1][$y] == "franchissable" AND $this->map[$x][$y+1] == "franchissable")        // X-1;    Y+1
            $casesAdjacentes[] = array($x-1, $y+1);
        if(isset($this->map[$x+1][$y-1], $this->map[$x+1][$y], $this->map[$x][$y-1]) AND $this->map[$x+1][$y-1] == "franchissable" AND $this->map[$x+1][$y] == "franchissable" AND $this->map[$x][$y-1] == "franchissable")        // X+1;    Y-1
            $casesAdjacentes[] = array($x+1, $y-1);
        if(isset($this->map[$x][$y+1]) AND $this->map[$x][$y+1] == "franchissable")        // X; Y+1
            $casesAdjacentes[] = array($x, $y+1);
        if(isset($this->map[$x][$y-1]) AND $this->map[$x][$y-1] == "franchissable")        // X;    Y-1
            $casesAdjacentes[] = array($x, $y-1);
        if(isset($this->map[$x-1][$y]) AND $this->map[$x-1][$y] == "franchissable")        // X-1;    Y
            $casesAdjacentes[] = array($x-1, $y);
        if(isset($this->map[$x+1][$y]) AND $this->map[$x+1][$y] == "franchissable")        // X+1;    Y
            $casesAdjacentes[] = array($x+1, $y);
        return $casesAdjacentes;
    }

    public function getChemin() {
        if(!empty($this->chemin))
            return $this->chemin;
        else
            return FALSE;
    }

    public function getOuvert() {
        return $this->ouvert;
    }

    public function getFerme() {
        return $this->ferme;
    }

    private function coutCase($caseCourrante, $caseParente = NULL) { // Calcul le cout de la case
        $coutAnalyse = array("f" => NULL, "g" => NULL, "h" => NULL);
        list($xCourrant, $yCourrant) = explode(',',$caseCourrante);
        $coutAnalyse["h"] = round(sqrt(pow($xCourrant-$this->xFinal,2)+pow($yCourrant-$this->yFinal,2)))*10; // Distance euclidienne
        if($caseParente !== NULL) {
            list($xParent, $yParent) = explode(',',$caseParente);
            $coutParent = (array_key_exists($caseParente, $this->couts)) ? $this->couts[$caseParente] : NULL;
            if($xParent != $xCourrant AND $yParent != $yCourrant) // Si c'est un dÃ©placement en diagonale
                $coutAnalyse["g"] = $coutParent["g"]+14;
            else
                $coutAnalyse["g"] = $coutParent["g"]+10;
        } else // En thÃ©orie appelÃ© seulement pour le calcul de la case dÃ©part qui n'as aucun parent
            $coutAnalyse["g"] = 0;
        $coutAnalyse["f"] = $coutAnalyse["h"] + $coutAnalyse["g"]; // Calcul du cout total F
        return $coutAnalyse;
    }

    private function analyseCasesAdjacentes($casesAdjacentes, $caseParente) { // Ajoutes Ã  la liste ouverte et analyses les cases adjacentes
        $coutParent = $this->couts[$caseParente]; // On rÃ©cupÃ¨re le coÃ»t du parent
        foreach ($casesAdjacentes as $coordAnalyse) { // On les analyse une par une
            list($xAnalyse,$yAnalyse) = $coordAnalyse;
            $caseAnalyse = "$xAnalyse,$yAnalyse";
            if(in_array($caseAnalyse, $this->ferme)) // Si la case a dÃ©jÃ  Ã©tÃ© traitÃ©
                continue; // On saute une itÃ©ration
            $this->ouvert[$caseAnalyse] = $caseAnalyse; // On l'ajoutes Ã  la liste ouverte
            $coutAnalyse = $this->coutCase($caseAnalyse, $caseParente); // On calcul son coÃ»t
            if(!array_key_exists($caseAnalyse, $this->parents) OR $this->couts[$caseAnalyse]["g"] > $coutAnalyse["g"]) { // Si la case n'as pas de parent, oÃ¹ que le parent actuel est plus rapide
                $this->parents[$caseAnalyse] = $caseParente;
                $this->couts[$caseAnalyse] = $coutAnalyse;
            }
            if($caseAnalyse == $this->caseFinale)
                break;
        }
        return TRUE;
    }

    private function plusPetitF() { // RÃ©cupÃ¨re dans la liste ouverte la case possÃ©dant le plus petit coÃ»t
        $plusPetitF = array("f" => NULL, "coordonnees" => NULL);
        foreach($this->ouvert as $coordonnees) {
            if($plusPetitF["f"] === NULL OR $plusPetitF["f"] > $this->couts[$coordonnees]["f"])
                $plusPetitF = array("f" => $this->couts[$coordonnees]["f"], "coordonnees" => $coordonnees);
        }
        return $plusPetitF["coordonnees"];
    }

    public function findPath($start, $end) {
        if($this->map[$this->xDepart][$this->yDepart] == "non-franchissable" OR $this->map[$this->xFinal][$this->yFinal] == "non-franchissable")
            return FALSE;

        // Valeurs initiales
        $this->ouvert[$this->caseDepart] = $this->caseDepart; // La case de dÃ©part est mise dans la liste ouverte
        $coutCaseDepart = $this->coutCase($this->caseDepart); // On calcule son coÃ»t
        $this->couts[$this->caseDepart] = $coutCaseDepart;
        $caseCourrante = $this->caseDepart; // Case courrante
        $coutCourrant = $coutCaseDepart;
        $while = 0;
        while ($caseCourrante != $this->caseFinale) {
            if (empty($this->ouvert)) // Si la liste ouverte est vide, alors Ã©chec dans la recherche du chemin
                return FALSE;
            list($xCourrant, $yCourrant) = explode(",", $caseCourrante); // On sÃ©pares les coordonnÃ©es de la case courrante
            $this->ferme[$caseCourrante] = $this->ouvert[$caseCourrante]; // On met la case courrante dans la liste fermÃ©
            unset($this->ouvert[$caseCourrante]); // Et on l'enlÃ¨ve de la liste ouverte
            $casesAdjacentes = $this->casesAdjacentes($caseCourrante); // On rÃ©cupÃ¨re la liste des cases adjacentes Ã  la case courrante
            $this->analyseCasesAdjacentes($casesAdjacentes, $caseCourrante); // On les analyses
            $caseCourrante = $this->plusPetitF(); // On rÃ©cupÃ¨re dans la liste ouverte la case avec le plus faible coÃ»t F
        }
        $caseParcouru = $this->caseFinale;
        while($caseParcouru != $this->caseDepart) { // On remonte le tableau $parent pour tracer le chemin;
            array_unshift($this->chemin, $caseParcouru); // On ajoutes la case parcouru au dÃ©but du tableau
            $caseParcouru = $this->parents[$caseParcouru]; // On remontes jusqu'au parent de la case parcouru
        }
        array_unshift($this->chemin, $this->caseDepart);
        return TRUE;
    }
    
    public function getMap() {
        return $this->map;
    }
}