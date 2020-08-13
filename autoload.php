<?php
function ruturndata($type,$url){
$data = file_get_contents($url);
$data = base64_encode($data);
$playload = '{
    "Parameters": [
        {
            "Name": "File",
            "FileValue": {
                "Name": "'.basename($url).'",
                "Data": "'.$data.'"
            }
        },
        {
            "Name": "StoreFile",
            "Value": true
        }
    ]
}';

// Prepare new cURL resource
$ch = curl_init('https://v2.convertapi.com/convert/'.$type.'/to'.'/pdf?Secret=3mI7vWnlb36ozE7A&StoreFile=true');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $playload);

// Set HTTP Header for POST request 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($playload))
);

// Submit the POST request
$result = curl_exec($ch);
$output = json_decode($result,true);
return $output['Files'][0]['Url'];
// Close cURL session handle
curl_close($ch);
}

function viewoffice($type,$urln,$width,$height){
    $c = "_officecatche_/".md5(file_get_contents($urln)).".pdf";
  if(file_exists($c)){
?>
 <embed src="<?php echo $c ?>" width="<?php echo  $width ?>" height="<?php echo  $height ?>"></embed>
<?php
  }else{
    

if(!is_dir("_officecatche_")){
  mkdir("_officecatche_");
      }
  $urldata = ruturndata($type,$urln);
  $contents = file_get_contents($urldata);
  $filename = md5(file_get_contents($urln));
  $file = fopen("_officecatche_/".$filename.".pdf","w");
  fwrite($file,$contents);
  ?>
   <embed src="<?php echo "_officecatche_/".$filename.".pdf" ?>" width="<?php echo  $width ?>" height="<?php echo  $height ?>"></embed>
  <?php
  
  }
}

  ?>

  
   
