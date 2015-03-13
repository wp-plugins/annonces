=== Annonces ===
Contributors: Eoxia
Tags: annonce, passerelle, administration, immobilier, map, carte google maps, geolocalisation, georeferencement, google maps, gateway, announcement, annonces, carte, maison, batiment, vendre, prix, surface
Donate link: http://www.eoxia.com/site-web/pluginannonces/
Requires at least: 2.8.4
Tested up to: 4.1.1
Stable tag: 1.2.0.7

Affiche vos annonces sur votre blog.

== Description ==

Annonces est un plugin de publication de petite annonce permettant d'ajouter facilement des annonces immobili&egrave;res sur son blog. Il suffit d'ajouter cette balise <code><div rel="annonces" id="annonces" ></div></code> dans le code html de votre page.


== Installation ==

1. T&eacute;l&eacute;charger l'extension.
2. D&eacute;compresser dans le repertoire "/wp-content/plugins/".
3. Activer l&#39;extension dans l'interface administrateur de Wordpress.
4. Placer <code><div rel="annonces" id="annonces" ></div></code> dans votre page.


== Frequently Asked Questions ==

= Message erreur: Ce site Web n&eacute;cessite une autre cl&eacute; d'API Google Maps. =

Lorsque vous activez l'extension pour la premi&egrave;re fois, vous pouvez vous retrouver face &agrave; cette erreur, il vous faut g&eacute;n&eacute;rer une cl&eacute; API Google Maps puis l'ins&eacute;rer dans les options de l'extension (R&eacute;glages > Annonces).

= L'extension ne trouve pas mes annonces. =

Si vous avez l'impression que l'extension ne retrouve pas vos annonces et que vous &ecirc;tes s&ucirc;r qu'elles existent et en mode valid, essayez d'actualiser le moteur de recherche (option disponible dans l'onglet Annonces de l'interface administrateur).

= L'interface de recherche ne ressemble pas aux prises de vues =

Nous faisons tout notre possible pour rendre l'extension compatible avec les diff&eacute;rents th&egrave;mes existants, il se peut que le th&egrave;me que vous utilisiez soit trop &eacute;troit, ce qui a pour effet que les composants soient mal plac&eacute;s. Si vous n'avez pas peur de mettre la main dans le code, vous pouvez modifier la feuille de style annonce.css.


== Screenshots ==

1. Recherche d'une annonce avec l'extension Annonces.


== Changelog ==

V1.2.0.7

Corrections

* Enregistrement des annonces impossible suite &agrave; un probl&egrave;me de retour de l'API google maps pour encodage des adresses


V1.2.0.6

Corrections

* ST825 - Probl&egrave;me retour API google maps

V1.2.0.5

Corrections

* ST784 - Erreurs retourn&eacute;es par google map api dans certains cas / Google api return undefined for some addresses (Si l'adresse entr&eacute;e n'&eacute;tait pas trouv&eacute;e alors le script retournait "undefined" ce qui provoque une erreur lors de l'enregistrement d'une annonce)

V1.2.0.4

Am&eacute;liorations

* ST768 - Mise en place de la derni&egrave;re version de l'API google maps / Installation of the last google maps API version

Corrections

* ST766 - Affichage de l'option permettant de s&eacute;lectionner la devise pour le plugin / Display of option for currency choice (La liste d&eacute;roulante n'&eacute;tait pas sur la bonne valeur lors de la r&eacute;&eacute;dition / When editing options the selected value was not the one previously selected)
* ST767 - Param&egrave;tre manquant pour la fonction wpdb-&gt;prepare() / Missing parameter for wpdb-&gt;prepare function
* ST776 - R&eacute;&eacute;criture d'url personnalis&eacute;e / Custom url rewriting

v1.2.0.3:

Corrections

* ST663 - Corrections de notice et d'erreur php

v1.2.0.2:

Corrections

* ST275 - Corrections d'une faille de s&eacute;curit&eacute; dans la librairie d'envoie des images

v1.2.0.1:

Corrections

* ST204 - Chemin du fichier d'administration &agrave; l'installation du plugin qui n'&eacute;tait pas correctement construit 
* ST205 - Probl&egrave;me de chemin de fichier inclus directement depuis une variable GET
* ST227 - Droits sur les dossiers cr&eacute;&eacute;s lors de l'envoie des images associ&eacute;es aux annonces 
* ST228 - Code postal qui ne conservait pas le "0" en d&eacute;but de cha&icirc;ne 


