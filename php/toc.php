<?php include("include_header.php");?>
<main class="cd-main-content">
		<div class="cd-scrolling-bg cd-color-2">
			<div class="cd-container">
<?php

include("connect.php");
require_once("common.php");

if(isset($_GET['vol'])){$volume = $_GET['vol'];}else{$volume = '';}
if(isset($_GET['part'])){$part = $_GET['part'];}else{$part = '';}

$dpart = preg_replace("/^0/", "", $part);
$dpart = preg_replace("/\-0/", "-", $dpart);

$yearMonth = getYearMonth($volume, $part);

echo '<h1 class="clr1 gapBelowSmall">Archive &gt; ' . getMonth($yearMonth['month']) . ' ' . $yearMonth['year'] . ' (Volume ' . intval($volume) . ', Issue ' . $dpart . ')</h1>';

if(!(isValidVolume($volume) && isValidPart($part)))
{
	echo '<span class="aFeature clr2">Invalid URL</span>';
	echo '</div> <!-- cd-container -->';
	echo '</div> <!-- cd-scrolling-bg -->';
	echo '</main> <!-- cd-main-content -->';
	include("include_footer.php");

    exit(1);
}

$query = 'select * from article where volume=\'' . $volume . '\' and part=\'' . $part . '\'';

$result = $db->query($query); 
$num_rows = $result ? $result->num_rows : 0;
//mysql_set_charset("utf8");

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
		echo ($row3['feat_name'] != '') ? '<div class="gapBelowSmall"><span class="aFeature clr2"><a href="feat.php?feature=' . urlencode($row3['feat_name']) . '&amp;featid=' . $row['featid'] . '">' . $featAbbre[$row3['feat_name']] . '</a></span></div>' : '';
		echo '	<span class="aTitle"><a target="_blank" href="../Volumes/djvu/' . $row['volume'] . '/' . $part . '/index.djvu?djvuopts&amp;page=' . $row['page'] . '.djvu&amp;zoom=page">' . $row['title'] . '</a></span><br />';
		if($row['authid'] != 0) {

			echo '	<span class="aAuthor itl">by ';
			$authids = preg_split('/;/',$row['authid']);
			$authornames = preg_split('/;/',$row['authorname']);
			$a=0;
			foreach ($authids as $aid) {

				echo '<a href="auth.php?authid=' . $aid . '&amp;author=' . urlencode($authornames[$a]) . '">' . $authornames[$a] . '</a> ';
				$a++;
			}
			
			echo '</span><br/>';
		}
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
