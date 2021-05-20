<?php
	require_once('lib/nusoap.php');
	$error  = '';
	$result = array();
	$response = '';
	$wsdl = "http://localhost/hotelsoap/server.php?wsdl";
	if(isset($_POST['sub'])){
		$no_hp = trim($_POST['no_hp']);
		if(!$no_hp){
			$error = 'Nomor Handphone Tidak Terdaftar!.';
		}

		if(!$error){
			//Buat objek klien
			$client = new nusoap_client($wsdl, true);
			$err = $client->getError();
			if ($err) {
				echo '<h2>Terdapat Kesalahan</h2>' . $err;
				// Pada point ini, Anda tahu panggilan berikutnya akan gagal
			    exit();
			}
			 try {
				$result = $client->call('DataKamar', array($no_hp));
				$result = json_decode($result);
			  }catch (Exception $e) {
			    echo 'Error: ',  $e->getMessage(), "\n";
			 }
		}
	}	

	/* Tambahkan Kamar Baru **/
	if(isset($_POST['addbtn'])){
		$atas_nama = trim($_POST['atas_nama']);
		$alamat = trim($_POST['alamat']);
		$no_hp = trim($_POST['no_hp']);
		$ket_kamar = trim($_POST['ket_kamar']);

		//Lakukan semua validasi yang diperlukan di sini
		if(!$no_hp || !$atas_nama || !$alamat || !$ket_kamar){
			$error = 'All fields are required.';
		}

		if(!$error){
			//Membuat Objek Klien
			$client = new nusoap_client($wsdl, true);
			$err = $client->getError();
			if ($err) {
				echo '<h2>Constructor error</h2>' . $err;
				//Pada point ini, Anda tahu panggilan berikutnya akan gagal
			    exit();
			}
			 try {
				/** Memanggil Metode Masukan Kamar*/
				 $response =  $client->call('InsertKamar', array($atas_nama, $alamat, $no_hp, $ket_kamar));
				 $response = json_decode($response);
			  }catch (Exception $e) {
			    echo 'Error: ',  $e->getMessage(), "\n";
			 }
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Booking Hotel</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <script src="assets/jquery/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

<!-- As a heading -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1">HOTEL SEMENTAWIS</span>
  </div>
</nav>

<div class="container">
<br>
<h2>Mencari Kamar</h2>
  <p>Masukan <strong>Nomor Handphone</strong> pada <strong>Masukan Nomor HP</strong> lalu klik tombol <strong>Cari</strong>.</p>
  <div class='row'>
  	<form class="form-inline" method = 'post' name='form1'>
  		<?php if($error) { ?> 
	    	<div class="alert alert-danger fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Error!</strong>&nbsp;<?php echo $error; ?> 
	        </div>
		<?php } ?>
	    <div class="form-group">
	      <label class="form-label">Nomor Handphone:</label>
	      <input class="form-control" name="no_hp" id="no_hp" placeholder="Masukan Nomor HP" required>
	    </div>
        <br>
	    <button type="submit" name='sub' class="btn btn-primary">Cari</button>
    </form>
   </div>
   <br />
   <h2>Informasi Kamar</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Atas Nama</th>
        <th>Alamat</th>
        <th>Nomor Handphone</th>
        <th>Keterangan Kamar</th>
      </tr>
    </thead>
    <tbody>
    <?php if($result){ ?>
      	
		      <tr>
		        <td><?php echo $result->atas_nama; ?></td>
		        <td><?php echo $result->alamat; ?></td>
		        <td><?php echo $result->no_hp; ?></td>
		        <td><?php echo $result->ket_kamar; ?></td>	
		      </tr>
      <?php 
  		}else{ ?>
  			<tr>
		        <td colspan='4'>Masukan Nomor Handphone dan click pada tombol cari untuk melihat hasil booking.</td>
		      </tr>
  		<?php } ?>
    </tbody>
  </table>
	<div class='row'>
	<h2>Booking Kamar Baru</h2>
	 <?php if(isset($response->status)) {

	  if($response->status == 200){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> Kamar Berhasil Ditambahkan.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
	  <?php }elseif(isset($response) && $response->status != 200) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Tidak Bisa Menambahkan Kamar, Coba Lagi.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
	 <?php } 
	 }
	 ?>
  	<form class="form-inline" method = 'post' name='form1'>
  		<?php if($error) { ?> 
	    	<div class="alert alert-danger fade in">
    			<a href="#" class="close" data-dismiss="alert">&times;</a>
    			<strong>Error!</strong>&nbsp;<?php echo $error; ?> 
	        </div>
		<?php } ?>
	    <div class="form-group">
	      <label for="email"></label>
	      <input type="text" class="form-control" name="atas_nama" id="atas_nama" placeholder="Masukan Nama" required>
				<input type="text" class="form-control" name="alamat" id="alamat" placeholder="Masukan Alamat" required>
				<input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="Masukan Nomor HP" required>
                <select class="form-select" aria-label="Default select example" name="ket_kamar" id="ket_kamar" required>
                <option selected>Keterangan Kamar : </option>
                <option value="VIP">VIP</option>
                <option value="Biasa">Biasa</option>
                </select>
				
	    </div>
        <br>
	    <button type="submit" name='addbtn' class="btn btn-primary">Booking</button>
        <br>
    </form>
   </div>
</div>
<br>



</body>
</html>



