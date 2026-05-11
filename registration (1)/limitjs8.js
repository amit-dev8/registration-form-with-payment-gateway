sessionStorage.clear();

//name="threedays" value="3days" id="threedays"

/*
function validate() {
    if ($('#threedays').is(':checked')) {
        console.log("Checked");
        $('#error').text("Checkbox is selected");
        let threedays = ($('#threedays').val() || '').trim();
    } 
    /*else {
        console.log("Not Checked");
        $('#error').text("Please select checkbox");
    }*/
//}
/*
// Trigger when checkbox changes
$('#threedays').on('change', function () {
    validate();
});

// Trigger on button click
$('#submit').on('click', function () {
    validate();
});

// Trigger on page load
$(function () {
    if ($('#threedays').is(':checked')) {
        validate();
    }
});
        if(threedays == "3days"){
    
        }
        else
        {*/
    
        

		function checkLimit() {
			//let cmorning1 = ($('#cmorning1').val() || '').trim();
			//let cmorning1 = ($('#cmorning1').val() || '').trim();
			//console.log(cmorning1);
			    //let cmorning1 = $("#cmorning1").val();
                //console.log(cmorning1);
                let cmorning1_raw = $("#cmorning1").val();
                let cmorning1 = cmorning1_raw ? parseInt(cmorning1_raw, 10) : null;
                console.log("DataType"+cmorning1, typeof cmorning1);
                

/*			if (cmorning1 === '') {
				$('#err_limit').text('Please select a sessions');
				return;
			}
			*/
			
			$.ajax({
				url: 'https://demo.iddllp.com/registration/check_limit.php',
				type: 'POST',
				data: { cmorning1: cmorning1 },
				success: function (response) {
					const status = (response || '').trim();
					if (status === 'exists') {
						$('#err_limit').text('Sold Out');
						// Disable only the currently selected option
						$('#cmorning1 option[value="' + cmorning1 + '"]').prop('disabled', true);
					} else if (status === 'available') {
						$('#err_limit').text('');
						$('#cmorning1 option[value="' + cmorning1 + '"]').prop('disabled', false);
					} else if (status === 'no_sessions_provided') {
						//$('#err_limit').text('No sessions provided');
					} else {
						$('#err_limit').text(response);
					}
				},
				error: function () {
					$('#err_limit').text('Request error');
				}
			});
		}

		// Check when dropdown changes
		$('#cmorning1').on('change', function() {
			checkLimit();
		});

		// Button to manually check
		$('#submit').on('click', function() {
			checkLimit();
		});

		// Also check on page load (if a value is preselected)
		$(function(){
			if ($('#cmorning1').val()) checkLimit();
		});
       
// Afternoon
        function checkLimitafter() {
			//let cmorning1 = ($('#cmorning1').val() || '').trim();
			let cafternoon1 = ($('#cafternoon1').val() || '').trim();
			console.log(cafternoon1);

/*			if (cmorning1 === '') {
				$('#err_limit').text('Please select a sessions');
				return;
			}*/

			$.ajax({
				url: 'https://demo.iddllp.com/registration/check_limit.php',
				type: 'POST',
				data: { cafternoon1: cafternoon1 },
				success: function (response) {
					const status = (response || '').trim();
					if (status === 'exists') {
						$('#err_limit').text('Sold Out');
						// Disable only the currently selected option
						$('#cafternoon1 option[value="' + cafternoon1 + '"]').prop('disabled', true);
					} else if (status === 'available') {
						$('#err_limit').text('');
						$('#cafternoon1 option[value="' + cafternoon1 + '"]').prop('disabled', false);
					} else if (status === 'no_sessions_provided') {
						//$('#err_limit').text('No sessions provided');
					} else {
						$('#err_limit').text(response);
					}
				},
				error: function () {
					$('#err_limit').text('Request error');
				}
			});
		}

		// Check when dropdown changes
		$('#cafternoon1').on('change', function() {
			checkLimitafter();
		});

		// Button to manually check
		$('#submit').on('click', function() {
			checkLimitafter();
		});

		// Also check on page load (if a value is preselected)
		$(function(){
			if ($('#cafternoon1').val()) checkLimitafter();
		});

//fullday

        function checkLimitfull() {
			//let cmorning1 = ($('#cmorning1').val() || '').trim();
			let fsession1 = ($('#fsession1').val() || '').trim();
			console.log(fsession1);

/*			if (cmorning1 === '') {
				$('#err_limit').text('Please select a sessions');
				return;
			}*/

			$.ajax({
				url: 'https://demo.iddllp.com/registration/check_limit.php',
				type: 'POST',
				data: { fsession1: fsession1 },
				success: function (response) {
					const status = (response || '').trim();
					if (status === 'exists') {
						$('#err_limit').text('Sold Out');
						// Disable only the currently selected option
						$('#fsession1 option[value="' + fsession1 + '"]').prop('disabled', true);
					} else if (status === 'available') {
						$('#err_limit').text('');
						$('#fsession1 option[value="' + fsession1 + '"]').prop('disabled', false);
					} else if (status === 'no_sessions_provided') {
						//$('#err_limit').text('No sessions provided');
					} else {
						$('#err_limit').text(response);
					}
				},
				error: function () {
					$('#err_limit').text('Request error');
				}
			});
		}

		// Check when dropdown changes
		$('#fsession1').on('change', function() {
			checkLimitfull();
		});

		// Button to manually check
		$('#submit').on('click', function() {
			checkLimitfull();
		});

		// Also check on page load (if a value is preselected)
		$(function(){
			if ($('#fsession1').val()) checkLimitfull();
		});

