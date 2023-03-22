<?php
    include 'functions.php';
    include 'dbconnect.php';
    $lang=$_POST['lang'];
    if(!empty($_FILES['inputfile']['name']) && !empty($_POST['lang'])){
        $input=getUploadedFileContent($_FILES['inputfile']);
        $input=htmlspecialchars($input);
    }else if(!empty($_POST['input']) && !empty($_POST['lang'])){
        $input=$_POST['input'];
    }else{
        echo "<script>alert('You didn\'t fill the form!');</script>";
        header('location:index.php');
    }
    if(!empty($input)){
        $data=array();
        
        if($_POST['radio1']=="db"){
            $db_data=getArticlesData($lang);
            foreach($db_data as $element){
                $path=$element['path'];
                $element_content=getFileContent($path);
                
                $data[]=array("content"=>$element_content,"path"=>$path);
            }

        }

        $results_table=array();
        //Methode 1
        
            $input_ngrams_list=pretraitement_ngrams($input,15,$lang);
            foreach($data as $element){
                $element_ngrams_list=pretraitement_ngrams($element['content'],15,$lang);
                $results=algorithm($input_ngrams_list,$element_ngrams_list);
                $score=percentage($results);
                $results_array[]=array('path'=>$element['path'],'score'=>$score,'ngrams_score'=>$results);
            }
        
          
        //Methode2     
        
        
           /* foreach($data as $element){
                $score=tfidfCosine($input,$element['content'],$lang)*100;
                $score=number_format($score,2);
                $results_array[]=array('path'=>$element['path'],'score'=>$score);
            }*/
       
        
        $results_array=sortByScore($results_array); 
        $percent=$results_array[0]['score'];
        
    }
?>