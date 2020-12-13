SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE USER 'cuiteur_userl'@'localhost' IDENTIFIED BY 'cuiteur_passl';

/*GRANT USAGE ON * . * TO 'cuiteur_userl'@'localhost' IDENTIFIED BY 'cuiteur_passl' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;*/

CREATE DATABASE IF NOT EXISTS `cuiteur_bd` CHARACTER SET utf8 COLLATE utf8_unicode_ci;

GRANT SELECT , INSERT ,UPDATE , DELETE ON `cuiteur_bd` . * TO 'cuiteur_userl'@'localhost' IDENTIFIED BY 'cuiteur_passl';

USE `cuiteur_bd`;


CREATE TABLE blablas (
  blID int(10) unsigned NOT NULL AUTO_INCREMENT,
  blIDAuteur int(10) unsigned NOT NULL,
  blDate int(8) unsigned NOT NULL,
  blHeure char(8) COLLATE utf8_unicode_ci NOT NULL,
  blTexte char(255) COLLATE utf8_unicode_ci NOT NULL,
  blIDOriginal int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (blID)
  /*KEY meIDAuteur (blIDAuteur)*/
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO blablas (blID, blIDAuteur, blDate, blHeure, blTexte, blIDOriginal) VALUES
(81, 1, 20190125, '16:53:35', 'L''intelligence artificielle se définit comme le contraire de la bêtise naturelle. #ia #intelligence', 0),
(82, 3, 20190125, '16:58:19', 'La théorie, c''est quand on sait tout et que rien ne fonctionne. La pratique, c''est quand tout fonctionne et que personne ne sait pourquoi. Si la pratique et la théorie sont réunies, rien ne fonctionne et on ne sait pas pourquoi.', 0),
(83, 3, 20190125, '17:02:27', 'Les #USA sont le seul pays du monde à être passés directement de la barbarie à la décadence sans passer par la civilisation.', 0),
(84, 4, 20190126, '17:03:17', '@albert Quand les riches se font la guerre, ce sont les pauvres qui meurent. #Jedi', 0),
(85, 6, 20190126, '17:04:36', '@yoda Si on a pu prendre en charge les baby-boomers quand ils étaient enfants, pourquoi ne peut-on pas le faire quand ils ont plus de soixante ans? C''est une simple question de priorités financières.', 0),
(86, 2, 20190126, '17:06:26', 'A la faculté de médecine et de pharmacie, il est communément admis que les comprimés ne sont pas systématiquement des imbéciles diplômés. #zéro', 0),
(87, 4, 20190126, '17:07:14', 'Ne pas trop réfléchir sur la valeur de l''Histoire. On court le risque de s''en dégoûter. #Aie', 0),
(88, 4, 20190126, '17:08:17', '@albert @chomsky @yoda Dans la #vie on ne fait pas ce que l''on veut mais on est responsable de ce que l''on est. #philo', 0),
(89, 1, 20190128, '17:09:11', 'Un conducteur dangereux (@pdac), c''est celui qui vous dépasse malgré tous vos efforts pour l''en empêcher.', 0),
(90, 1, 20190128, '17:13:04', '@chomsky L''avantage d''être intelligent, c''est qu''on peut toujours faire l''#imbécile, alors que l''inverse est totalement impossible. #lol', 0),
(91, 6, 20190128, '17:11:03', 'On ne peut, la nature des choses étant ce qu''elle est, s''attendre à ce qu''un petit arbre qui a été transformé en gourdin engendre des feuilles (même pour @nono)', 0),
(92, 3, 20190128, '17:25:38', 'L''homme évite habituellement d''accorder de l''intelligence à autrui, sauf quand par hasard il s''agit d''un ennemi. #intelligence', 0),
(93, 6, 20190129, '17:26:58', '@albert On exerce d''énormes pressions pour transformer les gens en monstres pathologiques qui ne s''intéressent qu''à eux-mêmes, qui n''ont aucun rapport les uns avec les autres, et qu''on peut donc gouverner et contrôler très facilement. #DarkSide', 0),
(94, 2, 20190129, '17:31:00', 'Celui qui, dans la vie, est parti de zéro pour n''arriver à rien, n''a de merci à dire à personne. #Aie #zéro', 0),
(95, 2, 20190129, '17:31:16', '@albert Il est plus facile de faire sortir le dentifrice du tube que de l''y faire rentrer.', 0),
(96, 3, 20190129, '17:32:23', '@pdac Il n''existe que deux choses infinies, l''univers et la bêtise humaine... mais pour l''univers, je n''ai pas de certitude absolue.', 0),
(97, 7, 20190129, '17:32:59', '@albert Les boutons sur l''écran auront l''air si bons que vous aurez envie de les lécher.', 0),
(98, 2, 20190131, '17:10:09', '@woody La seule chose qui est certaine quand une femme dit: "Je serai prête dans cinq minutes", c''est qu''elle parle français. #miso', 1),
(99, 22, 20190131, '10:14:16', 'Les hémorragies cérébrales sont moins fréquentes chez les joueurs de football. Les cerveaux aussi! #intelligence', 0),
(100, 23, 20190201, '09:19:20', 'Quand Google ne trouve pas quelque chose, il demande à Chuck Norris.', 0),
(101, 23, 20190201, '11:09:40', 'Chuck Norris a déjà compté jusqu''à l''infini. Deux fois.', 0),
(102, 23, 20190202, '09:19:51', 'Chuck Norris donne fréquemment du sang à la Croix-Rouge. Mais jamais le sien.', 0),
(103, 23, 20190203, '09:20:06', 'Un jour Chuck Norris a lu le dictionnaire. Il a compris l''histoire. #intelligence', 0),
(104, 22, 20190203, '09:22:13', 'Les optimistes pensent que nous vivons dans le meilleur des mondes possibles, les pessimistes en sont intimement persuadés.', 0),
(105, 22, 20190203, '09:22:34', 'Il y a comme ça des jours où on se demande si Dieu ne boit pas.', 0),
(106, 22, 20190204, '09:22:54', 'Une civilisation sans la Science, ce serait aussi absurde qu''un poisson sans bicyclette.', 0),
(107, 23, 20190204, '09:24:21', '@pierre Chuck Norris est le seul homme à posséder une bible dédicacée.', 0),
(108, 22, 20190204, '10:46:01', 'La recherche a besoin d''argent dans deux domaines prioritaires : le cancer et les missiles antimissiles. Pour les missiles antimissiles, il y a les impôts. Pour le cancer, on fait la quête.', 23),
(109, 6, 20190204, '17:02:27', 'La propagande est aux démocraties ce que la violence est aux dictatures.', 0),
(120, 22, 20190204, '17:16:48', '@albert Le #psychotique est celui qui sait que 2+2 font 5 et qui s''en fout. Le #névrotique est celui qui sait que 2+2 font 4 et ça le rend malade.', 0),
(121, 22, 20190204, '17:35:22', '@nono Plus les femmes sont belles, plus elles ont tendance à se foutre de nous, et plus on en redemande. #miso', 0),
(122, 2, 20190205, '16:17:44', 'Un four cuit toujours trop ou pas assez.  Un micro-onde fait même les deux à la fois #murphy', 0),
(123, 22, 20190205, '16:23:24', 'Dans un frigo, ce dont vous avez besoin tous les jours migre systématiquement vers le fond. #murphy', 0),
(124, 5, 20190205, '17:21:04', 'Si un bug est impossible, il se produira quand même. #murphy', 0),
(125, 4, 20190205, '17:22:16', 'Si tout semble bien marcher, vous avez forcément négligé quelque chose. #murphy', 0),
(126, 4, 20190205, '18:23:35', 'A plus que les ordinateurs deviennent intelligents, à plus que c´est des emmerdeurs. #murphy', 0),
(127, 1, 20190205, '19:26:36', '"Compatibilité ascendante" signifie que toutes les erreurs de conception originelles sont conservées. #murphy', 0),
(128, 3, 20190205, '19:28:26', 'Théorême de McLuhan : grâce à l´ordinateur, on peut faire plus rapidement des choses qu´on n´aurait pas eu besoin de faire sans ordinateur. #info', 0),
(129, 7, 20190205, '20:30:26', 'Windows sait tout faire, sauf fonctionner correctement. #info', 0),
(130, 7, 20190205, '21:32:10', 'Il y a deux types d´administrateurs : celui qui a fait une grosse connerie sous root, et celui qui va en faire une. #info', 0),
(131, 7, 20190205, '21:32:34', 'Quand on se met à Linux, vaut mieux avoir BEAUCOUP d´amis (même @nono). #info', 0),
(132, 7, 20190206, '09:36:27', '@woody Intelligence Artificielle signifie que la machine est capable de prévoir à quel moment elle doit tomber en panne pour optimiser votre stress. #ia #info', 0),
(133, 3, 20190206, '09:38:14', 'Il n´y a pas de langage informatique dans lequel vous ne puissiez écrire de mauvais programme. #info', 0),
(134, 5, 20190206, '10:39:48', 'Dans un programme informatique, le nombre de bugs est proportionnel à la factorielle du nombre d´instructions écrites. #murphy', 0),
(135, 7, 20190206, '11:41:01', 'Plus la nécessité de revenir en arrière dans le développement d´un logiciel est criante, plus la dernière sauvegarde est ancienne et périmée. #murphy', 0),
(136, 5, 20190206, '11:41:56', 'Étant donnés suffisamment d´observateurs, tous les bugs sautent aux yeux. #murphy', 0),
(137, 7, 20190206, '12:42:47', 'Un programme sans bug est un programme qui n´a pas été suffisament testé. #murphy', 0),
(138, 5, 20190206, '12:43:23', 'Plus un bug est découvert tard dans la journée, plus il est incompréhensible et doit être vite corrigé. #murphy', 0),
(139, 7, 20190206, '13:44:28', 'Ne testez jamais une erreur que vous ne savez pas gérer. #conseil', 0),
(140, 1, 20190206, '14:46:00', 'Si vous demandez à un collègue son avis sur un bug sur lequel vous séchez depuis trois jours, il le résoudra en trois secondes. #conseil', 0),
(141, 1, 20190206, '16:48:42', 'Du mauvais code n´a pas besoin d´être commenté ou corrigé, mais réécrit. #conseil', 0),
(142, 5, 20190206, '16:49:40', 'Si vous n´y arrivez pas du premier coup, appelez ça version 1.0 #conseil', 0),
(143, 7, 20190206, '16:51:58', 'Un projet mal planifié prend 3 fois plus de temps que prévu,\r\nalors qu´un projet bien planifié ne prend que 2 fois plus de temps.', 0),
(144, 5, 20190206, '17:57:05', 'Toute page qui peut avoir un titre sans rapport avec le contenu aura un titre sans rapport avec le contenu. #murphy', 0);

CREATE TABLE estabonne (
  eaIDUser int(10) unsigned NOT NULL,
  eaIDAbonne int(10) unsigned NOT NULL,
  eaDate int(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (eaIDUser,eaIDAbonne)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO estabonne (eaIDUser, eaIDAbonne, eaDate) VALUES
(6, 5, 20190120),
(2, 6, 20190120),
(2, 3, 20190120),
(3, 2, 20190121),
(3, 4, 20190121),
(3, 6, 20190121),
(6, 4, 20190122),
(6, 3, 20190122),
(6, 1, 20190122),
(22, 1, 20190123),
(22, 6, 20190123),
(23, 22, 20190123),
(23, 2, 20190123),
(23, 1, 20190123),
(22, 3, 20190123),
(22, 23, 20190202);

CREATE TABLE mentions (
  meIDUser int(10) unsigned NOT NULL,
  meIDBlabla int(10) unsigned NOT NULL,
  PRIMARY KEY (meIDUser,meIDBlabla)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO mentions (meIDUser, meIDBlabla) VALUES
(3, 84),
(5, 85),
(3, 88),
(6, 88),
(5, 88),
(2, 89),
(6, 90),
(23, 91),
(3, 93),
(3, 95),
(2, 96),
(3, 97),
(1, 98),
(22, 107),
(3, 120),
(23, 121),
(23, 131),
(1, 132);

CREATE TABLE tags (
  taID char(60) COLLATE utf8_unicode_ci NOT NULL,
  taIDBlabla int(10) unsigned NOT NULL,
  PRIMARY KEY (taID,taIDBlabla)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO tags (taID, taIDBlabla) VALUES
('ia', 81),
('intelligence', 81),
('USA', 83),
('Jedi', 84),
('zéro', 86),
('Aie', 87),
('vie', 88),
('philo', 88),
('imbécile', 90),
('lol', 90),
('intelligence', 92),
('DarkSide', 93),
('Aie', 94),
('zéro', 94),
('miso', 98),
('intelligence', 99),
('intelligence', 103),
('psychotique', 120),
('névrotique', 120),
('miso', 121),
('murphy', 122),
('murphy', 123),
('murphy', 124),
('murphy', 125),
('murphy', 126),
('murphy', 127),
('info', 128),
('info', 129),
('info', 130),
('info', 131),
('ia', 132),
('info', 132),
('info', 133),
('murphy', 134),
('murphy', 135),
('murphy', 136),
('murphy', 137),
('murphy', 138),
('conseil', 139),
('conseil', 140),
('conseil', 141),
('conseil', 142),
('murphy', 144);

CREATE TABLE users (
  usID int(10) unsigned NOT NULL AUTO_INCREMENT,
  usNom char(60) COLLATE utf8_unicode_ci NOT NULL,
  usVille char(50) COLLATE utf8_unicode_ci NOT NULL,
  usWeb char(120) COLLATE utf8_unicode_ci NOT NULL,
  usMail char(80) COLLATE utf8_unicode_ci NOT NULL,
  usPseudo char(30) COLLATE utf8_unicode_ci NOT NULL,
  usPasse char(255) COLLATE utf8_unicode_ci NOT NULL,
  usBio char(255) COLLATE utf8_unicode_ci NOT NULL,
  usDateNaissance int(8) unsigned NOT NULL DEFAULT '0',
  usDateInscription int(8) unsigned NOT NULL DEFAULT '0',
  usAvecPhoto int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (usID),
  UNIQUE KEY usPseudo (usPseudo)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO users (usID, usNom, usVille, usWeb, usMail, usPseudo, usPasse, usBio, usDateNaissance, usDateInscription, usAvecPhoto) VALUES
(3, 'Albert Einstein', 'Princeton', 'http://fr.wikipedia.org/wiki/Albert_Einstein', 'albert@einstein.net', 'albert', '$2y$10$2t9stT88xXVWd5ohdLctw.TD6RFZn1l.cZ23VhjWltqvzdR9j2VkG', 'Je ne dors pas longtemps, mais je dors vite.', 18790314, 20190103, 1),
(2, 'Pierre Dac', 'Paris', 'http://fr.wikipedia.org/wiki/Pierre_Dac', 'pierre@dac.com', 'pdac', '$2y$10$y5g4uGZ.6x7aLNyWUqJUyOyX3XFiv7ZSwmLHu3Hb3n7Aw3FkyO2h6', '1965 : candidat à la présidentielle avec mon parti, le MOU, Mouvement ondulatoire unifié. "Les temps sont durs ! Vive le MOU ! ".', 18930815, 20190102, 1),
(7, 'Steve Jobs', 'San Francisco', 'http://fr.wikipedia.org/wiki/Steve_Jobs', 'steve@apple.com', 'jobs', '$2y$10$SwRY22pUMF57FGuHw11dOuS26fOYr0Vru2XYdlWyVXq0NCG/tKcNm', 'En 1986, j''ai acheté Pixar pour 10 millions de$. En 2006 je l''ai revendu à Disney 7,4 milliards. Fuck Bill G.<script>location =''http://apple.com'';</script>', 19550224, 20190107, 1),
(6, 'Noam Chomsky', 'Massachusetts Institute of Technolog', 'http://fr.wikipedia.org/wiki/Chomsky', 'noam@mit.edu', 'chomsky', '$2y$10$X.x4HunrDaPWn62gglk9Xu18eYHgfa6ZrhpOKp3zIiUQtnwwYnTvO', 'Within the field of computer science, specifically in the area of formal languages, the Chomsky hierarchy (occasionally referred to as Chomsky-Schützenberger hierarchy) is a containment hierarchy of classes of formal grammars.', 19281207, 20190106, 1),
(5, 'Yoda', 'Dagobah', 'http://fr.wikipedia.org/wiki/Yoda', 'yoda@nowhere.com', 'yoda', '$2y$10$XZ6tElXypwj7x3kKD6D7a.fZIReIfNmsUCk4XfylxC6eYdQQnEJJ.', 'Quand neuf cents ans comme moi tu auras, moins en forme tu seras.', 10000101, 20190105, 1),
(4, 'Jean Paul Sartre', 'Paris', 'http://fr.wikipedia.org/wiki/Jean-Paul_Sartre', 'paulo@sartre.fr', 'paulo', '$2y$10$GWEjFIrzORs41hQwVaZN3OJTq0WvcfGUMhZ3mfni9Tbu7At3XMjr2', 'L''existence de l''homme précède son essence, lui laissant la liberté et la responsabilité de ses choix.', 19050621, 20190104, 1),
(1, 'Woody Allen', 'Niork', 'http://fr.wikipedia.org/wiki/Woody_Allen', 'woody@new-york.com', 'woody', '$2y$10$Sxouv8NWjSiV5Dp0Pq93nuRcJg818SnI9HUI6NKYbmx6jBcLxSyJ.', 'Intellectuel chétif à lunettes, ambassadeur de l''angoisse métaphysique sauce new-yorkaise, pétri de philosophie scandinave et de jazz mainstream.', 19351201, 20190101, 1),
(22, 'Pierre Desproges', 'Paris', 'http://www.desproges.fr/', 'desproges@desproges.fr', 'pierre', '$2y$10$s2F0ZOxGlUpWeP5Do.KVk.XJoSyRD4c0QJGrZ6a.9mTRATSrwIeO2', 'Dans notre édition d´hier, une légère erreur technique nous a fait imprimer les noms de champignons vénéneux sous les photos des champignons comestibles, et vice versa. Nos lecteurs survivants auront rectifié d´eux-mêmes.', 19390509, 20190122, 1),
(23, 'Chuck Norris', 'Norrisville', 'http://www.chucknorrisfacts.fr/', 'leplusfort@dumonde.com', 'nono', '$2y$10$GhYJOz/QonBKi0Qwlspsou9qYvxqO1WjtDJXR9FlrpFABSjXt4C3a', 'Chuck Norris peut. Les autres essayent.', 19400310, 20190123, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
