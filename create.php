<?php
require_once "config.php";

$nama_merk = $warna = $jumlah = "";
$nama_merk_err = $warna_err = $jumlah_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $input_name = trim($_POST["nama_merk"]);
    if(empty($input_name)){
        $nama_merk_err = "Mohon masukkan merk mobil.";
    } else{
        $nama_merk = $input_name;
    }

    $input_warna = trim($_POST["warna"]);
    if(empty($input_warna)){
        $warna_err = "Mohon masukkan warna mobil.";
    } else{
        $warna = $input_warna;
    }

    $input_jumlah = trim($_POST["jumlah"]);
    if(empty($input_jumlah)){
        $jumlah_err = "Mohon masukkan kuantitas mobil.";
    } elseif(!ctype_digit($input_jumlah)){
        $jumlah_err = "Mohon masukkan jumlah yang valid.";
    } else{
        $jumlah = $input_jumlah;
    }

    if(empty($nama_merk_err) && empty($warna_err) && empty($jumlah_err)){
        $sql = "INSERT INTO data_mobil (nama_merk, warna, jumlah) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_nama_merk, $param_warna, $param_jumlah);

            $param_nama_merk = $nama_merk;
            $param_warna = $warna;
            $param_jumlah = $jumlah;

            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
                exit();
            } else{
                echo "Ada kesalahan. Silahkan mencoba lagi!";
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Tambah Data Mobil</h2>
                </div>
                <p>Mohon isi form berikut untuk menambahkan data mobil.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($nama_merk_err)) ? 'has-error' : ''; ?>">
                        <label>Merk</label>
                        <input type="text" name="nama_merk" class="form-control" value="<?php echo $nama_merk; ?>">
                        <span class="help-block"><?php echo $nama_merk_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($warna_err)) ? 'has-error' : ''; ?>">
                        <label>Warna</label>
                        <input type="text" name="warna" class="form-control" value="<?php echo $warna; ?>">
                        <span class="help-block"><?php echo $warna_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($jumlah_err)) ? 'has-error' : ''; ?>">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" value="<?php echo $jumlah; ?>">
                        <span class="help-block"><?php echo $jumlah_err;?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-default">Cancel</a>
                    <button type="reset" value="Reset" class="btn btn-danger">Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>