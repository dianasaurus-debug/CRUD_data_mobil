<?php
require_once "config.php";

$nama_merk = $warna = $jumlah = "";
$nama_merk_err = $warna_err = $jumlah_err = "";

if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

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
        $sql = "UPDATE data_mobil SET nama_merk=?, warna=?, jumlah=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sssi", $param_nama_merk, $param_warna, $param_jumlah, $param_id);

            $param_nama_merk = $nama_merk;
            $param_warna = $warna;
            $param_jumlah = $jumlah;
            $param_id = $id;

            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
                exit();
            } else{
                echo "Ada kesalahan. Silahkan mencoba lagi!";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
} else{
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  trim($_GET["id"]);

        $sql = "SELECT * FROM data_mobil WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            $param_id = $id;

            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    $nama_merk = $row["nama_merk"];
                    $warna = $row["warna"];
                    $jumlah = $row["jumlah"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        mysqli_stmt_close($stmt);

        mysqli_close($link);
    }  else{
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2>Update Data Mobil</h2>
                </div>
                <p>Mohon isikan data untuk merubah data mobil jika perlu</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
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
