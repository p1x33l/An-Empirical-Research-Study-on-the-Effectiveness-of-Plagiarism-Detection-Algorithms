<!doctype html>
<html lang="en">
    <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Plagiarism Checker</title>
    </head>
    <body class="bg-light" style="overflow: hidden;" >
        
        <!-- Navbar -->
        <nav class="navbar navbar-expand navbar-light" style="display: flex;justify-content: center;">
            <div class="container" >
                <a class="navbar-brand text-dark" href="index.php">Plagiarism Checker</a>
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

        <!-- Form -->
        <div class="bg-light text-secondary px-4 py-5 text-center pxl_page" id="try">
            <form action="result.php" method="POST" enctype="multipart/form-data" onsubmit="return validate();">
                <div class="col-12 col-sm-6 col-md-10 col-lg-6 mx-auto">
                    
                    <p class="fs-5 mb-2">Copy and paste your text below in the text box</p>
                    <br>
                    <div class="form-floating">
                        <textarea name="input" class="form-control" placeholder="Enter text here to check plagiarism" id="floatingTextarea2 txtinput" style="height: 150px"><?php if(isset($_POST['input']) && !empty($_POST['input'])) echo $_POST['input']; ?></textarea>
                        <label for="floatingTextarea2">Enter text here to check plagiarism</label>
                    </div>
                    <div class="row mt-2">
                        <div class="col col-12 col-md-6">
                            <input class="form-control" name="inputfile" oninput="fileLoaded()" style="width:100%" type="file" id="formFile">
                        </div>                     
                        <div class="col col-12 col-md-4 mt-md-0 mt-2 ms-auto">
                            <button style="width:100%" type="submit" id="checkBtn" class="btn btn-primary px-5">Start checker</button>
                        </div>                     
                    </div>
                    <div class="row text-start mx-auto">
                        <div class="col col-6 col-md-5 mt-4  ">
                            <div class="form-check form-switch">
                                <input value="db" class="form-check-input" type="radio" role="switch" name="radio1" id="radioChoix1_1" checked>
                                <label class="form-check-label" for="radioChoix1_1">Search on databases</label>
                            </div>
                            <div class="form-check form-switch">
                                <input value="web" class="form-check-input" type="radio" role="switch" name="radio1" id="radioChoix1_2" disabled>
                                <label class="form-check-label" for="radioChoix1_2">Search on WEB</label>
                            </div>
                        </div>
                        
                            <div class="col col-6 col-md-5 mt-4 ">
                            <div class="form-check form-switch">
                                <input value="en" class="form-check-input" type="radio" role="switch" name="lang" id="radioChoix2_1">
                                <label class="form-check-label" for="radioChoix2_1">English</label>
                            </div>
                            <div class="form-check form-switch">
                                <input value="fr" class="form-check-input" type="radio" role="switch" name="lang" id="radioChoix2_2">
                                <label class="form-check-label" for="radioChoix2_2">Frensh</label>
                            </div>
                            <div class="form-check form-switch">
                                <input value="ar" class="form-check-input" type="radio" role="switch" name="lang" id="radioChoix2_3">
                                <label class="form-check-label" for="radioChoix2_3">Arabic</label>
                            </div>
                        </div>
                    
                    </div>
                    
                </div>
            </form>
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
        </div>





        
























    <!-- JavaScript -->
    <script>
        function loadingBtn(){
            var btn = document.querySelector("#checkBtn");
            btn.innerHTML='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
        }
        var toastTrigger = document.getElementById('liveToastBtn')
        var toastLiveExample = document.getElementById('liveToast')
        if (toastTrigger) {
        toastTrigger.addEventListener('click', function () {
            var toast = new bootstrap.Toast(toastLiveExample)

            toast.show()
        })
        }
        function fileLoaded() {
            document.getElementById("checkBtn").click();
        }
        function validate(){
            var lang = document.querySelector('input[name="lang"]:checked');
            if(lang != null) {
                loadingBtn();
                return true;
            }
            else{
                alert("Please Select The Language");
                return false;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    </body>
</html>