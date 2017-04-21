<?php
include('simple_html_dom.php');

/** For Main Page */

// Create DOM from URL or file
$html = file_get_html('http://www.specguideonline.com/search/crawler-dozers-lgp');
$count = 0; //counter variable
foreach($html->find('table tbody tr') as $table) { //traverse through the table and get <td> content
	
	if($count == 2){
		break;
	}

	$item['make']     	 = $table->find('.column1', 0)->plaintext;
	$item['model']   	 = $table->find('.column2', 0)->plaintext;
	$item['category']    = $table->find('.column3', 0)->plaintext;
	$item['url']    	 = @$table->find('.column7 a', 0)->attr['href'];
	
	if(!empty($item['url'])){
		$item['product_data'] = getInnerData($item['url'] );
	}else{
		$item['product_data'] = array();
	}
	

	$data[] = $item;
   	
   	//var_dump($table->find('.column7 a', 0)->attr['href']);

   	//echo $table;

	$count++;

}
echo '<pre>';
print_r($data);

/** End */

/** For Product page */
function getInnerData($url){
	$url = 'http://www.specguideonline.com'.$url;
	$html = file_get_html($url);
	foreach($html->find('.spec-grouping .spec') as $i => $parent) { 
		$item['parent']     	 = $parent->find('h2', 0)->plaintext;
		$fields = array();
		foreach($parent->find('table tbody tr') as $j => $sub){
			$fields[$j] = array(
				'name' 		=>  $sub->find('td.left', 0)->plaintext,
				'value'		=>	$sub->find('td.right', 0)->plaintext
			);
		} 
	    
	    $item['fields'] = $fields;

		$articles[] = $item;
	   	
	}

	return $articles;
}


	
?>