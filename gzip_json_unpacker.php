<?php
	$tpl_main = <<<EOT
		<style>
			.error {border: 3px solid red; background-color: #fcc; padding: 2em; border-radius: 1em; font-weight: 900}
		</style>
		<h1>Binary String Unpacker</h1>
		<p>This tool is to convert the hexadecimal representation of a json-encoded and then gzipped content into human-readable JSON format.</p>
		<p>For example, copy the content of one record from the following result set into the below field and press the JSON Format Only button:<br>
		<pre>SELECT hex(selection) FROM bet_intercept</pre>
		</p>
		<form action="" method="post">
			Hexadecimal Data (in fce2...ff format, case-insensitive):
			<input type="text" name="hex_string" value="#1#" placeholde="01FCE2...033C, paste the hexadecimal digits here" autofocus="autofocus"><br>
			<input type="submit" name="btn_json_only" value="JSON Format Only">
			<input type="submit" name="btn_all" value="All formats">
		</form>
EOT;

	$tpl_error_message = <<<EOT
		<div class="error">#1#</div>
EOT;

	$json_only = isset($_POST["btn_json_only"]);

	$hex_string = trim($_POST["hex_string"]??"");
	$html = str_replace("#1#", $hex_string, $tpl_main);

	try {
		if ($hex_string!="") {
			$bin = hex2bin($hex_string);
			if ($bin===false) {
				throw new Exception("Error: wrong hexadecimal string. It must contain even number of hexadecimal digits.");
			}

			$uncompressed = gzuncompress($bin);
			if ($uncompressed===false) {
				throw new Exception("Error: uncompressing error.");
			}

			$json_decoded = json_decode($uncompressed);
			if ($json_decoded===null) {
				throw new Exception("Error: uncompressed content cannot be JSON-decoded.");
			}

			$json_formatted = json_encode($json_decoded, JSON_PRETTY_PRINT);

			if ($json_only) {
				header("Content-type: application/json");
				print $uncompressed;
			} else {
				print $html;

				print "<h2>Binary:</h2>";
				var_dump($bin);

				print "<h2>Uncompressed:</h2>";
				var_dump($uncompressed);

				print "<h2>JSON-decoded:</h2>";
				print "<pre>";
				print_r($json_formatted);
			}
		} else {
			print $html;
		}
	} catch (Exception $e) {
		print $html;
		print str_replace("#1#", $e->getMessage(), $tpl_error_message);
	}

