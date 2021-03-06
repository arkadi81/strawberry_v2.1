<script>
//for more exhaustive description see module 5 header
// this module asks user to provide notes BELOW given note to construct intervals

// OPTIONS

var moduleNumber = 7;

var moduleOptions = [];

		moduleOptions[moduleNumber] = {
			'moduleHeadingText': 'Module '+ moduleNumber +': Intervals',
			'moduleInstructions':'Construct the indicated intervals BELOW the given pitch',
			'treble':2, // total questions in each of these clefs? used to be called q5Options / howManyQuestions 
			'bass':2,
			'alto':2,
			clefSpan: 16, /* the amount of unique roots that a clef contains without 
							accamulating too many ledger lines. we're assuming 
							symmetrical above / below staff distribution */

			totalQuestions: function() {return (this.treble+this.bass+this.alto);},

			clefArray: function() {
				// returns an array of text containing clefs based on the options above, e.g. ['treble','treble','bass']
				var res =[];
					for (i=0; i<this.treble; i++) { res.push('treble') };
					for (i=0; i<this.bass; i++) { res.push('bass') };
					for (i=0; i<this.alto; i++) { res.push("alto") };
					return res;
				}
	//returns an array with clefs based on Q5options
		};

var intervalSizes = ['unison','2nd','3rd','4th','5th','6th','7th','octave']; // we can do larger intervals later if needed
var intervalQualities = ['Diminished','Minor','Major','Perfect','Augmented'];


</script>

<!-- DOM CONSTRUCTOR -->
<div class="panel panel-primary" style="display:none" id="module7">
  <div class="panel-heading">
    <script>document.write(moduleOptions[moduleNumber].moduleHeadingText);</script>
  </div>
  <div class="panel-body" id='module7Container' align="center">
  	<script> 

  		// add instructions to module head 
  		document.getElementById('module'+moduleNumber+'Container').innerHTML+='<div>'+moduleOptions[moduleNumber].moduleInstructions+'</div>';


  		// prepare rootName selector
  		var rootNameSelector='';
		for (i=0; i<rootNames.length; i++) 	{
			rootNameSelector += '<option>' + rootNames[i] +'</option>';
		}
		rootNameSelector = '<select>' + rootNameSelector + '</select>';

		// prepare accidental selector
		var accidentalSelector="";
  		for (i=-2; i<=2; i++) {
			accidentalSelector += "<option>" + accidentalNames[i] +"</option>";
		}
		accidentalSelector = "<select>" + accidentalSelector + "</select>"; // DNF ADD EMPTY OPTION

		// ----------------------------------------------------------------

		// build visual - a div cell for each interval, a canvas inside div

  		
 		for (i=0; i<moduleOptions[moduleNumber].totalQuestions();i++) {
  			//dynamically build up the array of divs - note 4 and 6 change dimentions. configurable for later
			document.getElementById('module'+moduleNumber+'Container').innerHTML+='<div class="col-sm-4 col-xs-6 center"  align="left" id="module'+ moduleNumber + 'Question'+i+'"></div>';
		}
		for (i=0; i<moduleOptions[moduleNumber].totalQuestions();i++) {
  			//dynamically build up the array of instruction spacers and canvases inside divs
  			//document.getElementById('module'+5+'Question'+i+'div').innerHTML+='<div id="module5Question' + i + 'Prompt"</div>';
			document.getElementById('module'+moduleNumber+'Question'+i).innerHTML+='<table class="table"><tr><td><canvas height=100 width=300 id="module' + moduleNumber +'Canvas'+i+'"></canvas></td>';
			document.getElementById('module'+moduleNumber+'Question'+i).innerHTML+='<td><div id="module'+moduleNumber+'Interval'+ i+'"></div></td>';
			document.getElementById('module'+moduleNumber+'Question'+i).innerHTML+='<tr><td>Note below is: '+rootNameSelector+accidentalSelector +'</td>';
			document.getElementById('module'+moduleNumber+'Question'+i).innerHTML+='<tr><td><div id="module'+moduleNumber+'Answer'+ i+'"></div></td>';
			document.getElementById('module'+moduleNumber+'Question'+i).innerHTML+='<tr><td><div id="module'+moduleNumber+'Check'+ i+'"></div></td></table>';
		}

		// fill em up - this will be separated and only executed on load!! need to render canvas, and add instructors and selectors for maj and min
		if (global_theme_override) { document.getElementById('module'+moduleNumber).setAttribute('class','panel '+global_panel_class) };
		if (global_visibility_override) { document.getElementById('module'+moduleNumber).setAttribute('style',global_initial_visibility)};
	</script>
   </div>
   <div class="panel-heading">
    <?php include("panel_buttons.php"); ?>
  </div>
