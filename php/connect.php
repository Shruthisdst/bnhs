<?php
$user='root';
$password='mysql';
$database='bnhs';

$db = @new mysqli('localhost', "$user", "$password", "$database");

if($db->connect_errno > 0)
{
	echo '<span class="aFeature clr2">Not connected to the Database</span>';
	echo '</div> <!-- cd-container -->';
	echo '</div> <!-- cd-scrolling-bg -->';
	echo '</main> <!-- cd-main-content -->';
	include("include_footer.php");

    exit(1);
}

$featAbbre = array("A"=>"Advertisement","BR"=>"Book Review","C"=>"Correspondence","CN"=>"Conservation notes","E"=>"Editorial","empty"=>"empty","ER"=>"Errata","FS"=>"For sale","G"=>"General","GL"=>"Gleanings","I"=>"Index","LB"=>"List of books","LC"=>"List of contributors","LM"=>"List of members","LP"=>"List of plates","MN"=>"Miscellaneous notes","N"=>"Notice","ND"=>"New description","NN"=>"Notes and news","OB"=>"Obituary","P"=>"Proceedings","PB"=>"Publications","SA"=>"Statements of accounts","TOC"=>"Table of contents","TP"=>"Title page","W"=>"Wanted");
?>
