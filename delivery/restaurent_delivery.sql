-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 05 juin 2020 à 12:59
-- Version du serveur :  5.7.24
-- Version de PHP :  7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=1;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `restaurent_delivery`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_880E0D76A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `user_id`) VALUES
(12, 145),
(13, 146),
(14, 147),
(15, 148),
(16, 149);

-- --------------------------------------------------------

--
-- Structure de la table `command`
--

DROP TABLE IF EXISTS `command`;
CREATE TABLE IF NOT EXISTS `command` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `delivery` datetime NOT NULL,
  `price` double NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8ECAEAD4A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=501 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `command`
--

INSERT INTO `command` (`id`, `user_id`, `delivery`, `price`, `status`) VALUES
(469, 232, '2020-06-05 13:13:35', 28.5, 1),
(470, 232, '2020-06-05 13:14:34', 10.5, 1),
(471, 232, '2020-06-05 13:15:19', 22.5, 1),
(472, 233, '2020-06-05 13:16:58', 41.5, 1),
(473, 233, '2020-06-05 13:17:28', 22.5, 1),
(474, 233, '2020-06-05 13:18:05', 25.5, 1),
(475, 234, '2020-06-05 13:20:33', 50.5, 1),
(476, 234, '2020-06-05 13:20:58', 20.5, 1),
(477, 234, '2020-06-05 13:21:27', 23.5, 1),
(478, 235, '2020-06-05 13:22:12', 20.5, 1),
(479, 235, '2020-06-05 13:22:46', 50.5, 1),
(480, 235, '2020-06-05 13:23:18', 20.5, 1),
(481, 236, '2020-06-05 13:24:03', 22.5, 1),
(482, 236, '2020-06-05 13:24:36', 19.5, 1),
(483, 236, '2020-06-05 13:25:09', 16.5, 1),
(484, 237, '2020-06-05 13:26:58', 48.5, 1),
(485, 237, '2020-06-05 13:27:34', 42.5, 1),
(486, 237, '2020-06-05 13:28:07', 10.5, 1),
(487, 238, '2020-06-05 13:29:12', 32.5, 1),
(488, 238, '2020-06-05 13:29:36', 18.5, 1),
(489, 238, '2020-06-05 13:30:06', 31.5, 1),
(490, 239, '2020-06-05 13:30:54', 25.5, 1),
(491, 239, '2020-06-05 13:31:19', 32.5, 1),
(492, 239, '2020-06-05 13:31:48', 19.5, 1),
(493, 240, '2020-06-05 13:32:39', 34.5, 1),
(494, 240, '2020-06-05 13:33:11', 24.5, 1),
(495, 240, '2020-06-05 13:33:31', 24.5, 1),
(496, 240, '2020-06-05 13:33:54', 18.5, 1),
(497, 241, '2020-06-05 13:35:06', 25.5, 1),
(498, 241, '2020-06-05 13:35:56', 26.5, 1),
(499, 241, '2020-06-05 13:39:36', 22.5, 1),
(500, 241, '2020-06-05 13:39:55', 10.5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `command_dish`
--

DROP TABLE IF EXISTS `command_dish`;
CREATE TABLE IF NOT EXISTS `command_dish` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `command_id` int(11) DEFAULT NULL,
  `dish_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_34D7223533E1689A` (`command_id`),
  KEY `IDX_34D72235148EB0CB` (`dish_id`)
) ENGINE=InnoDB AUTO_INCREMENT=260 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `command_dish`
--

