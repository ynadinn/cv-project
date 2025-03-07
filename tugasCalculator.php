<!DOCTYPE html>
<html>
<head>
    <title>Kalkulator</title>
</head>
<body>
    <h2>Kalkulator</h2>
    <form method="get">
        Bilangan 1 = <input type="text" name="bil1" required/><br>
        Bilangan 2 = <input type="text" name="bil2" required/><br>
        <select name="operasi">
            <option value="tambah"> + </option>
            <option value="kurang"> - </option>
            <option value="kali"> * </option>
            <option value="bagi"> / </option>
        </select> <br><br>
        <input type="submit" value="Hitung"/>
    </form>
    <?php
    if (isset($_GET['bil1']) && isset($_GET['bil2']) && isset($_GET['operasi'])) {
        $bil1 = $_GET['bil1'];
        $bil2 = $_GET['bil2'];
        $operasi = $_GET['operasi'];
        
        if (is_numeric($bil1) && is_numeric($bil2)) {
            if ($operasi == 'tambah') {
                $hasil = $bil1 + $bil2;
                $operator = '+';
            } elseif ($operasi == 'kurang') {
                $hasil = $bil1 - $bil2;
                $operator = '-';
            } elseif ($operasi == 'kali') {
                $hasil = $bil1 * $bil2;
                $operator = '*';
            } elseif ($operasi == 'bagi') {
                if ($bil2 != 0) {
                    $hasil = $bil1 / $bil2;
                    $operator = '/';
                } else {
                    $hasil = "Tidak bisa membagi dengan nol";
                    $operator = '/';
                }
            } else {
                $hasil = "Operasi tidak valid";
                $operator = '';
            }
            echo "<h3>Hasil: $bil1 $operator $bil2 = $hasil</h3>";
        } else {
            echo "<h3>Masukkan angka yang valid!</h3>";
        }
    }
    ?>
</body>
</html>
