<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

	include_once 'includes/common.php';
	include_once 'includes/database.php';
	
	$pageName = 'Acquire privilege';
	$pageKey = 'acquirePrivilege';
		
	$evaluationKey = $_REQUEST['p'];
	$evaluationKey = Sanitize($evaluationKey);
		
	if (!isSet($evaluationKey))
		Redirect('index.php');
	
	$_SESSION['temporalPrivilege'] = '';
	
	if (isSet($_POST['Evaluate']))
	{
		$answerIds = "";
		foreach($_POST as $p)
		{
			if (substr($p, 0, strlen('answerId')) == 'answerId')
			{
				$answerId = substr($p, strlen('answerId'));
				if ($answerIds == "")
					$answerIds = "" . $answerId;
				else
					$answerIds .= ", " . $answerId;
			}
		}
		$count = -1;
		$correct = 1;
		
		if ($answerIds != "")
		{
			$sql = "select COUNT(*) as Count, sum(a.Correct) as Correct
				from 
					EvaluationAnswers a 
					inner join EvaluationQuestions q on q.EvaluationQuestionId = a.EvaluationQuestionId 
					inner join Evaluations e on e.EvaluationId = q.EvaluationId
				where
					e.`Key` = 'Register'
					and a.`EvaluationAnswerId` in ($answerIds)";
			$evaluateQuery = DBQuery($sql);
			$evaluateRow = DBFetch($evaluateQuery);
			$count = $evaluateRow['Count'];
			$correct = $evaluateRow['Correct'];
		}
		
		if ($count == $correct)
		{
			$_SESSION['temporalPrivilege'] = 'Register';
			Redirect('register.php');
		}
		$messages[] = "Incorrect answer, please try again.";
		$_SESSION['Messages'] = $messages;
	}	

	$sql = "select e.EvaluationId, e.Content from Evaluations e where e.Key = '$evaluationKey'";
	$evaluationsResult = DBQuery($sql);
	
	$evaluation = mysql_fetch_array($evaluationsResult);
	$evaluationId = $evaluation['EvaluationId'];
	
	$sql = "select * from EvaluationQuestions q where q.EvaluationId = $evaluationId";

	$questionsResult = DBQuery($sql);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?= _('Privilege acquisition') ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.20" />
</head>
<body>
	<?php include('includes/header.php'); ?>
<?php
	if (!$evaluationKey = 'Register')
		echo $evaluation['Content'];
	else
	{	
		echo '</p>' . _('Next lines are important. Please read carefully.') . '</p>';
		echo '</p>' . _('Main objective of this system is to let people to help people.') . '</p>';
		echo '</p>' . _('Second objective is to let world resource available for everybody to satified their needs, using them efficiently.') . '</p>';
		echo '</p>' . _('Given this objectives, we higly recommend to watch the movie Zeitgeist Addendum and Zeitgeist Moving Forworf to fully understand this system and its objective.') . '</p>';
		echo '</p>' . '<iframe width="560" height="315" src="http://www.youtube.com/embed/EewGMBOB4Gg" frameborder="0" allowfullscreen></iframe>' ;
		echo ' <iframe width="560" height="315" src="http://www.youtube.com/embed/4Z9WVZddH9w" frameborder="0" allowfullscreen></iframe>' . '</p>';
		echo '</p>' . _('Then, please answser this questions:') . '</p>';
	}
?>
	<form action="#" method="post" >
		<input type="hidden" name="p" value="<?= $evaluationKey ?>" />
<?php
	
	while($question = mysql_fetch_array($questionsResult))
	{
		$s = htmlentities($question['Question']);
		echo "<p><b>$s</b></p>";
		
		$questionId = $question['EvaluationQuestionId'];
		
		$sql = "select * from EvaluationAnswers a where a.EvaluationQuestionId = $questionId";
		$answerResult = DBQuery($sql);
		
		echo "<ul>";
		while($answer = mysql_fetch_array($answerResult))
		{
			$s = htmlentities($answer['Answer']);
			$answerId = $answer['EvaluationAnswerId'];
			echo "<li>$s<input type=\"radio\" name=\"answerId$answerId\" value=\"answerId$answerId\" /></li>";
		}
		echo "</ul>";
	}
?>
		<input type="submit" name="Evaluate" value="<?= _('Evaluate') ?>" />
	</form>
	<?php include('includes/footer.php'); ?>
</body>

</html>
