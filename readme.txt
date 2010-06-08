=== Annonces ===
Contributors: Eoxia
Tags: annonce, passerelle, administration, immobilier, map, carte google maps, geolocalisation, georeferencement, google maps, gateway, announcement, annonces, carte, maison, batiment, vendre, prix, surface
Donate link: http://www.eoxia.com/site-web/pluginannonces/
Requires at least: 2.8.4
Tested up to: 2.9.2
Stable tag: 1.1.2

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