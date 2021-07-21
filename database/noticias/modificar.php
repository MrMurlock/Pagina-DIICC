<?php
session_start();
$file = __FILE__;
include_once "../../include/functions.php";
include_once "../../config/config.php";

// Datos login
$id       = $_POST['id'];
$titulo       = $_POST['titulo'];
$descripcion     = $_POST['descripcion'];

if (!empty($_FILES['img'])){
	$errors = array();
	$file_name = $_FILES['img']['name'];
	$file_size = $_FILES['img']['size'];
	$file_tmp = $_FILES['img']['tmp_name'];
	$file_type = $_FILES['img']['type'];
	$file_ext = strtolower(end(explode('.', $_FILES['img']['name'])));

	$extensions = array("jpeg", "jpg", "png");

	if (in_array($file_ext, $extensions) === false) {
		$errors[] = "extension not allowed, please choose a JPEG or PNG file.";
	}

	if (empty($errors) == true) {
		move_uploaded_file($file_tmp, "../../img/upload/noticias/" . $file_name);
		$image = "img/upload/noticias/" . $file_name;
		$sql = "UPDATE `noticias` SET `img_path` = '{$image}' WHERE `noticias`.`id` = {$id};";
		$result = $conexion->query($sql);
	} else {
		$errors[] = "No se pudo subir la imagen";
	}
	
}

/*if ($_FILES['img']['size'] != 0 && $_FILES['img']['error'] != 0)
{
    if(is_file(addslashes($_FILES["img"]["tmp_name"]))) {
		$img= addslashes(file_get_contents($_FILES["img"]["tmp_name"]));
		$query="UPDATE noticias SET imagen=$img WHERE id=$id";
		$conexion->query($query);
	}
}*/
// Insertar usuario
$sql = $conexion->prepare(
	"UPDATE `noticias` SET `titulo` = ?, `descripcion` = ? WHERE `noticias`.`id` = {$id};"
);
$sql->bind_param( "ss", $titulo, $descripcion);
$sql->execute();

#header(sprintf('Location:%s', fromroot($file, "dashboard/AdminGestorNoticias.php", True)));
?>

