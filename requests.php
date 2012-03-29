<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

	$pageName = _('Requests page');
	$pageKey = 'Requests';

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
		$title = htmlentities(utf8_decode(trim($_POST['title'])));
		$description = htmlentities(utf8_decode(trim($_POST['description'])));
		
		if ($title == '')
			$errorMessage = _('Title required');
		
		if ($errorMessage == '')	
		{
			if (isset($id) && $id != '')
				$sql = "update Requests set `Title` = '$title', `Description` = '$description' where RequestId = $id";
			else
				$sql = "insert into Requests (`Title`, `Description`, CreatorId) values ('$title', '$description', ".$_SESSION['beingId'].") ";
			$requests = DBQuery($sql);	
			Redirect('requests.php');
		}
	}
	else if (isset($_POST['delete']))
	{ 
		$id = trim(Sanitize($_POST['id']));		
		$sql = "delete from Requests where RequestId = $id";
		$requests = DBQuery($sql);	
		Redirect('requests.php');
	}
	
	if ($action == 'select')
	{ 
		$sql = "select r.RequestId, r.Title, b.Name as Creator from Requests r inner join Beings b on b.BeingId = r.CreatorId";
		$requests = DBQuery($sql);			
	}
	else if ($action == 'edit' || $action == 'delete' )
	{ 
		$id = trim(Sanitize($_GET['id']));

		$sql = "select * from Requests t where t.RequestId = $id";
		$requests = DBQuery($sql);	
		$request = DBFetch($requests);
	
		$id = $request['RequestId'];
		$title = $request['Title'];
		$description = $request['Description'];
		
		$sql = "select * from Solutions t where t.RequestId = $id";
		$solutions = DBQuery($sql);	
		
		foreach($_POST as $p)
		{
			if (substr($p, 0, strlen('solution')) == 'solution')
			{
				$selectedSolutionId = substr($p, strlen('solution'));
			}
		}
				
		if (!isset($selectedSolutionId))
		{
			$sql = "select SolutionId from Solutions t where t.RequestId = $id";
			$auxSolutions = DBQuery($sql);	
			$auxSolution = DBFetch($auxSolutions);
			$selectedSolutionId = $auxSolution['SolutionId'];			
		}
		
		if (isset($selectedSolutionId))
		{
			$sql = "select * from SolutionTechnoligies t where t.SolutionId = $selectedSolutionId";
			$selectedSolutionTechnologies = DBQuery($sql);	
			
			$sql = "select * from SolutionItems t where t.SolutionId = $selectedSolutionId";
			$selectedSolutionItems = DBQuery($sql);			
		}
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
			<tr>
				<td></td>
				<td>Title</td>
				<td>Creator</td>
			</tr>
		<?php
			while($request = DBFetch($requests))
			{
		?>
			<tr>
				<td>
					<a href="?action=edit&id=<?= $request['RequestId'] ?>"><img alt="" src="images/edit.png" border="0" align="absmiddle"></a>
					<a href="?action=delete&id=<?= $request['RequestId'] ?>"><img alt="" src="images/delete.png" border="0" align="absmiddle"></a>
				</td>
				<td><?= $request['Title'] ?></td>
				<td><?= $request['Creator'] ?></td>
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
                          <?= _('Title:') ?>
                    </td>
                    <td>
                        <input type="text" name="title" value="<?= $title ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                          <?= _('Description:') ?>
                    </td>
                    <td>
                        <textarea name="description" rows="15" cols="50"><?= $description ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="<?= $action == 'delete' ? 'delete' : 'save' ?>" value="<?= $action == 'delete' ? _('Delete') : _('Save') ?>" />
                        <input type="submit" name="cancel" value="<?= _('Cancel') ?>" />
                    </td>
                </tr>
                <tr>
					<td colspan="2">
						<?= _('Solutions') ?>
					</td>
                </tr>
                <tr>
					<td colspan="2">
						<?php
							while($solution = DBFetch($solutions))
							{
						?>
						<input type="submit" name="solution<?= $solution['SolutionId'] ?>" value="<?= $solution['SolutionId'] ?>" />
						<?php
							}
						?>
						<input type="submit" name="solutionNew" value="<?= _('New solution') ?>" />
					</td>
                </tr>
                <tr>
					<td colspan="2">
						<?= _('Technologies') ?>
					</td>
                </tr>
				<?php
					if (isset($selectedSolutionTechnologiessolutions))
						while($selectedSolutionTechnology = DBFetch($selectedSolutionTechnologiessolutions))
						{	
				?>
                <tr>
					<td colspan="2">
						<?= $selectedSolutionTechnology['Title'] ?>
					</td>
                </tr>
				<?php
						}
				?>
            </table>
        </form>
<?php
	}
?>
        <?php include('includes/footer.php'); ?>
    </body>
</html>
