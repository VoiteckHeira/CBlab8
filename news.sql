-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 31 Maj 2023, 09:50
-- Wersja serwera: 10.4.21-MariaDB
-- Wersja PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `news`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL COMMENT 'name of the message',
  `type` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'type of the message\r\n(private/public)',
  `message` varchar(2000) COLLATE utf8_polish_ci NOT NULL COMMENT 'message text',
  `deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'existing message - 0, deleted - 1',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `message`
--

INSERT INTO `message` (`id`, `name`, `type`, `message`, `deleted`, `user_id`) VALUES
(1, 'New Intel technology', 'public', 'Intel has announced a new processor for desktops', 0, NULL),
(2, 'Intel shares raising', 'private', 'brokers announce: Intel shares will go up!', 0, NULL),
(3, 'New graphic card from NVidia', 'public', 'NVidia has announced a new graphic card for desktops', 0, NULL),
(4, 'Airplane crash', 'public', 'A passenger plane has crashed in Europe', 0, NULL),
(5, 'Coronavirus', 'private', 'A new version of virus was found!', 0, NULL),
(6, 'Bitcoin price raises', 'public', 'Price of bitcoin reaches new record.', 0, NULL),
(9, 'New Windows announced', 'public', 'A new version of windows was announced. Present buyers of Widows\r\n10 can update the system to the newest version for free.', 0, NULL),
(18, 'test', 'public', 'cos tam zmiana', 0, NULL),
(23, 'zad4_1', 'public', 'test zad4', 0, NULL),
(24, 'zad4_2', 'public', 'test zad4 nr2', 0, NULL),
(26, 'zad4_3', 'public', '<script>alert(\\\'test zad4 nr3\\\');</script>', 0, NULL),
(27, 'dodana nowa', 'public', 'sprawdzamy czy działa', 0, NULL),
(29, 'pawel4', 'public', 'pawel4 dodaje wiadomość - edit', 0, 23),
(30, 'sss', 'public', 'sss', 0, 28);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `permissions`
--

INSERT INTO `permissions` (`id`, `name`) VALUES
(1, 'Admin'),
(4, 'Delete'),
(5, 'View'),
(6, 'Role1'),
(7, 'Role2'),
(8, 'Role3'),
(9, 'msg_edit'),
(10, 'msg_delete');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'User'),
(3, 'Other'),
(4, 'Useless'),
(5, 'msg_role');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `role_permission`
--

CREATE TABLE `role_permission` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `role_permission`
--

INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(2, 5),
(3, 6),
(3, 7),
(3, 8),
(5, 9),
(5, 10);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_polish_ci NOT NULL,
  `hash` blob DEFAULT NULL,
  `salt` blob DEFAULT NULL COMMENT 'salt to use in password hashing',
  `2fa` tinyint(4) DEFAULT NULL,
  `sms_code` varchar(6) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'security code sent via sms or e-mail',
  `code_timelife` timestamp NULL DEFAULT NULL COMMENT 'timelife of security code',
  `security_question` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'additional security question used while password recovering',
  `answer` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'security question answer',
  `lockout_time` timestamp NULL DEFAULT NULL COMMENT 'time to which user account is blocked',
  `session_id` blob DEFAULT NULL COMMENT 'user session identifier',
  `id_status` int(11) NOT NULL COMMENT 'account status',
  `password_form` int(11) NOT NULL DEFAULT 1 COMMENT '1- SHA512, 2-SHA512+salt,3- HMAC'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `login`, `email`, `hash`, `salt`, `2fa`, `sms_code`, `code_timelife`, `security_question`, `answer`, `lockout_time`, `session_id`, `id_status`, `password_form`) VALUES
(1, 'john', 'johny@gmail.com', 0x3535326432396639323930623935323165363031366332323936666134353131, 0x734635256752, 0, '345543', '2022-01-05 13:25:36', 'Your friend\'s name?', 'Peter', NULL, NULL, 2, 1),
(2, 'susie', 'susie@gmail.com', 0x3863393066323836373836633766336239363536346531653838653064646162, 0x6a363752, 0, '674545', '2022-01-12 13:25:36', 'Where were you on your 2015\'s holiday?', 'Turkey', NULL, NULL, 5, 1),
(3, 'anie', 'anie@gmail.com', 0x6463623731306135363663326132346338626661663833363138653732386637, 0x73646667683534, 0, NULL, NULL, 'Your favorite color?', 'Navy blue', NULL, NULL, 1, 1),
(5, 'test123', 'emailok@gmail.com', 0x3332663133373732346463623533396266363034613866326465373562643962376535663230643065373661656631343236376666653064656466636561623132663537303463663161643866383332323166613764333335356437326561336634393832383263613564363837623563313932353964356130306462303462, 0x2aaeda0f4ed14bbd24b5954a8c377d7e, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1),
(7, 'test1234', 'test1234@wp.pl', 0x3964346430663637393565366662643535366463373362613838643930353936366635616265643137326666333839613831346534356631646130323261656464616338393563623261316532333532663965613535653336393062306135343134313362373064356165613063666264643133353939646335636433333734, 0x8151d6dc5f4df88735f36a44e1cb2815, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1),
(9, 't1', 't1@wp.pl', 0x533f553f4e003f445c023f792e43d4b24a3f3f1b3f3f7916023f253f3f52333f4d0e3f4c3fd3add085603f3f353d3f305d3f0b3f60763f7d4c786d5e3fdea5156d75113f3f3f0e3f783f5b403f3fd9bc3f3f78463f503f3f3f051e3f093f213f3f4d5ad09a40393f663e78783f3f230a423f3f3f167329663d2a723e3f6f3f190446663f3f7a643f3f3f083f523f7e5e, 0x89169b84b7b8b9ac3ef63991bfb5cdf9, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1),
(10, 'user', 'user@gmail.com', 0x3335363136336438613233623939336636383837383631623264313566303035623934613036336365353061326265316563323266373230333537626562303663666436656566303766346262313965313762663737356166656363396264646437376536613262343930666664353637653465623264363935653564386264, 0xe9995593a9a99984c5170a591e683ed8, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1),
(18, 'sprawdzam', 'paweljabloniec8@gmail.com', 0x6139336433333631346638653038373161386161343838303962393365643739623830666437366237636231396133636561646565386364383337326132376237643739386335343133363336303665373533623331383234383638323437613636306166393739363734323838356334626663623962303664383163393839, 0x9fc9c3e5ca9a839a94e4fed6a834ff91, 1, '785848', '2023-04-25 20:38:27', NULL, NULL, NULL, NULL, 1, 1),
(19, 'pawel', 'pawelmail@wp.pl', 0x6339613538656234643736383364646335353938623261666161613931653464323733313638616630386334666265303066363137666530363035613032343264613937396138336537633066386436383661346636613365373635633338353236353863303630656638333735393231353563353864336261623164336362, 0xcf0f6f23b4505760e79effd009e02571, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1),
(20, 'pawel1', 'pawel1mail@gmail.com', 0x3435323033363262653936663332643837353238393738616163613965363236333436643436313064666564613331363062356165623564306239663131623762306565663865316238653934313132636362666162623136326633356532363661623761326534636661316336356632316166666635616365353736643536, 0xfea055f5f0782ec88635c41b6a675eb0, 1, '303039', '2023-04-26 12:39:29', NULL, NULL, NULL, NULL, 1, 1),
(21, 'pawel2', 'pawel2@gmail.com', 0x6131333564643935313431616638353335623137383465653764316531306233343262656239653138623031393733353838323664353963323330343761343063623536343962336137633165656135643434323236353634396461396334313136313532383535396366373035343234336363323334306232663834623330, 0x877027825d17a187b662110f4da0c05b, 1, '125401', '2023-05-30 17:45:03', NULL, NULL, NULL, NULL, 1, 1),
(22, 'pawel3', 'pawel3@gmail.com', 0x3861323464386234313662363562653239663162316335663963313766623864326661366461353436323930613562316564363063633633356135356130643636666162336236366234306439363930373366663035356339306636306466323732616333626337306662363336356562303565353863323735316565343263, 0xf32660f47fe177170c73397d4b3a5e3f, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1),
(23, 'pawel4', 'pawel4@gmail.com', 0x6135326233366637323465396430653937373633373734383136373961623136353439663166653538346666386164653434653932383864353034306466383138396330323338373137646333646262613562663064366637323166626665353364333164393036663134376463653434326639356264663137393738363933, 0xd9fcafa8453ec2de3c8e58bb009b3116, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1),
(24, 'pawel5', 'pawel5@gmail.com', 0x3861373838313833383264346365643662353635643930633266373630373339313666626331323864313861653638396661326163656235363264656333616334396233333563643236613663306163353963366438306439363230376533656562333039626664353933323736663733663063316631373632663538356134, 0xb5495b23225d10fbbc9d07a2dae45601, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1),
(27, 'ada', 'ada@ada.pl', 0x3734326165636234613036663335303737383831326162353862346461353366386531623365626561343336643133653937343632326263666332303232383365356365396135313534313435633962343431353535353532653464383132396430663930343530666166346666626435653365383837663364336338653434, 0x78304ed0c40c74ea92e754b7b0cf8218, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1),
(28, 'sss', 'sss@sss.pl', 0x6263386337303037663239333334353365316435646335666164326164306634396430396130376539393732663731646635383230326366343632383462646539303662323164376331643765646331343830626266376166343635323432333633353765653830346133633834363838663962646462316632656633333334, 0xcfb217678fa038b5b40afc77e0f338ff, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_sessions`
--

