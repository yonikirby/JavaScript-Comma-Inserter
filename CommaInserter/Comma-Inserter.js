// JavaScript Document
//Author: Yonatan Kirby
//yonikirby@gmail.com
//Personal Website: http://yonatankirby.atwebpages.com/

var AddedCommas = [];



function doAllCommaRules(WordTagArray){
	var wta = doCommaRuleOne(WordTagArray);
 
	wta = doCommaRuleTwoThree(wta);

	wta = doCommaRuleFour(wta);
 
	return wta;


}

/*
Rule 1.	To avoid confusion, use commas to separate words and word groups with a series of three or more.
Example:	My $10 million estate is to be split among my husband, daughter, son, and nephew. Omitting the comma after son would indicate that the son and nephew would have to split one-third of the estate.
*/

function doCommaRuleOne(WordTagArray) {
	var nounCounter = 0;
	var currentPosition = 0;
	var lastWordWasANoun = false;
	var myPossibleNounString = new Array(3);
	for (i in WordTagArray) {
		  var node = WordTagArray[i];
		  var word = node[0];
		  var tag = node[1];
		  if (lastWordWasANoun == false) {
				if (tag == "NN" || tag == "NNS" || tag == "NNP" || tag == "NNPS") {
						lastWordWasANoun = true;
						nounCounter = 1;
				}
				else nounCounter = 0;
		  }
		  else if (lastWordWasANoun == true) {
			  	if (word == "and" && nounCounter >=2) {
					(WordTagArray[i-2])[0] = (WordTagArray[i-2])[0] + ",";
					AddedCommas.push(i);
					nounCounter = 0;
					lastWordWasANoun = false;
				}
				
			    
				else if (tag == "NN" || tag == "NNS" || tag == "NNP" || tag == "NNPS") {
						nounCounter++;
						
						if (nounCounter >= 3) {
							
							(WordTagArray[i-2])[0] = (WordTagArray[i-2])[0] + ",";
							AddedCommas.push(i);
						}
				}
				else {
					nounCounter = 0;
					lastWordWasANoun = false;
		  		}
		  }
		  if (WordTagArray[i][0] == ". "){
		  		WordTagArray[i][0] = ".  ";
		  }
	}
	  
	  
	  
	  
	return WordTagArray;
	
}



/*

Rule 2. Use a comma to separate two adjectives when the word and can be inserted between them.

Examples: He is a strong, healthy man. We stayed at an expensive summer resort. You would not say expensive and summer resort, so no comma.

Rule 3. Use a comma when an -ly adjective is used with other adjectives. To test whether an -Iy word is an adjective, see if it can be used alone with the noun. If it can, use the comma.

Examples: Felix was a lonely, confused boy. I get headaches in brightly lit rooms. Brightly is not an adjective because it cannot be used alone with rooms; it is an adverb describing lit. Therefore, no comma is used between brightly and lit.
*/


function doCommaRuleTwoThree(WordTagArray) {
	var determinerCounter = 0;
	var currentPosition = 0;
	var lastWordWasADeterminer = false;
	var myPossibleNounString = new Array(3);
	var length = WordTagArray.length;
	for (i = 0; i < WordTagArray.length; i ++) {
		  var node = WordTagArray[i];
		  var word = node[0];
		  var tag = node[1];
		  if (lastWordWasADeterminer == false) {
				if (tag == "CD" || tag == "DT") {
						lastWordWasADeterminer = true;
				}
				else if ((tag == "JJ" || tag == "RB")&& i < length - 1 && WordTagArray[i+1][1] == "JJ"){

						(WordTagArray[i])[0] = (WordTagArray[i])[0] + ",";
						AddedCommas.push(i);
						lastWordWasADeterminer = false;
					
				}
		  }
		  
		  else {
		  		if ((tag == "JJ" ||  tag == "RB") && i < length - 1 && WordTagArray[i+1][1] == "JJ"){
					
						(WordTagArray[i])[0] = (WordTagArray[i])[0] + ",";
						AddedCommas.push(i);
						lastWordWasADeterminer = false;
					
				}
				else if (tag == "CD" || tag == "DT") {
					lastWordWasADeterminer = true;
				}
				else {
					lastWordWasADeterminer = false;
				}
					
		  }
		  
	}
	  
	  
	return WordTagArray;
	
}





/*
Rule 4:

Use commas before or surrounding the name or title of a person directly addressed.

Examples:	Will you, Aisha, do that assignment for me?

Yes, Doctor, I will.

NOTE:	Capitalize a title when directly addressing someone.
*/


function doCommaRuleFour(WordTagArray) {
	var determinerCounter = 0;
	var currentPosition = 0;
	var lastWordWasADeterminer = false;
	var myPossibleNounString = new Array(3);
	var length = WordTagArray.length;
	for (i = 0; i < WordTagArray.length; i ++) {
		  var node = WordTagArray[i];

		  var word = node[0];
		  var tag = node[1];
		  
		  
		 
		  
		  if (/*(tag == "NNP" || tag == "NNPS") || */((tag == "PRP" || tag == "UH") && (WordTagArray[i+1][1]== "NN" ||
																	 WordTagArray[i+1][1]== "NNS" ||
																	 WordTagArray[i+1][1]== "NNP" ||
																	 WordTagArray[i+1][1]== "NNPS"))) {//surround it by commas or periods or either/or
		  	
				
				
				
				if (i > 0){
					if ((i > 0) || (WordTagArray[i-1][1] == ".")){//just put a comma after
						 (WordTagArray[i])[0] = (WordTagArray[i])[0] + ",";
						 
						 if (i < length - 2){
							 (WordTagArray[i+1])[0] = (WordTagArray[i+1])[0] + ",";
						 }
					}
				}
				else if (i == 0){
					 (WordTagArray[i])[0] = (WordTagArray[i])[0] + ",";//just put a comma after-- at absolute beginning
					 
					 if (i < length - 2 && (WordTagArray[i+2])[0] != "."){
							 (WordTagArray[i+1])[0] = (WordTagArray[i+1])[0] + ",";
					  }
				}
				else if ((i < WordTagArray.length - 1) && (WordTagArray[i+1][1] == ".")) {//just put a comma before
					 
					 (WordTagArray[i-1])[0] = (WordTagArray[i-1])[0] + ",";
																			 }
				else if ((i == WordTagArray.length - 1) && (WordTagArray[i][1] == ".")) {//just put a comma before if absolute end
					 (WordTagArray[i-1])[0] = (WordTagArray[i-1])[0] + ",";
																			 }
				else{//put a comma both before and after
					
					(WordTagArray[i-1])[0] = (WordTagArray[i-1])[0] + ",";
					(WordTagArray[i])[0] = (WordTagArray[i])[0] + ",";
				}
																			 
																		
						 
		  
		  
		  }
		  
		  
		  
	}
	  
	return WordTagArray;
	
}
