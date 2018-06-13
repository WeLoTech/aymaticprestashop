<?php
/**
 * This source file is subject to the Open Software License (OSL 3.0)
 *  @author    Markus Peitl <mp@welovetech.at>
 *  @copyright 2018 WeLo Tech GmbH
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../..//init.php');

//$encryptpassword = "asfasfsdgsdfhdfhdfhdfh"; // should be the same as api key
//$jsonString = "{\"videoEmbedUrl\":\"http://localhost:4200/#/video-embed?uid=httpssportgigantatpferdesportuhren26422polarpferdesportpulsuhrequinem400schwarz725882022468html\",\"productPageUrl\":\"https://sportgigant.at/pferdesportuhren/26422-polar-pferdesport-pulsuhr-equine-m400-schwarz-725882022468.html\",\"thumbNailUrl\":\"https://sportgigant.at/52554-thickbox_default/polar-pferdesport-pulsuhr-equine-m400-schwarz.jpg\"}";
//$postMessage = encryptEncode($jsonString,$encryptpassword);
//$_POST['message'] = $postMessage;

$transferMessage = $_POST['message'];
if(isset($transferMessage)){
	//echo "\n\n\nPost was a success\n";
	//echo "RECEIVED MESSAGE: " . $_POST['test'] . "\n\n";
	//$restoredsecret = base64_decode($_POST['test']);
	//echo "DECODED64 MESSAGE: " . $restoredsecret . "\n\n";
	//$restoredsecret = $_POST['test'];
	
	$api_key = Configuration::get('API_KEY');
	$reconstructedMessage = decodeDecrypt($transferMessage,$api_key);
	//echo $reconstructedMessage;
	if($reconstructedMessage == null){
		//echo "Failed hash check: ".$result;
		http_response_code(403);
		echo "Integritiy Check failed";
	}
	else{
		$tableName = "productvideos";

		$testmode = false;
		$testproductcount = 8;

		$defaultEnable = true;
		
		$jsonObj = json_decode($reconstructedMessage);

		if(isset($jsonObj->{'videoEmbedUrl'}) && isset($jsonObj->{'productPageUrl'}) && isset($jsonObj->{'thumbNailUrl'})){

			//echo 'videoEmbedUrl: ' . $jsonObj->{'videoEmbedUrl'} . "----";
			//echo 'productPageUrl: ' . $jsonObj->{'productPageUrl'} . "----";
			//echo 'thumbNailUrl: ' . $jsonObj->{'thumbNailUrl'} . "----";
			//echo 'Reconst Product ID: '.reconstructProductId($jsonObj->{'productPageUrl'});
			$extractedid = (int)reconstructProductId($jsonObj->{'productPageUrl'});
			if($testmode){
				$extractedid = ($extractedid % $testproductcount) + 1;
			}
			

			$entry = array(
				'id_product' => (int)$extractedid,
				'is_enable' => (int)$defaultEnable,
				'video_url' => pSQL($jsonObj->{'videoEmbedUrl'}),
				'thubmnail_url'=> pSQL($jsonObj->{'thumbNailUrl'}),
				'product_url' => pSQL($jsonObj->{'productPageUrl'})
			);

			$sql = 'SELECT * FROM '._DB_PREFIX_.$tableName.' WHERE id_product = ' . (int)$extractedid;
			//echo $sql;
			if ($row = Db::getInstance()->getRow($sql)){
				//echo "ID EXISTS: " . $row['is_enable'];
				$entry['is_enable'] = (int)$row['is_enable'];
				//$updateQuery = "UPDATE * FROM "._DB_PREFIX_.$tableName." SET (is_enable,video_url,thubmnail_url,product_url) VALUES('".$entry['is_enable']."','".$entry['video_url']."','".$entry['thubmnail_url']."','".$entry['product_url']."') WHERE id_product=".(int)$extractedid;
				//Db::getInstance()->Execute($updateQuery);
				Db::getInstance()->update($tableName,$entry, 'id_product = ' . (int)$extractedid);
			}
			else{
				Db::getInstance()->insert($tableName,$entry);
			}
			http_response_code(200);
			echo "OK";
		}
		else{
			http_response_code(403);
			echo "Failed retrieving values";
		}
	}
}
else{
	http_response_code(400);
	echo "Nothing was sent";
}
function reconstructProductId($productUrl){
	$urlParts = explode( '/', $productUrl);
	//$canndidates = array();
	$idPart = null;
	for($i = 0; $i < sizeof($urlParts) && $idPart == null; $i++){
		if(isset($urlParts[$i]) && $urlParts[$i]!="" && is_numeric($urlParts[$i][0])){
			//$canndidates.push($urlParts[i]);
			$idPart = $urlParts[$i];
		}
	}
	//echo $idPart;
	$idnumberstring = "";
	//$idnumber = -1;
	$finished = false;
	for($i = 0; $i < strlen($idPart) && !$finished; $i++){
		//echo $idPart[$i] . "##";
		if(is_numeric($idPart[$i])){
			//echo "*number*";
			$idnumberstring=$idnumberstring.$idPart[$i];
		}
		else{
			$finished = true;
		}
	}
	//echo $idnumberstring;
	return intval($idnumberstring);
}

function encryptEncode($plainText,$password){
	$encryptedString = encrypt($plainText,$password);
	$encodedString = base64_encode($encryptedString);
	return $encodedString;
}

function decodeDecrypt($base64String,$password){
	$decodedData = base64_decode($base64String);
	return decrypt($decodedData,$password);
}

function encrypt($plaintext, $password) {
    $method = "aes-256-cbc";
    $key = hash('md5', $password, false);
    $iv = openssl_random_pseudo_bytes(16);

    $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    $hash = hash_hmac('sha256', $ciphertext, $key, true);

    return $iv . $hash . $ciphertext;
}

function decrypt($ivHashCiphertext, $password) {
    $method = "aes-256-cbc";
    $iv = substr($ivHashCiphertext, 0, 16);
	//echo "IV:" . $iv . "\n\n";
    $hash = substr($ivHashCiphertext, 16, 32);
	//echo "HASH:" . $hash . "\n\n";
    $ciphertext = substr($ivHashCiphertext, 48);
	//echo "CIPHERTEXT:" . $ciphertext . "\n\n";
    $key = hash('md5', $password, false);
	//echo "HashedPW:" . $key . "\n\n";
	//echo "NEW HASH:" . hash_hmac('sha256', $ciphertext, $key, true) . "\n\n";
    if (hash_hmac('sha256', $ciphertext, $key, true) !== $hash) return null;

    return openssl_decrypt( $ciphertext, $method, $key,OPENSSL_RAW_DATA, $iv);
}


//if($_POST['message']){
	//echo "\n\n\nPost was a success\n";
	//echo "RECEIVED MESSAGE: " . $_POST['test'] . "\n\n";
	
	//$restoredsecret = base64_decode($_POST['test']);
	
	//echo "DECODED64 MESSAGE: " . $restoredsecret . "\n\n";
	
	//$restoredsecret = $_POST['test'];
	
	/*$result = decodeDecrypt($_POST['message'],"asoidfjisdjgpisppdgosdgpoj");
	if($result == null){
		//echo "Failed hash check: ".$result;
		echo "Integritiy Check failed";
	}
	else{
		echo "OKccc";
		//echo "WhyPlainText: ".$result;
	}*/
	
	//$backmessage = "this is my response!";
	
	//$encodedData = encryptEncode($result,"whoisyourpassword");
	//$plainText = decodeDecrypt($encodedData,"whoisyourpassword");
	//echo "\n".$plainText;
	
	//echo $encodedData;
	
	/*$response = array(
		"response"=>$encodedData
	);*/
	//header('Content-Type: application/json');
	//echo ($response);
	//echo json_encode($response);
	
	//echo "ENC:MESSAGE: " . encrypt("Hail to my message","whoisyourpassword");
