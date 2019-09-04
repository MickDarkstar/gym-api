# jwt-REST-API
PHP Boilerplate REST-API with jwt token authentication 

*** Arkitektur ***
-- OOP och multitier architecture (fyra lager där middleware användas före och efter lager beroende på syfte)
-- *Konstanter*: läggs i /includes/Constants.php, MiddleWare har dock en constant i MiddleWare.settings.php som bör ses över.

-- *MiddleWare*: Mellanlager som används före anrop för authentisering och verifiering av användare. Används för och efter anrop beroende på case. 

-- *Basklasser*: Basklasser finns för nästan alla typer av klasser och skall populeras med återanvändbar kod för att följa DRY-principen

-- *Repositories*: ställer frågor mot respektive tabell eller område i appen och mappar till "objekt-resultat", med område menas vy eller funktion. Ej skrivet i sten angående många små eller stora frågor är att föredra. Det visar sig vid utveckling av klient-app

-- *Servicar* Validerar användarinput och använder Repositories för att ställa frågor till databasen. Alla resultat returneras som json_encode()

-- *Kontrollers* Anropar servicar för att hämta och manipulera data, kontrollers och deras funktioner anropas i de routes som sätts upp i "routes/Routes.php" 

-- *index.php* inkluderas det minimalt nödvändiga för att appen skall fungera. T.ex autoloader.php och Routes.php. I framtiden skall själva appen abstraheras bort i en egen klass för att ännu mer kunna återanvända denna boilerplate utan att knyta den till någon specifik lösning.

-- *Klasser och funktioner*:
https://www.sitepoint.com/community/t/to-static-or-not-to-static/263096
Då testbarhet inte finns på kartan så är det okej med statiska metoder och egenskaper, så länge de är stateless

*** Namnkonventioner ***
!!Måste följas om inte autloader önskas skrivas om!!

-- *Klassnamn*, PascalCase och innehåller avsett område + suffix för typ av klass. T.ex UserController, UserModel.

-- *Filnamn* innehåller avsett område med punkseparerat suffix t.ex User.controller.php där '.controller.php' är suffix

-- *Mappstruktur* skall följande den redan satta, så utplattad som möjligt.
Klasser i includes/classes, databasmodeller i includes/models osv.

-- *Filer* som används en gång (som inte är klasser och bara en "include-fil") eller är långt upp i lagerhierarkin, som .tex Routes.php, kan ha en egen mapp eller om det är t.ex settings kan de läggas i rotmapp och räcker med att de namnges med PascalCase
