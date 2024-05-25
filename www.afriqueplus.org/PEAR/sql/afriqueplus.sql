/*
host: localhost,
database: afriqueplus,
Creation des tables de la base de donnee "afriqueplus"
 Le champ "cle" est toujours une cle primaire purement informatique;
*/

-- Creation de la table "source".
-- Elle rassemble l'ensemble des information sur les sources d'information du site
/*
 "initial" initials de la source;
 "nom" nom complet de la source;
 "description" courte description de la source
 "logo" lien vers le logo de la source:
 "url" url principale de la source;
 "recherche" Lien permettant d'effectuer une recherche sur le site source;
 "mail" adresse courriel de la source;
 "adresse" adresse postale de la source;
 "activite" activite principale de la source;
 "date" date à laquelle la source a ete ajoutee;
 "Fulltext" creation d'un groupe de colone pour des recherches en texte integrale
*/
CREATE TABLE `source` (
 `cle` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `initial` VARCHAR(10) NOT NULL UNIQUE,
 `nom` VARCHAR(125) NOT NULL,
 `description` TEXT NOT NULL,
 `logo` VARCHAR(255) NOT NULL UNIQUE,
 `url` VARCHAR(100) NOT NULL UNIQUE,
 `recherche` VARCHAR(255) NOT NULL UNIQUE,
 `mail` VARCHAR(100) NOT NULL,
 `adresse` TINYTEXT NOT NULL DEFAULT '',
 `activite` VARCHAR(100) NOT NULL,
 `date` TIMESTAMP(8) NOT NULL,

INDEX(`initial`),  
FULLTEXT(`nom`,`initial`,`description`,`url`,`mail`)
)ENGINE=MyISAM DEFAULT CHARSET=latin1 
-- Fin de creation de la table source
;
-- Creation de la table "resume"
-- Elle journalise l'etat du site source par source
/*
 "initial" Cle etrangere de source.initial;
 "entree" Nombre total de visites du site reçus grace à la source;
 "sortie" Nombre total de visite de la source grace à nous;
 "lecture" Nombre total de lecture de tout les articles de la source;
 "article" Nombre total d'article provenant de la source;
 "image" Nombre total d'images provenant de la source;
 "audio" Nombre total de fichier audio provenant de la source;
 "video" Nombre total de fichier video provenant de la source;
*/
CREATE TABLE `resume` (
 `cle` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `initial` VARCHAR(10) NOT NULL UNIQUE,
 `entree` INT UNSIGNED NOT NULL,
 `sortie` INT UNSIGNED NOT NULL,
 `lecture` INT UNSIGNED NOT NULL,
 `article` INT UNSIGNED NOT NULL,
 `image` INT UNSIGNED NOT NULL,
 `audio` INT UNSIGNED NOT NULL,
 `video` INT UNSIGNED NOT NULL,
 
INDEX(`initial`),
FOREIGN KEY (initial) REFERENCES source(initial) ON UPDATE CASCADE
)ENGINE=MyISAM DEFAULT CHARSET=latin1
-- Fin de creation de la table "resume"

;

-- Creation de la Table "article"
-- Elle reference tout les article disponible sur le site
/*
 "id" identifiant unique d'article sous la forme `source`+(`resume`.`article`+1);
 "title" Titre de l'article;
 "keyword" Mots cle de l'article;
 "description" Courte description de l'article;
 "Text" Corps de l'article;
 "link" Lien vers la page d'origne;
 "date" Date de creation de l'article;
 "organisme" Nom du pays,Etat,Ville,Organisation,etc Ou se deroule le recit;
 "lecture" Nombre de lecture de l'article;
 "vote" Note d'evaluation de l'article par les lecteur;
 "source" Initial du media source de l'article;
 "categorie" Categorie à laquelle appartient son fichier rss;
 "genre" Categorie plus specifique à l'article elle meme;
*/
CREATE TABLE `article`(
 `cle` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `id` VARCHAR(20) NOT NULL UNIQUE,
 `title` TINYTEXT NOT NULL,
 `keyword` TINYTEXT NOT NULL,
 `description` TEXT NOT NULL,
 `text` TEXT NOT NULL,
 `link` VARCHAR(255) NOT NULL UNIQUE,
 `date` TIMESTAMP(14) NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `categorie` VARCHAR(100) NOT NULL,
 `genre` VARCHAR(100) NOT NULL,
 `source` VARCHAR(10) NOT NULL,
 `organisme`VARCHAR(255) NOT NULL,
 `lecture` SMALLINT UNSIGNED NOT NULL,
 `vote` SMALLINT UNSIGNED NOT NULL,
 `nbvote` SMALLINT UNSIGNED NOT NULL,
 
INDEX(`id`),
FOREIGN KEY(source) REFERENCES source(initial) ON UPDATE CASCADE,
FOREIGN KEY(categorie) REFERENCES rss(categorie) ON UPDATE CASCADE,
FULLTEXT(`title`,`keyword`,`description`,`categorie`,`genre`,`organisme`),
FULLTEXT(`title`,`keyword`,`description`,`text`,`categorie`,`genre`,`organisme`)
)ENGINE=MyISAM DEFAULT CHARSET=latin1
-- Fin de creation de la Table article

