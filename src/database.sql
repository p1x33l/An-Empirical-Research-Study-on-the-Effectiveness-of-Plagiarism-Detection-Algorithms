-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 22 juin 2022 à 22:00
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pfe`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles41`
--

CREATE TABLE `articles41` (
  `id` int(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `lang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `articles41`
--

INSERT INTO `articles41` (`id`, `path`, `lang`) VALUES
(2, 'files/Nn2RM_24_Hour_Fitness.txt', 'en'),
(3, 'files/OUpoB_Acne_And_Its_Treatment.txt', 'en'),
(4, 'files/DblYD_Acne_Skin_Care.txt', 'en'),
(5, 'files/wuC0D_All_About_Facial_Skin_Care.txt', 'en'),
(6, 'files/ZXdxY_All_About_Sensitive_Skin_Care.txt', 'en'),
(7, 'files/5jWMs_Anti_Aging_Skin_Care_Products.txt', 'en'),
(8, 'files/493WU_Anti_Aging_Skin_Care.txt', 'en'),
(9, 'files/0i5rU_Antiaging_Skin_Care.txt', 'en'),
(10, 'files/p27BX_Are_Natural_Skin_Care_Products_The_Answer_To_All_Problems.txt', 'en'),
(11, 'files/KzRac_Beauty_Fitness.txt', 'en'),
(12, 'files/RiG0n_Diet_Fitness.txt', 'en'),
(13, 'files/sitUT_Equipment_Apparel.txt', 'en'),
(14, 'files/CdQlI_Exercise_Fitness.txt', 'en'),
(15, 'files/Tdjrg_Extreme_Fitness.txt', 'en'),
(16, 'files/kMpRs_Facial_Skin_Care_Product.txt', 'en'),
(17, 'files/UwWMq_Fitness_Babes.txt', 'en'),
(18, 'files/20Zyd_Fitness_Center.txt', 'en'),
(19, 'files/6u0SX_Fitness_Club.txt', 'en'),
(20, 'files/T03PB_Fitness_Equipment.txt', 'en'),
(21, 'files/G8z61_Fitness_Magazine.txt', 'en'),
(22, 'files/x6CpI_Fitness_Model.txt', 'en'),
(23, 'files/n6Xkq_Fitness_Program.txt', 'en'),
(24, 'files/7LZGI_Fitness_Trainer.txt', 'en'),
(25, 'files/zLyn0_Fitness_Training.txt', 'en'),
(26, 'files/ZgBlc_Fitness_Woman.txt', 'en'),
(27, 'files/mw9iS_Health_Fitness.txt', 'en'),
(28, 'files/yAme6_Herbal_Skin_Care.txt', 'en'),
(29, 'files/nt8xB_Home_Fitness_Equipment.txt', 'en'),
(30, 'files/zebio_La_Fitness.txt', 'en'),
(31, 'files/Y4OBy_Lifetime_Fitness.txt', 'en'),
(32, 'files/ZjReA_Lotions_Vs._Skin_Care_Creams.txt', 'en'),
(33, 'files/e8K31_Man_Fitness.txt', 'en'),
(34, 'files/Vgcw2_Man_Skin_Care.txt', 'en'),
(35, 'files/e3CKc_Muscle_Fitness.txt', 'en'),
(36, 'files/ImQcc_Organic_Skin_Care.txt', 'en'),
(37, 'files/CJNRk_Personal_Skin_Care.txt', 'en'),
(38, 'files/Efnfq_Physical_Fitness.txt', 'en'),
(39, 'files/d7BM9_Serious_Skin_Care.txt', 'en'),
(40, 'files/YcGPz_Skin_Care_Cosmetic_O_Useful_Or_Harmful.txt', 'en'),
(41, 'files/PqKtg_Skin_Care_Product.txt', 'en'),
(42, 'files/E2mUD_Skin_Care_Treatment_For_The_Most_Common_Skin_Conditions.txt', 'en'),
(43, 'files/XqrBV_Sports_Fitness.txt', 'en'),
(44, 'files/lBVfn_The_Facts_About_Oily_Skin_Care.txt', 'en'),
(45, 'files/0OjGn_The_Recipe_For_Dry_Skin_Care.txt', 'en'),
(46, 'files/eeKb6_Tips_For_Make_Up_And_Skin_Care.txt', 'en'),
(47, 'files/CyNSH_Top_10_Skin_Care_Tips.txt', 'en'),
(48, 'files/5ZXN6_Vitamin_C_Skin_Care_O_The_Challenge.txt', 'en'),
(49, 'files/1yjNB_What_Is_Natural_Skin_Care.txt', 'en'),
(50, 'files/pgAxC_Which_Is_The_Best_Skin_Care_Product.txt', 'en'),
(51, 'files/OFd0i_Women_Fitness.txt', 'en'),
(52, 'files/AMeSp_Amara_Camara,_porte-parole_de_la_présidence_guinéenne__Alpha_Condé_va_revenir_en_Guinée.txt', 'fr'),
(53, 'files/iPmVH_Christophe_Lutundula,_chef_de_la_diplomatie_congolaise__La_RDC_n\'a_jamais_envisagé_la_guerre.txt', 'fr'),
(54, 'files/NkoYo_En_RD_Congo,_le_roi_Philippe_renouvelle_ses_regrets_mais_ne_présente_pas_d\'excuses.txt', 'fr'),
(55, 'files/qBVDN_Le_Sahel_face_à_la_menace_jihadiste.txt', 'fr'),
(56, 'files/cuhpb_Mali__la_junte_se_donne_deux_ans_pour_rendre_le_pouvoir_aux_civils.txt', 'fr'),
(57, 'files/5Libo_RD_Congo__à_Kinshasa,_le_roi_des_Belges_entame_une_visite_très_symbolique.txt', 'fr'),
(58, 'files/rWU5U_RD_Congo__le_roi_de_Belgique_exprime_ses_profonds_regrets_pour_la_période_coloniale.txt', 'fr'),
(59, 'files/eVMxG_RD_Congo__une_visite_historique_du_roi_des_Belges_pour_resserrer_les_liens.txt', 'fr'),
(60, 'files/MX0A6_Sahara_occidental__l\'Algérie_suspend_le_traité_de_coopération_avec_l\'Espagne.txt', 'fr'),
(61, 'files/NoQXS_Scandale_de_corruption_en_Afrique_du_Sud__l\'extradition_des_frères_Gupta_se_précise,_selon_Dubaï.txt', 'fr'),
(62, 'files/2kzA5_اتفاقية_شراكة_في_كلية_الحقوق_بالمحمدية.txt', 'ar'),
(63, 'files/Boxki_أحمد_التوفيق_يصدر_رواية_واحة_تينونا.txt', 'ar'),
(64, 'files/aUQBE_المديني_يتذكر_الخوري_بمعرض_الكتاب.txt', 'ar'),
(65, 'files/8eias_أنغام_إفريقية_تطرب_زوار_معرض_الكتاب.txt', 'ar'),
(66, 'files/xGwnE_حشر_الكاتبات_في_صنف_الأدب_النسائي_يخلق_الجدل_في_معرض_الكتاب.txt', 'ar'),
(67, 'files/WTL1x_سبيلا_يتجول_في_أروقة_الحداثة_بين_أضلاع_ثالوث_النفس_والنص_والواقع.txt', 'ar'),
(68, 'files/KM7VA_في_تقديم_أحمر_أسود_.._مبارك_ربيع_ينتقد_ظاهرة_تبخيس_الثقافة_المغربية.txt', 'ar'),
(69, 'files/rpgPg_مأكولات_إيطالية_تزور_معرض_الكتاب.txt', 'ar'),
(70, 'files/jg2bJ_متحف_في_مراكش_يعرض_الزربية_الصربية.txt', 'ar'),
(71, 'files/UOl1l_ندوة_تسلط_الضوء_على_الأمية_الرقمية.txt', 'ar');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `articles41`
--
ALTER TABLE `articles41`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `path` (`path`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles41`
--
ALTER TABLE `articles41`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
