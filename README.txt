
El que s'ha fet:
1. Creació de tallers
2. Edició de tallers
3. Eliminació de tallers
4. Assignació d'administradors
5. Afegir ajudants i espai, el qual es realitza editant un taller com a administrador



· Especificació dels passos i canvis de configuració que s'han de fer per a desplegar el teu projecte en una màquina diferent.
    Per a desplegar el projecte en una màquina diferent, s'ha de seguir els següents passos:
        1. Copiar el projecte en una màquina amb laravel instal·lat i un servidor web (apache o nginx) configurat per a laravel i una base de dades mysql.
        2. Per poder utilitzar el Oauth de Google, s'ha de crear un projecte a la consola de desenvolupadors de Google, i crear un client OAuth 2.0. Aquest client ha de tenir com a redirect URI: http://tallernadal.com/google-callback. I al fitxer env s'ha de posar el client id, el client secret i la uri.
        3. Utilitzar la comanda: php artisan migrate per crear les bases de dades i afegir el usuari administrador.
        4. Per importar els usuaris es pot utilitzar un fitxer csv sguint la estructura de exemple del fitxer import.csv que es troba dintre del projecte.


· Especificació dels canvis que s'han realitzat al .env
    DB_DATABASE=taller_nadal
    GOOGLE_OAUTH_ID="aqui va el client id del projecte de google"
    GOOGLE_OAUTH_KEY="aqui va el client secret del projecte de google"
    GOOGLE_REDIRECT_URI="/google-callback" aqui va la uri del projecte de google
        

· Desenvolupaments o funcionalitats que no queden prou clares a l'enunciat i com has decidit portar-les a terme, és a dir, PER EXEMPLE, si heu decidit que un alumne que ha creat un taller, pugui aputar-se a 3 tallers, doncs expliqueu el perquè d'aquesta funcionalitat. Penseu que podeu arribar a fer casuístiques diferents que si ens basem només en l'enunciat pot semblar que el que feu està malament quan realment és una interpretació vostre.
    · casuística creació de tallers:
        Els usuaris només podran crear un taller si no hi han creat cap altre taller o si no estan assignats a cap taller com ajudant o participant. Si el usuari es administrador, podrà crear tants tallers com vulgui.
    · casuística participants:
        Per a que un usuari es pugui afegir com participant a un taller, aquest taller ha de no haver sobrepassat el límit de participants configurat per el creador o administrador, i l'usuari no pot estar afegit com a ajudant a cap altre taller. Tampoc pot tenir un taller creat o ser administrador. L'alumne es podrà afegir com a participant a un màxim de 3 tallers.
    · casuística importació de usuaris:
        És podrà importar en qualsevol moment, els usuaris que estiguin a la base de dades no s'esborraran, i s'afegiràn el reste d'usuaris del fitxer que no estiguin a la base de dades. 
        Per el fitxer d'importació he utilitzat un format propi. El fitxer està inclos al projecte amb el nom de import.csv.
    · Afegir ajudants o espai a un taller:
        Per a afegir ajudants o espai a un taller, s'ha d'editar el taller com a administrador, i afegir els usuaris que es vulguin com a ajudants o afegir el espai. Els usuaris que es vulguin afegir han d'estar registrats a la base de dades.
    · 
        

· Punts de l'enunciat que heu acabat desenvolupant
    · Creació de tallers
    · Edició de tallers
    · Eliminació de tallers
    · Assignació d'administradors
    · Afegir o esborrar ajudants i espai, el qual es realitza editant un taller com a administrador
    · Importació de usuaris a partir d'un fitxer csv
    · Login amb Google
    · Afegir alumnes no registrats a tallers
    · Afegir-se com a participant a tallers
    · Desapuntar-se de tallers
    · Esborrar a participants d'un taller com a administrador
