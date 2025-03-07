<html>

<body>
     <?php
     echo "Kalkulator";
     ?>
     <form action="calculator2.php" method="get">
        bilangan 1 = <input type="text" name="bil1"/><br>
        bilangan 2 = <input type="text" name="bil2"/><br>
        <select name="operasi">
            <option value="tambah"> + </option>
            <option value="kurang"> - </option>
            <option value="kali"> * </option>
            <option value="bagi"> / </option>
        </select> <br>
        <input type="submit" value="Kirim"/>
     </form>
</body>

</html>