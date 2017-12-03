<?PHP

/**
 * Draws header/toolbar 
 * @param  String - $title - The text to appear at the top of each page
 */
function mobile_math_header($title){

	print "<div data-role='header'>";
	print "<h1>".$title."</h1>";
	print "<div data-role='navbar'>
		      	<ul>";
	print "<li><a href='#home-page'>Home</a></li>
			        <li><a class='large-text' href='#add-page'>&#43;</a></li>
			        <li><a class='large-text' href='#sub-page'>&#8722;</a></li>
			        <li><a class='large-text' href='#mul-page'>&#120;</a></li>
			        <li><a class='large-text' href='#div-page'>&#247;</a></li>";

	print "</ul></div>"; // End navbar
	print "</div>"; // End data-role="header"
} 

/**
 * Draws UI interface that is present at the top of each operation page
 * @param  String - $op - operation short
 */
function math_controls_top($op) {

	print "<div class='center' data-role='controlgroup'  data-type='horizontal' data-mini='true'>";
	print "<a href='#".$op."Info' data-rel='popup' data-transition='flip' class='ui-btn ui-btn-inline ui-icon-info ui-btn-icon-notext ui-corner-all ui-shadow'>Info</a>";
	print "<div data-role='popup' id='".$op."Info' class='center'>
				<a href='#' data-rel='back' class='ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right'>Close</a>
			    <p>Score 20 points before the timer runs out!</p>
			    <p>Press Start to begin a round.</p>
			  	<p>Complete the problems and press 'Next' to get more problems.</p>
			  	<p>Change difficulty on the Settings Page</p>
			  	<p>If you reach 20 before the timer is up...</p>
			  	<p>You Win!!!</p>
			</div>";
	print "<a id='reset-".$op."' class='ui-btn ui-corner-all ui-btn-a'>Reset</a>";
	print "<a id='start-".$op."' class='ui-btn ui-corner-all'>Start</a></div>";
}


?>


<!-- Below is the text that math_controls_top($op) is replacing -->
<!-- <div class="center" data-role="controlgroup"  data-type="horizontal" data-mini="true">
	<a href="#addInfo" data-rel="popup" data-transition="flip" class="ui-btn ui-btn-inline ui-icon-info  ui-btn-icon-notext ui-corner-all ui-shadow ">Info</a>
			<div data-role="popup" id="addInfo" class="center">
				<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
			    <p>Score 20 points before the timer runs out!</p>
			    <p>Press Start to begin a round.</p>
			  	<p>Complete the problems and press 'Next' to get more problems.</p>
			  	<p>Change difficulty on the Settings Page</p>
			  	<p>If you reach 20 before the timer is up...</p>
			  	<p>You Win!!!</p>
			</div>
	<a id="reset-add" class="ui-btn ui-corner-all ui-btn-a">Reset</a>
	<a id="start-add" class="ui-btn ui-corner-all ">Start</a>
</div>
 -->



