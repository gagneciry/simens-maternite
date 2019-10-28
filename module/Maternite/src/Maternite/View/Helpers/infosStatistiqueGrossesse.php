<?php

namespace Maternite\View\Helpers;

use ZendPdf;
use Maternite\View\Helpers\fpdf181\fpdf;

class infosStatistiqueGrossessePdf extends fpdf
{

	function Footer()
	{
		// Positionnement � 1,5 cm du bas
		$this->SetY(-15);
		
		$this->SetFillColor(0,128,0);
		$this->Cell(0,0.3,"",0,1,'C',true);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Times','',9.5);
		$this->Cell(81,5,'T�l�phone: 33 961 00 21 ',0,0,'L',false);
		$this->SetTextColor(128);
		$this->SetFont('Times','I',9);
		$this->Cell(20,8,'Page '.$this->PageNo(),0,0,'C',false);
		$this->SetTextColor(0,0,0);
		$this->SetFont('Times','',9.5);
		$this->Cell(81,5,'SIMENS+: www.simens.sn',0,0,'R',false);
	}
	
	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';
	
	function WriteHTML($html)
	{
		// Parseur HTML
		$html = str_replace("\n",' ',$html);
		$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				// Texte
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else
			{
				// Balise
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					// Extraction des attributs
					$a2 = explode(' ',$e);
					$tag = strtoupper(array_shift($a2));
					$attr = array();
					foreach($a2 as $v)
					{
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
							$attr[strtoupper($a3[1])] = $a3[2];
					}
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}
	
	function OpenTag($tag, $attr)
	{
		// Balise ouvrante
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF = $attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
	}
	
	function CloseTag($tag)
	{
		// Balise fermante
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF = '';
	}
	
	function SetStyle($tag, $enable)
	{
		// Modifie le style et s�lectionne la police correspondante
		$this->$tag += ($enable ? 1 : -1);
		$style = '';
		foreach(array('B', 'I', 'U') as $s)
		{
			if($this->$s>0)
				$style .= $s;
		}
		$this->SetFont('',$style);
	}
	
	function PutLink($URL, $txt)
	{
		// Place un hyperlien
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}
	
	
	
	
	
	
	
	
	protected $tabInformations ;
	//protected $nomService;
	protected $infosComp;
	protected $periode;
	protected $vat1;
	protected $vat2;
	protected $vat4;
	protected $vat3;

	
	
	
	
	/**
	 * @return the $vat1
	 */
	public function getVat1() {
		return $this->vat1;
	}

	/**
	 * @return the $vat2
	 */
	public function getVat2() {
		return $this->vat2;
	}

	/**
	 * @return the $vat4
	 */
	public function getVat4() {
		return $this->vat4;
	}

	/**
	 * @return the $vat3
	 */
	public function getVat3() {
		return $this->vat3;
	}

	/**
	 * @param field_type $vat1
	 */
	public function setVat1($vat1) {
		$this->vat1 = $vat1;
	}

	/**
	 * @param field_type $vat2
	 */
	public function setVat2($vat2) {
		$this->vat2 = $vat2;
	}

	/**
	 * @param field_type $vat4
	 */
	public function setVat4($vat4) {
		$this->vat4 = $vat4;
	}

	/**
	 * @param field_type $vat3
	 */
	public function setVat3($vat3) {
		$this->vat3 = $vat3;
	}

	public function getTabInformations()
	{
		return $this->tabInformations;
	}
	
	public function getNomService()
	{
		return $this->nomService;
	}
	
	public function setNomService($nomService)
	{
		$this->nomService = $nomService;
	}
	
	public function getPeriodeDiagnostic()
	{
		return $this->periode;
	}
	
	public function setPeriode($periode)
	{
		$this->periode = $periode;
	}

	public function getInfosComp()
	{
		return $this->infosComp;
	}
	
	public function setInfosComp($infosComp)
	{
		$this->infosComp = $infosComp;
	}
	
	function EnTetePage()
	{
		$this->SetFont('Times','',10.3);
		$this->SetTextColor(0,0,0);
		$this->Cell(0,4,"R�publique du S�n�gal");
		$this->SetFont('Times','',8.5);
		$this->Cell(0,4,"Saint-Louis, le ".$this->getInfosComp()['dateImpression'],0,0,'R');
		$this->SetFont('Times','',10.3);
		$this->Ln(5.4);
		$this->Cell(100,4,"Minist�re de la sant� et de l'action sociale");
		
		$this->AddFont('timesbi','','timesbi.php');
		$this->Ln(5.4);
		$this->Cell(100,4,"C.H.R de Saint-louis");
		$this->Ln(5.4);
		$this->SetFont('timesbi','',10.3);
		$this->Cell(14,4,"Service : ",0,0,'L');
		$this->SetFont('Times','',10.3);
		$this->Cell(86,4,$this->getNomService(),0,0,'L');
		
		$this->Ln(8);
		$this->SetFont('Times','',14.3);
		$this->SetTextColor(0,128,0);
		$this->Cell(0,5,"RAPPORT STATISTIQUES",0,1,'C');
		$this->SetFillColor(0,128,0);
		$this->Cell(0,0.3,"",0,1,'C',true);
	
		// EMPLACEMENT DU LOGO
		// EMPLACEMENT DU LOGO
		$baseUrl = $_SERVER['SCRIPT_FILENAME'];
		$tabURI  = explode('public', $baseUrl);
		$this->Image($tabURI[0].'public/images_icons/hrsl.png', 162, 19, 35, 15);
		
	}
	
	function CorpsDocument(){
		
		
		
		
		if($this->getPeriodeDiagnostic()){
			$dateConvert = new DateHelper();
			$date_debut = $dateConvert->convertDate($this->getPeriodeDiagnostic()[0]);
			$date_fin   = $dateConvert->convertDate($this->getPeriodeDiagnostic()[1]);
				
			$this->Ln(4);
			$this->SetFillColor(220,220,220);
			$this->SetDrawColor(205,193,197);
			$this->SetTextColor(0,0,0);
			$this->AddFont('zap','','zapfdingbats.php');
			$this->SetFont('zap','',13);
				
			$this->SetFillColor(255,255,255);
			$this->Cell(55,7,'','',0,'L',1);
				
			$this->SetFillColor(220,220,220);
			$this->SetLineWidth(1);
			$this->Cell(5,8,'B','BLT',0,'L',1);
				
			$this->AddFont('timesb','','timesb.php');
			$this->AddFont('timesi','','timesi.php');
			$this->AddFont('times','','times.php');
				
			$this->SetFont('times','',12.5);
			$this->Cell(70,8,"Periode du ".$date_debut." au ".$date_fin,'BRT',0,'L',1);
				
			$this->SetFillColor(255,255,255);
			$this->Cell(53,7,'','L',0,'L',1);
				
			$this->Ln(7);
			$this->SetLineWidth(0);
		}
		
		
		
		$tabInformations = $this->getTabInformations();
		
	
	   $vat1=$this->getVat1();
	   $vat2=$this->getVat2();
	   $vat3=$this->getVat3();
	   $vat4=$this->getVat4();
	
		if($vat1 || $vat2 ||$vat3||$vat4){
	
			//for($i=0 ; $i < count($tabInformations) ; $i++){
	
				//if($i%2==0){
						
					$this->Ln(5.4);
					$this->SetFillColor(220,220,220);
					$this->SetDrawColor(205,193,197);
					$this->SetTextColor(0,0,0);
					//$this->AddFont('zap','','zapfdingbats.php');
					//$this->SetFont('zap','',13);
	
					$this->AddFont('timesb','','timesb.php');
					$this->AddFont('timesi','','timesi.php');
					$this->AddFont('times','','times.php');
						
					//$this->Cell(25,7,($i+1).')','BT',0,'L',1);
	
					$this->SetFont('times','',12.5);
					
					$this->Cell(78,7,"VAT1  ",'BT',0,'L',1);
					$this->Cell(27,7,iconv ('UTF-8' , 'windows-1252', $vat1),'BT',0,'L',1);
					$this->Cell(78,7,iconv ('UTF-8' , 'windows-1252', $tabInformations[1][$tabInformations[0][0]]),'BT',0,'R',1);
						
					$this->Ln(2);
					$this->SetFillColor(249,249,249);
					$this->SetDrawColor(220,220,220);
						
				//}else {
						
					$this->Ln(5.4);
					$this->SetFillColor(240,240,240);
					$this->SetDrawColor(205,193,197);
					$this->SetTextColor(0,0,0);
					//$this->AddFont('zap','','zapfdingbats.php');
					//$this->SetFont('zap','',13);
	
					$this->AddFont('timesb','','timesb.php');
					$this->AddFont('timesi','','timesi.php');
					$this->AddFont('times','','times.php');
						
					//$this->Cell(25,7,($i+1).')','BT',0,'L',1);
	
					$this->SetFont('times','',12.5);
					$this->Cell(78,7,"VAT2  ",'BT',0,'L',1);
					$this->Cell(27,7,iconv ('UTF-8' , 'windows-1252', $vat2),'BT',0,'L',1);
					$this->Cell(78,7,iconv ('UTF-8' , 'windows-1252', $tabInformations[1][$tabInformations[0][0]]),'BT',0,'R',1);
					$this->Ln(6);
					$this->SetFillColor(249,249,249);
					$this->SetDrawColor(220,220,220);
					$this->Ln(5.4);
					$this->SetFillColor(240,240,240);
					$this->SetDrawColor(205,193,197);
					$this->SetTextColor(0,0,0);
					//$this->AddFont('zap','','zapfdingbats.php');
					//$this->SetFont('zap','',13);
					
					$this->AddFont('timesb','','timesb.php');
					$this->AddFont('timesi','','timesi.php');
					$this->AddFont('times','','times.php');
					
					//$this->Cell(25,7,($i+1).')','BT',0,'L',1);
					
					$this->SetFont('times','',12.5);
					$this->Cell(78,7,"VAT3",'BT',0,'L',1);
					$this->Cell(27,7,iconv ('UTF-8' , 'windows-1252', $vat3),'BT',0,'L',1);
					$this->Cell(78,7,iconv ('UTF-8' , 'windows-1252', $tabInformations[1][$tabInformations[0][0]]),'BT',0,'R',1);
					$this->Ln(6);
					$this->SetFillColor(249,249,249);
					$this->SetDrawColor(220,220,220);
					$this->Ln(5.4);
					$this->SetFillColor(240,240,240);
					$this->SetDrawColor(205,193,197);
					$this->SetTextColor(0,0,0);
					//$this->AddFont('zap','','zapfdingbats.php');
					//$this->SetFont('zap','',13);
					
					$this->AddFont('timesb','','timesb.php');
					$this->AddFont('timesi','','timesi.php');
					$this->AddFont('times','','times.php');
					
					//$this->Cell(25,7,($i+1).')','BT',0,'L',1);
					$this->SetFont('times','',12.5);
					$this->Cell(78,7,"VAT4",'BT',0,'L',1);
					$this->Cell(27,7,iconv ('UTF-8' , 'windows-1252', $vat4),'BT',0,'L',1);
					$this->Cell(78,7,iconv ('UTF-8' , 'windows-1252', $tabInformations[1][$tabInformations[0][0]]),'BT',0,'R',1);
					$this->Ln(6);
					$this->SetFillColor(249,249,249);
					$this->SetDrawColor(220,220,220);
					$this->Ln(5.4);
					$this->SetFillColor(240,240,240);
					$this->SetDrawColor(205,193,197);
					$this->SetTextColor(0,0,0);
					//$this->AddFont('zap','','zapfdingbats.php');
					//$this->SetFont('zap','',13);
					
					
						
		}else{
			echo  "<div align='center' style='font-size: 30px; font-family: times new roman;'> Aucune information � afficher </div>"; exit();
		}
		
		
	}
	
	//IMPRESSION DES INFOS STATISTIQUES
	//IMPRESSION DES INFOS STATISTIQUES
	function ImpressionInfosStatistiques()
	{
		$this->AddPage();
		$this->EnTetePage();
		$this->CorpsDocument();
	}

}

?>
