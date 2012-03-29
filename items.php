<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

	$pageName = _('Items page');
	$pageKey = 'Items';

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
		$code = htmlentities(utf8_decode(trim($_POST['code'])));
		
		if ($name == '')
			$errorMessage = _('Name required');
		
		if ($errorMessage == '')	
		{
			if (isset($id) && $id != '')
				$sql = "update Items set `Name` = '$name', `Code` = '$code' where ItemId = $id";
			else
				$sql = "insert into Items (`Name`, `Code`) values ('$name', '$code') ";
			$items = DBQuery($sql);	
			Redirect('items.php');
		}
	}
	else if (isset($_POST['delete']))
	{ 
		$id = trim(Sanitize($_POST['id']));		
		$sql = "delete from Items where ItemId = $id";
		$items = DBQuery($sql);	
		Redirect('items.php');
	}
	
	if ($action == 'select')
	{ 
		$sql = "select * from Items";
		$items = DBQuery($sql);	
	}
	else if ($action == 'edit' || $action == 'delete' )
	{ 
		$id = trim(Sanitize($_GET['id']));

		$sql = "select * from Items t where t.ItemId = $id";
		$items = DBQuery($sql);	
		$item = DBFetch($items);
	
		$id = $item['ItemId'];
		$name = $item['Name'];
		$code = $item['Code'];
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
			while($item = DBFetch($items))
			{
		?>
			<tr>
				<td>
					<a href="?action=edit&id=<?= $item['ItemId'] ?>"><img alt="" src="images/edit.png" border="0" align="absmiddle"></a>
					<a href="?action=delete&id=<?= $item['ItemId'] ?>"><img alt="" src="images/delete.png" border="0" align="absmiddle"></a>
				</td>
				<td><a href="stocks.php?type=item&id=<?= $item['ItemId'] ?>"><?= $item['Name'] ?></a></td>
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
                          <?= _('Code:') ?>
                    </td>
                    <td>
                        <textarea name="code" rows="15" cols="50"><?= $code ?></textarea>
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
