<?php
if ( !isset($_SESSION) ) {
	session_start(); 
}

$_SESSION['progress']=0;
session_write_close();

require("key.php");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!--
		First, include the main jQuery and jQuery UI javascripts (not included with reformed; you may use Google's CDN links as below:)
	-->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
		<!--
		Next, include links to the form's CSS, taking care to ensure the correct paths dependent upon where you have uploaded the files
		contained within the reformed.zip and the reformed-form-(YOUR-THEME-HERE).zip files.

		Be sure to edit the line:
		<link rel="stylesheet" href="css/reformed-form-YOUR-THEME/jquery-ui-1.8.7.custom.css" type="text/css" />
		replacing "YOUR-THEME" with the name of your theme (in this case, it's ui-lightness).
	-->
		<!-- necessary reformed CSS -->
		<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="reformed/css/ie_fieldset_fix.css" />
	<![endif]-->
		<!--
			<link rel="stylesheet" href="reformed/css/uniform.aristo.css" type="text/css" />
		<link rel="stylesheet" href="reformed/css/ui.reformed.css" type="text/css" />
		<link rel="stylesheet" href="reformed/css/jquery-ui-1.8.7.custom.css" type="text/css" />
		-->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
		<!-- end necessary reformed CSS -->

		<!--
		Finally, include the necessary javascript to enable the validation rules and style the form.

		Be sure to edit the line:
		$('#YOURFORMID').reformed().validate();
		and replace YOURFORMID with the actual id attribute's value of your form (e.g., "demo" below).
	-->
		<!-- necessary reformed js -->
		<script src="reformed/js/jquery.uniform.min.js" type="text/javascript"></script>
		<script src="reformed/js/jquery.validate.min.js" type="text/javascript"></script>
		<script src="reformed/js/jquery.ui.reformed.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(function() { //on doc ready
				//set validation options
				//(this creates range messages from max/min values)
				$.validator.autoCreateRanges = true;
				$.validator.setDefaults({
					highlight: function(input) {
						$(input).addClass("ui-state-highlight");
					},
					unhighlight: function(input) {
						$(input).removeClass("ui-state-highlight");
					},
					errorClass: 'error_msg',
					wrapper: 'dd',
					errorPlacement: function(error, element) {
						error.addClass('ui-state-error');
						error.prepend('<span class="ui-icon ui-icon-alert"></span>');
						error.appendTo(element.closest('dl.ui-helper-clearfix').effect('highlight', {},
							2000));
					}
				});

				//call reformed on your form
				$('#ShelfLister').reformed().validate();
			});
		</script>
		<!-- end necessary reformed js -->
		<!-- start lookup Ajax js -->
		<script type="text/javascript">
			function AjaxFunction() {
				var httpxml;
				try {
					// Firefox, Opera 8.0+, Safari
					httpxml = new XMLHttpRequest();
				} catch (e) {
					// Internet Explorer
					try {
						httpxml = new ActiveXObject("Msxml2.XMLHTTP");
					} catch (e) {
						try {
							httpxml = new ActiveXObject("Microsoft.XMLHTTP");
						} catch (e) {
							alert("Your browser does not support AJAX!");
							return false;
						}
					}
				}

				function stateck() {
					if (httpxml.readyState == 4) {
						var myarray = JSON.parse(httpxml.responseText);
						// Remove the options from 2nd dropdown list
						for (j = document.ShelfLister.location.options.length - 1; j >= 0; j--) {
							document.ShelfLister.location.remove(j);
						}

						for (i = 0; i < myarray.locationData.length; i++) {
							var optn = document.createElement("OPTION");
							optn.text = myarray.locationData[i].name;
							optn.value = myarray.locationData[i].code;
							document.ShelfLister.location.options.add(optn);
						}
					}
				} // end of function stateck
				var url = "almaLocationsAPI.php";
				var cat_id = document.getElementById('library').value;
				url = url + "?lib_id=" + cat_id;
				url = url + "&sid=" + Math.random();
				httpxml.onreadystatechange = stateck;
				httpxml.open("GET", url, true);
				httpxml.send(null);
			} 
		</script>
		<!-- end location lookup Ajax js -->
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1>Inventory Report</h1>
					<div>Fill in form and submit</div>
				</div>
			</div>
			<form method="post" name="ShelfLister" id="ShelfLister"
				action="<?php echo 'http://' . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) . 'process_barcodes.php'; ?>"
				enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-12">
						<label for="file" class="form-label">Barcode XLSX File</label>
						<input type="file" id="file" class="required form-control" name="file" accept=".xlsx" />
						<div id="barcode-help" class="form-text">Select Excel (.xlxs) file from your comupter.</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="cnType" id="cnTypelc" value="lc" checked="checked" />
							<label class="form-check-label" for="cnTypelc">
								LC
							</label>
							<label for="cnType">Call Number Type</label>
							<input type="radio" class="required" id="cnType" name="cnType" value="lc" checked="checked" />
							<label>LC</label>
							<input type="radio" class="required" id="cnType" name="cnType" value="dewey" />
							<label>Dewey</label>
							<input type="radio" class="required" id="cnType" name="cnType" value="other" />
							<label>Other</label>
						</div>
					</div>
				</div>
				<dl>
					<dt>
						
					</dt>
					<dd>
						<ul>
							<li><input type="radio" class="required" id="cnType" name="cnType" value="lc"
									checked="checked" />
								<label>LC</label>
							</li>
							<li><input type="radio" class="required" id="cnType" name="cnType" value="dewey" />
								<label>Dewey</label>
							</li>
							<li><input type="radio" class="required" id="cnType" name="cnType" value="other" />
								<label>Other</label>
							</li>
						</ul>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="library">Library</label>
					</dt>
					<dd>
						<select size="1" name="library" id="library" class="required" onchange=AjaxFunction();>
