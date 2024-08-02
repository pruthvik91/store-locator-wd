<?php

function paginate($reload, $page, $tpages) {
	$out = "";
	$out.=  '<ul class="pagination justify-content-center">';
    $adjacents = 4;
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
	$firstlabel = "&lsaquo;&lsaquo; First";
    $lastlabel = "Last &rsaquo;&rsaquo;";
 
    // previous
    if ($page == 1) {
		$out.= "<li class='page-item'><a  class='page-link'>" . $firstlabel . "</a>\n</li>";
        $out.= "<li class='page-item'><a class='page-link' >" . $prevlabel . "</a>\n</li>";
    }
	elseif ($page == 2) {
		$out.= "<li class='page-item'><a  class='page-link' href=\"" . $reload . "\">" . $firstlabel . "</a>\n</li>";
        $out.= "<li class='page-item'><a    class='page-link' href=\"" . $reload . "\">" . $prevlabel . "</a>\n</li>";
    } else {
		$out.= "<li class='page-item'><a   class='page-link'  href=\"" . $reload . "\">" . $firstlabel . "</a>\n</li>";
        $out.= "<li class='page-item'><a   class='page-link'  href=\"" . $reload . "&amp;page=" . ($page - 1) . "\">" . $prevlabel . "</a>\n</li>";
    }
  
    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out.= "<li  class=\"page-item active\"><a class='page-link' >" . $i . "</a></li>\n";
        } elseif ($i == 1) {
            $out.= "<li class='page-item'><a   class='page-link'  href=\"" . $reload . "\">" . $i . "</a>\n</li>";
        } else {
            $out.= "<li class='page-item'><a   class='page-link'  href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a>\n</li>";
        }
    }
    
    // next
    if ($page < $tpages) {
        $out.= "<li class='page-item'><a    class='page-link' href=\"" . $reload . "&amp;page=" . ($page + 1) . "\">" . $nextlabel . "</a>\n</li>";
		$out.= "<li class='page-item'><a    class='page-link' href=\"" . $reload . "&amp;page=" . ($tpages ) . "\">" . $lastlabel . "</a>\n</li>";
    }
	else {
        $out.= "<li class='page-item'><a class='disabled page-link'   >" . $nextlabel . "</a>\n</li>";
		$out.= "<li class='page-item'><a  class='disabled page-link'>" . $lastlabel . "</a>\n</li>";
    }
    $out.= "";
	$out.= "</ul>";
    return $out;
}