INSERT INTO `command_dish` (`id`, `command_id`, `dish_id`, `quantity`) VALUES
(180, 469, 2253, 3),
(181, 469, 2254, 1),
(182, 469, 2255, 1),
(183, 470, 2281, 1),
(184, 470, 2280, 1),
(185, 471, 2303, 1),
(186, 471, 2305, 1),
(187, 471, 2304, 1),
(188, 472, 2286, 1),
(189, 472, 2287, 2),
(190, 472, 2284, 1),
(191, 472, 2283, 3),
(192, 473, 2309, 4),
(193, 474, 2328, 1),
(194, 474, 2329, 1),
(195, 474, 2330, 1),
(196, 475, 2315, 3),
(197, 475, 2317, 2),
(198, 475, 2316, 1),
(199, 475, 2313, 1),
(200, 475, 2314, 1),
(201, 476, 2336, 2),
(202, 477, 2262, 1),
(203, 477, 2261, 1),
(204, 478, 2339, 1),
(205, 478, 2341, 1),
(206, 479, 2263, 2),
(207, 479, 2264, 2),
(208, 479, 2266, 2),
(209, 480, 2288, 1),
(210, 480, 2290, 1),
(211, 480, 2292, 1),
(212, 481, 2268, 1),
(213, 482, 2293, 1),
(214, 482, 2296, 1),
(215, 483, 2319, 1),
(216, 483, 2321, 1),
(217, 483, 2318, 1),
(218, 484, 2302, 1),
(219, 484, 2299, 2),
(220, 484, 2300, 2),
(221, 484, 2298, 2),
(222, 484, 2301, 1),
(223, 485, 2323, 1),
(224, 485, 2324, 1),
(225, 485, 2325, 1),
(226, 485, 2327, 1),
(227, 486, 2346, 1),
(228, 487, 2305, 3),
(229, 487, 2304, 3),
(230, 488, 2349, 2),
(231, 489, 2256, 2),
(232, 489, 2257, 1),
(233, 489, 2255, 1),
(234, 489, 2254, 1),
(235, 490, 2330, 1),
(236, 490, 2329, 1),
(237, 490, 2328, 1),
(238, 491, 2261, 2),
(239, 492, 2281, 1),
(240, 492, 2282, 1),
(241, 492, 2280, 2),
(242, 493, 2264, 1),
(243, 493, 2266, 2),
(244, 493, 2267, 1),
(245, 494, 2289, 2),
(246, 494, 2290, 1),
(247, 494, 2288, 1),
(248, 495, 2313, 2),
(249, 496, 2334, 2),
(250, 497, 2269, 1),
(251, 497, 2271, 1),
(252, 497, 2272, 1),
(253, 497, 2270, 1),
(254, 498, 2301, 1),
(255, 498, 2298, 2),
(256, 498, 2299, 2),
(257, 499, 2322, 1),
(258, 499, 2321, 2),
(259, 500, 2351, 1);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `dish_id` int(11) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publication` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  KEY `IDX_9474526C148EB0CB` (`dish_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dish`
--

DROP TABLE IF EXISTS `dish`;
CREATE TABLE IF NOT EXISTS `dish` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `restorer_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_957D8CB829C3E95E` (`restorer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2353 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dish`
--

INSERT INTO `dish` (`id`, `restorer_id`, `name`, `img`, `price`) VALUES
(2253, 88, 'maki', 'img_1591304221_66951_sushi-373587_1280.jpg', '5'),
(2254, 88, 'sushi au saumon', 'img_1591304282_63977_sushis.jpg', '5'),
(2255, 88, 'riz', 'img_1591304313_52202_rice-3997767_1280.jpg', '3'),
(2256, 88, 'brochette', 'img_1591304546_85950_skewer-3440006_1280.jpg', '8'),
(2257, 88, 'maki specialité', 'img_1591304585_38250_asian-food-2090947_1280.jpg', '5'),
(2258, 89, 'riz', 'img_1591304827_63036_rice-2294365_1280.jpg', '3'),
(2259, 89, 'maki california', 'img_1591304884_70944_california maki.jpg', '6'),
(2260, 89, 'soupe', 'img_1591304952_7240_soupe.jpg', '4'),
(2261, 89, 'plateau', 'img_1591304992_5666_plateau.jpg', '15'),
(2262, 89, 'katsudon', 'img_1591305080_43596_katsudon.jpg', '6'),
(2263, 90, 'riz', 'img_1591305166_61810_rice-3997767_1280.jpg', '3'),
(2264, 90, 'plateau', 'img_1591305207_1086_platea.jpg', '14'),
(2265, 90, 'curry', 'img_1591305247_1441_curry.jpg', '5'),
(2266, 90, 'soupe', 'img_1591305298_11168_sou.jpg', '7'),
(2267, 90, 'brochette', 'img_1591305352_96706_brochette.jpg', '4'),
(2268, 91, 'plateau', 'img_1591305468_4133_pla.jpg', '20'),
(2269, 91, 'maki', 'img_1591305507_95694_makki.jpg', '5'),
(2270, 91, 'riz cantonais', 'img_1591305548_34790_cant.jpg', '4'),
(2271, 91, 'brochette', 'img_1591305575_55501_bro.jpg', '8'),
(2272, 91, 'soupe', 'img_1591305606_41625_soup.jpg', '6'),
(2273, 92, 'plateau', 'img_1591305695_5910_plaaaaaa.jpg', '18'),
(2274, 92, 'soupe', 'img_1591305751_20783_soupeeeeeeeee.jpg', '9'),
(2275, 92, 'salade', 'img_1591305785_80626_salade.jpg', '5'),
(2276, 92, 'canard', 'img_1591305864_50420_canard.jpg', '7'),
(2277, 92, 'maki', 'img_1591305892_42239_makiiiiiiiiii.jpg', '4'),
(2278, 93, 'burger', 'img_1591347822_2065_burger.jpg', '5'),
(2279, 93, 'double burger', 'img_1591348007_91091_burger-2762431_1280.jpg', '8'),
(2280, 93, 'frite', 'img_1591348091_76196_frite.jpg', '3'),
(2281, 93, 'hamburger', 'img_1591348118_77980_hamburger-1238246_1280.jpg', '5'),
(2282, 93, 'burger maison', 'img_1591348191_23361_burger-maison.jpg', '6'),
(2283, 94, 'frite', 'img_1591348255_91126_frite.jpg', '4'),
(2284, 94, 'burger bacon', 'img_1591348311_98795_burgerBacon.jpg', '7'),
(2285, 94, 'burger australien', 'img_1591348344_59244_burgerAus.jpg', '8'),
(2286, 94, 'burger vegan', 'img_1591348387_85766_burger-vegan-1280x720.jpg', '10'),
(2287, 94, 'hamburger', 'img_1591348425_81549_hamburg.jpg', '5'),
(2288, 95, 'burger bacon au sirop d\'erable', 'img_1591348530_74566_shutterstock_582437995_10.jpg', '7'),
(2289, 95, 'burger poulet', 'img_1591348615_425_burger_poulet_01-1280x854.jpg', '6'),
(2290, 95, 'frite', 'img_1591348663_56824_frite.jpg', '3'),
(2291, 95, 'burger barbecue', 'img_1591348694_90181_burger-bbq-3-1030x687.jpg', '6'),
(2292, 95, 'burger vegetarien', 'img_1591348731_76067_i134182-burger-vegetarien-aux-lentilles.jpg', '8'),
(2293, 96, 'home burger', 'img_1591348838_37855_87301_w1024h768c1cx2213cy1453cxt0cyt0cxb4500cyb3000.jpg', '13'),
(2294, 96, 'burger bacon et bleu', 'img_1591348874_34079_Blue-Cheese-and-Bacon-Burger-recette.jpg', '8'),
(2295, 96, 'burger raclette', 'img_1591348912_48035_i140702-burger-raclette.jpeg', '7'),
(2296, 96, 'frite', 'img_1591348931_86183_frite.jpg', '4'),
(2297, 96, 'double burger', 'img_1591349001_98252_téléchargement.jpg', '8'),
(2298, 97, 'burger poulet', 'img_1591349090_1410_unnamed.jpg', '6'),
(2299, 97, 'frite', 'img_1591349110_55591_frite.jpg', '3'),
(2300, 97, 'cheeseburger', 'img_1591349174_74938_cheeseburger-classique.jpg', '5'),
(2301, 97, 'burger avocat', 'img_1591349214_95089_burgers-d-avocats-saumon-fume-et-pickles-d-oignon-rouge.jpeg', '6'),
(2302, 97, 'plateau burger', 'img_1591349262_24903_burger-festival-paris-1160x770.png', '12'),
(2303, 98, 'pizza', 'img_1591349358_44933_pizza-2000595_1280.jpg', '10'),
(2304, 98, 'frite', 'img_1591349375_82984_frite.jpg', '4'),
(2305, 98, 'tacos', 'img_1591349438_87960_tacos.jpg', '6'),
(2306, 98, 'sandwich', 'img_1591349506_83369_sandwich.jpg', '5'),
(2307, 98, 'super sandwich', 'img_1591349615_96620_le-poivron-rouge-juteux-regarde-sous-le-pain-entier-dans-le-sandwich_1304-2927.jpg', '8'),
(2308, 99, 'pizza', 'img_1591349658_12847_pizza-2068272_1280.jpg', '10'),
(2309, 99, 'kebab', 'img_1591349691_27210_kebab.jpg', '5'),
(2310, 99, 'frite', 'img_1591349709_34884_frite.jpg', '4'),
(2311, 99, 'begard', 'img_1591349743_9463_begard.jpg', '9'),
(2312, 99, 'nuggets', 'img_1591349780_74126_nugget.jpg', '5'),
(2313, 100, 'pizza', 'img_1591349843_39402_pizza-2802332_1280.jpg', '11'),
(2314, 100, 'le regal', 'img_1591349880_24498_unnamed (1).jpg', '7'),
(2315, 100, 'nuggets', 'img_1591349929_36644_nug.jpg', '5'),
(2316, 100, 'burger', 'img_1591349967_10519_unnamed (2).jpg', '7'),
(2317, 100, 'frite', 'img_1591349993_79620_frite.jpg', '4'),
(2318, 101, 'frite', 'img_1591350022_71029_frite.jpg', '3'),
(2319, 101, 'wrap', 'img_1591350050_43724_snack_hakan_5941bc7d89ec4.png', '6'),
(2320, 101, 'burger', 'img_1591350082_58709_ptit-boursin-burger-speedy-tacos-nice-cannes-snack-kebab-pizza-livraison-nuit.jpg', '7'),
(2321, 101, 'sandwich', 'img_1591350119_42969_Famiflora_famisnack_snack008.jpg', '5'),
(2322, 101, 'pizza', 'img_1591350157_88040_pizza.jpg', '10'),
(2323, 102, 'pizza royale', 'img_1591350230_6314_pizza-royale.jpeg', '10'),
(2324, 102, 'pizza regina', 'img_1591350264_2554_pizza-regina.jpeg', '10'),
(2325, 102, 'pizza margherita', 'img_1591350319_87346_margherita-800x600.jpg', '10'),
(2326, 102, 'frite', 'img_1591350333_5921_frite.jpg', '4'),
(2327, 102, 'pizza reine', 'img_1591350363_97650_i96018-pizza-reine.jpg', '10'),
(2328, 103, 'riz au poulet', 'img_1591350455_27667_plat-oriental-riz-au-curry-avec-du-poulet-pt3te3.jpg', '8'),
(2329, 103, 'Kefta aux aubergines', 'img_1591350521_18336_unnamed (3).jpg', '7'),
(2330, 103, 'pilaf de riz', 'img_1591350647_69481_29536278-plov-pilaf-de-riz-viande-boeuf-mouton-haricots-purée-délicieux-plat-oriental.jpg', '8'),
(2331, 103, 'Khashlama avec des pommes de terre', 'img_1591350697_13680_3aefb2348138657845529689d5be69e9.jpg', '6'),
(2332, 103, 'Shaskshuka', 'img_1591350771_92664_a1252dc15bf33bc3046c924606639e66.jpg', '7'),
(2333, 104, 'riz au poulet', 'img_1591350843_74300_plat-oriental-riz-au-curry-avec-du-poulet-pt3te3.jpg', '9'),
(2334, 104, 'Kefta aux aubergines', 'img_1591350865_21970_unnamed (3).jpg', '8'),
(2335, 104, 'pilaf de riz', 'img_1591350893_19747_29536278-plov-pilaf-de-riz-viande-boeuf-mouton-haricots-purée-délicieux-plat-oriental.jpg', '8'),
(2336, 104, 'Khashlama avec des pommes de terre', 'img_1591350916_84238_3aefb2348138657845529689d5be69e9.jpg', '9'),
(2337, 104, 'Shaskshuka', 'img_1591350941_65676_a1252dc15bf33bc3046c924606639e66.jpg', '7'),
(2338, 105, 'riz au poulet', 'img_1591351015_73500_plat-oriental-riz-au-curry-avec-du-poulet-pt3te3.jpg', '10'),
(2339, 105, 'Kefta aux aubergines', 'img_1591351053_10644_unnamed (3).jpg', '9'),
(2340, 105, 'pilaf de riz', 'img_1591351067_68948_29536278-plov-pilaf-de-riz-viande-boeuf-mouton-haricots-purée-délicieux-plat-oriental.jpg', '8'),
(2341, 105, 'Khashlama avec des pommes de terre', 'img_1591351085_35309_3aefb2348138657845529689d5be69e9.jpg', '9'),
(2342, 105, 'Shaskshuka', 'img_1591351101_51330_a1252dc15bf33bc3046c924606639e66.jpg', '10'),
(2343, 106, 'riz au poulet', 'img_1591351163_55344_plat-oriental-riz-au-curry-avec-du-poulet-pt3te3.jpg', '11'),
(2344, 106, 'Kefta aux aubergines', 'img_1591351180_37226_unnamed (3).jpg', '10'),
(2345, 106, 'pilaf de riz', 'img_1591351195_89645_29536278-plov-pilaf-de-riz-viande-boeuf-mouton-haricots-purée-délicieux-plat-oriental.jpg', '9'),
(2346, 106, 'Khashlama avec des pommes de terre', 'img_1591351211_61898_3aefb2348138657845529689d5be69e9.jpg', '8'),
(2347, 106, 'Shaskshuka', 'img_1591351238_9417_a1252dc15bf33bc3046c924606639e66.jpg', '9'),
(2348, 107, 'riz au poulet', 'img_1591351281_33368_plat-oriental-riz-au-curry-avec-du-poulet-pt3te3.jpg', '9'),
(2349, 107, 'Kefta aux aubergines', 'img_1591351303_27936_unnamed (3).jpg', '8'),
(2350, 107, 'pilaf de riz', 'img_1591351319_74089_29536278-plov-pilaf-de-riz-viande-boeuf-mouton-haricots-purée-délicieux-plat-oriental.jpg', '7'),
(2351, 107, 'Khashlama avec des pommes de terre', 'img_1591351334_18198_3aefb2348138657845529689d5be69e9.jpg', '8'),
(2352, 107, 'Shaskshuka', 'img_1591351392_97494_a1252dc15bf33bc3046c924606639e66.jpg', '9');

-- --------------------------------------------------------

--
-- Structure de la table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sold` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_70E4FA78A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `member`
--

