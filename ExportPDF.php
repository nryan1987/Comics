<?php
	require("fpdf17/fpdf.php");
	session_start();
	$cxn=@mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
	$sql="SELECT * FROM Comics ORDER BY Title, Volume, Issue, Notes LIMIT 0, 500";
	$result=mysqli_query($cxn,$sql);

	$pdf=new FPDF();
	
	$pdf->AddPage();
	$pdf->SetFont("Arial","","20");
	$pdf->Cell(0, 10, "Comic List");
	$pdf->Ln();
	$prevTitle = "";
	while($row=mysqli_fetch_assoc($result))
	{
		extract($row);
		$pdf->SetFont("Arial","","8");
		if(strcmp($prevTitle, $Title)) {
			$pdf->Cell(0, 6, "$Title", B, R);
			$pdf->Cell(0, 6, "$Publisher", B, L);
			$prevTitle = $Title;
		}
		$pdf->Ln();
		$pdf->Cell(0, 4, "     $Volume     $Issue     $Notes");
		$pdf->Ln();
	}
	$pdf->Output();
?>

/*<td>$Volume</td>
		<td>$Issue</td>
		<td>$Month</td>
		<td>$Year</td>
		<td>$Notes</td>
		<td>$StoryTitle</td>
		<td>$Publisher</td>
		<td>$$PricePaid</td>
		<td>$$Value</td>
		<td>$Condition</td>*/