<?php session_start();
include("config.php");

$myID = $_SESSION["userid"];


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

?>                        

                            <div id = "ShareToFriendsWallSearch">
                                    <div id = "MyInput">
                                            <input type="text" onfocus = "this.value=''" onfocusout = "this.value='Search for friends'" 
                                            id="ShareToFriendsWallSearchInput" name="ShareToFriendsWallSearchInput" value = "Search for friends">
                                    
                                           

                                            <img src="images/magnifyingglass.png" />    
                                    </div>
                                    

                                 
                            </div>
                            <br />


                            <div id = "ShareToFriendsWallSuggestionsHeader">
                                    SUGGESTIONS
                            </div>
                            
                            <?php
                            $sql = "SELECT * FROM friends WHERE status = '1' AND ('$myID' = user_two_id OR '$myID' = user_one_id)";
                      		
                      		
                      		if (!$result = mysqli_query($con,$sql)){
                          		echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                          		
                      		}
                                            if (mysqli_num_rows($result) > 0){
                                            $IDsToQuery = (array) null;
                                            
                                            while ($row = mysqli_fetch_array($result)) {
                                    
                                                $UserOneID = $row['user_one_id'];
                                                $UserTwoID = $row['user_two_id'];
                                                
                                                if ($myID == $UserOneID){
                                                    array_push($IDsToQuery,$UserTwoID);
                                                }
                                                else {
                                                    array_push($IDsToQuery,$UserOneID);
                                                
                                                }
                                            }


                                                $sql = "SELECT * FROM userinfo WHERE id in (" . implode(',', $IDsToQuery) . ")";
                                                
                                                if (!$result = mysqli_query($con,$sql)){
                                                                            echo '<script type="text/javascript">alert("First Query: '.mysqli_error($con).'");</script>';
                                                                            
                                                                        }

                                          


                                            while ($row = mysqli_fetch_array($result)) {
                                                                                        $firstname = $row['firstname'];
                                                                                        $lastname = $row['lastname'];
                                                                                        $profile_src = $row['profilephotopath'];
                                                                                        $id = $row['id'];

                                                    echo '
                                                            <div class = "OneShareToFriendsWallResult" id = "OneShareToFriendsWallResult'.$id.'" 
                                                            data-id = "'.$id.'" data-firstname = "'.$firstname.'">

                                                                    <img src = "'.$profile_src.'" />
                                                                    '.$firstname.' '.$lastname.'
                                                    
                                                    
                                                                    <div class = "RightArrowDiv"><img src = "images/right-arrow.png" /></div>
                                                            </div>
                                                    ';

                                            }
                                }
                            ?>


<script>


$(document).ready(function(){

$(document).on('click', '.OneShareToFriendsWallResult', function(event){ //
           
            var id = $(this).data("id");
            alert("id: " + id);
            showCreatePost(id, $(this).data("firstname"));


            alert(currentHTMLToBeShared);

            $("#CreateProfileInput").append("<br />" + currentHTMLToBeShared);
            
});



});















var currentShareToFriendsWallValue = "";

$("#ShareToFriendsWallSearchInput").keyup(function(){
    
    	
    if (event.keyCode != 16){            
                    

                    var search = encodeURI($(this).val());
                    
                
                    
                    if (search == ""){
                        //alert("got here 1");
                        $.ajax({
                            url: 'tagsgetallfriends.php',
                            type: 'post',
                            data: {search:search},
                            dataType: 'json',
                            success:function(response){
                                $(".OneShareToFriendsWallResult" ).remove();
                          
                            
                                currentTagSearchValue = search;
                            
                                var len = response.length;
                                
                                for( var i = 0; i<len; i++){
                                
                                
                                    var id = response[i]['id'];
                                
                                    var firstname = response[i]['firstname'];
                                    var lastname = response[i]['lastname'];
                                    var profilephotopath = response[i]['profilephotopath'];
                                    
                                    
                                    $("#ShareToFriendsWall").append("<div class = 'OneShareToFriendsWallResult' id = 'OneShareToFriendsWallResult" + id + 
                                    "' data-id = '" + id + "' data-firstname = '" + firstname + "'>\
                                                    <img src = '" + profilephotopath + "' />"
                                                     + firstname + " " + lastname
                                              + "   <div class = 'RightArrowDiv'><img src = 'images/right-arrow.png' /></div>"
                                         + "   </div> ");
                                            
                                    
                                }
                            
                                
                            }
                        });
                    }
                    

                    
                    else if(search != "" && search != currentShareToFriendsWallValue){
                    
                        $(".OneShareToFriendsWallResult" ).remove();

                        $.ajax({

                            url: "getsearch.php",
                            
                            type: 'post',
                            data: {search:search},
                            dataType: 'json',
                            success:function(response){
                            
                       
                            
                                currentShareToFriendsWallValue = search;
                            
                                var len = response.length;
                                
                                for( var i = 0; i<len; i++){
                                
                               
                                    var id = response[i]['id'];
                              
                                    var firstname = response[i]['firstname'];
                                    var lastname = response[i]['lastname'];
                                    var profilephotopath = response[i]['profilephotopath'];
                                    
                                    
                                    $("#ShareToFriendsWall").append("<div class = 'OneShareToFriendsWallResult' id = 'OneShareToFriendsWallResult" + id + "\
                                            ' data-id = '" + id + "' data-firstname = '" + firstname + "'>\
                                                    <img src = '" + profilephotopath + "' />"
                                                     + firstname + " " + lastname
                                              + "   <div class = 'RightArrowDiv'><img src = 'images/right-arrow.png' /></div>"
                                         +"   </div> ");
                                            
                                    
                                }
                            
                             
                            }
                        });
                    }
                }
});

</script>