INSERT INTO `member` (`id`, `user_id`, `username`, `lastname`, `address`, `sold`) VALUES
(101, 232, 'Lucille', 'Lefort', '10 Rue Marcel Deniau', 46),
(102, 233, 'Ghislain', 'Delmas', '33 Rue de l\'Aveneau', 18),
(103, 234, 'Suzanne', 'Martin', '33 Rue Bouchaud', 13),
(104, 235, 'Sandrine', 'Rey', '39 Rue de Maryland', 16),
(105, 236, 'Isabelle', 'Coulon', '17 Rue de la Tamise', 49),
(106, 237, 'Patrick', 'Bouvet', '15 Rue de la Croix des Fosses', 6),
(107, 238, 'Philibert', 'Renaud', '26 Rue Beaurepaire', 25),
(108, 239, 'Catherine', 'Forest', '5 Rue Jules Massenet', 30),
(109, 240, 'Ariane', 'Ferrand', '17 Place Jean Ligonday', 8),
(110, 241, 'Eustache', 'Pons', '28 Rue de la Licorne', 25);

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE IF NOT EXISTS `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20200520121948', '2020-05-20 12:20:00'),
('20200520122133', '2020-05-20 12:21:37'),
('20200601094133', '2020-06-01 09:41:43');

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `dish_id` int(11) DEFAULT NULL,
  `note` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CFBDFA14A76ED395` (`user_id`),
  KEY `IDX_CFBDFA14148EB0CB` (`dish_id`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`id`, `user_id`, `dish_id`, `note`) VALUES
