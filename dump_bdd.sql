--
-- Structure de la table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
`id` int(11) NOT NULL,
  `modules` varchar(255) NOT NULL,
  `id_mods` varchar(255) NOT NULL,
  `author` varchar(32) NOT NULL,
  `text` text NOT NULL,
  `date_create` datetime NOT NULL,
  `ip` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL
);


-- --------------------------------------------------------

--
-- Structure de la table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `config`
--

INSERT INTO `config` (`name`, `value`) VALUES
('active_icon', '1'),
('active_jquery', '0'),
('comment_guest', '0'),
('mail_admin', 'admin@bel-cms.be'),
('name_website', 'Web-Help'),
('template', 'default_bel_cms'),
('tpl_full', 'downloads, user, forum');

-- --------------------------------------------------------

--
-- Structure de la table `forum`
--

CREATE TABLE IF NOT EXISTS `forum` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `forum_order` int(3) NOT NULL,
  `groups` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum`
--

INSERT INTO `forum` (`id`, `name`, `description`, `forum_order`, `groups`) VALUES
(1, 'Presentation', 'Présentez vous', 1, '0,1,2'),
(2, 'Aide', 'Aidez nous', 2, '0,1,2');

-- --------------------------------------------------------

--
-- Structure de la table `forum_cat`
--

CREATE TABLE IF NOT EXISTS `forum_cat` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `forum_order` int(3) NOT NULL,
  `groups` text NOT NULL,
  `rewrite_name` varchar(255) NOT NULL,
  `main_cat` int(3) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `forum_cat`
--

INSERT INTO `forum_cat` (`id`, `name`, `description`, `forum_order`, `groups`, `rewrite_name`, `main_cat`) VALUES
(1, 'Presentation', 'présenter vous ici', 1, '0,1,2', 'Presentation', 1),
(2, 'Forum 2', 'Je suis le 2eme Forum', 2, '0,1,2', 'forum-2', 2),
(3, 'Forum 3', '', 2, '0,1,2', 'forum-3', 2),
(4, 'Forum 1', 'je suis le 1er Forum', 1, '0,1,2', 'forum-1', 2);

-- --------------------------------------------------------

--
-- Structure de la table `forum_post`
--

CREATE TABLE IF NOT EXISTS `forum_post` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `author` varchar(60) NOT NULL,
  `text_content` text NOT NULL,
  `thread_id` int(6) NOT NULL,
  `rewrite_name` varchar(255) NOT NULL
);

-- --------------------------------------------------------

--
-- Structure de la table `forum_threads`
--

CREATE TABLE IF NOT EXISTS `forum_threads` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `status` int(1) NOT NULL,
  `author` varchar(60) NOT NULL,
  `cat_id` varchar(3) NOT NULL,
  `last_post_date` datetime NOT NULL,
  `last_post_by` varchar(60) NOT NULL,
  `view` int(6) NOT NULL,
  `reply` int(11) NOT NULL,
  `rewrite_name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
`id` int(10) NOT NULL,
  `name` varchar(32) NOT NULL,
  `id_group` int(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `groups`
--

INSERT INTO `groups` (`id`, `name`, `id_group`) VALUES
(1, 'Administrateur', 1),
(2, 'V.I.P', 2),
(3, 'membre', 3);

-- --------------------------------------------------------

--
-- Structure de la table `infos_action`
--

CREATE TABLE IF NOT EXISTS `infos_action` (
`id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `date_insert` datetime NOT NULL,
  `text` text NOT NULL,
  `modules` varchar(30) NOT NULL
);


-- --------------------------------------------------------

--
-- Structure de la table `mails_blacklist`
--

CREATE TABLE IF NOT EXISTS `mails_blacklist` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `mails_blacklist`
--

INSERT INTO `mails_blacklist` (`id`, `name`) VALUES
(4, 'yopmail'),
(5, 'jetable'),
(6, 'mail-temporaire'),
(7, 'ephemail'),
(8, 'trashmail'),
(9, 'kasmail'),
(10, 'spamgourmet'),
(11, 'tempomail'),
(12, 'mytempemail'),
(13, 'saynotospams'),
(14, 'tempemail'),
(15, 'mailinator'),
(16, 'mytrashmail'),
(17, 'mailexpire'),
(18, 'maileater'),
(19, 'guerrillamail'),
(20, '10minutemail'),
(21, 'dontreg'),
(22, 'filzmail'),
(23, 'spamfree24'),
(24, 'brefmail'),
(25, '0-mail'),
(26, 'link2mail'),
(27, 'DodgeIt'),
(28, 'dontreg'),
(29, 'e4ward'),
(30, 'gishpuppy'),
(31, 'haltospam'),
(32, 'kasmail'),
(33, 'mailEater'),
(34, 'mailNull'),
(35, 'mytrashMail'),
(36, 'nobulk'),
(37, 'nospamfor'),
(38, 'PookMail'),
(39, 'shortmail'),
(40, 'sneakemail'),
(41, 'spam'),
(42, 'spambob'),
(43, 'spambox'),
(44, 'spamDay'),
(45, 'spamh0le'),
(46, 'spaml'),
(47, 'tempInbox'),
(48, 'temporaryinbox'),
(49, 'willhackforfood'),
(50, 'willSelfdestruct'),
(51, 'wuzupmail'),
(52, 'mailhazard'),
(53, 'mail');

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_text` text NOT NULL,
  `long_text` text NOT NULL,
  `date_create` datetime NOT NULL,
  `tags` varchar(255) NOT NULL,
  `author` varchar(30) NOT NULL,
  `img` varchar(255) NOT NULL,
  `count_news` int(11) NOT NULL,
  `rewrite_name` varchar(255) NOT NULL
);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `password` char(255) NOT NULL,
  `mail` varchar(128) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `hash_key` char(32) NOT NULL,
  `date_registration` datetime NOT NULL,
  `last_visit` datetime NOT NULL,
  `website` varchar(128) NOT NULL,
  `groups` text NOT NULL,
  `valid` int(1) NOT NULL,
  `last_ip` varchar(255) NOT NULL,
  `token` varchar(50) NOT NULL
);


--
-- Index pour les tables exportées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `config`
--
ALTER TABLE `config`
 ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `forum`
--
ALTER TABLE `forum`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `forum_cat`
--
ALTER TABLE `forum_cat`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `forum_post`
--
ALTER TABLE `forum_post`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `forum_threads`
--
ALTER TABLE `forum_threads`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `groups`
--
ALTER TABLE `groups`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id_group` (`id_group`);

--
-- Index pour la table `infos_action`
--
ALTER TABLE `infos_action`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mails_blacklist`
--
ALTER TABLE `mails_blacklist`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `news`
--
ALTER TABLE `news`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `rewrite_name` (`rewrite_name`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`), ADD UNIQUE KEY `mail` (`mail`), ADD KEY `password` (`password`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `forum`
--
ALTER TABLE `forum`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `forum_cat`
--
ALTER TABLE `forum_cat`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `forum_post`
--
ALTER TABLE `forum_post`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `forum_threads`
--
ALTER TABLE `forum_threads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `groups`
--
ALTER TABLE `groups`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `infos_action`
--
ALTER TABLE `infos_action`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `mails_blacklist`
--
ALTER TABLE `mails_blacklist`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT pour la table `news`
--
ALTER TABLE `news`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;