<?php 
include '../../main/connect.php';

$username = $_POST['Username'];
$password = $_POST['Password']; // Disarankan pakai password_hash() untuk real project
$role     = $_POST['Role'];

$query = mysqli_query($conn, "INSERT INTO user (Username, Password, Role) VALUES ('$username', '$password', '$role')");

if($query) {
    header("location:index.php?pesan=sukses");
} else {
    header("location:index.php?pesan=gagal");
}
?>