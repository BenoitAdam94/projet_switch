-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 19, 2020 at 04:50 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `switch`
--

-- --------------------------------------------------------

--
-- Table structure for table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id_avis` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) NOT NULL,
  `id_salle` int(3) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(2) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_membre` (`id_membre`),
  KEY `id_salle` (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `avis`
--

INSERT INTO `avis` (`id_avis`, `id_membre`, `id_salle`, `commentaire`, `note`, `date_enregistrement`) VALUES
(7, 8, 3, 'Ce n\'est pas une salle de sport !', 3, '2020-02-14 00:00:00'),
(8, 6, 2, 'salle lumineuse', 9, '2020-02-18 00:00:00'),
(9, 8, 6, 'Ideal pour coacher', 7, '2020-02-18 00:00:00'),
(10, 8, 2, 'Mozart, un vituose !', 9, '2020-02-18 00:00:00'),
(11, 12, 2, 'Mozart Amadeus !', 6, '2020-02-18 00:00:00'),
(12, 8, 1, 'Très belle peinture', 7, '2020-02-20 00:00:00'),
(13, 14, 1, 'Je prefère les hiéroglyphes', 3, '2020-02-13 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) DEFAULT NULL,
  `id_produit` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_membre` (`id_membre`),
  KEY `id_produit` (`id_produit`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_membre`, `id_produit`, `date_enregistrement`) VALUES
(1, 7, 3, '2019-02-11 08:00:00'),
(2, 7, 3, '2019-02-10 00:00:00'),
(3, 16, 10, '2020-02-19 00:00:00'),
(4, 14, 4, '2020-02-18 00:00:00'),
(5, 6, 1, '2020-02-20 00:00:00'),
(6, 16, 13, '2020-02-19 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(3) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(1) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(6, 'jerome', '$2y$10$3FxzerWGmiaFhPgum1DZKexIca5LGs8l1hlg9dlJ/Q9.9reglhkx6', 'klklk', 'lklkl', 'adam050986@yahoo.fr', 'm', 1, '2020-02-14 12:46:07'),
(7, 'amine', '$2y$10$uuGJh2/o3VE0fAVkRiOY4eJIbyAjwLfnsDpgvPF0Ri8IOPQQnTtCu', 'MOUNDER', 'amine', 'aminemouder@morroco.mo', 'm', 1, '2020-02-18 15:32:03'),
(8, 'Arnold', '$2y$10$f9qWFl3K6SNN4Y1gbYkHwOB6/RpOmr0baQnRb.NyNbA7RcgvnxpPK', 'Schwarzenegger', 'Arnold', 'arnold@mail.fr', 'm', 1, '2020-02-17 12:41:36'),
(12, 'test2', '$2y$10$hv4FiaIEltyn8l2TB1CJg.LuMypss/fNU9umR.2BWkJBz9S6rslKC', 'test3', 'test', 'test@mail.fr', 'm', 1, '2020-02-17 12:37:15'),
(13, 'EmilieJolie', '$2y$10$58Q8TaJn/X02DD2wKZ1pa.97PmZtMyFkEQWH2EpUDvEWgEZW2rJRK', 'TOUZEAU', 'Emilie', 'emilietouzeau@ifocop.fr', 'f', 1, '2020-02-18 15:07:04'),
(14, 'LaReineDegypte', '$2y$10$yh97xKGuqL50Ls6Rjo/mhejOxzCoPoOl.C2S9Ytxv3ZTIvzsA1rc2', 'Philopator', 'Cléopatre', 'cleopatrephilopator@gmail.com', 'f', 1, '2020-02-18 15:12:08'),
(15, 'Mikado', '$2y$10$nb8JqTT1Djnc6UVoZCBzxOUt7Cd9HYnB9ACQm9FzpWmdVBtbxJ6le', 'MBO', 'Mikaidou', 'Mikaidoumbo@yahoo.fr', 'm', 1, '2020-02-18 15:22:04'),
(16, 'OmarSharif', '$2y$10$nDtfr4sRHrgqQ3y0gI6VG.NGquNtwR9Oa9KetAO7fv8QcgU3QwyDG', 'CHALHOUB', 'Michel Dimitri', 'halhoub@yahoo.fr', 'm', 1, '2020-02-18 15:23:54'),
(17, 'admin', '$2y$10$rCx5BKc9AgYH1rT1.jbbleCBvEGwhe9itV/UaHYcae.9m5O/ClQbC', 'admin', 'admin', 'admin@gmail.com', 'm', 2, '2020-02-18 15:37:45');

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit` int(3) NOT NULL AUTO_INCREMENT,
  `id_salle` int(3) NOT NULL,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `prix` int(3) NOT NULL,
  `etat` enum('libre','reservation') NOT NULL,
  PRIMARY KEY (`id_produit`),
  KEY `id_salle` (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id_produit`, `id_salle`, `date_arrivee`, `date_depart`, `prix`, `etat`) VALUES
(1, 1, '2016-11-22 09:00:00', '2016-11-27 19:00:00', 1200, 'libre'),
(2, 1, '2016-11-29 09:00:00', '2016-12-03 19:00:00', 990, 'libre'),
(4, 2, '2016-11-29 09:00:00', '2016-12-03 19:00:00', 850, 'libre'),
(5, 1, '2020-02-15 09:00:00', '2020-02-06 19:00:00', 77, 'libre'),
(9, 1, '2020-02-19 09:00:00', '2020-02-13 19:00:00', 1500, 'libre'),
(10, 6, '2020-02-21 09:00:00', '2020-02-21 19:00:00', 666, 'libre'),
(11, 6, '2020-02-20 09:00:00', '2020-02-21 19:00:00', 666, 'libre'),
(12, 11, '2020-02-12 09:00:00', '2020-02-12 19:00:00', 456, 'libre'),
(13, 3, '2020-02-19 09:00:00', '2020-02-19 19:00:00', 9845, 'libre');

-- --------------------------------------------------------

--
-- Table structure for table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `id_salle` int(3) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `pays` varchar(20) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cp` int(5) NOT NULL,
  `capacite` int(3) NOT NULL,
  `categorie` enum('reunion','bureau','formation') NOT NULL,
  PRIMARY KEY (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salle`
--

INSERT INTO `salle` (`id_salle`, `titre`, `description`, `photo`, `pays`, `ville`, `adresse`, `cp`, `capacite`, `categorie`) VALUES
(1, 'Cezane', 'Cette salle sera parfaite pour vos formation', 'cezane-1.jpg', 'france', 'paris', '30 rue mademoiselle', 75015, 10, 'formation'),
(2, 'Mozart', 'Pour recevoir vos collaborateur en petits comité', 'mozart-2.jpg', 'France', 'Paris', '17 rue de Turbigo', 75002, 5, 'reunion'),
(3, 'Picasso', 'Pour travailler en couleur', 'picasso-3.jpg', 'france', 'paris', '28 quai claude bernard', 69007, 14, 'bureau'),
(6, 'Satie', 'Gymnopedie66', '1278-satie-2.png', 'france', 'paris', '3 rue de la paix', 75256, 12, 'bureau'),
(11, 'Beethoven', 'Très belle salle au calme', '9179-Salledali8perpf.jpg=420x420xcrop.jpg', 'france', 'paris', '3 rue de la beauderie', 75004, 6, 'bureau');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
