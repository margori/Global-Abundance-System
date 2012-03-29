<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

	$type = $_REQUEST['type'] ? $_REQUEST['type'] : 'stock';

	if ($type == 'warehouse')
		$pageName = _('Warehouse stock page');
	else if ($type == 'item')
		$pageName = _('Item stock page');
	else
	{
		$type = 'stock';
		$pageName = _('Stock page');
	}
		
	$pageKey = 'Stock';

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
	
	$id = Sanitize($_REQUEST['id']);

	if (isset($_POST['cancel']))
	{
		$action = 'select';
	} 
	else if (isset($_POST['deposit']) || isset($_POST['withdraw']))
	{ 
		$stockId = trim(Sanitize($_POST['stockId']));
		$quantity = htmlentities(utf8_decode(trim($_POST['quantity'])));
		if (isset($_POST['withdraw']))
			$quantity = 0 - $quantity;
		
		if ($quantity != '')
		{
			$sql = "update Stocks set Balance = Balance + $quantity where StockId = $stockId";
			$stocks = DBQuery($sql);	
		}
	}
	else if (isset($_POST['save']))
	{ 
		$itemId = trim(Sanitize($_POST['itemId']));		
		$warehouseId = trim(Sanitize($_POST['warehouseId']));		
		$quantity = trim(Sanitize($_POST['quantity']));		
		$sql = "insert into Stocks (ItemId, WarehouseId, Balance) value ($itemId, $warehouseId, $quantity)";
		$stocks = DBQuery($sql);	
		Redirect("stocks.php?type=$type");
	}
	
	if ($action == 'select')
	{ 
		if ($type == 'warehouse')
			$sql = "select s.StockId, i.Name as ItemName, s.Balance  from Stocks s inner join Items i on i.ItemId = s.ItemId where s.WarehouseId = $id";
		else if ($type == 'item')
			$sql = "select s.StockId, h.Name as WarehouseName, s.Balance from Stocks s inner join Warehouses h on h.WarehouseId = s.WarehouseId where s.ItemId = $id";
		else
			$sql = "select s.StockId, i.Name as ItemName, h.Name as WarehouseName, s.Balance from Stocks s inner join Warehouses h on h.WarehouseId = s.WarehouseId inner join Items i on i.ItemId = s.ItemId";
		
		$stocks = DBQuery($sql);	
	}
	else if ($action == 'edit' || $action == 'delete' )
	{ 
		$id = trim(Sanitize($_GET['id']));

		$sql = "select * from Items t where t.ItemId = $id";
		$stocks = DBQuery($sql);	
		$item = DBFetch($stocks);
	
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
			</tr>
			<tr>
				<td><?= $type == 'item' ? _('Warehouse') : $type == 'warehouse' ? _('Item') : _('Item in warehouse') ?></td>
				<td><?= _('Balance') ?></td>
				<td><?= _('Change') ?></td>
			</tr>
		<?php
			while($stock = DBFetch($stocks))
			{
		?>
			<tr>
				<td><?= $stock['ItemName'] . ($type == 'stock' ? ' in ' : '') . $stock['WarehouseName'] ?></td>
				<td><?= $stock['Balance'] ?></td>
				<td>
					<form action="#" method="post">
						<input type="hidden" name="stockId" value="<?= $stock['StockId'] ?>" />
						<input type="text" name="quantity" size="5" />
						<input type="submit" name="deposit" value="<?= _('Deposit') ?>" />
						<input type="submit" name="withdraw" value="<?= _('Withdraw') ?>" />
					</form>
				</td>
			</tr>
		<? 
			}
		?>
			<tr>
				<td>
					<a href="?type=<?= $type ?></a>&action=new"><img alt="" src="images/new.png" border="0" align="absmiddle"></a>
				</td>
				<td></td>
			</tr>
		</table>
<?php 
	} 
	else if ($action == 'new' || $action == 'edit' || $action == 'delete')
	{
		$sql = "select * from Items";
		$items = DBQuery($sql);
		
		$sql = "select * from Warehouses";
		$warehouses = DBQuery($sql);
		
?>
        <form action="#" method="post" >
			<input type="hidden" name="type" value="<?= $type ?>" />
            <table>
                <tr>
                    <td>
                          <?= _('Item:') ?>
                    </td>
                    <td>
                        <select name="itemId">
							<?php 
								while($item = DBFetch($items)) 
									echo '<option value="' .$item['ItemId'] .'">' . $item['Name'] . '</option>';
							?>							
						</select> 
                    </td>
                </tr>
                <tr>
                    <td>
                          <?= _('Warehouse:') ?>
                    </td>
                    <td>
                        <select name="warehouseId">
							<?php 
								while($warehouse = DBFetch($warehouses)) 
									echo '<option value="'. $warehouse['WarehouseId'] .'">' . $warehouse['Name'] . '</option>';
							?>							
						</select> 
                    </td>
                </tr>
                <tr>
                    <td>
                          <?= _('Quantity:') ?>
                    </td>
                    <td>
                        <input type="text" name="quantity" value="1"/>
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
