<?php
if(basename($_SERVER['PHP_SELF']) == "paginanummering.class.php"){
	header("Location: ../");
	exit();
}

/**
	Creërt een juiste Query op basis van een gegeven query, het aantal elementen en het aantal elementen per pagina.
	Deze klasse voert geen query's uit maar zorgt voor puur voor het generen van de juiste query en het eventueel weergeven van de pagina nummering
	
	Deze klasse gebruikt $_GET['page'] voor de huidige pagina
*/

class Nummering{
	
	// Instellingen
	private $perpagina;
	private $query;
	private $num;
	
	// Waardes
	private $totPage;
	private $curPage;
	private $fixedQuery;
	
	/**
	 * Constructor, creeërt en zet de instellingen.
	 * @param string $query De query die gebruikt moet worden
	 * @param int $num Het totale aantal elementen
	 * @param int $perpagina Hoeveel elementen er op 1 pagina staan, standaard 25
	 * @param boolean $containsWhere Of de query al een WHERE clausule bevat, standaard false
	 */
	public function __construct($query, $num, $perpagina = 25){
		// Instellingen
		$this->query = $query;
		$this->num = $num;
		$this->perpagina = $perpagina;
		
		$this->totPage = ceil($this->num/$this->perpagina);
		if(!isset($_GET['page']) || !is_numeric($_GET['page'])){
			$this->curPage = 1;
		}
		else{
			if($_GET['page'] < 1 || $_GET['page'] > $this->totPage){
				$this->curPage = 1;
			}
			else{
				$this->curPage = $_GET['page'];
			}
		}
		
		$this->fixedQuery = $this->query." LIMIT ".$this->perpagina*($this->curPage-1).", ".$this->perpagina;
	}
	
	/**
	 * Geeft de gegenereerde query terug
	 */
	public function getQuery(){
		return $this->fixedQuery;
	}
	
	/**
	 * Geeft de paginanummering weer.
	 */
	public function showNummering(){
		$postFix = "";
		foreach($_GET as $key => $val){
			if($key != "page")
				$postFix .= "&".$key."=".$val;
		}
		
		$string = "<nav><ul class='pagination'>";
		
		$string .= "<li".($this->curPage == 1 ? " class='disabled'" : "")."><a href='?page=1".$postFix."'>&laquo;</a></li>";
		
		if($this->curPage == $this->totPage && $this->curPage >= 5) $string .= "<li><a href='?page=".($this->curPage-4).$postFix."'>".($this->curPage-4)."</a></li>";
		if($this->curPage >= $this->totPage-1 && $this->curPage >= 4) $string .= "<li><a href='?page=".($this->curPage-3).$postFix."'>".($this->curPage-3)."</a></li>";
		if($this->curPage >= 3) $string .= "<li><a href='?page=".($this->curPage-2).$postFix."'>".($this->curPage-2)."</a></li>";
		if($this->curPage >= 2) $string .= "<li><a href='?page=".($this->curPage-1).$postFix."'>".($this->curPage-1)."</a></li>";
		
		$string .= "<li class='active'><a href='?page=".$this->curPage.$postFix."'>".$this->curPage."</a></li>";
		
		if($this->curPage <= $this->totPage-1) $string .= "<li><a href='?page=".($this->curPage+1).$postFix."'>".($this->curPage+1)."</a></li>";
		if($this->curPage <= $this->totPage-2) $string .= "<li><a href='?page=".($this->curPage+2).$postFix."'>".($this->curPage+2)."</a></li>";
		if($this->curPage <= $this->totPage-3 && $this->curPage <= 2) $string .= "<li><a href='?page=".($this->curPage+3).$postFix."'>".($this->curPage+3)."</a></li>";
		if($this->curPage <= $this->totPage-4 && $this->curPage == 1) $string .= "<li><a href='?page=".($this->curPage+4).$postFix."'>".($this->curPage+4)."</a></li>";
		
		$string .= "<li".($this->curPage == $this->totPage ? " class='disabled'" : "")."><a href='?page=".$this->totPage.$postFix."'>&raquo;</a></li>";
		
		$string .= "</ul></nav>";
		return $string;
	}
}
?>