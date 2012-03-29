<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

	$pageName = _('Producers page');
	$pageKey = 'Producers';

	include_once 'includes/common.php';
	include_once 'includes/database.php';
  
	if (!isset($_SESSION['beingId']))
	{
		Redirect('login.php');
		exit;
	}
	
	if (isset($_GET['logout']))
	{
		unset($_SESSION['beingId']);
		unset($_SESSION['beingName']);		
		Redirect('login.php');
	}
	
	$action = trim(Sanitize($_REQUEST['action']));
	if ($action != 'select' &&
		$action != 'new' &&
		$action != 'edit' &&
		$action != 'delete')
		$action = 'select';
	
	$errorMessage = '';
	
	if (isset($_POST['cancel']))
	{
		$action = 'select';
	} 
	else if (isset($_POST['save']))
	{ 
		$id = trim(Sanitize($_POST['id']));
		$name = htmlentities(utf8_decode(trim($_POST['name'])));
		$address = htmlentities(utf8_decode(trim($_POST['address'])));
		$latitude = $_POST['latitude'] ? htmlentities(utf8_decode(trim($_POST['latitude']))) : 0;
		$longitude = $_POST['longitude'] ? htmlentities(utf8_decode(trim($_POST['longitude']))) : 0;
		
		if ($name == '')
			$errorMessage = _('Name required');
		
		if ($errorMessage == '')	
		{
			if (isset($id) && $id != '')
				$sql = "update Producers set `Name` = '$name', `Address` = '$address', `Location` = Point($latitude, $longitude) where ProducerId = $id";
			else
				$sql = "insert into Producers (`Name`, `Address`, `Location`) values ('$name', '$address', Point($latitude, $longitude)) ";
			$producers = DBQuery($sql);	
			Redirect('producers.php');
		}
	}
	else if (isset($_POST['delete']))
	{ 
		$id = trim(Sanitize($_POST['id']));		
		$sql = "delete from Producers where ProducerId = $id";
		$producers = DBQuery($sql);	
		Redirect('producers.php');
	}
	
	if ($action == 'select')
	{ 
		$sql = "select * from Producers";
		$producers = DBQuery($sql);	
	}
	else if ($action == 'edit' || $action == 'delete' )
	{ 
		$id = trim(Sanitize($_GET['id']));

		$sql = "select t.ProducerId, t.`Name`, t.Address, X(t.Location) as Latitude, Y(t.Location) as Longitude from Producers t where t.ProducerId = $id";
		$producers = DBQuery($sql);	
		$producer = DBFetch($producers);
	
		$id = $producer['ProducerId'];
		$name = $producer['Name'];
		$address = $producer['Address'];
		$latitude = $producer['Latitude'];
		$longitude = $producer['Longitude'];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= _($pageName) ?></title>
    </head>
    <body>
        <?php include('includes/header.php'); ?>
        <?php include('includes/mainmenu.php'); ?>
<?php 
	if ($action == 'select') // Selection -----------------------------------
	{ 
?>
        <table>
			<tr>
				<td>
					<a href="?action=new"><img alt="" src="images/new.png" border="0" align="absmiddle"></a>
				</td>
				<td></td>
			</tr>
		<?php
			while($producer = DBFetch($producers))
			{
		?>
			<tr>
				<td>
					<a href="?action=edit&id=<?= $producer['ProducerId'] ?>"><img alt="" src="images/edit.png" border="0" align="absmiddle"></a>
					<a href="?action=delete&id=<?= $producer['ProducerId'] ?>"><img alt="" src="images/delete.png" border="0" align="absmiddle"></a>
				</td>
				<td><?= $producer['Name'] ?></td>
			</tr>
		<? 
			}
		?>
			<tr>
				<td>
					<a href="?action=new"><img alt="" src="images/new.png" border="0" align="absmiddle"></a>
				</td>
				<td></td>
			</tr>
		</table>
<?php 
	} 
	else if ($action == 'new' || $action == 'edit' || $action == 'delete')
	{
?>
        <form action="#" method="post" >
			<input type="hidden" name="id" value="<?= $id ?>" />
            <table>
                <tr>
                    <td>
                          <?= _('Name:') ?>
                    </td>
                    <td>
                        <input type="text" name="name" value="<?= $name ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                          <?= _('Address:') ?>
                    </td>
                    <td>
                        <textarea name="address" rows="6" cols="50"><?= $address ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                          <?= _('Latitude:') ?>
                    </td>
                    <td>
                        <input type="text" name="latitude" value="<?= $latitude ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                          <?= _('Longitude:') ?>
                    </td>
                    <td>
                        <input type="text" name="longitude" value="<?= $longitude ?>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="<?= $action == 'delete' ? 'delete' : 'save' ?>" value="<?= $action == 'delete' ? _('Delete') : _('Save') ?>" />
                        <input type="submit" name="cancel" value="<?= _('Cancel') ?>" />
                    </td>
                </tr>
            </table>
        </form>
<?php
	}
?>
        <?php include('includes/footer.php'); ?>
    </body>
</html>
