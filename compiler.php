<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Avios Script Minification Service Using Google Closure Compiler</title>
<link rel="stylesheet" type="text/css" href="css/base-styles.css">
<link rel="stylesheet" type="text/css" href="css/compiler-styles.css">
</head>

<body>

<div class="container test">
  <div class="row">
    <div class="col_12 header">
		<h2>Avios Javascript Minification Service</h2>
		<p> Use the browse button to choose the file you would like to minify, then hit the 'Minify Script Now' button</p>
	</div>
	
	<div class="col_8 pre_2 suf_2 get-js omega">
		<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<div class="get-file">
				<label for="file">Please Choose JS File:</label>
				<input type="file" name="file" id="file">
			</div>
			<div class="file-name selfclear">
				<label> Enter Desired Filename Please (without file suffix) :</label>
				<input name="filename" type="text" id="filename"  maxlength="300">
			</div>
			<div class="opt-level">
			<label> Enter Level Of Optimisation Required:</label>
			<select id="optim" name="optim">
			<option selected value="WHITESPACE_ONLY">Whitespace Only</option>
			<option value="SIMPLE_OPTIMIZATIONS">Simple Optimisations</option>
			<option value="ADVANCED_OPTIMIZATIONS">Advanced Optimisations</option>
			</select>
			<p>(For a Guide to Optimisation levels see <a href="https://developers.google.com/closure/compiler/docs/api-ref">This API Guide</a> )</p>
			</div>
			<div class="p-print">
			<label>Pretty Print Output? :</label>
			<input type="radio" checked Name ="pp" value="no"> No
			<input type="radio" Name ="pp" value="yes"> Yes			
			</div>
			<div class="sub-form">
				<input type="submit" name="submit" value="Minify Script Now" id="submit-btn">
			</div>
		</form>	
	</div>
	
  </div>
</div><!--class="container" -->


<!-- below is where the action happens -->	


<?php
// define path for packed scripts to be saved
define('SCRIPT_PATH','/js/packed-scripts'); 
// has submit been hit ? 
if (isset($_POST['submit'])) {
// ok, good - gimme the file selected	
$filename = $_FILES['file']['tmp_name'];
// and grab the contents
$output = file_get_contents($_FILES['file']['tmp_name']);
// what optimisation level does the user require
$optimisation_level = $_POST['optim'];

// API call using cURL  
// REST API arguments
// conditional depending on whether the user requires pretty print
	
	if($_POST['pp'] ==='yes') {
	
		$apiArgs = array(
			'compilation_level' => $optimisation_level,
			'output_format' => 'text',
			'output_info' => 'compiled_code',				
			'formatting' => 'pretty_print'
		); 
	} else {
		$apiArgs = array(
			'compilation_level' => $optimisation_level,
			'output_format' => 'text',
			'output_info' => 'compiled_code'			
		); 		
	}
// construct the agrument to be pass to the closure api	
	$args = 'js_code=' . urlencode($output);
	foreach ($apiArgs as $key => $value) {
		$args .= '&' . $key . '=' . urlencode($value);
	}
	
// API call using cURL
	$call = curl_init();
	curl_setopt_array($call, array(
		CURLOPT_URL => 'http://closure-compiler.appspot.com/compile',
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => $args,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_FOLLOWLOCATION => 0
	));
	$jscomp = curl_exec($call);
	curl_close($call);
	
	// if the string that comes back is empty, lets tell the user that - its a syntax / formatting error in their js 
	if(strlen($jscomp) <=1) {
		
	echo '<div class="container test"><div class="row"><div class="col_8 pre_2 suf_2 results"><h2>Your Result</h2>';	
	echo '<p> The output returned by the compiler was empty. You may have a formatting  / syntax error in your JavaScript. Please check your code, and attempt recompiliation.</p>';
	echo '</div></div></div><!--class="container"';	
// or output the file for the user 	
	} else {
	
	// get the server root
		$serverRoot = $_SERVER['DOCUMENT_ROOT'];
		//set the output directory for the compressed script
		$cachedir = $serverRoot. SCRIPT_PATH;
		// grab the required filename from the user, and suffix the .js extension
		$outputfile = $_POST['filename'] . '.js';	
		if ($fp = fopen($cachedir . '/' . $outputfile, 'w')) {
				fwrite($fp, $jscomp);
				fclose($fp);
	// if the write has taken place, echo the result out to the user, in our grid
			echo '<div class="container test"><div class="row"><div class="col_8 pre_2 suf_2 results"><h2>Your Result</h2>';	 
			echo '<p>length = ' .strlen($jscomp) . '</p>';
			echo '<p>Your file is named:<strong> ' .$outputfile . '</strong> in the <strong>' . $cachedir . '</strong> folder</p>';
		echo '</div></div></div><!--class="container"';	
		}
	} // close else 


} // close isset

?>
 </body>
</html>