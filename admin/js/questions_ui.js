$(document).ready(function(){
	$("#questForm").bind('submit',function(e){
		var error = false;
		if(jQuery.trim($('#testTitle').val()) == '')
		{
			alert('Please enter test title');	
			return false;
		}

		$(this).trigger('validate');
		if($(".main").data('error') > 0)
		{
			alert('Please ensure that all the values has been filled and answers selected');
			$(".main").data('error',0);
			return false;
		}
		
		$("#testWeight").val($("#testCanvas").data("itemCount"));
		return true;
	});
	
	$("#sbtButton").attr('disabled','disabled');
	$(".main").data('error',0);
	$("#testCanvas").data("itemCount", 0);
	$("#questionCount").html("0");
	$(".question").draggable({
				helper: "clone",
				opacity: 0.5,
				revert: 'valid',
				revertDuration: 800,
				scroll: true,
				snap: true,
				snapMode: 'both',
				connectToSortable:"#testCanvas"
				});
	
	$("#testCanvas").sortable({
					handle: ".questionHandle",
					revert: 'invalid',
					revertDuration: 2000,
					scroll: true,
					axis: 'y',
					receive: function(event, ui){
						var structure = null;
						var questionType = $(ui.item).attr("id");

						var itemCount = $("#testCanvas").data("itemCount");
						itemCount++;
						$('#sbtButton').attr('disabled','');
						//initialize divs
						$(ui.item).html('');
						$(ui.item).removeClass("question");
						$(ui.item).addClass("sortableQuestion");
						
						//Just counter for questions added
						$("#testCanvas").data("itemCount", itemCount);
						$("#questionCount").html(itemCount.toString());
						
						//give unique id to added dragged block
						$(ui.item).data("id", itemCount);
						$(ui.item).attr("id","quest"+itemCount);

						//To know which id's are present when form is submitted
						$(ui.item).append("<input type='hidden' name='id[]' value='"+itemCount+"'>");
						//To know question type
						$(ui.item).append("<input type='hidden' name='questionType[]' value='"+questionType+"'>");
						
						//Gray Strip on top of question
						structure = "<div class='questionHandle'>";
						structure += "<div style='float:right;'><a id='quest"+itemCount+"HideShow' href='#'>Collapse</a>&nbsp;&nbsp;&nbsp;<a id='quest"+itemCount+"Delete' href='#'>Delete</a></div>";
						structure += "<div id='quest"+itemCount+"Title'>Question Title</div>";
						structure += "</div>";

						//All the question content will go in this
						structure += "<div class='questionContent' id='quest"+itemCount+"Content'></div>";
					
						$(ui.item).append(structure);

						//Add input for title
						structure = "Enter Title: <input type='text' id='quest"+itemCount+"InputTitle' name='quest"+itemCount+"title' maxLength='100' size='100' />";
						
						$("#quest"+itemCount+"Content").append(structure);

						//OnClick event for Hide/Show question
						$("#quest"+itemCount+"HideShow").bind("click", function(e){
							if($("#quest"+itemCount+"Content").is(":visible"))
							{
								$("#quest"+itemCount+"Content").hide('normal');
								$("#addNewOptionForQuest"+itemCount).hide();
								$(this).html('Expand');
							}
							else
							{
								$("#quest"+itemCount+"Content").show('normal');
								$("#addNewOptionForQuest"+itemCount).show();
								$(this).html('Collapse');
							}
						});

						//OnClick event for deleting question
						$("#quest"+itemCount+"Delete").bind("click", function(e){
							$("#quest"+itemCount).addClass('redBorder');
							if(confirm('Are you sure you want to delete this question?'))
							{
								$("#quest"+itemCount).remove();
								itemCount = $("#testCanvas").data("itemCount");
								itemCount--;
								if(itemCount == 0)
								{
									$('#sbtButton').attr('disabled','disabled');
								}
								$("#testCanvas").data("itemCount", itemCount);
								$("#questionCount").html(itemCount.toString());
							}
							$("#quest"+itemCount).removeClass('redBorder');
							return;
						});

						//OnChange event for Question Title
						$("#quest"+itemCount+"InputTitle").bind("onchange keyup", function(e){
							if(jQuery.trim($(this).val()) != '')
							{
								$("#quest"+itemCount+"Title").html($(this).val());
								return;
							}
							$("#quest"+itemCount+"Title").html("Question Title");
						});

						switch(questionType)
						{
							case '1':
								$("#quest"+itemCount+"Content").data("optionsCount", 2); //Will have atleast two options

								//Add options with checkboxes
								structure = "<div>Options:<br /><div class='option_left' id='quest"+itemCount+"LeftOptions'><input type='text' id='quest"+itemCount+"Option1' name='quest"+itemCount+"Option1' size='30' /> <input type='checkbox' id='quest"+itemCount+"Correct1' name='quest"+itemCount+"Correct[]' value='1' /></div>";
								structure += "<div class='option_right' id='quest"+itemCount+"RightOptions'><input type='text' id='quest"+itemCount+"Option2' name='quest"+itemCount+"Option2' size='30' /> <input type='checkbox' name='quest"+itemCount+"Correct[]' value='2' /></div><div style='clear:both;'</div>";
								structure += "<div class='option'><a id='addNewOptionForQuest"+itemCount+"' href='#'>Add More Options</a></div></div>";

								$("#quest"+itemCount+"Content").append(structure);

								//Click event for Add More Options
								$("#addNewOptionForQuest"+itemCount).bind("click", function(e){
									var optionsCount = $("#quest"+itemCount+"Content").data("optionsCount");
									optionsCount++;
									var addToDiv = "#quest"+itemCount+"RightOptions";
									
									if((optionsCount % 2) == 1)
									{
										addToDiv = "#quest"+itemCount+"LeftOptions";
									}

									$(addToDiv).append("<div class='option' id='quest"+itemCount+"option"+optionsCount+"Div'></div>");
									$("#quest"+itemCount+"option"+optionsCount+"Div").hide();
									$("#quest"+itemCount+"option"+optionsCount+"Div").append("<input type=\"text\" id=\"quest"+itemCount+"Option"+optionsCount+"\" name=\"quest"+itemCount+"Option"+optionsCount+"\" size=\"30\" /> <input type='checkbox' id='quest"+itemCount+"Correct"+optionsCount+"' name='quest"+itemCount+"Correct[]' value='"+optionsCount+"' /> <input type='hidden' name='quest"+itemCount+"optionsId[]' value='"+optionsCount+"' /> <a id=\"quest"+itemCount+"Option"+optionsCount+"Remove\" href='#'>Remove</a>");
									$("#quest"+itemCount+"option"+optionsCount+"Div").show("normal");
									$("#quest"+itemCount+"Content").data("optionsCount", optionsCount);

									$("#quest"+itemCount+"Option"+optionsCount+"Remove").bind('click', function(e){
										$("#quest"+itemCount+"option"+optionsCount+"Div").hide("normal",function(e){
											$(this).remove();
										});
										optionsCount--;
										$("#quest"+itemCount+"Content").data("optionsCount", optionsCount);
									});
								});
								
								//Validations
								//Only check for empty input fields
								$("#questForm").bind("validate", function(e){
									var error = $(".main").data('error');
									textInputs = $("#quest"+itemCount+"Content input[type='text']");	
									checkInputs = $("#quest"+itemCount+"Content input[type='checkbox']")
									textInputs.each(function(){
										$(this).removeClass('error');
										if(jQuery.trim($(this).val()) == '')
										{
											error++;
											$(".main").data('error',error);
											$(this).addClass('error');
										}
									});
									
									
									var found = false;
									checkInputs.each(function(){
										$("#quest"+itemCount).removeClass('error');
										
										if($(this).attr('checked'))
										{
											found = true;
										}
									});

									if(!found)
									{
										error++;
										$(".main").data('error',error);
										$("#quest"+itemCount).addClass('error');
									}
									return false;
								})

							break;

							case '2':
								$("#quest"+itemCount+"Content").data("optionsCount", 2); //Will have atleast two options

								//Add options with radio buttons
								structure = "<div>Options:<br /><div class='option_left' id='quest"+itemCount+"LeftOptions'><input type='text' id='quest"+itemCount+"Option1' name='quest"+itemCount+"Option1' size='30' /> <input type='checkbox' name='quest"+itemCount+"Correct' value='1' /></div>";
								structure += "<div class='option_right' id='quest"+itemCount+"RightOptions'><input type='text' id='quest"+itemCount+"Option2' name='quest"+itemCount+"Option2' size='30' /> <input type='checkbox' name='quest"+itemCount+"Correct' value='2' /></div><div style='clear:both;'</div>";
								structure += "<div class='option'><a id='addNewOptionForQuest"+itemCount+"' href='#'>Add More Options</a></div></div>";
						
								$("#quest"+itemCount+"Content").append(structure);
								$.radioCheckboxGroup("quest"+itemCount+"Correct");

								//Click event to Add More Options
								$("#addNewOptionForQuest"+itemCount).bind("click", function(e){
									var optionsCount = $("#quest"+itemCount+"Content").data("optionsCount");
									optionsCount++;
									var addToDiv = "#quest"+itemCount+"RightOptions";
									
									if((optionsCount % 2) == 1)
									{
										addToDiv = "#quest"+itemCount+"LeftOptions";
									}

									$(addToDiv).append("<div class='option' id='quest"+itemCount+"option"+optionsCount+"Div'></div>");
									$("#quest"+itemCount+"option"+optionsCount+"Div").hide();
									$("#quest"+itemCount+"option"+optionsCount+"Div").append("<input type='text' id='quest"+itemCount+"Option"+optionsCount+"' name='quest"+itemCount+"Option"+optionsCount+"' size='30' /> <input type='checkbox' id='quest"+itemCount+"Correct"+optionsCount+"' name='quest"+itemCount+"Correct' value='"+optionsCount+"' /> <input type='hidden' name='quest"+itemCount+"optionsId[]' value='"+optionsCount+"' /> <a id='quest"+itemCount+"Option"+optionsCount+"Remove' href='#'>Remove</a>");
									$("#quest"+itemCount+"option"+optionsCount+"Div").show("normal");
									$("#quest"+itemCount+"Content").data("optionsCount", optionsCount);
									$.radioCheckboxGroup("quest"+itemCount+"Correct");
									$("#quest"+itemCount+"Option"+optionsCount+"Remove").bind('click', function(e){
										if($("#quest"+itemCount+"Correct"+optionsCount).attr('checked'))
										{
											$("#quest"+itemCount+"Correct1").attr('checked',true);
										}
										$("#quest"+itemCount+"option"+optionsCount+"Div").hide("normal",function(e){
											$(this).remove();
										});
										optionsCount--;
										$("#quest"+itemCount+"Content").data("optionsCount", optionsCount);
									});
								});
								
								//Validations
								//Only check for empty input fields
								$("#questForm").bind("validate", function(e){
									var error = $(".main").data('error');
									textInputs = $("#quest"+itemCount+"Content input[type='text']");	
									radioInputs = $("#quest"+itemCount+"Content input[type='checkbox']")
									textInputs.each(function(){
										$(this).removeClass('error');
										if(jQuery.trim($(this).val()) == '')
										{
											error++;
											$(".main").data('error',error);
											$(this).addClass('error');
										}
									});
		
									var found = false;
									radioInputs.each(function(){
										$("#quest"+itemCount).removeClass('error');
										
										if($(this).attr('checked'))
										{
											found = true;
										}
									});

									if(!found)
									{
										error++;
										$(".main").data('error',error);
										$("#quest"+itemCount).addClass('error');
									}


									return false;
								})

							break;

							case '3':
								
								structure = "<br /><div>Enter Correct Answer <input type='text' id='quest"+itemCount+"Correct' name='quest"+itemCount+"Correct' size='30' /></div>";
								
								$("#quest"+itemCount+"Content").append(structure);

								//Validations
								//Only check for empty input fields
								$("#questForm").bind("validate", function(e){
									var error = $(".main").data('error');
									var value;
									inputs = $("#quest"+itemCount+"Content input[type='text']");	
									inputs.each(function(){
										$(this).removeClass('error');
										value = jQuery.trim($(this).val());
										if(value == '' || ($(this).attr('id') == "quest"+itemCount+"InputTitle" && value.indexOf('____') == -1))
										{
											error++;
											$(".main").data('error',error);
											$(this).addClass('error');
										}
									});
									return false;
								});

							break;
						}
					}
				});
	});