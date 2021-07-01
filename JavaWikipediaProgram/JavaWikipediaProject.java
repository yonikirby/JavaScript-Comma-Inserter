//Yonatan Kirby--Wikipedia Project
//yonikirby@gmail.com
/*
Hello.

Please also see the accompanied readme file for this.

Aside from this project, I also have experience programming in Java in several classes, including Data Structures, Algorithms, Networking and Software Engineering.

Yonatan Kirby

yonikirby@gmail.com

http://yonatankirby.atwebpages.com/

*/


package OurProject;

import java.nio.file.Files;
import java.nio.file.Paths;
import java.text.BreakIterator;
import java.util.ArrayList;
import java.util.Arrays;



import javax.script.ScriptEngine;
import javax.script.ScriptEngineManager;




import java.util.List;
import java.util.ListIterator;
import java.util.Locale;
import java.util.Scanner;

import org.graalvm.polyglot.Context;




public class WikipediaProject {

	
	public String[] doesInputSentenceContainNumberAndWhatIsIt(String inputSentence){
		
		
		
		
		return null;
		
	}
	
	
	public void callJS() throws Exception{
		ScriptEngineManager manager = new ScriptEngineManager(null);
		
		ScriptEngine engine = manager.getEngineByName("JavaScript");
	
		
try (Context context = Context.newBuilder().allowExperimentalOptions(true).option("js.nashorn-compat", "true").build()) {
    
    
    
    context.eval("js", Files.readString(Paths.get("C:\\Users\\Yonatan Kirby\\Documents\\txtwiki\\txtwiki.js-master\\query.js")));
    context.eval("js", Files.readString(Paths.get("C:\\Users\\Yonatan Kirby\\Documents\\txtwiki\\txtwiki.js-master\\txtwiki.js")));
  			
		System.out.println("Enter Your Question:");
		//For example: How tall is the average giraffe?
		Scanner sc = new Scanner(System.in);
		String inputLine = sc.nextLine();
		
		String[] inputArray = inputLine.split(" ");
		
		
		
		ArrayList<String> forFastTag = new ArrayList<String>(Arrays.asList(inputArray));
		
		//Print out the parts of speech for the question
		List<String> posResult = FastTag.tag(forFastTag);
		System.out.println(posResult);
		
		ListIterator<String> posIterator = posResult.listIterator(); 

		
		//Now We Will Look Up The Noun (Subject) on Wikipedia
		//First we determine the subject of the question, which will be the noun
		
		int wordIndex = 0;
		while(posIterator.hasNext()){
			if (posIterator.next().equals("NN")){
				break;
			}
			else{
				wordIndex++;
			}
		}
		String wordToLookUp = inputArray[wordIndex];  //is the noun (i.e. "giraffe")
		String wordModifier = "";// is the word modifier, for example "average".  
		
		if(wordIndex > 0){
			wordModifier = inputArray[wordIndex-1];
		}
		
		System.out.println("Word modifier: " + wordModifier);
		
		
		//Delete the question mark at the end of the word "giraffe"
			StringBuilder sb = new StringBuilder(wordToLookUp);
			for(int i = 0; i < wordToLookUp.length();i++){
				if(sb.charAt(i)=='?'){
					sb.deleteCharAt(i);
				}
			}
		wordToLookUp=sb.toString();  
		System.out.println("Word we look up: "+wordToLookUp);
		
		
		String urlToRead = "https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&explaintext&titles="+wordToLookUp;
		
		String everything = GetFromWikipedia.getHTML(urlToRead);
		
		
		//then find "tall" (is JJ/adjective)
		posIterator = posResult.listIterator(); //iterate through the parts of speech
				wordIndex = 0;
				while(posIterator.hasNext()){
					if (posIterator.next().equals("JJ")){	//find index of the adjective "tall"
						break;						
					}
					else{
						wordIndex++;
					}
				}
				
				String ourAdjective = inputArray[wordIndex];  //set ourAdjective = "tall"
				System.out.println("Our adjective: " + ourAdjective);
				
		/*Now we split the Wikipedia text into sentences and then look for both "tall "
	      and "giraffe" in the same sentence
	     */
						
				ArrayList<String> mySentences = new ArrayList<String>();
				
				BreakIterator iterator = BreakIterator.getSentenceInstance(Locale.US);
				String source = everything;
				iterator.setText(source);
				int start = iterator.first();
				for (int end = iterator.next();
				    end != BreakIterator.DONE;
				    start = end, end = iterator.next()) {
				  mySentences.add(source.substring(start,end));//add the sentences to my arraylist
				}
				
				ListIterator<String> li = mySentences.listIterator();
				ArrayList<String> resultList = new ArrayList<String>();
				while(li.hasNext()){
					String currentSentence = li.next();
					//Now we only keep the sentences that both contain "tall" and "giraffe"
					
					if(currentSentence.contains(ourAdjective)&&currentSentence.contains(wordToLookUp)){
						System.out.println("one candidate = " + currentSentence);
						resultList.add(currentSentence);
					}
				}
				
				
				
				ListIterator<String> myLI = resultList.listIterator();
				
				//Now we clean up the candidate sentence that has json data
				
				if(myLI.hasNext()){
					everything = myLI.next();
					if (everything.contains("extract"))	{
						int location = everything.indexOf("extract");
						everything = everything.substring(location);
						everything = everything.substring(10);
						myLI.add(everything);//add the cleaned up text to my result list of sentences
						
						ListIterator<String> myLI2 = resultList.listIterator();
						myLI2.next();
						myLI2.remove();			//delete the old json text from my result list of sentences
					}
				}
				
				
				ListIterator<String>moreLI = resultList.listIterator();
				
				//Now we only keep the results that have numbers
				ArrayList<String> withNumbers = new ArrayList<String>();
				while(moreLI.hasNext()){
					String myString = moreLI.next();
					if(myString.matches(".*\\d+.*")){
						withNumbers.add(myString);
					}
				
				}
				
				
				//Now we clean up the Wikipedia formatting
				myLI = withNumbers.listIterator();
				ArrayList<String> cleanedUpFinalists = new ArrayList<String>();
				while(myLI.hasNext()){
					everything = myLI.next();
					CharSequence cs1 = "2013";
					if(everything.contains(cs1)){
						everything = everything.replace("2013", "-");
					}
					String myString = everything.replace("\\u", "");
					myString = myString.replace("\\n", "");
					myString = myString.replace("2020", "");
					System.out.println("One sentence containing a number range: " + myString);
					cleanedUpFinalists.add(myString);	
				}
				
				//Now we select the sentences that has a dash ("-") in between two numbers
				myLI = cleanedUpFinalists.listIterator();
				ArrayList<String> sentencesWithHyphensSurroundedByNumbers = new ArrayList<String>();
				while(myLI.hasNext()){
					String myString = myLI.next();
					while(myString.indexOf("-") != -1){//if sentence still contains a hyphen somewhere 
						
							int hyphenIndex = myString.indexOf("-");
							String charBeforeHyphen = myString.substring(hyphenIndex+1, hyphenIndex+2); 
							String charAfterHyphen = myString.substring(hyphenIndex-1, hyphenIndex); 
							
							if(charBeforeHyphen.matches(".*\\d+.*")&&charAfterHyphen.matches(".*\\d+.*")){
								sentencesWithHyphensSurroundedByNumbers.add(myString);
								break;//if the dash is surrounded by numbers, we keep it
							}
									
							myString = myString.substring(hyphenIndex+1);			 
					}
				}
				//Now we tag parts of speech for finalist sentences
				ListIterator<String> listITR = sentencesWithHyphensSurroundedByNumbers.listIterator();
				while(listITR.hasNext()){
					
						String ourCurrentLine = listITR.next();
						inputArray = ourCurrentLine.split(" ");
				
						forFastTag = new ArrayList<String>(Arrays.asList(inputArray));
						
						posResult = FastTag.tag(forFastTag);
						
				}
				
				//Now we get the modifier of "giraffe" in each finalist sentence i.e. "newborn" and "adult"
				//for our two finalist sentences:
				// 1)Fully grown giraffes stand 4.3-5.7 m (14.1-18.7 ft) tall, with males taller than females.
				// 2)A newborn giraffe is 1.7-2 m (5.6-6.6 ft) tall.
				
				//We then see how far "newborn" and "adult" are from "average" synonym-wise in the phrase
				//"average giraffe".  The winner is our final answer.
				
				ListIterator<String> myListIterator2 = sentencesWithHyphensSurroundedByNumbers.listIterator();
				
				ArrayList<String> wordsBeforeGiraffe = new ArrayList<String>();
				while(myListIterator2.hasNext()){
					
					String st = myListIterator2.next();
					String[] words = st.split(" ");
					for(int a = 0; a < words.length; a++){
						if(words[a].equalsIgnoreCase(wordToLookUp)||words[a].equalsIgnoreCase(wordToLookUp+"s")){
							System.out.println("word before giraffe is: " + words[a-1]);
							wordsBeforeGiraffe.add(words[a-1]);
						}
					}
					
									}
				
				
				ListIterator<String> myListItr = wordsBeforeGiraffe.listIterator();
				double lowestScore = 100.0;
				String current="";
				String finalCurrent = "";
				while(myListItr.hasNext()){
					current = myListItr.next();
					double test = SimilarityCalculationDemo.compute2(current,wordModifier);
					System.out.println("distance between " + current
							+ " and " + wordModifier + " is "+test);
					if(test<lowestScore){
						lowestScore = test;
						finalCurrent = current;
					}
				}
				
				System.out.println("lowest score is: " + lowestScore);
				
				
				String winner = "";
				myLI = cleanedUpFinalists.listIterator();
				while(myLI.hasNext()){
					//System.out.println(myLI.next());
					String myString = myLI.next();
					if (myString.contains(finalCurrent)){
						winner = myString;
					}
				}
				
				
				int b = winner.indexOf(finalCurrent);//grown
				
				
				boolean done = false;
				while(!done){
					
					if(winner.contains("==")){
						winner = winner.substring(winner.indexOf("==")+2);
					}
					else{
						done=true;
						break;
					}
				}
				
				System.out.println("Here's your answer: " + winner);
						
	}	
		
		
	}
	
	
	public static void main(String[] args) throws Exception {
		WikipediaProject st = new WikipediaProject();
		st.callJS();
			
	}
	
	//To test: how tall is the average giraffe?  how tall is a baby giraffe?

/*
Run the program again. Enter the question: How tall is the average elephant?

Your answer will be “Male African bush elephants are typically 23% taller than females, whereas male Asian elephants are only around 15% taller than females.” This is the wrong answer, obviously, and in the future I need to weed out answers that have numbers with a percent symbol next to them. But it is still interesting to see how the candidate sentences are whittled down.
*/
}
