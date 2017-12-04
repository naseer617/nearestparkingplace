<?php



$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "parkman";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


function updateCountries($conn){
	$total = 125547;
	$batchSize = 50;

	$i = 0;

	while($i < $total){
		$sql = "SELECT id,lat,lng FROM garages ORDER BY lat ASC LIMIT ".$i.",".$batchSize;
		$result = $conn->query($sql);

		$delete = [];
		$update = [];

		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		        $country = getCountry($row['lat'], $row['lng']);

		        if($country == NULL){
		        	$delete[] = $row['id'];
		        }else{
		        	$update[$row['id']] = $country;
		        }

		    }
		} else {
		    echo "0 results";
		}

		if(count($delete)){
			$sql = "DELETE FROM garages WHERE id IN('".implode("','", $delete)."')";
			$conn->query($sql);
		}
		
		foreach($update as $rowId => $address){
			$sql = "UPDATE garages SET country = '".$address."' WHERE id ='".$rowId."'"	;
			$conn->query($sql);
		}

		$i = $i + $batchSize;	

		echo "** ".$i." completed \n";
		echo "*".count($delete)." deleted \n";
		echo "*".count($update)." updated. \n\n";
	}
}


function getCountry($lat, $lng){

	$apiKey = "AIzaSyCqHtoANjbEjDsOhiJy6-1D1VBKOhR1Q7k";
	$qry_str = "?latlng=".$lat.",".$lng."&sensor=true&key=".$apiKey;
	$ch = curl_init();

	// Set query data here with the URL
	curl_setopt($ch, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/geocode/json' . $qry_str); 

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, '3');
	$content = json_decode(trim(curl_exec($ch)),true);
	curl_close($ch);

	if(isset($content['results'][0]['formatted_address'])){
		return $content['results'][0]['formatted_address'];
	}else{
		print_r($content);
		return NULL;
	}
}


function setOwnerNames($conn){

	$sql= 'UPDATE garages SET owner = "Tampere Rautatientori" WHERE MOD(id,51) = 0';
	$conn->query($sql);


	$sql= 'UPDATE garages SET owner = "Punavuori Garage" WHERE MOD(id,79) = 0';
	$conn->query($sql);


	$sql= 'UPDATE garages SET owner = "Unknown" WHERE MOD(id,90) = 0';
	$conn->query($sql);


	$sql= 'UPDATE garages SET owner = "Fitnesstukku" WHERE MOD(id,111) = 0';
	$conn->query($sql);

	$sql= 'UPDATE garages SET owner = "Kauppis" WHERE MOD(id,120) = 0';
	$conn->query($sql);


	$sql= 'UPDATE garages SET owner = "Q-Park1" WHERE MOD(id,31) = 0';
	$conn->query($sql);

}



//updateCountries();
setOwnerNames($conn);
$conn->close();

?>