<?php include("include_header.php");?>
<main class="cd-main-content">
		<div class="cd-scrolling-bg cd-color-2">
			<div class="cd-container">
				<h1 class="clr1">Archive &gt; Titles</h1>
				<div class="alphabet gapBelowSmall gapAboveSmall">
					<span class="letter"><a href="articles.php?letter=A">A</a></span>
					<span class="letter"><a href="articles.php?letter=B">B</a></span>
					<span class="letter"><a href="articles.php?letter=C">C</a></span>
					<span class="letter"><a href="articles.php?letter=D">D</a></span>
					<span class="letter"><a href="articles.php?letter=E">E</a></span>
					<span class="letter"><a href="articles.php?letter=F">F</a></span>
					<span class="letter"><a href="articles.php?letter=G">G</a></span>
					<span class="letter"><a href="articles.php?letter=H">H</a></span>
					<span class="letter"><a href="articles.php?letter=I">I</a></span>
					<span class="letter"><a href="articles.php?letter=J">J</a></span>
					<span class="letter"><a href="articles.php?letter=K">K</a></span>
					<span class="letter"><a href="articles.php?letter=L">L</a></span>
					<span class="letter"><a href="articles.php?letter=M">M</a></span>
					<span class="letter"><a href="articles.php?letter=N">N</a></span>
					<span class="letter"><a href="articles.php?letter=O">O</a></span>
					<span class="letter"><a href="articles.php?letter=P">P</a></span>
					<span class="letter"><a href="articles.php?letter=Q">Q</a></span>
					<span class="letter"><a href="articles.php?letter=R">R</a></span>
					<span class="letter"><a href="articles.php?letter=S">S</a></span>
					<span class="letter"><a href="articles.php?letter=T">T</a></span>
					<span class="letter"><a href="articles.php?letter=U">U</a></span>
					<span class="letter"><a href="articles.php?letter=V">V</a></span>
					<span class="letter"><a href="articles.php?letter=W">W</a></span>
					<span class="letter"><a href="articles.php?letter=X">X</a></span>
					<span class="letter"><a href="articles.php?letter=Y">Y</a></span>
					<span class="letter"><a href="articles.php?letter=Z">Z</a></span>
					<span class="letter"><a href="articles.php?letter=Special">#</a></span>
				</div>
<?php

include("connect.php");
require_once("common.php");

if(isset($_GET['letter']))
{
	$letter=$_GET['letter'];
	
	if(!(isValidLetter($letter)))
	{
		echo '<span class="aFeature clr2">Invalid URL</span>';
		echo '</div> <!-- cd-container -->';
		echo '</div> <!-- cd-scrolling-bg -->';
		echo '</main> <!-- cd-main-content -->';
		include("include_footer.php");

        exit(1);
	}
	
	($letter == '') ? $letter = 'A' : $letter = $letter;
}
else
{
	$letter = 'A';
}
if($letter == 'Special')
{
	$query = "select * from article where title not regexp '^[a-zA-Z]|^`[a-zA-Z]|^&#146;[a-zA-Z]|^&#146;&#146;[a-z]|^<|^\"<' order by title";
}
else
{
	$query = "select * from article where title like '$letter%' union select * from article where title like '`$letter%' union select * from article where title like '&#146;$letter%' union select * from article where title like '&#146;&#146;$letter%' order by TRIM(BOTH '`' FROM TRIM(BOTH '&#146;' FROM TRIM(BOTH '&#146;&#146;' FROM title)))";
}

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
		echo ($row3['feat_name'] != '') ? '<span class="aFeature clr2"><a href="feat.php?feature=' . urlencode($row3['feat_name']) . '&amp;featid=' . $row['featid'] . '">' . $featAbbre[$row3['feat_name']] . '</a></span> | ' : '';
		echo '<span class="aIssue clr5"><a href="toc.php?vol=' . $row['volume'] . '&amp;part=' . $row['part'] . '">';
		echo getMonth($row['month']) . ' ' . $row['year'] . '  (Volume ' . intval($row['volume']) . ', Issue ' . $dpart;
		echo ')</a></span>';
		echo '</div>';
		$part = $row['part'];
		echo '<span class="aTitle"><a target="_blank" href="../Volumes/djvu/' . $row['volume'] . '/' . $part . '/index.djvu?djvuopts&amp;page=' . $row['page'] . '.djvu&amp;zoom=page">' . $row['title'] . '</a></span><br />';
		if($row['authid'] != 0) {

			echo '<span class="aAuthor itl">by ';
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
else
{
	echo '<span class="sml">Sorry! No articles were found to begin with the letter \'' . $letter . '\' in BNHS</span>';
}

if($result){$result->free();}
$db->close();

?>
			</div> <!-- cd-container -->
		</div> <!-- cd-scrolling-bg -->
	</main> <!-- cd-main-content -->
<?php include("include_footer.php");?>
