#Projet 06  - Site communautaire Snowtricks

##Installation du projet

###Récupération du code source :

Vérifier que git est bien installé sur votre machine,
ouvrez un terminal, et lancer cette commande :

``git@github.com:jonascarfantan/snowtricks.git``

###Activer l'infrastructure docker
Depuis la racine du projet déplacez-vous dans le repertoire /infra et executer cette commande

``docker compose up -d``

``docker exec php bash``

###Récupérer les dépendances

Une fois que vous êtes à l'interieur de votre container faites :

``composer install``
et
``yarn``

### Variable d'environement

Faites une copy du fichier .env.local et renomer le en .env, à l'interieur renseigner ces queslques lignes

`
DATABASE_URL="mysql://root:toor@mariadb:3306/snowtricks?serverVersion=mariadb-10.5.5"
MAILER_DSN=smtp://your.domain.name@gmail.com:kmapdpoburjakjig@smtp.gmail.com:587
`

### Modifier le fichiers hosts de votre machine
Y ajouter cette ligne : 

``127.0.0.1 snowtricks.local``

### Création de la base de donnée

####Etape 1:
Pour monitorer la base de données rendez-vous à l'url suivante: 
http://snowtricks.local:8080

Créer une nouvelle table et nommer là "snowtricks".

####Etape 2:
Dans votre terminal à l'interieur du container taper les commandes suivantes

``
bin/console doctrine:schema:update --force
``

``
bin/console doctrine:fixture:load
``
Répondez "yes" à la question posée par le prompt.

###Build les assets
A la racine du projet dans le terminal taper :
``yarn dev``

##Félicitation le projet est prêt !
Rendez-vous à cette url pour consulter le site :
http://snowtricks.local


