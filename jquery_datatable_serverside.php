<?php 
	// get products listing
	function getProducts(){
		// columns to get data from the products table 
		$aColumns = array('p.iProductId', 'p.vProductId','p.vSellerId','p.eStatus','c.vConditionName','p.dCreatedDate','p.dSoldDate','p.iPrice','p.vDescription','p.fDollarDiscount','p.fPercentDiscount','p.vOrderId');

		// colums on which sorting & searching will be performed
		$bColumns = array('','p.vProductId','p.vSellerId','p.eStatus','','','','','p.vOrderId','p.dCreatedDate','p.dSoldDate','p.iPrice','p.fDollarDiscount','p.fPercentDiscount','','p.vDescription','');


		$columnarray = array();
		foreach($bColumns as $key => $value):
			if($value == ''):
				$columnarray[] = $key;
			endif;
		endforeach;
		
		$coloumn = implode(",",$columnarray);

		$sIndexColumn = "p.iProductId";
		$sTable = "products p";
		$jTable1 = "conditions c";

		$getwhere = "";


		//Paging
		$sLimit = "";
		$pageI = '';
		if ($_GET['iDisplayStart'] != '' && $_GET['iDisplayLength'] != '-1') {
		    $sLimit = "LIMIT " . $_GET['iDisplayStart'] . ", " .
		            $_GET['iDisplayLength'];
		    $pageI = $_GET['iDisplayStart'];
		}

		//Ordering
		if (!empty($_GET['iSortCol_0'])) {
		    $sOrder = "ORDER BY  ";
		    for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
		        if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
		            $sOrder .= $bColumns[intval($_GET['iSortCol_' . $i])] . " " . $_GET['sSortDir_' . $i] . ", ";
		        }
		    }

		    $sOrder = substr_replace($sOrder, "", -2);
		    if ($sOrder == "ORDER BY") {
		        $sOrder = "iProductId DESC ";
		    }
		}
		else{
			 $sOrder = "ORDER BY  ";
			 $sOrder .= "iProductId DESC ";
		}

		// Filtering

		$sWhere = "";
		if (!empty($_GET['sSearch'])) {
		    $sWhere = "WHERE (";
		    for ($i = 0; $i < count($bColumns); $i++) {
		        if ($bColumns[$i] != "" && $bColumns[$i] != " ") {
		            $sWhere .= $bColumns[$i] . " LIKE '%" . $_GET['sSearch'] . "%' OR ";
		        }
		    }
		    $sWhere = substr_replace($sWhere, "", -3);
		    $sWhere .= ')';
		}

		if ($sWhere != '' && $getwhere!= '') {
		    $sWhere = $sWhere . " and " . $getwhere;
		} elseif ($getwhere != '') {
		    $sWhere = " where " . $getwhere;
		}
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . " FROM $sTable LEFT JOIN $jTable1 ON p.iConditionId=c.iConditionId";
		if(!empty($_GET['vUserId'])){
			$sQuery .= " ";    
		}
		$sGroupBy = ""; 
		$sQuery .= " " . $sWhere . " " . $sGroupBy ." ". $sOrder . " " . $sLimit;
		$rResult = $this->db->query($sQuery)->result_array();
		$sQuery = "SELECT FOUND_ROWS() as total";
		// Data set length after filtering
		$sQuery = "SELECT FOUND_ROWS() as total";
		$rResultFilterTotal = $this->db->query($sQuery)->result();
		$iFilteredTotal = $rResultFilterTotal[0]->total;
		// Total data set length
		$sQuery = " SELECT COUNT(" . $sIndexColumn . ") as count FROM $sTable ";
		$aResultTotal = $this->db->query($sQuery)->result();
		$iTotal = $aResultTotal[0]->count;
		//OUTPUT
		$output = array(
		    "sEcho" => intval(!empty($_GET['sEcho']) ? $_GET['sEcho'] : ''),
		    "iTotalRecords" => $iFilteredTotal,
		    "iTotalDisplayRecords" => $iFilteredTotal,
		    "aaData" => array(),
		    'emptyColumn' => $coloumn,
		);

		$i = 1 + $pageI;
		//pr($rResult,1);
		foreach ($rResult as $aRow) { //pr($aRow);
		    $row = array();
		    $vImages = array();
		    $iProductId = !empty($aRow["iProductId"]) ? $aRow["iProductId"] : '';
			
		    $row[] = $i;
		    $row[] = $vProductId;

		  
		    $output['aaData'][] = $row;
		    $i++;
		}
 		return $output; 
   }

?>