$(function() {

	$(document).bind('mobileinit',function(){
	    $.extend(  $.mobile , {
	      defaultPageTransition: "flip"
	    });
	});


	$(document).bind("mobileinit", function(){
        $.mobile.ajaxenabled = false;
    });


	// Set default operation values //
	var aMAX = 20, sMAX = 20,
		mMAX = 12, dMAX = 12;
		
	var mTABLE_MAX = 2;
	var dTABLE_MAX = 2;

	var TARGET_POINTS = 10;
	var MAX_TIME = 60;
	var time = MAX_TIME;
	var roundFinished = true;
	var roundStarted = false;
	var curOperation = '';
	// End default operation vals //	
	var score = 0;
	var probArr = [];
	var played;
	var curOpMax;
	var t;
	// When page is changed, reset addition so timer stops
	$(document).on('pagecontainerchange', function(){
	    prob_reset();
	});

	$('[id^=reset-]').click(function(){
		prob_reset();
	});

	$('#start-add').click(function() {
		console.log('starting addition')
		document.getElementById('boing').play();
		//Make sure to set this on each op button
		curOperation = 'add';
		curOpMax = aMAX;
		console.log(roundFinished)
		start_add_round() 
		add_sub_nums(curOpMax);	
	})

	$('#start-sub').click(function() {
		document.getElementById('boing').play();
		//Make sure to set this on each op button
		curOperation = 'sub';
		curOpMax = sMAX;
		start_add_round() 
		add_sub_nums(curOpMax);	
	})


	$('#start-mul').click(function() {
		document.getElementById('boing').play();
		curOperation = 'mul';
		curOpMax = mMAX;
		tableMax = mTABLE_MAX;
		start_add_round() 
		add_sub_nums(curOpMax);	
		console.log(curOpMax+"=curOpMax  and  mTABLE_MAX="+mTABLE_MAX)
	})

	$('#start-div').click(function() {
		document.getElementById('boing').play();
		// roundFinished = false;
		curOperation = 'div';
		curOpMax = dMAX;
		tableMax = dTABLE_MAX;
		start_add_round() 
		add_sub_nums(curOpMax);	
		console.log(curOpMax+"=curOpMax  and  dTABLE_MAX="+dTABLE_MAX)
	})



	// Listener for 'Next' button to get more problems
	$('[id$=-check]').click(function() {
		document.getElementById('wand').play();
		if(!roundFinished) {
			// console.log("round finished is: "+roundFinished)
			add_check();	
		}
		// else console.log(roundFinished)
		
	})

	// =========  Start Listeners for Settings Page ========== //

	// User sets new default max val for addition //
	$('#a-set').click(function() {
		// var n = parseInt(Math.abs($('#a-max').val())) 
		var n = Math.abs(parseInt($('#a-max').val()));
		if(n !== 0 && n < 51) {
			aMAX = n;	
			document.getElementById('ting').play();
			$('#a-max').val("")
			console.log(aMAX)
		}
		else { document.getElementById('error').play() }
	})

	// User sets new max value for Subtraction problems // 
	$('#s-set').click(function() {
		// var n = parseInt(Math.abs($('#s-max').val()));
		var n = Math.abs(parseInt($('#s-max').val()));
		if(n > 0 && n <51) {
			sMAX = n;
			document.getElementById('ting').play();	
			$('#s-max').val("")
		} 
		else { document.getElementById('error').play() }
	})

	// Set timer start value //
	$('#t-set').click(function() {
		// var n = parseInt(Math.abs($('#t-max').val()));
		var n = Math.abs(parseInt($('#t-max').val()));
		if(n > 0 && n <= 300) {
			MAX_TIME = n;
			document.getElementById('ting').play();	
			$('#t-max').val("")
		} 
		else { document.getElementById('error').play() }
	})

	// User sets max multiplier value for multiplication problems
	$('#m-m-set').click(function() {
		// var n = parseInt(Math.abs($('#m-mult').val()));
		var n = Math.abs(parseInt($('#m-mult').val()));
		if(n > 0 && n < 51) {
			mMAX = n;
			document.getElementById('ting').play();	
			$('#m-mult').val("")
			console.log(mMAX);
		}
		else { document.getElementById('error').play() }
	})

	// User sets value for mult table value
	$('#m-t-set').click(function() {
		var n = Math.abs(parseInt($('#m-table').val()))
		if(n > 0 && n < 51) {
			mTABLE_MAX = n;
			document.getElementById('ting').play();	
			$('#m-table').val("");
			console.log(mTABLE_MAX)
		}
		else { document.getElementById('error').play();	 }
	})

	// User sets max dividend value for division problems
	$('#d-m-set').click(function() {
		var n = parseInt(Math.abs($('#d-div').val()));
		if(n > 0 && n < 101) {
			dMAX = n;
			document.getElementById('ting').play();	
			$('#d-div').val("")
			console.log(dMAX);
		}
		else { document.getElementById('error').play() }
	})

	// User sets value for division table value
	$('#d-t-set').click(function() {
		var n = Math.abs(parseInt($('#d-table').val()))
		if(n > 0 && n < 14) {
			dTABLE_MAX = n;
			document.getElementById('ting').play();	
			$('#d-table').val("");
			console.log(dTABLE_MAX)
		}
		else { document.getElementById('error').play();	 }
	})

	// =====  End Listeners for Settings Page  ======= //



	// Init popups //
	$( "#addFailPopup" ).popup({
		theme: 'a',
		transition: 'flip'
	});
	$( "#addSuccessPopup" ).popup({
		theme: 'a',
		transition: 'flip'
	});


	// ===== Functions run at page load ===== //
	
	show_clock(time)




	/**
	 * Sets timer to formatted zeros plus rest of initial time string
	 * @param {[type]} Number of seconds
	 * Generic
	 */
	function show_clock(t) {
		var m = t / 60;
		if (t < 10) {
			// [id$=_BBB]
			$("[id$=-time]").val('00:0'+t);
		}
		else if (t < 60){
			$("[id$=-time]").val('00:'+t);
		}
		else {
			$("[id$=-time]").val("0"+m+":00");
		}
	}

	/**
	 * Resets addition fields
	 * Generic
	 */
	function prob_reset() {
		$("[id$=-score]").css('background-color', '#fff');
		$('[class$=-input] input').val("")
		score = 0;
		update_score();
		// clear_colors()
		if(t){
			clearTimeout(t);
		}
		show_clock(MAX_TIME);
		time = MAX_TIME;
		roundStarted = false;
		roundFinished = true;
		// console.log(roundFinished);
		// curOperation = "";
	}




	/**
	 *  Create new set of add/sub questions
	 *  Save questions as Prob objects
	 *  Push objs to array of questions 
	 *  AT PRESENT works for addition and subtraction
	 */
	function add_sub_nums(pMax) {
		// 
		// Reset arr to empty
		probArr = [];
		played = false;
		clear_answers()

		// Create new addition problems //
		for(var i = 0;i<4;i++) {
			var min, max, ans, p;
			var a1 = Math.floor(Math.random() * pMax );
			var a2 = Math.floor(Math.random() * pMax );
			// console.log(curOperation)
			if(curOperation === 'add'){
				ans = a1 + a2;
				var p = new Prob(a1, a2, ans)
			}
			else if (curOperation === 'sub') {
				console.log(a1+"  and  "+a2)
				var hi = Math.max(a1, a2);
				var lo = Math.min(a1, a2);
				ans = hi - lo;
				var p = new Prob(hi, lo, ans)
			}
			else if (curOperation === 'mul') {
				console.log(pMax+" is the max multiplier");
				console.log(mTABLE_MAX+" is the  mult TABLES");
				var m = parseInt(Math.random()*pMax);
				var p = new Prob(m, mTABLE_MAX, (m * mTABLE_MAX));
			}
			else if (curOperation === 'div') {
				// console.log("I heart division");
				var validAnswers = [];
				for(var j = 1; j <= dMAX; j++) {
					var n = j * dTABLE_MAX;
					if (n <= dMAX) {
						validAnswers.push(n);
					}
				}
				var randomElementNum = parseInt(Math.random()*validAnswers.length);
				var x = validAnswers[randomElementNum]
				var p = new Prob(x, dTABLE_MAX, (x / dTABLE_MAX)); 
				// console.log(validAnswers);
			}

			probArr.push(p);
			// console.log(probArr)
		}
		// Write add problems to screen //
		for(var i = 1;i<5;i++){
			var o = curOperation.substring(0,1);
			if(probArr[i-1]) {
				// console.log(o+" this is the first letter of the curOperation")
				$('#'+o+'-'+i+'-a1').val(probArr[i-1].a1)
				$('#'+o+'-'+i+'-a2').val(probArr[i-1].a2)
			}
			
		}
	}

	// Gets pushed to probArr[]
	// Generic
	function Prob(a1, a2, r) {
		this.a1 = a1;
		this.a2 = a2;
		this.r = r;
	}

	/**
	 * Checks the answers submitted in the addition section 
	 */
	function add_check() {
		var ans = [];
		var localScore = 0;
		// console.log(curOperation);
		
		// Get answer vals and put in an array
		$('#'+curOperation+'-quest .answer').each(function (){
			ans.push(this.value);
		})

		// Problems here with undefined rs
		// console.log(probArr.length)
		if(probArr.length > 0 ) {
			// console.log(probArr.length)
			for(var i = 0;i<probArr.length;i++){
				// var a = $('#add-quest .answer').get(i)
				// console.log(probArr)
				if(probArr[i] !== undefined) {

					if(probArr[i].r !== parseInt(ans[i])){
						// console.log(i+': incorrrect');
					}
					else {
						localScore += 1;
						// console.log("localScore: "+localScore)
					} 

				}
				
			}	
		}
		
		if(!played){
			// console.log("adding "+score+' plus '+localScore)
			score += localScore;
			update_score();
			played = true;	
		}	
		add_sub_nums(curOpMax);
	}

	/**
	 * Sets answer inputs to all blank
	 * Generic
	 */
	function clear_answers(){
		$('[id$=-quest] .answer').val('')
	}
	/**
	 * Sets new value of score
	 */
	function update_score() {
		$('[id$=-score]').val(score);
	}

	/**
	 * Runs timer, and checks if score has reached max. 
	 * Runs curOperations success / failure function
	 */
	function start_opp_timer() {
		time--;
		run_timer(time);

		if(score >= TARGET_POINTS){
			// console.log('WOOOOOHHOOOO!!!   your at '+TARGET_POINTS);
			if(t){
				clearTimeout(t);	
			}
			roundFinished = true;
			op_success();	
		}
		else if(time < 1) {
			// console.log('UHH OHH!!!   your at '+TARGET_POINTS);
			roundFinished = true;
			op_fail();
		}
		else {
			t = setTimeout(start_opp_timer, 1000);
		}
	}

	/**
	 * Displays incremented time to clock
	 * @param  {[type]} t  time value to display
	 * @param  {[type]} id Location of element to display in
	 * Generic
	 */
	function run_timer(t) {
		if(t < 10){
			$("[id$=-time]").val('00:0'+t);
		}
		else if(time < 60){
			$("[id$=-time]").val('00:'+t);
		}
		else {
			var m = parseInt(t / 60);
			var s = t % 60;
			if (s > 9) {
				$("[id$=-time]").val('0'+m+':'+s)	
			}
			else {
				$("[id$=-time]").val('0'+m+':0'+s)
			}
		}
	}
	

	function start_add_round() {
		prob_reset();
		roundFinished = false;
		// score = 0;
		if(!roundStarted){
			start_opp_timer()	
		}
		roundStarted = true;
	}


	/**
	 * Runs when target score has been reached on Addition page
	 * Generic!!!
	 */
	function op_success() {
		var o = curOperation.substring(0,1);
		$('#'+o+'-score').css('background-color', 'lightGreen');
		console.log('WOOOOOHHOOOO!!!  Youve reached '+TARGET_POINTS+' points!');
		document.querySelector('#kid-cheer').play();
		$( "#"+curOperation+"SuccessPopup" ).popup();
		$( "#"+curOperation+"SuccessPopup" ).popup( "open" );
	}
	function op_fail() {
		$('#a-score').css('background-color', '#FF4500');
		document.querySelector('#foghorn').play();
		$( "#"+curOperation+"FailPopup" ).popup();
		$( "#"+curOperation+"FailPopup" ).popup( "open" );
	}

	

})