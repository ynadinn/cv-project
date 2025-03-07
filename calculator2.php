<html>
    <?php
    $bil1=$_GET['bil1'];
    $bil2=$_GET['bil2'];
    $operasi=$_GET['operasi']
    if ($operasi=='tambah'){
        $hasil=$bil1 + $bil2;
        echo "hasil $bil1 $operasi $bil2 = $hasil1";
    }else{
        echo "hasil $bil1 $operasi $bil2 = ....";
    }
    ?>
</html>