<?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    if($_GET['idp'] == '') $strona = './html/glowny.html';
    if($_GET['idp'] == 'podstrona1') $strona = './html/mytop1.html';
    if($_GET['idp'] == 'podstrona2') $strona = './html/mytop2.html';
    if($_GET['idp'] == 'podstrona3') $strona = './html/mytop3.html';
    if($_GET['idp'] == 'podstrona4') $strona = './html/mytop4.html';
    if($_GET['idp'] == 'podstrona5') $strona = './html/mytop5.html';
    if($_GET['idp'] == 'filmy') $strona = './html/filmy.html';

    if($strona == null || !file_exists($strona)){
        $strona = './html/glowny.html';
    }
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="Content-Language" content="pl"/>
        <meta name="Author" content="Wiktor Wewersajtys"/>
        <title>Największe mosty świata</title>
        <link rel="stylesheet" href="./css/styles.css">
        <script src="./js/kolorujtlo.js"></script>
        <script src="./js/timedate.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </head>
    <body onload="startclock()">
        <header>
            <span class="topText">
                <h1><b>Największe mosty świata</b></h1>
                <h2>Hejka, na tej stronie przedstawię wam największe mosty świata jakie znam.</h2>
                <h2>Wszystkie które znam i pamiętam znajdują się po lewej stronie w MENU</h2>
                <h3>Jeśli o jakimś zapomniałem to dajcie mi znać w formularzu na dole :)</h3>
            </span>
        </header>
        <div id="dataiczas">
            <h5>Aktualny czas:</h5>
            <div id="data"></div>
            <div id="zegarek"></div>
        </div>
        <?php
            include($strona);
        ?>
        <div id="animations">
            <div id="animacjaTestowa1" class="test-block">Kliknij, a się powiększe</div>
            <script>
                $("#animacjaTestowa1").on("click", function(){
                    $(this).animate({
                        width: "500px",
                        opacity: 0.4,
                        fontSize: "3em",
                        borderWidth: "10px"
                    }, 1500);
                });
            </script>
            <div id="animacjaTestowa2" class="test-block">Najedź kursorem, a się powiększy</div>
            <script>
                $("#animacjaTestowa2").on({
                    "mouseover" : function(){
                        $(this).animate({
                            width: 300
                        }, 800);
                    },
                    "mouseout" : function(){
                        $(this).animate({
                            width: 200
                        }, 800);
                    }
                });
            </script>
            <div id="animacjaTestowa3" class="test-block">Klikaj, abym urósł</div>
            <script>
                $("#animacjaTestowa3").on("click", function(){
                    if(!$(this).is(":animated")){
                        $(this).animate({
                            width: "+=" + 50,
                            height: "+=" + 10,
                            opacity: "-=" + 0.1,
                            duration: 3000
                        });
                    }
                });
            </script>
        </div>
        <aside>
            <h2><u>Kontakt</u></h2>
            <form action="mailto:wikto-wewersajtys@uwm.pl" method="post" enctype="text/plain">
                Imię: <input type="text" name="imie"><br>
                Email: <input type="email" name="email"><br>
                Wiadomość:<br>
                <textarea name="wiadomosc" rows="5" cols="40"></textarea><br>
                <input type="submit" value="Wyślij">
            </form>
        </aside>
        <footer>
            <div class="AuthorName">
                &copy; Wiktor Wewersajtys 2023
            </div>
        </footer>
        <?php
            $nr_indeksu = '164439';
            $nrGrupy = '4ISI';
            echo 'Autor: Wiktor Wewersajtys ' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br><br><br><br>';
        ?>
    </body>
</html>