<?php include("include_header.php");?>
<main class="cd-main-content">
		<div class="cd-scrolling-bg cd-color-2">
			<div class="cd-container">
<?php

include("connect.php");
require_once("common.php");

if(isset($_GET['authid'])){$authid = $_GET['authid'];}else{$authid = '';}
if(isset($_GET['author'])){$authorname = $_GET['author'];}else{$authorname = '';}

echo '<h1 class="clr1 gapBelowSmall">Archive &gt; Authors &gt; ' . $authorname . '</h1>';

$authorname = entityReferenceReplace($authorname);

if(!(isValidAuthid($authid) && isValidAuthor($authorname)))
{
	echo '<span class="aFeature clr2">Invalid URL</span>';
	echo '</div> <!-- cd-container -->';
	echo '</div> <!-- cd-scrolling-bg -->';
	echo '</main> <!-- cd-main-content -->';
	include("include_footer.php");

    exit(1);
}

$query = 'select * from article where authid like \'%' . $authid . '%\'';

$result = $db->query($query); 
$num_rows = $result ? $result->num_rows : 0;

if($num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$query3 = 'select feat_name from feature where featid=\'' . $row['featid'] . '\'';
		$result3 = $db->query($query3); 
		$row3 = $result3->fetch_assoc();

		$dpart = preg_replace("/^0/", "", $row['part']);
		$dpart = preg_replace("/\-0/", "-", $dpart);

		if($result3){$result3->free();}
		
		echo '<div class="article">';
		echo '	<div class="gapBelowSmall">';
		echo ($row3['feat_name'] != '') ? ' <span class="aFeature clr2"><a href="feat.php?feature=' . urlencode($row3['feat_name']) . '&amp;featid=' . $row['featid'] . '">' . $featAbbre[$row3['feat_name']] . '</a></span> | ' : '';
		echo '<span class="aIssue clr5"><a href="toc.php?vol=' . $row['volume'] . '&amp;part=' . $row['part'] . '">';
		echo getMonth($row['month']) . ' ' . $row['year'] . '  (Volume ' . intval($row['volume']) . ', Issue ' . $dpart;
		echo ')</a></span>';
		echo '</div>';
		$part = $row['part'];
		echo '<span class="aTitle"><a target="_blank" href="../Volumes/djvu/' . $row['volume'] . '/' . $part . '/index.djvu?djvuopts&amp;page=' . $row['page'] . '.djvu&amp;zoom=page">' . $row['title'] . '</a></span><br/>';
		echo "<span class=\"download\"><a href=\"downloadPdf.php?titleid=" . $row['titleid'] . "\" target=\"_blank\">Download Pdf</a></span>";
		$plates = preg_split('/,/',$row['platPages']);
		if($row['platPages'] != '')
		{
			echo ' | <span class="download"><a target="_blank" href="../Volumes/djvu/' . $row['volume'] . '/' . $part . '/index.djvu?djvuopts&amp;page=' . $plates[0] . '.djvu&amp;zoom=page">View plates</a></span><br />';
		}
		echo '</div>';
	}
}

if($result){$result->free();}
$db->close();

?>
			</div> <!-- cd-container -->
		</div> <!-- cd-scrolling-bg -->
	</main> <!-- cd-main-content -->
<?php include("include_footer.php");?>