</div>

<!---RANDOMIZING DATA STAGE -->
<script> // 12/30/2015

// get random interval array - original in theory library
/*function buildIntervalArray(size ,direction) {
	//returns a randomized array of intervals of indicated size and direction:
	// the function builds triplets of [CLEF,ORIGINAL NOTE, interval, NEW NOTE] 
	//checks for weird combinations and weeds them out in process of creation */

var randomIntervalArray = buildIntervalArray(moduleOptions[moduleNumber].totalQuestions(),moduleOptions[moduleNumber].clefArray(),"below");
// in this context, the indication 'above' isnt necessary
var infoArray = randomIntervalArray;


</script>

<script>
// optional - show answers

	for (i=0; i<moduleOptions[moduleNumber].totalQuestions();i++) { 
		var bottomNote = infoArray[i][1].getRootName() + accidentalNames[infoArray[i][1].getAccidental()]+"/"+infoArray[i][1].getOctave();
		var topNote = infoArray[i][3].getRootName() + accidentalNames[infoArray[i][3].getAccidental()]+"/"+infoArray[i][3].getOctave();

		var testText = 'Answer: ' + topNote + ' is ' + infoArray[i][2] + ' below ' + bottomNote;
		document.getElementById('module' + moduleNumber +'Answer'+i).innerHTML+= ' ' +testText;
		document.getElementById('module' + moduleNumber +'Check'+i).innerHTML+='<span class="glyphicon glyphicon-ok"></span>'; // ' remove for X'
	};
  

</script>