v1.2.0.0:

* FIXED - Correction de l'option cl&eacute; api de Google map qui n'&eacute;tait pas homog&egrave;ne
* FIXED - Correction de toutes les erreurs html pour rendre le code valide w3c (partie admin)
* FIXED - Correction de la g&eacute;olocalisation qui recherchait automatiquement en "Languedoc roussillon" pour les coordonn&eacute;es d'une adresse
* FIXED - Correction de l'appel du formulaire de gestion des annonces qui &eacute;tait appel&eacute; avec la fonction stripslashes() qui provoquait une erreur fatale sur certains serveurs
* ADD - Option permettant de choisir si lors de l'export les photos doivent &ecirc;tre envoy&eacute;es ou si uniquement les liens doivent &ecirc;tre envoy&eacute;s
* CHANGE - Style des champs du formulaire de gestion des annonces pour homog&eacute;n&eacute;iser l'affichage
* CHANGE - D&eacute;placement de certains javascripts pour regrouper
* CHANGE - Ajout des valeurs possible pour l'attribut "Bilan consommation &eacute;nergie" et "Bilan &eacute;mission GES" pour &eacute;viter toute erreur
* CHANGE - Gestion des options selon les bonnes pratiques de wordpress
* DELETED - Inclusion librairie uploadify (probl&egrave;me de s&eacute;curit&eacute;)


v1.1.2.6:

* FIXED - Liens r&eacute;&eacute;crits sur les cartes


v1.1.2.5:

* FIXED - Suppression de zend pour la recherche
* FIXED - R&eacute;&eacute;criture d'url lorsqu'il y a plusieurs pages, et sur l'image des annonces


v1.1.2.4:

* FIXED - Correction d'une erreur de readme


v1.1.2.3:

* FIXED - Mise &agrave; du pictogramme de l'onglet "Annonces"
* FIXED - R&eacute;solution du conflit jQuery avec d'autres plugins
* FIXED - Le nombre de pages et d'annonces s'affiche dans le listing des annonces
* FIXED - Url personnalisable lors de l'ajout et la modification de l'annonce
* FIXED - Mise en place de la r&eacute;&eacute;criture d'URL avec URL type personnalisable
* CHANGE - Changement du type du "nomoption" dans la base, on est pass&eacute; du char(70) au varchar(1000)


v1.1.2.2:

* FIXED - Mise &agrave; jour du fichier langue POEdit
* FIXED - Listing via JQueryDataTable pour les annonces, les cat&eacute;gorie et les attributs
* FIXED - Ajout du bouton "Effacer tout" qui r&eacute;initialise le formulaire
* FIXED - Ajout de 4 monnaies via l'interface option du plugin : Euro, Dollar US, Livre Sterling et Yen
* FIXED - Validation de l'annonce puis ajout de l'image
* FIXED - Toutes les images ne sont plus en dur
* FIXED - Les styles sont maintenant dans des CSS
* FIXED - Mise en place du dico avec POEdit
* FIXED - L'ic&ocirc;ne google a retrouver sa forme normale et est changeable
* FIXED - Mise en place des passerelles deja cod&eacute;es et par d&eacute;faut


v1.1.2.1:

* FIXED - La taille du champs ville &eacute;tait limit&eacute;e &agrave; 20 caract&egrave;res contre 255 dans la base


v1.1.2:

* FIXED - la r&eacute;f&eacute;rence de l'annonce &eacute;tait r&eacute;cup&eacute;r&eacute;e dans la mauvaise table
* FIXED - le titre ne s'affiche pas &agrave; cause d'un double appel de la fonction de r&eacute;cup&eacute;ration
* FIXED - la carte google ne s'affiche pas dans la fiche d'une annonce, d&ucirc; &agrave; une parenth&egrave;se orpheline


v1.1.1:

* CHANGE - Affiche la r&eacute;f&eacute;rence de l'annonce si l'option date est d&eacute;coch&eacute;e


v1.1.0:

* FIXED - Corrige le probl&egrave;me de m&eacute;moire manquante lors d'un export.
* CHANGE - N'affiche que les annonces de la page en cours sur la carte google
* CHANGE - Les annonces sont tri&eacute;es par prix par d&eacute;faut


v1.0.1:

* Am&eacute;liore l'habillage et les feuilles de style pour diff&eacute;rentes tailles de th&egrave;mes.


== Upgrade Notice ==

* Mise &agrave; jour directe depuis wordpress.org