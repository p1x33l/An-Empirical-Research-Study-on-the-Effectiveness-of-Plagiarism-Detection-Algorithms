<!doctype html>
<html lang="en">
    <head>
    <!-- Required meta tags -->
    <title>Plagiarism Checker</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Scripts -->
    <script src="jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function (){
        $("[data-toggle=tooltip]").tooltip()
      })
    </script>

    
    </head>
    <body class="bg-light" >
        <?php
            include 'checker.php';
        ?>
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-light" style="display: flex;justify-content: center;">
            <div class="container">
                <a class="navbar-brand text-dark" href="index.php" >Plagiarism Checker</a>
                <button style="padding: 3px 5px;" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link text-dark" href='#' id="liveToastBtn">About</a>
                        <a class="nav-link text-secondary disabled" href="#" id="Donate">Donate</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col col-2">
                    <h4>Scan Results:</h4>
                </div>
                <div class="col col-10 align-self-center">
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $percent; ?>%" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $percent; ?>% Plagiat</div>
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo 100-$percent; ?>%" aria-valuenow="<?php echo 100-$percent; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo 100-$percent; ?>% Not Plagiat</div>
                    </div>
                </div>
                
            </div>
            <div class="row justify-content-center">
                <p class="col col-10 text-center">
                <?php
                    echo "<table class='table'> <thead> <tr> <th scope='col'>#</th> <th scope='col'>Similarity Score (%)</th> <th scope='col'>Path</th>  </tr> </thead> <tbody>";
                    $i=1;
                    foreach($results_array as $element){
                        //echo "<tr> <th scope='row'>$i</th> <td>".$element['score']."</td> <td>".$element['path']."</tr>";
                        echo "<tr> <th scope='row'>$i</th> <td>".$element['score']."</td> <td><a href='".$element['path']."'>".substr(str_replace('.txt', '', str_replace('_', ' ', $element['path'])),12)."</a></tr>";
                    $i++;
                    }
                    echo "</tbody></table>";
                ?>
                </p>
            </div>
        </div>
            <!--- NOTIFICATION START --->
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">About:</strong>
                        <small>now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body text-start">
                        Mohamed Elazzaoui, Abdelkabir Elbahmadi, Oumaima Afzaid.
                    </div>
                    
                </div>
                
            </div>
            <!--- NOTIFICATION END --->
            




        
























    <!-- JavaScript -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    </body>
</html>