(100, 232, 2303, 1),
(101, 232, 2305, 2),
(102, 232, 2304, 2),
(103, 232, 2280, 5),
(104, 232, 2254, 4),
(105, 233, 2328, 3),
(106, 233, 2329, 2),
(107, 233, 2330, 2),
(108, 233, 2309, 3),
(109, 233, 2286, 0),
(110, 233, 2287, 4),
(111, 233, 2284, 5),
(112, 233, 2283, 4),
(113, 234, 2261, 4),
(114, 234, 2336, 3),
(115, 234, 2315, 3),
(116, 234, 2317, 5),
(117, 234, 2313, 3),
(118, 234, 2314, 1),
(119, 235, 2288, 4),
(120, 235, 2290, 4),
(121, 235, 2292, 4),
(122, 235, 2263, 2),
(123, 235, 2264, 4),
(124, 235, 2266, 2),
(125, 235, 2339, 2),
(126, 235, 2341, 4),
(127, 236, 2319, 4),
(128, 236, 2321, 1),
(129, 236, 2318, 2),
(130, 236, 2293, 5),
(131, 236, 2296, 3),
(132, 236, 2268, 4),
(133, 237, 2346, 1),
(134, 237, 2323, 3),
(135, 237, 2324, 4),
(136, 237, 2325, 3),
(137, 237, 2327, 5),
(138, 237, 2302, 5),
(139, 237, 2299, 3),
(140, 237, 2300, 1),
(141, 237, 2298, 3),
(142, 237, 2301, 4),
(143, 238, 2256, 3),
(144, 238, 2257, 2),
(145, 238, 2255, 2),
(146, 238, 2254, 4),
(147, 238, 2349, 3),
(148, 238, 2305, 4),
(149, 238, 2304, 5),
(150, 239, 2281, 1),
(151, 239, 2282, 5),
(152, 239, 2280, 5),
(153, 239, 2261, 3),
(154, 239, 2330, 2),
(155, 239, 2329, 4),
(156, 239, 2328, 1),
(157, 240, 2334, 0),
(158, 240, 2313, 4),
(159, 240, 2289, 5),
(160, 240, 2290, 2),
(161, 240, 2288, 5),
(162, 240, 2264, 3),
(163, 240, 2266, 2),
(164, 240, 2267, 1),
(165, 241, 2351, 3),
(166, 241, 2322, 4),
(167, 241, 2321, 3),
(168, 241, 2301, 1),
(169, 241, 2298, 4),
(170, 241, 2299, 5),
(171, 241, 2269, 4),
(172, 241, 2271, 2),
(173, 241, 2272, 4),
(174, 241, 2270, 3);

