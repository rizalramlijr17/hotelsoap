<?php
 require_once('koneksi.php');
 require_once('lib/nusoap.php'); 
 $server = new nusoap_server();

 /* Metode untuk menambahkan kamar baru */
function InsertKamar($atas_nama, $alamat, $no_hp, $ket_kamar){
  global $dbconn;
  $sql_insert = "insert into kamar (atas_nama, alamat, no_hp, ket_kamar) values ( :atas_nama, :alamat, :no_hp, :ket_kamar)";
  $stmt = $dbconn->prepare($sql_insert);
  // Menambahkan baris baru
  $result = $stmt->execute(array(':atas_nama'=>$atas_nama, ':alamat'=>$alamat, ':no_hp'=>$no_hp, ':ket_kamar'=>$ket_kamar));
  if($result) {
    return json_encode(array('status'=> 200, 'msg'=> 'success'));
  }
  else {
    return json_encode(array('status'=> 400, 'msg'=> 'fail'));
  }
  
  $dbconn = null;
  }
/* Mengambil 1 data kamar */
function DataKamar($no_hp){
	global $dbconn;
	$sql = "SELECT id, atas_nama, alamat, no_hp, ket_kamar FROM kamar 
	        where no_hp = :no_hp";
  // Menyiapkan parameter sql dan bind
    $stmt = $dbconn->prepare($sql);
    $stmt->bindParam(':no_hp', $no_hp);
    // insert a row
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($data);
    $dbconn = null;
}
$server->configureWSDL('kamarServer', 'urn:kamar');
$server->register('DataKamar',
			array('no_hp' => 'xsd:string'),  //parameter
			array('data' => 'xsd:string'),  //output
			'urn:kamar',   //namespace
			'urn:kamar#DataKamar' //soapaction
      );  
      $server->register('InsertKamar',
			array('atas_nama' => 'xsd:string', 'alamat' => 'xsd:string', 'no_hp' => 'xsd:string', 'ket_kamar' => 'xsd:string'),  //parameter
			array('data' => 'xsd:string'),  //output
			'urn:kamar',   //namespace
			'urn:kamar#DataKamar' //soapaction
			);  
$server->service(file_get_contents("php://input"));

?>