<?php
session_start();
    
$conn = mysqli_connect("localhost","root","","stockbarang");

    //tambahbarang
    if(isset($_POST['addnewbarang'])){
        $namabarang =$_POST['namabarang'];
        $deskripsi =$_POST['deskripsi'];
        $harga =$_POST['harga'];
        $stock =$_POST['stock'];

        $addtotable = mysqli_query($conn,"insert into stock (namabarang , deskripsi , harga ,stock) values ('$namabarang' ,  '$deskripsi' , '$harga','$stock')");
        if($addtotable){
            header('Location:index.php');
        }else{
            echo 'Gagal';
            header('Location:index.php');
        }
    }

    //barangmasuk
    if(isset($_POST['barangmasuk'])){
        $barangnya =$_POST['barangnya'];
        $penerima =$_POST['penerima'];
        $qty =$_POST['qty'];

        $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
        $ambildatanya = mysqli_fetch_array($cekstocksekarang);
        $stocksekarang = $ambildatanya['stock'];
        $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;
    
        $addtotable = mysqli_query($conn,"insert into masuk (idbarang ,  Penerima , qty) values ('$barangnya' ,  '$penerima' , '$qty')");
        $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
        if($addtomasuk && $updatestockmasuk){
            header('Location:barang-masuk.php');
        }else{
            echo 'Gagal';
            header('Location:barang-masuk.php');
        }
    }


    //barangkeluar
    if(isset($_POST['barangkeluar'])){
        $barangnya =$_POST['barangnya'];
        $penerima =$_POST['penerima'];
        $qty =$_POST['qty'];

        $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
        $ambildatanya = mysqli_fetch_array($cekstocksekarang);
        $stocksekarang = $ambildatanya['stock'];
        $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;
    
        $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang ,  penerima , qty) values ('$barangnya' ,  '$penerima' , '$qty')");
        $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
        if($addtokeluar&&$updatestockmasuk){
            header('Location:barang-keluar.php');
        }else{
            echo 'Gagal';
            header('Location:barang-keluar.php');
        }
    }

    //update barang
    if(isset($_POST['updatebarang'])){
        $idb =$_POST['idb'];
        $namabarang=$_POST['namabarang'];
        $deskripsi=$_POST['deskripsi'];
        $harga=$_POST['harga'];
        $update = mysqli_query($conn, "update stock set namabarang='$namabarang', deskripsi='$deskripsi' ,harga='$harga' where idbarang ='$idb'");
        if($update){
            header('Location:index.php');
        }else{
            echo 'Gagal';
            header('Location:index.php');
        }
    }

    //hapusbarang
    if(isset($_POST['hapusbarang'])){
        $idb =$_POST['idb'];
        $hapus = mysqli_query($conn, "DELETE FROM stock where idbarang='$idb'");
        var_dump($hapus);
        // $update = mysqli_query($conn, "update stock set namabarang='$namabarang', deskripsi='$deskripsi', harga='$harga', where idbarang ='$idb'");
        if($hapus){
            header('Location:index.php');
        }else{
            echo 'Gagal';
            header('Location:index.php');
        }
    }

    //checkout
    if(isset($_POST['pesanbarang'])){
        $email = $_SESSION['log'];
        $user = mysqli_query($conn,"select * from login where email='$email'");
        $data_user = mysqli_fetch_array($user);

        $id = $_POST['idb'];
        
        $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$id'");
        $data_barang=mysqli_fetch_array($cekstocksekarang);
        $stocksekarang = $data_barang['stock'];
        $quantity = $_POST['quantity'];
        $total_stok = $stocksekarang-$quantity;

        $namabarang = $data_barang['namabarang'];
        $total_harga = $data_barang['harga'] * $quantity;
        $tgl=date("Y/m/d");
        $pesan = mysqli_query($conn,"insert into transaksi (email , nama_barang ,quantity ,total_harga,tanggal_pembelian) values ('$email' ,  '$namabarang' , '$quantity','$total_harga','$tgl' )");
        $update = mysqli_query($conn, "update stock set stock='$total_stok' where idbarang ='$id'");
    }

    //hapusbarangcheckout
    if(isset($_POST['hapusbarangcheckout'])){
        $nb =$_POST['idbarangcheck'];
        $hapus = mysqli_query($conn, "delete from transaksi where id='$nb'");
        // $update = mysqli_query($conn, "update transaksi set nama_barang='$namabarang', quantity='$jumlah', total_harga='$harga', where idbarang ='$nb'");
        if($hapus){
            header('Location:checkout.php');
        }else{
            echo 'Gagal';
            header('Location:checkout.php');
        }
    }
?>