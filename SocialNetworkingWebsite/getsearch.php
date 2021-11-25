<?php

//https://makitweb.com/make-autocomplete-search-jquery-ajax/
//https://www.w3schools.com/howto/howto_js_autocomplete.asp

include("config.php");



// Search result

    $searchText = mysqli_real_escape_string($con,$_POST['search']);

	

    $sql = "SELECT id, firstname, lastname, profilephotopath FROM userinfo where firstname like '%".$searchText."%' order by firstname asc limit 5";



	if (!$result = mysqli_query($con,$sql)){
                            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                            
    }

    $search_arr = array();

    while($fetch = mysqli_fetch_assoc($result)){
        $id = $fetch['id'];
        $firstname = stripslashes($fetch['firstname']);
        $lastname = stripslashes($fetch['lastname']);
        $name = $firstname . " " . $lastname;
        $profilephotopath = $fetch['profilephotopath'];
        $search_arr[] = array("id" => $id, "name" => $name, "profilephotopath" => $profilephotopath, "firstname" => $firstname, "lastname" => $lastname);
    }

    echo json_encode($search_arr);








?>