;

-- Creation de la Table "media"
-- Elle reference toute les resources multimedia du site
/*
 "article" Cle etrangere de article.id;
 "type" Type de ressource multimedia;
 "link" Lien vers la resource;
*/
CREATE TABLE `media` (
 `cle` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `article` VARCHAR(11) NOT NULL,
 `type` ENUM('I','A','V') NOT NULL DEFAULT 'V',
 `link` VARCHAR(255) NOT NULL UNIQUE,
 `alt` VARCHAR(255) NOT NULL,
UNIQUE(`article`,`link`),
INDEX(`article`),
FOREIGN KEY(article) REFERENCES article(id) ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=latin1
-- Fin de creation de la table "media"

;

-- Creation de la Table "rss"
-- Elle reference tout les flux rss etrangers utilisés par le site pour la syndication
/*
 "source" Cle etrangere de source.initial;
 "link" Lien vers le fichier rss;
 "categorie" Categorie des informations syndiquees par le flux;
 "period" Periode apres laquelle le flux doit etre analyse;
 "date" Date de la prochaine analyse;
*/
CREATE TABLE `rss`(
 `cle` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `source` VARCHAR(10) NOT NULL,
 `link` VARCHAR(255) NOT NULL UNIQUE,
 `categorie` VARCHAR(100) NOT NULL,
 `period` TIME NOT NULL DEFAULT '01:00:00',
 `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
INDEX(`source`),
UNIQUE(`source`,`categorie`),
FOREIGN KEY(source) REFERENCES source(initial) ON UPDATE CASCADE 
) ENGINE=MyISAM DEFAULT CHARSET=latin1
-- Fin de creation de la Table "rss"

;

-- Creation de la table "pays".
-- Elle rassemble l'ensemble des pays et/ou territoires de la planète
/*
 "code" Code du pays;
 "nom" nom complet du pays
*/
CREATE TABLE `pays` (
 `cle` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `code` VARCHAR(3) NOT NULL UNIQUE,
 `nom` VARCHAR(125) NOT NULL
)ENGINE=MyISAM DEFAULT CHARSET=latin1 
-- Fin de creation de la table pays

;

-- Creation de la Table "message"
-- Elle Contient tout les messages reçus
/*
 "source" Cle etrangere de source.initial;
 "link" Lien vers le fichier rss;
 "categorie" Categorie des informations syndiquees par le flux;
 "period" Periode apres laquelle le flux doit etre analyse;
 "date" Date de la prochaine analyse;
*/
CREATE TABLE `message`(
 `cle` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `civilite` ENUM('Mr','Mme','Mlle') NOT NULL DEFAULT 'Mr',
 `nom` VARCHAR(125) NOT NULL,
 `prenom` VARCHAR(125) NOT NULL,
 `email` VARCHAR(255) NOT NULL,
 `tel`  VARCHAR(30) NOT NULL,
 `pays` VARCHAR(3) NOT NULL,
 `categorie` ENUM('Actualité Panafricaine','Webmastering','Publicité','Tchat & Forum','Sondage','') NOT NULL DEFAULT '',
 `sujet` VARCHAR(255) NOT NULL,
 `message` TEXT NOT NULL,
 `connu` ENUM('TV Radio','Internet','Papier','Bouche à Oreille','') NOT NULL DEFAULT ''

) ENGINE=MyISAM DEFAULT CHARSET=latin1
-- Fin de creation de la Table "rss"