//}

/*function encrypt($plaintext, $password) {
    $method = "aes-256-cbc";
	$key = bin2hex(hash('md5', $password, true));
	$key = bin2hex(hash('md5', $password, true));
	echo "\nENC:HashedPW:" . $key . "\n\n";
    $iv = base64_encode(openssl_random_pseudo_bytes(16));
	echo "ENC:IV:" . $iv . "\n\n";
    $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
	echo "ENC:CIPHERTEXT:" . $ciphertext . "\n\n";
    $hash = hash_hmac('sha256', $ciphertext, $key, true);
	echo "ENC:HASH:" . $hash . "\n\n";

    return $iv . $hash . $ciphertext;
}*/

/*function decrypt($ivHashCiphertext, $password) {
    $method = "aes-256-cbc";
    $iv = substr($ivHashCiphertext, 0, 16);
	echo "IV:" . $iv . "\n\n";
    $hash = substr($ivHashCiphertext, 16, 64);
	echo "HASH:" . $hash . "\n\n";
    $ciphertext = substr($ivHashCiphertext, 16+64);
	echo "CIPHERTEXT:" . $ciphertext . "\n\n";
    $key = hash('md5', $password, false);
	//$key = md5($password);
	echo "HashedPW:" . $key . "\n\n";
	echo "NEW HASH:" . hash_hmac('sha256', $ciphertext, $key, false) . "\n\n";

    if (hash_hmac('sha256', $ciphertext, $key, false) !== $hash) return "ddd";

    return openssl_decrypt( hex2bin($ciphertext), $method, hex2bin($key),OPENSSL_RAW_DATA, hex2bin($iv));
}*/


/*function decrypt2($ivHashCiphertext, $password) {
    $method = "AES-256-CBC";
    $iv = substr($ivHashCiphertext, 0, 16);
    $hash = substr($ivHashCiphertext, 16, 32);
    $ciphertext = substr($ivHashCiphertext, 48);
    $key = hash('sha256', $password, true);

    if (hash_hmac('sha256', $ciphertext, $key, true) !== $hash) return null;

    return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
}*/

/*if($_GET['test']){
	echo "Hi i am test: ".$_GET['test'];
}

$dirName = "productvideos";

$query = "select * from productvideos";

$entry = array(
	'is_enable' => true,
	'video_url' => "test",
	'thubmnail_url'=> "thumbnail",
	'product_url' => "product_url"
);

Db::getInstance()->insert($dirName,$entry);

if ($results = Db::getInstance()->ExecuteS($query)){
    foreach ($row in $results)
        echo $row['id_product'].' -- '.$row['is_enable'].' -- '.$row['video_url'].' -- '.$row['thubmnail_url'].' -- '.$row['product_url'].'<br />';
}

echo "At the end";*/