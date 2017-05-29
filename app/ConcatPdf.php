<?php
 namespace App;
use FPDI;
//require '../vendor/autoload.php';

 class ConcatPdf extends FPDI
{
    public $files = array();
    public $links = [];
    public function setFiles($files)
    {
        $this->files = $files;
    }
    
    public function Footer()
    {
        // Positionnement à 1,5 cm du bas
        $this->SetY(-15);
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }
    
    public function concat2() {
        $j=0;
        foreach($this->files AS $file) {
                  $j++;
            $pageCount = $this->setSourceFile($file);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                if($pageNo == 1) {
                     //$page = $this->pageNo();
                    $links[$j] = $this->AddLink();
                      
                }
            }
        }

    }
    
    public function concat()
    {
        //$lien = Array ( [p] => 7 [y] => 0 [f] => );
        foreach($this->files AS $file) {
             
            $pageCount = $this->setSourceFile($file);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $tplIdx = $this->ImportPage($pageNo);
                $s = $this->getTemplatesize($tplIdx);
                $this->AddPage($s['w'] > $s['h'] ? 'L' : 'P', array($s['w'], $s['h']));
                $this->useTemplate($tplIdx);
                //$this -> SetY(5); 
                //$this->Cell(190,5,'Reviens au Sommaire',0,0,'L',false,$link);
            }
                
        }
    }
    public function CreateSommaire()
    {
        
    }
}