//2day
		function checkLimit2() {
			//let cmorning1 = ($('#cmorning1').val() || '').trim();
			let cmorning2 = ($('#cmorning2').val() || '').trim();
			console.log(cmorning2);

/*			if (cmorning1 === '') {
				$('#err_limit').text('Please select a sessions');
				return;
			}
			*/
			$.ajax({
				url: 'https://demo.iddllp.com/registration/check_limit.php',
				type: 'POST',
				data: { cmorning2: cmorning2 },
				success: function (response) {
					const status = (response || '').trim();
					if (status === 'exists') {
						$('#err_limit').text('Sold Out');
						// Disable only the currently selected option
						$('#cmorning2 option[value="' + cmorning2 + '"]').prop('disabled', true);
					} else if (status === 'available') {
						$('#err_limit').text('');
						$('#cmorning2 option[value="' + cmorning2 + '"]').prop('disabled', false);
					} else if (status === 'no_sessions_provided') {
						//$('#err_limit').text('No sessions provided');
					} else {
						$('#err_limit').text(response);
					}
				},
				error: function () {
					$('#err_limit').text('Request error');
				}
			});
		}

		// Check when dropdown changes
		$('#cmorning2').on('change', function() {
			checkLimit2();
		});

		// Button to manually check
		$('#submit').on('click', function() {
			checkLimit2();
		});

		// Also check on page load (if a value is preselected)
		$(function(){
			if ($('#cmorning2').val()) checkLimit2();
		});
       
// Afternoon
        function checkLimitafter2() {
			//let cmorning1 = ($('#cmorning1').val() || '').trim();
			let cafternoon2 = ($('#cafternoon2').val() || '').trim();
			console.log(cafternoon2);

/*			if (cmorning1 === '') {
				$('#err_limit').text('Please select a sessions');
				return;
			}*/

			$.ajax({
				url: 'https://demo.iddllp.com/registration/check_limit.php',
				type: 'POST',
				data: { cafternoon2: cafternoon2 },
				success: function (response) {
					const status = (response || '').trim();
					if (status === 'exists') {
						$('#err_limit').text('Sold Out');
						// Disable only the currently selected option
						$('#cafternoon2 option[value="' + cafternoon2 + '"]').prop('disabled', true);
					} else if (status === 'available') {
						$('#err_limit').text('');
						$('#cafternoon2 option[value="' + cafternoon2 + '"]').prop('disabled', false);
					} else if (status === 'no_sessions_provided') {
						//$('#err_limit').text('No sessions provided');
					} else {
						$('#err_limit').text(response);
					}
				},
				error: function () {
					$('#err_limit').text('Request error');
				}
			});
		}

		// Check when dropdown changes
		$('#cafternoon2').on('change', function() {
			checkLimitafter2();
		});

		// Button to manually check
		$('#submit').on('click', function() {
			checkLimitafter2();
		});

		// Also check on page load (if a value is preselected)
		$(function(){
			if ($('#cafternoon2').val()) checkLimitafter2();
		});

//fullday

        function checkLimitfull2() {
			//let cmorning1 = ($('#cmorning1').val() || '').trim();
			let fsession2 = ($('#fsession2').val() || '').trim();
			console.log(fsession2);

/*			if (cmorning1 === '') {
				$('#err_limit').text('Please select a sessions');
				return;
			}*/

			$.ajax({
				url: 'https://demo.iddllp.com/registration/check_limit.php',
				type: 'POST',
				data: { fsession2: fsession2 },
				success: function (response) {
					const status = (response || '').trim();
					if (status === 'exists') {
						$('#err_limit').text('Sold Out');
						// Disable only the currently selected option
						$('#fsession2 option[value="' + fsession2 + '"]').prop('disabled', true);
					} else if (status === 'available') {
						$('#err_limit').text('');
						$('#fsession2 option[value="' + fsession2 + '"]').prop('disabled', false);
					} else if (status === 'no_sessions_provided') {
						//$('#err_limit').text('No sessions provided');
					} else {
						$('#err_limit').text(response);
					}
				},
				error: function () {
					$('#err_limit').text('Request error');
				}
			});
		}

		// Check when dropdown changes
		$('#fsession2').on('change', function() {
			checkLimitfull2();
		});

		// Button to manually check
		$('#submit').on('click', function() {
			checkLimitfull2();
		});

		// Also check on page load (if a value is preselected)
		$(function(){
			if ($('#fsession2').val()) checkLimitfull2();
		});