<script>
// dispaly requested interval and build graphical representation of the randomIntervalArray

	for (i=0; i<moduleOptions[moduleNumber].totalQuestions();i++) {

		// display interval
		var intervalText = 'Interval: ' + infoArray[i][2];
		document.getElementById('module' + moduleNumber +'Interval'+i).innerHTML+= intervalText;

		canvasName = 'module' + moduleNumber + 'Canvas'+i;
		var newCanvas = document.getElementById(canvasName)
		var canvas = $("div.one div.a canvas")[0];
		var renderer = new Vex.Flow.Renderer(newCanvas,Vex.Flow.Renderer.Backends.CANVAS);
		var ctx = renderer.getContext();
		var stave = new Vex.Flow.Stave(0, 0,100);
		//clefNames = clefArray();
		//stave.addClef(clefNames[i]).setContext(ctx).draw();

		// Create the notes using values from the randomIntervalArray

		var bottomNote = infoArray[i][1].getRootName() + accidentalNames[infoArray[i][1].getAccidental()]+"/"+infoArray[i][1].getOctave();
		var topNote = infoArray[i][3].getRootName() + accidentalNames[infoArray[i][3].getAccidental()]+"/"+infoArray[i][3].getOctave();

		
		tmp = new Vex.Flow.StaveNote({ clef:infoArray[i][0], keys: [bottomNote /*no top note here*/] , duration: "w" });
			if (infoArray[i][1].getAccidental()!=0) { 
				tmp.addAccidental(0, new Vex.Flow.Accidental(accidentalNames[infoArray[i][1].getAccidental()]));
		}
		/*if (infoArray[i][3].getAccidental()!=0) { 
			tmp.addAccidental(1, new Vex.Flow.Accidental(accidentalNames[infoArray[i][3].getAccidental()]));
		}*/
		var notes = [tmp];
    
	    // Create a second voice, with just one whole note
		/* tmp2 = new Vex.Flow.StaveNote({ keys: [topNote], duration: "w" });
		if (infoArray[i][3].getAccidental()!=0) { 
		tmp2.addAccidental(0, new Vex.Flow.Accidental(accidentalNames[infoArray[i][3].getAccidental()]));
		}
		var notes2 = [tmp2];*/
 
		  
		// Create a voice in 4/4
  		function create_4_4_voice() {
			return new Vex.Flow.Voice({
				  num_beats: 4,
				  beat_value: 4,
				  resolution: Vex.Flow.RESOLUTION
			});
		  }
			  
		// Create voices and add notes to each of them.
		var voice = create_4_4_voice().addTickables(notes);
		// var voice2 = create_4_4_voice().addTickables(notes2);
		 
		// Format and justify the notes to 500 pixels
		var formatter = new Vex.Flow.Formatter().
		joinVoices([voice]).format([voice], 200);
		   
		stave.addClef(infoArray[i][0]).setContext(ctx).draw(); 
		// Render voice
			 
		voice.draw(ctx, stave);
		// voice2.draw(ctx, stave);
			 
			 			 
		/* experimental part for identifying locations here
		newCanvas.addEventListener("click", function(){
		var x = event.clientX;     // Get the horizontal coordinate of mouse in page!
		var y = event.clientY;     // Get the vertical coordinate of mouse in page!

			var x = event.layerX;     // Get the horizontal coordinate of mouse incurrent layer, works great.
			var y = event.layerY;   

						// 40 pixels from the top for nothing, then each 10 is distance between 2 barlines
			getPitchfromY = function() {
				return Math.floor(event.layerY / 5); // each 5 pixels is one unit
			}

			var coor = "X coords: " + x + ", Y coords: " + y;
			var staff_coor = "y " + stave.y + " corresponds to pitch number " + getPitchfromY();

			// TODO: build a function which will convert pitch number and clef to the actual pitch.
			// do this later. first get done formatting EVERYTHING. this implementation can wait


		  	document.getElementById("mousePosition").innerHTML=coor;
		  	document.getElementById("pos").innerHTML=staff_coor;

		  });

		  
		  //stave.addKeySignature(keySignatures[res[i]]).setContext(ctx).draw();
		  
		  // ----------------------------------- build a key selector ---------------------------
		  //console.log(selectionOptions);
		  //add the selection box to allow for answers
		  $("#"+myCell.id).append(selectionOptions);
		  //for testing purposes, display the control line
		
	      ++cellNumber;
	      ++canvasNumber;
		*/	  
	};
	  

/* // function q5Render(infoArray,targetDivId) {

	// 20151230 this is now absolete

	//renders the two notes as a harmonic interval - we will probably not even need the top note for now, but this could be useful for reverse intervals
	// the array is: [0] = clef [1] = bottom [2] = interval (not visible) [3] = top
	  //
	  size = qmoduleNumberOptions.totalQuestions();
  	  placeHolder = document.getElementById(targetDivId);
	  
	  myTable = document.createElement("table");
	  myTable.border=1;
	  document.body.insertBefore(myTable,placeHolder);
	  myRow = document.createElement("tr");
	  myTable.appendChild(myRow);
	  for (i=0; i<size; i++) {
		  // build a cell for the new question and set it in the DOM, assign sequential ID to cell stored in cellNumber
		  myCell = document.createElement("td");
		  myCell.id = "cell" +cellNumber;
		  newContent = document.createTextNode("I am cell number " + cellNumber);
		  myCell.appendChild(newContent);
		  myRow.appendChild(myCell);
		  
		  // --------------------------------------------- render code: get the cell to have a div inside of it, get context of div,
		  // -------------------------------------------- plant a key sig inside of it
		 
		  //canvasName = "c" + targetID.slice(-1);
		  document.getElementById(myCell.id).innerHTML="<canvas width=120 height = 120 id=c"+ cellNumber +"></canvas>";
		  var newCanvas = document.getElementById("c" + cellNumber)

		  // TEST: FIND OUT WHERE IM CLICKING

		  // new: create a new DOM canvas for our thingy
		  //var newCanvas = document.createElement("canvas");
		  //var currentcanvas = document.getElementById(targetID); // lets eliminate the necessity for t1
		  // todo. saparate canvas creation from the rendering process. canvas creation should go with the design element of
		  // how many questions does the global indicate - table should be built based off that
		  //document.body.insertBefore(newCanvas,currentcanvas); */
		  
		  
</script>