CREATE TABLE `users_sessions` (
  `user_id` int(11) NOT NULL,
  `data_login` timestamp NULL DEFAULT NULL,
  `data_logout` timestamp NULL DEFAULT NULL,
  `hash_session_id` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `users_sessions`
--

INSERT INTO `users_sessions` (`user_id`, `data_login`, `data_logout`, `hash_session_id`) VALUES
(19, '2023-05-30 19:18:36', '2023-05-30 19:20:15', '4a3a28c5946d56760dc216a6ebb1c9533d7ca0ac528b074636861baee474e3f0'),
(23, '2023-05-30 19:20:22', '2023-05-30 19:21:10', 'ed097ec801f008dc5cab216e9c2e270838d10570047823e432f49d9d84abae1d'),
(19, '2023-05-30 19:21:20', '2023-05-30 19:22:02', 'cabcbb3b0f92526cd9122fdda36d0f947395513fa3d11492569741bf0f901c5a'),
(19, '2023-05-30 19:55:31', '2023-05-31 01:01:06', 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855'),
(19, '2023-05-30 19:55:42', '2023-05-30 20:02:04', 'ce097b44fc35dea3a5a569bd091e11630575825fa73cc00449a7dba2680bbdee'),
(19, '2023-05-30 20:02:13', NULL, 'b9d545ea81f49995bbe9e786646ac1aef5d579ff379faec3cf7fbc37bf7a5765'),
(23, '2023-05-30 23:11:49', '2023-05-31 01:01:30', 'caf2a49facce4e18ddf11cddc1eb573897dbfd291f77574ad8836cc33246e2e0'),
(27, '2023-05-31 01:01:23', '2023-05-31 01:01:30', 'caf2a49facce4e18ddf11cddc1eb573897dbfd291f77574ad8836cc33246e2e0'),
(19, '2023-05-31 01:01:37', '2023-05-31 01:21:57', 'dbc6f1bfc91d9deb5f2b7f065d8714a3325b842a286a6618c9f2a8ac5694d79a'),
(27, '2023-05-31 01:22:01', '2023-05-31 06:03:39', 'd59eb6599899cb93e809b97615abd352befdcd741d37bd28c8d4ab3b1160228f'),
(27, '2023-05-31 05:55:48', '2023-05-31 06:03:39', 'd59eb6599899cb93e809b97615abd352befdcd741d37bd28c8d4ab3b1160228f'),
(28, '2023-05-31 06:04:28', NULL, 'f57feb4c0e4c20d4b0c97b1a640df6c22599d3e53e0e61aa924ac1ba0587ebd6'),
(28, '2023-05-31 07:09:31', NULL, 'f57feb4c0e4c20d4b0c97b1a640df6c22599d3e53e0e61aa924ac1ba0587ebd6'),
(27, '2023-05-31 07:45:32', NULL, 'f57feb4c0e4c20d4b0c97b1a640df6c22599d3e53e0e61aa924ac1ba0587ebd6');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_permission`
--

CREATE TABLE `user_permission` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user_permission`
--

INSERT INTO `user_permission` (`user_id`, `permission_id`) VALUES
(27, 1),
(27, 4),
(27, 5),
(27, 6),
(27, 7),
(27, 8),
(27, 9),
(27, 10);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_role`
--

CREATE TABLE `user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user_role`
--

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
(19, 1),
(22, 5),
(27, 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `FKuser674283` (`id_status`);

--
-- Indeksy dla tabeli `user_permission`
--
ALTER TABLE `user_permission`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `FK_user_permission_permission` (`permission_id`);

--
-- Indeksy dla tabeli `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT dla tabeli `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `user_permission`
--
ALTER TABLE `user_permission`
  ADD CONSTRAINT `FK_user_permission_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_user_permission_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