//day3

		function checkLimit3() {
			//let cmorning1 = ($('#cmorning1').val() || '').trim();
			let cmorning3 = ($('#cmorning3').val() || '').trim();
			console.log(cmorning3);

/*			if (cmorning1 === '') {
				$('#err_limit').text('Please select a sessions');
				return;
			}
			*/
			$.ajax({
				url: 'https://demo.iddllp.com/registration/check_limit.php',
				type: 'POST',
				data: { cmorning3: cmorning3 },
				success: function (response) {
					const status = (response || '').trim();
					if (status === 'exists') {
						$('#err_limit').text('Sold Out');
						// Disable only the currently selected option
						$('#cmorning3 option[value="' + cmorning3 + '"]').prop('disabled', true);
					} else if (status === 'available') {
						$('#err_limit').text('');
						$('#cmorning3 option[value="' + cmorning3 + '"]').prop('disabled', false);
					} else if (status === 'no_sessions_provided') {
						//$('#err_limit').text('No sessions provided');
					} else {
						$('#err_limit').text(response);
					}
				},
				error: function () {
					$('#err_limit').text('Request error');
				}
			});
		}

		// Check when dropdown changes
		$('#cmorning3').on('change', function() {
			checkLimit3();
		});

		// Button to manually check
		$('#submit').on('click', function() {
			checkLimit3();
		});

		// Also check on page load (if a value is preselected)
		$(function(){
			if ($('#cmorning3').val()) checkLimit3();
		});
       
// Afternoon
        function checkLimitafter3() {
			//let cmorning1 = ($('#cmorning1').val() || '').trim();
			let cafternoon3 = ($('#cafternoon3').val() || '').trim();
			console.log(cafternoon3);

/*			if (cmorning1 === '') {
				$('#err_limit').text('Please select a sessions');
				return;
			}*/

			$.ajax({
				url: 'https://demo.iddllp.com/registration/check_limit.php',
				type: 'POST',
				data: { cafternoon3: cafternoon3 },
				success: function (response) {
					const status = (response || '').trim();
					if (status === 'exists') {
						$('#err_limit').text('Sold Out');
						// Disable only the currently selected option
						$('#cafternoon3 option[value="' + cafternoon3 + '"]').prop('disabled', true);
					} else if (status === 'available') {
						$('#err_limit').text('');
						$('#cafternoon3 option[value="' + cafternoon3 + '"]').prop('disabled', false);
					} else if (status === 'no_sessions_provided') {
						//$('#err_limit').text('No sessions provided');
					} else {
						$('#err_limit').text(response);
					}
				},
				error: function () {
					$('#err_limit').text('Request error');
				}
			});
		}

		// Check when dropdown changes
		$('#cafternoon3').on('change', function() {
			checkLimitafter3();
		});

		// Button to manually check
		$('#submit').on('click', function() {
			checkLimitafter3();
		});

		// Also check on page load (if a value is preselected)
		$(function(){
			if ($('#cafternoon3').val()) checkLimitafter3();
		});

//fullday

        function checkLimitfull3() {
			//let cmorning1 = ($('#cmorning1').val() || '').trim();
			let fsession3 = ($('#fsession3').val() || '').trim();
			console.log(fsession3);

/*			if (cmorning1 === '') {
				$('#err_limit').text('Please select a sessions');
				return;
			}*/

			$.ajax({
				url: 'https://demo.iddllp.com/registration/check_limit.php',
				type: 'POST',
				data: { fsession3: fsession3 },
				success: function (response) {
					const status = (response || '').trim();
					if (status === 'exists') {
						$('#err_limit3').text('Session Is Sold Out');
						// Disable only the currently selected option
						$('#fsession3 option[value="' + fsession3 + '"]').prop('disabled', true);
					} else if (status === 'available') {
						$('#err_limit').text('');
						$('#fsession3 option[value="' + fsession3 + '"]').prop('disabled', false);
					} else if (status === 'no_sessions_provided') {
						//$('#err_limit').text('No sessions provided');
					} else {
						$('#err_limit').text(response);
					}
				},
				error: function () {
					$('#err_limit').text('Request error');
				}
			});
		}

		// Check when dropdown changes
		$('#fsession3').on('change', function() {
			checkLimitfull3();
		});

		// Button to manually check
		$('#submit').on('click', function() {
			checkLimitfull3();
		});

		// Also check on page load (if a value is preselected)
		$(function(){
			if ($('#fsession3').val()) checkLimitfull3();
		});
        //}
//}
	/*	// Check when dropdown changes
		$('#threedays').on('change', function() {
			validate();
		});

		// Button to manually check
		$('#submit').on('click', function() {
			validate();
		});

		// Also check on page load (if a value is preselected)
		$(function(){
			if ($('#threedays').val()) validate();
		});
		*/