<?Php
	$ch = curl_init();
	$url = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/conf/libraries';
	$queryParams = '?' . urlencode('lang') . '=' . urlencode('en') . '&' . urlencode('apikey') . '=' . ALMA_SHELFLIST_API_KEY;
	curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	curl_close($ch);
	$xml_result = simplexml_load_string($response);

	// PARSE RESULTS
	foreach($xml_result->library as $library) {
		echo "<option value=$library->code>$library->name</option>";
	}
?>
						</select>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="location">Scan Location</label>
					</dt>
					<dd>
						<select size="1" name="location" id="location" class="required">
						</select>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="itemType">Primary Item<BR> Type for Scanned Location</label>
					</dt>
					<dd>
						<select size="1" name="itemType" id="itemType" class="required">
							<option value="BOOK">Book</option>
							<option value="PERIODICAL">Periodical</option>
							<option value="DVD">DVD</option>
							<option value="THESIS">Thesis</option>
						</select>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="itemType">Primary Policy<BR> Type for Scanned Location</label>
					</dt>
					<dd>
						<select size="1" name="policy" id="policy" class="required">
							<option value="core">Core</option>
							<option value="reserve">Reserve</option>
							<option value="cont lit">Contemporary Lit</option>
							<option value="media">Media</option>
							<option value="juvenile">Juvenile</option>
						</select>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="cnType">Only Report<BR>CN Order Problems?</label>
					</dt>
					<dd>
						<ul>
							<li><input type="radio" class="required" id="onlyOrder" name="onlyorder" value="false"
									checked="checked" />
								<label>No</label>
							</li>
							<li><input type="radio" class="required" id="onlyOrder" name="onlyorder" value="true" />
								<label>Yes</label>
							</li>
						</ul>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="cnType">Only Report<BR>Problems Other Than CN?</label>
					</dt>
					<dd>
						<ul>
							<li><input type="radio" class="required" id="onlyOrder" name="onlyother" value="false"
									checked="checked" />
								<label>No</label>
							</li>
							<li><input type="radio" class="required" id="onlyOrder" name="onlyother" value="true" />
								<label>Yes</label>
							</li>
						</ul>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="cnType">Report Only<BR> Problems?</label>
					</dt>
					<dd>
						<ul>
							<li><input type="radio" class="required" id="onlyProblems" name="onlyproblems" value="false"
									checked="checked" />
								<label>No</label>
							</li>
							<li><input type="radio" class="required" id="onlyProblems" name="onlyproblems" value="true" />
								<label>Yes</label>
							</li>
						</ul>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="cnType">Clear Cache?</label>
					</dt>
					<dd>
						<ul>
							<li><input type="radio" class="required" id="clearCache" name="clearCache" value="false"
									checked="checked" />
								<label>No</label>
							</li>
							<li><input type="radio" class="required" id="clearCache" name="clearCache" value="true" />
								<label>Yes</label>
							</li>
						</ul>
					</dd>
				</dl>
				<div id="submit_buttons">
					<input type="submit" name="submit" />
				</div>
			</form>
		</div>
	</body>
</html>