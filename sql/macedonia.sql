-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 25, 2017 at 06:06 AM
-- Server version: 5.7.17
-- PHP Version: 7.0.13-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `granada`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit`
--

CREATE TABLE `audit` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aws_credentials`
--

CREATE TABLE `aws_credentials` (
  `id` int(22) NOT NULL,
  `id_user` int(22) NOT NULL,
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `aws_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `aws_secret` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `id_audit` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ',
  `ip_domain` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devices_files_nmap`
--

CREATE TABLE `devices_files_nmap` (
  `id` int(11) NOT NULL,
  `id_device` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devices_ports`
--

CREATE TABLE `devices_ports` (
  `id` int(11) NOT NULL,
  `id_device` int(11) NOT NULL,
  `id_work` int(255) NOT NULL,
  `port` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `protocol` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `service` varchar(210) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `extra` text COLLATE utf8_unicode_ci,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dns`
--

CREATE TABLE `dns` (
  `id` int(11) NOT NULL,
  `id_audit` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ',
  `ip_domain` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scripts`
--

CREATE TABLE `scripts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `module` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `scripts`
--

INSERT INTO `scripts` (`id`, `name`, `description`, `module`, `url`) VALUES
(1, 'Nmap', 'Script to scan host with nmap', 'device', '/device/nmap/list/'),
(2, 'dns', 'test dns', 'device', '');

-- --------------------------------------------------------

--
-- Table structure for table `script_work`
--

CREATE TABLE `script_work` (
  `id` int(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_script_server` int(11) NOT NULL,
  `id_info_work` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_execute` timestamp NULL DEFAULT NULL,
  `date_start` timestamp NULL DEFAULT NULL,
  `date_finish` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE `servers` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `ip_domain` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `public_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `private_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '0->inactive,1->active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servers_scripts`
--

CREATE TABLE `servers_scripts` (
  `id_server_script` int(11) NOT NULL,
  `id_server` int(255) NOT NULL,
  `id_scripts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(400) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `user_group` int(2) NOT NULL DEFAULT '2',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `salt`, `user_group`, `create_at`, `active`) VALUES
(1, 'demo', 'demo@demo.com', 'b395a206026c434f1746c0bf29e5f11bdf142dfa869f9050ea16d3fde65714b6cb9fb57e20e817c19d2268d37b47a9287111e81147dcbf1de03c435aa73b0746', 'c6460ecbf05f5e4a7dca3c39b92c0753e61b76ef0329c10fe4bc12437a82f0dc111913ade52399bd107b24ea72dd39493ed0f0175f0409ab01c7a9df7b4f3132', 1, '2016-11-25 12:29:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_login`
--

CREATE TABLE `users_login` (
  `id` int(255) NOT NULL,
  `id_user` int(255) NOT NULL,
  `token` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_url_access`
--

CREATE TABLE `user_url_access` (
  `id` int(255) NOT NULL,
  `url` varchar(300) NOT NULL,
  `group_user` int(10) NOT NULL,
  `method` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_url_access`
--

INSERT INTO `user_url_access` (`id`, `url`, `group_user`, `method`) VALUES
(1, '/wifi/list/view', 1, 'GET');

-- --------------------------------------------------------

--
-- Table structure for table `wifi_data`
--

CREATE TABLE `wifi_data` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_audit` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mac` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `process` int(2) NOT NULL DEFAULT '0',
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `xrequests_urls`
--

CREATE TABLE `xrequests_urls` (
  `id` int(11) NOT NULL,
  `domain` varchar(300) NOT NULL,
  `max_request` int(11) NOT NULL,
  `type` enum('1','2','3') NOT NULL COMMENT '1->day,2->Week,3->month'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `xrequests_urls_client`
--

CREATE TABLE `xrequests_urls_client` (
  `id` int(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit`
--
ALTER TABLE `audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `aws_credentials`
--
ALTER TABLE `aws_credentials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_audit` (`id_audit`);

--
-- Indexes for table `devices_files_nmap`
--
ALTER TABLE `devices_files_nmap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devices_ports`
--
ALTER TABLE `devices_ports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_device` (`id_device`),
  ADD KEY `id_work` (`id_work`);

--
-- Indexes for table `dns`
--
ALTER TABLE `dns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_audit` (`id_audit`);

--
-- Indexes for table `scripts`
--
ALTER TABLE `scripts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `script_work`
--
ALTER TABLE `script_work`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_script_server` (`id_script_server`);

--
-- Indexes for table `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `servers_scripts`
--
ALTER TABLE `servers_scripts`
  ADD PRIMARY KEY (`id_server_script`),
  ADD UNIQUE KEY `id_server` (`id_server`,`id_scripts`),
  ADD KEY `id_scripts` (`id_scripts`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_login`
--
ALTER TABLE `users_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user_url_access`
--
ALTER TABLE `user_url_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wifi_data`
--
ALTER TABLE `wifi_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_audit` (`id_audit`);

--
-- Indexes for table `xrequests_urls`
--
ALTER TABLE `xrequests_urls`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit`
--
ALTER TABLE `audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aws_credentials`
--
ALTER TABLE `aws_credentials`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `devices_files_nmap`
--
ALTER TABLE `devices_files_nmap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `devices_ports`
--
ALTER TABLE `devices_ports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dns`
--
ALTER TABLE `dns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scripts`
--
ALTER TABLE `scripts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `script_work`
--
ALTER TABLE `script_work`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `servers`
--
ALTER TABLE `servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `servers_scripts`
--
ALTER TABLE `servers_scripts`
  MODIFY `id_server_script` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users_login`
--
ALTER TABLE `users_login`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_url_access`
--
ALTER TABLE `user_url_access`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `wifi_data`
--
ALTER TABLE `wifi_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `xrequests_urls`
--
ALTER TABLE `xrequests_urls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit`
--
ALTER TABLE `audit`
  ADD CONSTRAINT `audit_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `aws_credentials`
--
ALTER TABLE `aws_credentials`
  ADD CONSTRAINT `aws_credentials_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`id_audit`) REFERENCES `audit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `devices_ports`
--
ALTER TABLE `devices_ports`
  ADD CONSTRAINT `devices_ports_ibfk_1` FOREIGN KEY (`id_device`) REFERENCES `devices` (`id`),
  ADD CONSTRAINT `devices_ports_ibfk_2` FOREIGN KEY (`id_work`) REFERENCES `script_work` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `script_work`
--
ALTER TABLE `script_work`
  ADD CONSTRAINT `script_work_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `servers`
--
ALTER TABLE `servers`
  ADD CONSTRAINT `servers_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `servers_scripts`
--
ALTER TABLE `servers_scripts`
  ADD CONSTRAINT `servers_scripts_ibfk_1` FOREIGN KEY (`id_server`) REFERENCES `servers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `servers_scripts_ibfk_2` FOREIGN KEY (`id_scripts`) REFERENCES `scripts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `users_login`
--
ALTER TABLE `users_login`
  ADD CONSTRAINT `users_login_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `wifi_data`
--
ALTER TABLE `wifi_data`
  ADD CONSTRAINT `wifi_data_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wifi_data_ibfk_2` FOREIGN KEY (`id_audit`) REFERENCES `audit` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