-- --------------------------------------------------------

--
-- Structure de la table `restorer`
--

DROP TABLE IF EXISTS `restorer`;
CREATE TABLE IF NOT EXISTS `restorer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_418CC73FA76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `restorer`
--

INSERT INTO `restorer` (`id`, `user_id`, `name`, `logo`, `address`, `category`) VALUES
(88, 212, 'la Prime', 'logo_1591304768_44303_asie1.jpg', '50 Place Cambronne', 'asiatique'),
(89, 213, 'le Colibri', 'logo_1591277244_7187_asia2.jpg', '43 Rue Laennec', 'asiatique'),
(90, 214, 'le Tableau Carré', 'logo_1591277340_67474_asia3.jpg', '39 Impasse Fraboulet', 'asiatique'),
(91, 215, 'la Cantine Silencieuce', 'logo_1591277402_47072_asia4.jpg', '49 Route de Gachet', 'asiatique'),
(92, 216, 'le Manoir Amical', 'logo_1591277475_15758_asia5.jpg', '30 Rue de la Grolerie', 'asiatique'),
(93, 217, 'la Bougie', 'logo_1591277574_90309_burger1.jpg', '37 Rue Contrescarpe', 'burger'),
(94, 218, 'la Prairie', 'logo_1591277650_95934_burger2.jpg', '28 Rue de l\'Elan', 'burger'),
(95, 219, 'Merveille', 'logo_1591277762_53454_burger3.jpg', '47 Avenue du Rocher d\'Enfer', 'burger'),
(96, 220, 'le Goût de l\'Ouest', 'logo_1591277853_11026_burger4.jpg', '7 Rue du Clos Davy', 'burger'),
(97, 221, 'le Podium', 'logo_1591277944_33277_burger5.jpg', '41 Rue de Guingamp', 'burger'),
(98, 222, 'la Petite Maison', 'logo_1591278048_70783_snack1.jpg', '50 Impasse du Queyras', 'snack'),
(99, 223, 'la Caverne', 'logo_1591278151_52310_snack2.jpg', '35 Avenue Armand Duez', 'snack'),
(100, 224, 'le Labyrinthe', 'logo_1591278225_78490_snack3.jpg', '17 Chemin des Farfadets', 'snack'),
(101, 225, 'Vagabonds', 'logo_1591278291_92569_snack4.jpg', '16 Place Bellevue', 'snack'),
(102, 226, 'Indigo', 'logo_1591278357_23589_snack5.jpg', '4 Chemin des Hautes Vignes', 'snack'),
(103, 227, 'le Crépuscule', 'logo_1591278468_35037_orientale1.jpg', '21 Chemin du Parc', 'orientale'),
(104, 228, 'le Piment de Cuibre', 'logo_1591278548_97043_orientale2.jpg', '15 Impasse Parmentier', 'orientale'),
(105, 229, 'la Fusion Harmonique', 'logo_1591278612_45568_orientale3.jpg', '18 Avenue Utrillo', 'orientale'),
(106, 230, 'la Source de Paradis', 'logo_1591278681_37681_orientale4.jpg', '26 Rue Maurice Audin', 'orientale'),
(107, 231, 'Révélations', 'logo_1591278763_37913_orientale5.jpg', '47 Rue de Bulgarie', 'orientale');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D6495126AC48` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=242 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `mail`, `roles`, `password`) VALUES
(145, 'user0@gmail.com', 'a:1:{i:0;s:5:\"ADMIN\";}', '$argon2i$v=19$m=65536,t=4,p=1$OGMxOTY2SWxCUEtoaEkwZg$ea98AsvONAbww3jPAzJsTYbh4mKc/8gEKMARLUYXKlI'),
(146, 'user1@gmail.com', 'a:1:{i:0;s:5:\"ADMIN\";}', '$argon2i$v=19$m=65536,t=4,p=1$OGMxOTY2SWxCUEtoaEkwZg$ea98AsvONAbww3jPAzJsTYbh4mKc/8gEKMARLUYXKlI'),
(147, 'user2@gmail.com', 'a:1:{i:0;s:5:\"ADMIN\";}', '$argon2i$v=19$m=65536,t=4,p=1$OGMxOTY2SWxCUEtoaEkwZg$ea98AsvONAbww3jPAzJsTYbh4mKc/8gEKMARLUYXKlI'),
(148, 'user3@gmail.com', 'a:1:{i:0;s:5:\"ADMIN\";}', '$argon2i$v=19$m=65536,t=4,p=1$OGMxOTY2SWxCUEtoaEkwZg$ea98AsvONAbww3jPAzJsTYbh4mKc/8gEKMARLUYXKlI'),
(149, 'user4@gmail.com', 'a:1:{i:0;s:5:\"ADMIN\";}', '$argon2i$v=19$m=65536,t=4,p=1$OGMxOTY2SWxCUEtoaEkwZg$ea98AsvONAbww3jPAzJsTYbh4mKc/8gEKMARLUYXKlI'),
(212, 'resto1@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$MHhjVkFSSi90Z2ZjbFZXNw$BBieKWjjzO65gEoHpffLBCXnr0DFoWolWiyvt5DFXdI'),
(213, 'resto2@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$V1pDdm1MTFZZelBUQ0FFUQ$HsHYbmZA9G58xoX6DTl7+E2WbcYGBGq8yeFlP9AMaxU'),
(214, 'resto3@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$T3cwTVBZRG9nRWc4UExzLw$T6YdU4Y6/8CNIWBh5sbvcmKa/oxBkmOPp0GDlMT7P+A'),
(215, 'resto4@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$QTFPTWM4UkkwNlg4Y3Mvcw$8h06XnFRNHTcUWRw93DVx2JS3vDhNDPCZQ5i5L6Hjgw'),
(216, 'resto5@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$dEpVZUVScnN6UFAyTmRYbQ$S9saayg0JQOb4H5cUG+DdetcE1UJ4TqxeBrQ4Rx9/lQ'),
(217, 'resto6@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$RDNOeHBPMS4udVRvNWE5ag$uL56dnSzb0T7tqCAhoNZOSNybZH0gnt4DbmZrALCWXo'),
(218, 'resto7@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$dTVrWGxCbS9Qd0NSSXM0VA$HdiX6C2fugnrLFubpRRmCjdt8a67mpU8vpz34+pa17k'),
(219, 'resto8@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$N1JaWDZZU3BqUWRHNmh1RQ$n1lhGliD5cG9G3NP5+7ip/+P3w6UhGjkBcSv74J+dDc'),
(220, 'resto9@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$b1BFWDZqejAvajcuQlh3TA$GE/5zMbZJzKCH+jLt4wjJZMixYK8nv4l661ZLeuSJls'),
(221, 'resto10@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$c3dpNXBlVHZ3TTU4TmZ6bw$fG1NY0gwLlrglt/noBqRcICHDeeXELLxPLEYEFvsgbo'),
(222, 'resto11@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$YktyVVp3ZUE5QVlFbGVxeg$UcaehRfL3PLLtbMdQkwKx1EZQ6Rj3nNuk24lmh5mwXw'),
(223, 'resto12@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$Sk5LSTl3SmJub2NyMVZ0cw$gJq6kRBJXe3JD2x+BCzyc/O3hNTUQ5NEXLsDfcrhIds'),
(224, 'resto13@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$dXd5T0liYm5CdjZsNkxnNQ$8t+tlWFlsb++g3geWM+1A4WfUoVt4fe1uD9M7UND2cQ'),
(225, 'resto14@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$cDdPbHh4ekxVRVloZ3lKUg$HElb8+b6k6dZ1TaMz8fDOr3FiAxS7IKun7R80m1948E'),
(226, 'resto15@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$VnVFYU5CVGU2TjZhSDlwTQ$jBYpmCS8uYnwZwboaKGsrzp2V97+vBoX9EP1oNUNgmI'),
(227, 'resto16@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$L0xsTWE1ODFURWg2Q2MuZw$UARMOdw5PwTk56vCMl3/7bXXrGVSx6Uq9ltadOvYouQ'),
(228, 'resto17@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$ZzVOdHd6UGdKRDBBZnNMdg$KX79BXOIplW878NzstC1gzPQfZP3kzGg7j+gzJXYtXU'),
(229, 'resto18@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$LkJIQ2hrQ0dIdkZxQlBFbw$V6NhV2nBX4BFqngJVEhYIoV0YIVO7TgZEtK4IZQcRhM'),
(230, 'resto19@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$ZHVRQ3k1NFJxaUxRSTRKSQ$C+JIeUNTEhhRBcEBP+pWDNbk0iMT5kQX2sHMOX+celw'),
(231, 'resto20@ynov.com', 'a:1:{i:0;s:8:\"RESTORER\";}', '$argon2i$v=19$m=65536,t=4,p=1$ZnFSQktPdFd1LlZaYVQwdQ$AQRhJz+dm+gi4O+XSXCx8HiJCwsQS6nJQCOg1J4lhJ4'),
(232, 'user1@ynov.com', 'a:1:{i:0;s:6:\"MEMBER\";}', '$argon2i$v=19$m=65536,t=4,p=1$Nk1PajNEdHdBRFhLSmlnbQ$cwM/sy2GH3wkwYfvCnYH8QJhxebUvNPqj0l2PIWEI1I'),
(233, 'user2@ynov.com', 'a:1:{i:0;s:6:\"MEMBER\";}', '$argon2i$v=19$m=65536,t=4,p=1$T1RpNFZRcXB0R2Y2cTZtbA$WwNqxG90hzaAgYRDW7maJadBDqB7jktjk27rjBXGvHo'),
(234, 'user3@ynov.com', 'a:1:{i:0;s:6:\"MEMBER\";}', '$argon2i$v=19$m=65536,t=4,p=1$NjJIcFBSVm1UdkdLYU96eQ$cwXDu9BimPNAL9oGjIMULEvo+HNxYuSGJ8DOE5pYovw'),
(235, 'user4@ynov.com', 'a:1:{i:0;s:6:\"MEMBER\";}', '$argon2i$v=19$m=65536,t=4,p=1$ekloWDhpS1Z3MFByOFFNeA$L+N7Qf3Yt0dCCrtHPlBZlKgWtoulWiEsWqsD6yzWMNk'),
(236, 'user5@ynov.com', 'a:1:{i:0;s:6:\"MEMBER\";}', '$argon2i$v=19$m=65536,t=4,p=1$Z2sySG9VMHZnSXUxVmJUVQ$Z7eHzJLoXDp2XEViPEKkXxR875T876sz53PDvgdbLhA'),
(237, 'user6@ynov.com', 'a:1:{i:0;s:6:\"MEMBER\";}', '$argon2i$v=19$m=65536,t=4,p=1$ZnBCSDF3bEVuMnRQU2RzLg$4gqSHFg8h4cgRVi0UtDuNu+xJ6ay0ETRXQELkuvDyvc'),
(238, 'user7@ynov.com', 'a:1:{i:0;s:6:\"MEMBER\";}', '$argon2i$v=19$m=65536,t=4,p=1$eUVDZ2lsdk9Dc2M3T1JOTw$v55vqK3XybGq2DmIzfLrwXBe2spB5EUsc+2uwtCgHyk'),
(239, 'user8@ynov.com', 'a:1:{i:0;s:6:\"MEMBER\";}', '$argon2i$v=19$m=65536,t=4,p=1$SEt0V1JiTnlYSWZacFp2Sg$Q+QtBuABnH448WxRGWLss0dRF8bsEvtdr98b3sK3L6g'),
(240, 'user9@ynov.com', 'a:1:{i:0;s:6:\"MEMBER\";}', '$argon2i$v=19$m=65536,t=4,p=1$OTVQbGp6LlBtZWM1YVFIMg$OcbrkvMctPq9lO5HY61psjd1RhfbTKVMRwWPublf5uM'),
(241, 'user10@ynov.com', 'a:1:{i:0;s:6:\"MEMBER\";}', '$argon2i$v=19$m=65536,t=4,p=1$L3lTVHo1TGZtSnBvcDQ2cQ$M/AmSIjlsyzH/9Qedolt+2hOsf6YRLP/q2i0PboVPZo');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `FK_880E0D76A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `command`
--
ALTER TABLE `command`
  ADD CONSTRAINT `FK_8ECAEAD4A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `command_dish`
--
ALTER TABLE `command_dish`
  ADD CONSTRAINT `FK_34D72235148EB0CB` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`),
  ADD CONSTRAINT `FK_34D7223533E1689A` FOREIGN KEY (`command_id`) REFERENCES `command` (`id`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C148EB0CB` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `dish`
--
ALTER TABLE `dish`
  ADD CONSTRAINT `FK_957D8CB829C3E95E` FOREIGN KEY (`restorer_id`) REFERENCES `restorer` (`id`);

--
-- Contraintes pour la table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `FK_70E4FA78A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `FK_CFBDFA14148EB0CB` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`),
  ADD CONSTRAINT `FK_CFBDFA14A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `restorer`
--
ALTER TABLE `restorer`
  ADD CONSTRAINT `FK_418CC73FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
