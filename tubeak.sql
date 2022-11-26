-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-11-2022 a las 19:31:20
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tubeak`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banned`
--

CREATE TABLE `banned` (
  `id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blocked`
--

CREATE TABLE `blocked` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `broadcasts`
--

CREATE TABLE `broadcasts` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `video_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `save` tinyint(1) NOT NULL DEFAULT 0,
  `chat` tinyint(1) NOT NULL DEFAULT 1,
  `live` tinyint(1) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `broadcasts_chat`
--

CREATE TABLE `broadcasts_chat` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `text` text DEFAULT NULL,
  `broadcast_time` int(11) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pinned` tinyint(1) DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lists`
--

CREATE TABLE `lists` (
  `id` int(11) NOT NULL,
  `list_id` varchar(20) NOT NULL,
  `by_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `privacy` tinyint(1) NOT NULL DEFAULT 1,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `type` varchar(25) NOT NULL,
  `notify_key` varchar(32) NOT NULL,
  `seen` int(11) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `type` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pages`
--

INSERT INTO `pages` (`id`, `type`, `text`, `active`) VALUES
(1, 'terms-of-use', '<h4>1- Write the terms of use of your website.</h4>  \n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam hendrerit sem quis urna commodo gravida ac eget diam. Sed tristique id quam auctor iaculis. Donec eget volutpat elit. Curabitur in enim eu nulla aliquet pellentesque nec quis neque. Maecenas ac nulla convallis, aliquam dui posuere, dignissim mi. Maecenas iaculis arcu vitae velit aliquet, vitae blandit ante mattis. Mauris varius sollicitudin purus, mattis blandit urna consequat condimentum. Curabitur vulputate sem finibus ipsum eleifend, et convallis felis egestas. Quisque condimentum faucibus maximus.\n<br><br>\nVestibulum sagittis, ex non condimentum lacinia, lorem libero ornare elit, quis placerat sapien nisi ac eros. Mauris in justo porta, auctor ante non, pulvinar enim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sit amet pulvinar leo. Duis ut velit vitae felis bibendum feugiat. Donec urna orci, dapibus ut erat non, pellentesque cursus tellus. Nullam accumsan rhoncus felis, vel porta tortor suscipit pellentesque. Cras non ante ac orci elementum mattis vel et metus. Proin luctus consequat suscipit. Quisque pharetra lorem augue, nec aliquet nulla aliquam quis. Phasellus ac dictum dui. Etiam mollis ultrices dolor, nec suscipit ex ornare semper. Donec aliquet velit varius volutpat vehicula. Pellentesque interdum urna ac turpis gravida, quis rhoncus ligula ornare.\n<br><br>\nCurabitur vulputate tellus ac mauris pellentesque euismod. Nulla gravida erat nulla, non dictum ipsum faucibus in. In hac habitasse platea dictumst. Morbi libero nulla, laoreet sit amet sagittis et, semper ut ex. Integer eleifend ac justo porta semper. Suspendisse bibendum turpis et odio ullamcorper tincidunt. Sed at lectus est. Pellentesque semper eros arcu, et aliquet mi eleifend et. Donec quis ipsum condimentum, tristique massa a, bibendum turpis. Nunc volutpat tristique urna a hendrerit. In hac habitasse platea dictumst. Nam ligula sem, ultricies non condimentum at, tempus eget sapien. Nulla facilisi. Morbi sit amet sodales nulla, ut dapibus velit.\n<br><br>\nQuisque at leo vel lacus iaculis pharetra. Cras rhoncus eros sit amet nulla fermentum, eget tempor est imperdiet. Sed placerat nec felis eu maximus. Mauris ut purus gravida, rhoncus justo vel, iaculis purus. Nam vitae arcu nisl. Pellentesque efficitur est nulla, quis sollicitudin tellus venenatis eget. In pellentesque mi eu luctus porta. Vestibulum ultricies ex nisl, in scelerisque metus egestas vitae. Fusce suscipit orci quam, sed ornare lorem imperdiet sit amet. In non viverra sapien.\n<br><br>\nIn blandit turpis nec sapien dictum pulvinar. Donec venenatis non tellus eget venenatis. Donec lobortis ipsum sed nulla lacinia, eu pretium eros pretium. Sed volutpat ante et dolor vulputate tempus. Morbi erat felis, viverra vel elit eget, pretium efficitur tellus. Duis malesuada eget ipsum eget placerat. In aliquet felis nisi, ut luctus metus interdum ac. Sed nec egestas magna. Ut aliquet, ante vitae ullamcorper dignissim, ipsum leo fringilla ligula, sed accumsan odio ante ac risus. Vivamus dolor nunc, porta vel neque id, tristique fringilla ex. Vivamus sit amet sapien eu nulla tincidunt viverra nec nec lorem. Integer nibh dolor, varius sit amet nisl vitae, euismod gravida nulla. Nullam volutpat volutpat luctus. Praesent fermentum sapien semper, lacinia augue sed, viverra odio. Maecenas vel arcu volutpat magna gravida imperdiet. Proin eleifend tincidunt diam, vel elementum arcu.\n<br><br>\nNunc tempor vel tortor ac lacinia. Phasellus feugiat feugiat lorem, vel rhoncus dui aliquet ut. Nam ultricies placerat nibh vitae congue. Vestibulum fermentum gravida sem, eget rhoncus urna efficitur ac. Aenean eget ligula ipsum. Aenean vel fermentum mauris. Duis volutpat ligula diam, in sollicitudin lacus ullamcorper eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce elit lacus, posuere ac venenatis quis, sollicitudin vel eros. Mauris laoreet elit non sem tempus, non ornare mi luctus. In viverra porttitor nisi a viverra.\n<br><br>\nUt metus nunc, semper eu dui nec, laoreet sollicitudin ante. Fusce iaculis velit eget feugiat blandit. Curabitur eu mollis sem. Curabitur vehicula sapien in volutpat semper. Nullam fermentum, lectus et blandit scelerisque, dolor odio hendrerit ligula, eu iaculis ex velit ac urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam lobortis porttitor odio, ac vestibulum urna elementum ac. Sed sem sem, blandit consectetur lorem id, convallis rhoncus diam. In mi tortor, malesuada et nulla eget, mattis varius ligula. Ut dignissim molestie lorem. In bibendum pharetra leo in mattis.\n<br><br>\nDuis et erat sit amet ex dictum accumsan at eu quam. Morbi eget tempor eros, eu tempus neque. Donec lacinia, ante id porta pellentesque, arcu augue dictum nibh, quis pretium est turpis sed eros. Cras et arcu id tellus venenatis luctus. Aenean id nisi ut magna scelerisque finibus. Ut non erat id magna euismod interdum. Proin urna mauris, suscipit quis velit condimentum, tincidunt convallis neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Curabitur tortor elit, aliquam ac tincidunt efficitur, elementum eget tortor. Maecenas ligula ipsum, rhoncus id accumsan vel, elementum nec sapien. Mauris neque dolor, vehicula quis magna eu, laoreet eleifend risus. Vivamus sed sem sapien. Aliquam erat volutpat. Integer tristique porttitor est. Donec maximus fermentum diam nec bibendum.\n<br><br>\nNam in mauris nec est rutrum vestibulum. Fusce consequat pulvinar tincidunt. Proin at sem vel est euismod semper vel ac mi. Mauris posuere sapien a efficitur semper. Nulla facilisi. Duis condimentum hendrerit luctus. Pellentesque mattis hendrerit libero, id vestibulum metus rhoncus vel. Etiam ut ante nulla. Aliquam eget urna quam.\n<br><br>\nNunc ut eros sed dolor elementum lacinia non quis tortor. Nulla facilisi. Morbi blandit pharetra augue aliquet lobortis. Donec nisi lorem, lacinia quis tempus at, viverra nec nibh. Ut luctus placerat mi id commodo. Etiam lorem magna, varius eu ex eu, suscipit laoreet velit. Nullam posuere nunc vel lacinia porta. Praesent porttitor lectus luctus, fringilla ipsum ac, semper elit. Cras rutrum scelerisque arcu, eu venenatis lectus consectetur eu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae;\n<br><br>\n<h4>2- Random title</h4>\n\nAliquam non suscipit nisl. Donec posuere mollis dignissim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent leo turpis, tempor at massa et, dapibus accumsan ex. Etiam sed consequat eros, ac ornare magna. Sed leo magna, facilisis vitae metus eget, mattis vestibulum felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Proin mauris risus, sodales tempor neque sit amet, posuere volutpat mauris. Aliquam facilisis imperdiet quam, sed cursus quam pretium ut. Donec interdum libero vitae fermentum lobortis. Nullam tincidunt risus vitae nunc auctor porta. Nulla nec nulla bibendum, dapibus nisi vel, consequat leo. In congue accumsan nunc ut tincidunt. Nullam ut odio dapibus, tristique orci sed, maximus eros.\n<br><br>\nDonec tincidunt, ipsum eu consequat varius, justo tortor laoreet nunc, sed blandit felis ligula quis est. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In viverra dolor massa, id pellentesque tortor porttitor eu. Nunc venenatis non eros et lacinia. Suspendisse finibus nunc sed erat ullamcorper luctus. Sed ultricies placerat sollicitudin. Maecenas nisl ante, venenatis at nisi ut, varius consectetur tortor. Etiam vitae vehicula erat. Pellentesque orci tellus, interdum sed lorem a, varius aliquam justo. Etiam ac tincidunt quam, ac accumsan turpis. Maecenas laoreet sapien at commodo tincidunt. Cras at rutrum dolor. Sed blandit diam ac lacus commodo, a aliquam erat euismod. Nam ac metus ex. Aenean hendrerit interdum eros at condimentum.\n<br><br>\nUt et risus id est aliquet laoreet vel sit amet eros. Nullam dui nisl, bibendum et lobortis vitae, commodo eu eros. Vivamus ac magna eleifend, tempus justo a, lobortis justo. Mauris a tincidunt ante, ut pharetra erat. Donec ante metus, accumsan sed neque quis, pulvinar sagittis metus. Nullam quis convallis nisl, non tempus purus. Pellentesque non felis eget velit posuere finibus. Nullam arcu metus, tincidunt et gravida id, ultricies nec erat. Phasellus gravida velit vitae fringilla maximus. Donec vestibulum pulvinar mollis. Curabitur sit amet aliquam dui. Donec at eros pretium est ullamcorper laoreet. Nulla interdum accumsan justo, eget auctor ipsum posuere nec. Donec vitae elit a justo facilisis lobortis. Nam eu libero eu lacus varius luctus a sed velit. Praesent in augue augue.\n', 1),
(2, 'privacy-policy', '<h4>1- Write the terms of service for your website.</h4>  \n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam hendrerit sem quis urna commodo gravida ac eget diam. Sed tristique id quam auctor iaculis. Donec eget volutpat elit. Curabitur in enim eu nulla aliquet pellentesque nec quis neque. Maecenas ac nulla convallis, aliquam dui posuere, dignissim mi. Maecenas iaculis arcu vitae velit aliquet, vitae blandit ante mattis. Mauris varius sollicitudin purus, mattis blandit urna consequat condimentum. Curabitur vulputate sem finibus ipsum eleifend, et convallis felis egestas. Quisque condimentum faucibus maximus.\n<br><br>\nVestibulum sagittis, ex non condimentum lacinia, lorem libero ornare elit, quis placerat sapien nisi ac eros. Mauris in justo porta, auctor ante non, pulvinar enim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sit amet pulvinar leo. Duis ut velit vitae felis bibendum feugiat. Donec urna orci, dapibus ut erat non, pellentesque cursus tellus. Nullam accumsan rhoncus felis, vel porta tortor suscipit pellentesque. Cras non ante ac orci elementum mattis vel et metus. Proin luctus consequat suscipit. Quisque pharetra lorem augue, nec aliquet nulla aliquam quis. Phasellus ac dictum dui. Etiam mollis ultrices dolor, nec suscipit ex ornare semper. Donec aliquet velit varius volutpat vehicula. Pellentesque interdum urna ac turpis gravida, quis rhoncus ligula ornare.\n<br><br>\nCurabitur vulputate tellus ac mauris pellentesque euismod. Nulla gravida erat nulla, non dictum ipsum faucibus in. In hac habitasse platea dictumst. Morbi libero nulla, laoreet sit amet sagittis et, semper ut ex. Integer eleifend ac justo porta semper. Suspendisse bibendum turpis et odio ullamcorper tincidunt. Sed at lectus est. Pellentesque semper eros arcu, et aliquet mi eleifend et. Donec quis ipsum condimentum, tristique massa a, bibendum turpis. Nunc volutpat tristique urna a hendrerit. In hac habitasse platea dictumst. Nam ligula sem, ultricies non condimentum at, tempus eget sapien. Nulla facilisi. Morbi sit amet sodales nulla, ut dapibus velit.\n<br><br>\nQuisque at leo vel lacus iaculis pharetra. Cras rhoncus eros sit amet nulla fermentum, eget tempor est imperdiet. Sed placerat nec felis eu maximus. Mauris ut purus gravida, rhoncus justo vel, iaculis purus. Nam vitae arcu nisl. Pellentesque efficitur est nulla, quis sollicitudin tellus venenatis eget. In pellentesque mi eu luctus porta. Vestibulum ultricies ex nisl, in scelerisque metus egestas vitae. Fusce suscipit orci quam, sed ornare lorem imperdiet sit amet. In non viverra sapien.\n<br><br>\nIn blandit turpis nec sapien dictum pulvinar. Donec venenatis non tellus eget venenatis. Donec lobortis ipsum sed nulla lacinia, eu pretium eros pretium. Sed volutpat ante et dolor vulputate tempus. Morbi erat felis, viverra vel elit eget, pretium efficitur tellus. Duis malesuada eget ipsum eget placerat. In aliquet felis nisi, ut luctus metus interdum ac. Sed nec egestas magna. Ut aliquet, ante vitae ullamcorper dignissim, ipsum leo fringilla ligula, sed accumsan odio ante ac risus. Vivamus dolor nunc, porta vel neque id, tristique fringilla ex. Vivamus sit amet sapien eu nulla tincidunt viverra nec nec lorem. Integer nibh dolor, varius sit amet nisl vitae, euismod gravida nulla. Nullam volutpat volutpat luctus. Praesent fermentum sapien semper, lacinia augue sed, viverra odio. Maecenas vel arcu volutpat magna gravida imperdiet. Proin eleifend tincidunt diam, vel elementum arcu.\n<br><br>\nNunc tempor vel tortor ac lacinia. Phasellus feugiat feugiat lorem, vel rhoncus dui aliquet ut. Nam ultricies placerat nibh vitae congue. Vestibulum fermentum gravida sem, eget rhoncus urna efficitur ac. Aenean eget ligula ipsum. Aenean vel fermentum mauris. Duis volutpat ligula diam, in sollicitudin lacus ullamcorper eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce elit lacus, posuere ac venenatis quis, sollicitudin vel eros. Mauris laoreet elit non sem tempus, non ornare mi luctus. In viverra porttitor nisi a viverra.\n<br><br>\nUt metus nunc, semper eu dui nec, laoreet sollicitudin ante. Fusce iaculis velit eget feugiat blandit. Curabitur eu mollis sem. Curabitur vehicula sapien in volutpat semper. Nullam fermentum, lectus et blandit scelerisque, dolor odio hendrerit ligula, eu iaculis ex velit ac urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam lobortis porttitor odio, ac vestibulum urna elementum ac. Sed sem sem, blandit consectetur lorem id, convallis rhoncus diam. In mi tortor, malesuada et nulla eget, mattis varius ligula. Ut dignissim molestie lorem. In bibendum pharetra leo in mattis.\n<br><br>\nDuis et erat sit amet ex dictum accumsan at eu quam. Morbi eget tempor eros, eu tempus neque. Donec lacinia, ante id porta pellentesque, arcu augue dictum nibh, quis pretium est turpis sed eros. Cras et arcu id tellus venenatis luctus. Aenean id nisi ut magna scelerisque finibus. Ut non erat id magna euismod interdum. Proin urna mauris, suscipit quis velit condimentum, tincidunt convallis neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Curabitur tortor elit, aliquam ac tincidunt efficitur, elementum eget tortor. Maecenas ligula ipsum, rhoncus id accumsan vel, elementum nec sapien. Mauris neque dolor, vehicula quis magna eu, laoreet eleifend risus. Vivamus sed sem sapien. Aliquam erat volutpat. Integer tristique porttitor est. Donec maximus fermentum diam nec bibendum.\n<br><br>\nNam in mauris nec est rutrum vestibulum. Fusce consequat pulvinar tincidunt. Proin at sem vel est euismod semper vel ac mi. Mauris posuere sapien a efficitur semper. Nulla facilisi. Duis condimentum hendrerit luctus. Pellentesque mattis hendrerit libero, id vestibulum metus rhoncus vel. Etiam ut ante nulla. Aliquam eget urna quam.\n<br><br>\nNunc ut eros sed dolor elementum lacinia non quis tortor. Nulla facilisi. Morbi blandit pharetra augue aliquet lobortis. Donec nisi lorem, lacinia quis tempus at, viverra nec nibh. Ut luctus placerat mi id commodo. Etiam lorem magna, varius eu ex eu, suscipit laoreet velit. Nullam posuere nunc vel lacinia porta. Praesent porttitor lectus luctus, fringilla ipsum ac, semper elit. Cras rutrum scelerisque arcu, eu venenatis lectus consectetur eu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae;\n<br><br>\n<h4>2- Random title</h4>\n\nAliquam non suscipit nisl. Donec posuere mollis dignissim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent leo turpis, tempor at massa et, dapibus accumsan ex. Etiam sed consequat eros, ac ornare magna. Sed leo magna, facilisis vitae metus eget, mattis vestibulum felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Proin mauris risus, sodales tempor neque sit amet, posuere volutpat mauris. Aliquam facilisis imperdiet quam, sed cursus quam pretium ut. Donec interdum libero vitae fermentum lobortis. Nullam tincidunt risus vitae nunc auctor porta. Nulla nec nulla bibendum, dapibus nisi vel, consequat leo. In congue accumsan nunc ut tincidunt. Nullam ut odio dapibus, tristique orci sed, maximus eros.\n<br><br>\nDonec tincidunt, ipsum eu consequat varius, justo tortor laoreet nunc, sed blandit felis ligula quis est. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In viverra dolor massa, id pellentesque tortor porttitor eu. Nunc venenatis non eros et lacinia. Suspendisse finibus nunc sed erat ullamcorper luctus. Sed ultricies placerat sollicitudin. Maecenas nisl ante, venenatis at nisi ut, varius consectetur tortor. Etiam vitae vehicula erat. Pellentesque orci tellus, interdum sed lorem a, varius aliquam justo. Etiam ac tincidunt quam, ac accumsan turpis. Maecenas laoreet sapien at commodo tincidunt. Cras at rutrum dolor. Sed blandit diam ac lacus commodo, a aliquam erat euismod. Nam ac metus ex. Aenean hendrerit interdum eros at condimentum.\n<br><br>\nUt et risus id est aliquet laoreet vel sit amet eros. Nullam dui nisl, bibendum et lobortis vitae, commodo eu eros. Vivamus ac magna eleifend, tempus justo a, lobortis justo. Mauris a tincidunt ante, ut pharetra erat. Donec ante metus, accumsan sed neque quis, pulvinar sagittis metus. Nullam quis convallis nisl, non tempus purus. Pellentesque non felis eget velit posuere finibus. Nullam arcu metus, tincidunt et gravida id, ultricies nec erat. Phasellus gravida velit vitae fringilla maximus. Donec vestibulum pulvinar mollis. Curabitur sit amet aliquam dui. Donec at eros pretium est ullamcorper laoreet. Nulla interdum accumsan justo, eget auctor ipsum posuere nec. Donec vitae elit a justo facilisis lobortis. Nam eu libero eu lacus varius luctus a sed velit. Praesent in augue augue.\n', 1),
(3, 'about-us', '<h4>1- Write about your website here.</h4>  \n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Integer tristique orci quis tortor semper, at interdum ipsum malesuada. Etiam vel vulputate sem. Mauris lorem leo, interdum id leo at, efficitur iaculis justo. Praesent faucibus venenatis finibus. Nullam interdum odio non vehicula fringilla. Nunc sagittis tellus lorem, id sagittis nisi consectetur quis. Proin non orci vitae quam facilisis feugiat eget eget metus. Vestibulum at odio eu leo faucibus dictum id ac felis. Quisque blandit purus nec maximus auctor. Sed varius ullamcorper lectus, hendrerit convallis ligula lacinia vitae.\n<br><br>\nPhasellus at congue nisl. Suspendisse mollis in sapien id cursus. Maecenas eget posuere eros. Donec non dui tortor. Integer ipsum magna, fringilla nec nisl non, mollis porta lectus. Ut eu consequat tellus. Praesent a blandit sapien. Duis volutpat enim interdum mauris elementum, eu suscipit mauris volutpat. Proin nec nibh purus. Vivamus ut venenatis dui. Sed sit amet nulla posuere, ultricies ipsum eget, gravida libero. Nunc dapibus elementum nulla. In condimentum ut nunc eget gravida. Nullam rutrum orci eu auctor varius.\n\n<h4>2- Random title</h4>\n\nAliquam non suscipit nisl. Donec posuere mollis dignissim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent leo turpis, tempor at massa et, dapibus accumsan ex. Etiam sed consequat eros, ac ornare magna. Sed leo magna, facilisis vitae metus eget, mattis vestibulum felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Proin mauris risus, sodales tempor neque sit amet, posuere volutpat mauris. Aliquam facilisis imperdiet quam, sed cursus quam pretium ut. Donec interdum libero vitae fermentum lobortis. Nullam tincidunt risus vitae nunc auctor porta. Nulla nec nulla bibendum, dapibus nisi vel, consequat leo. In congue accumsan nunc ut tincidunt. Nullam ut odio dapibus, tristique orci sed, maximus eros.\n<br><br>\nDonec tincidunt, ipsum eu consequat varius, justo tortor laoreet nunc, sed blandit felis ligula quis est. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In viverra dolor massa, id pellentesque tortor porttitor eu. Nunc venenatis non eros et lacinia. Suspendisse finibus nunc sed erat ullamcorper luctus. Sed ultricies placerat sollicitudin. Maecenas nisl ante, venenatis at nisi ut, varius consectetur tortor. Etiam vitae vehicula erat. Pellentesque orci tellus, interdum sed lorem a, varius aliquam justo. Etiam ac tincidunt quam, ac accumsan turpis. Maecenas laoreet sapien at commodo tincidunt. Cras at rutrum dolor. Sed blandit diam ac lacus commodo, a aliquam erat euismod. Nam ac metus ex. Aenean hendrerit interdum eros at condimentum.\n<br><br>\nUt et risus id est aliquet laoreet vel sit amet eros. Nullam dui nisl, bibendum et lobortis vitae, commodo eu eros. Vivamus ac magna eleifend, tempus justo a, lobortis justo. Mauris a tincidunt ante, ut pharetra erat. Donec ante metus, accumsan sed neque quis, pulvinar sagittis metus. Nullam quis convallis nisl, non tempus purus. Pellentesque non felis eget velit posuere finibus. Nullam arcu metus, tincidunt et gravida id, ultricies nec erat. Phasellus gravida velit vitae fringilla maximus. Donec vestibulum pulvinar mollis. Curabitur sit amet aliquam dui. Donec at eros pretium est ullamcorper laoreet. Nulla interdum accumsan justo, eget auctor ipsum posuere nec. Donec vitae elit a justo facilisis lobortis. Nam eu libero eu lacus varius luctus a sed velit. Praesent in augue augue.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `playlists`
--

CREATE TABLE `playlists` (
  `id` int(11) NOT NULL,
  `list_id` varchar(20) NOT NULL,
  `by_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progress`
--

CREATE TABLE `progress` (
  `id` int(11) UNSIGNED NOT NULL,
  `by_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `loaded_progress` int(11) NOT NULL DEFAULT 0,
  `percent_loaded` tinyint(3) NOT NULL DEFAULT 0,
  `last_progress` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `queue`
--

CREATE TABLE `queue` (
  `id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `video_width` int(4) NOT NULL,
  `processing` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reactions`
--

CREATE TABLE `reactions` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1,
  `place` varchar(50) CHARACTER SET utf8 NOT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `replies`
--

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `text` text DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `option` tinyint(2) NOT NULL DEFAULT 0,
  `type` varchar(12) NOT NULL DEFAULT 'video',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requests`
--

CREATE TABLE `requests` (
  `id` int(20) NOT NULL,
  `by_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `session_id` varchar(150) NOT NULL,
  `details` text CHARACTER SET utf8 DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`name`, `value`) VALUES
('analytics_id', ''),
('animation_video', 'on'),
('approve_videos', 'off'),
('authentication', 'on'),
('autoplay', 'on'),
('binary_path', '/usr/local/bin'),
('blocked_users', 'on'),
('carrousel_players', 'on'),
('censor', ''),
('comments_ad', '{\"content\":\"\",\"active\":\"0\"}'),
('comments_load_limit', '16'),
('convert_speed', 'fast'),
('data_load_limit', '16'),
('delete_account', 'on'),
('description', 'Tubeak script is an amazing video sharing and live streaming site'),
('download_type', '1'),
('download_videos', 'on'),
('email', ''),
('embed_video', 'on'),
('enable_watermark', 'on'),
('header_ad', '{\"content\":\"\",\"active\":\"0\"}'),
('history', 'on'),
('icon_favicon', 'themes/default/images/icon-favicon.png'),
('icon_logo', 'themes/default/images/icon-logo.png'),
('keyword', 'tubeak,video sharing, broadcast live'),
('language', 'en'),
('last_sitemap', '0'),
('last_statistics', '0'),
('live_broadcast', 'on'),
('max_queue', '1'),
('max_upload_size', '256000000'),
('name', 'Tubeak'),
('recaptcha', 'off'),
('recaptcha_key', ''),
('server_type', 'smtp'),
('sidebar_ad', '{\"content\":\"\",\"active\":\"0\"}'),
('smtp_encryption', 'tls'),
('smtp_host', ''),
('smtp_password', ''),
('smtp_port', ''),
('smtp_username', ''),
('statistics', '{\"total_videos\":0,\"total_views\":0,\"total_users\":3,\"total_subs\":0,\"total_comments\":0,\"total_likes\":0,\"total_dislikes\":0,\"total_saved\":0}'),
('storyboard', 'on'),
('theme', 'default'),
('title', 'Tubeak'),
('type_carrousel', 'all'),
('upload_limit', '256000000'),
('upload_videos', 'on'),
('user_registration', 'on'),
('validate_email', 'off'),
('verification_badge', 'on'),
('verification_subscribers_cap', '5000'),
('version', 'v1.0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `subscriber_id` int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `type` varchar(12) NOT NULL DEFAULT 'category',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `types`
--

INSERT INTO `types` (`id`, `name`, `type`, `time`) VALUES
(1, 'creators', 'category', 1610740854),
(2, 'gaming', 'category', 1610740854),
(3, 'learning', 'category', 1610740854),
(4, 'music', 'category', 1610740854),
(5, 'news', 'category', 1610740854),
(6, 'sports', 'category', 1610740854),
(7, 'others', 'category', 1610740854),
(8, 'English', 'en', 1610747234),
(9, 'العربية', 'ar', 1610747234),
(10, 'Nederlands', 'nl', 1610747295),
(11, 'Français', 'fr', 1610747295),
(12, 'Deutshc', 'an', 1610747295),
(13, 'Pусский', 'ru', 1610747295),
(14, 'Español', 'es', 1610747295),
(15, 'Türkçe', 'tr', 1610747295);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `ip` varchar(43) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gender` tinyint(1) NOT NULL DEFAULT 1,
  `token` varchar(50) CHARACTER SET latin1 NOT NULL,
  `live_key` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(2) CHARACTER SET latin1 NOT NULL DEFAULT 'en',
  `dark` tinyint(1) NOT NULL DEFAULT 0,
  `avatar` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT 'images/default-avatar.jpg',
  `cover` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT 'images/default-cover.jpg',
  `watermark` text CHARACTER SET utf8 DEFAULT NULL,
  `country` int(11) NOT NULL DEFAULT 0,
  `date_birthday` int(50) NOT NULL DEFAULT 0,
  `about` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `mail_contact` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `facebook` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `twitter` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `instagram` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `role` tinyint(1) NOT NULL DEFAULT 0,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `cover_changed` int(11) NOT NULL DEFAULT 0,
  `age_changed` int(11) NOT NULL DEFAULT 0,
  `user_changed` int(11) NOT NULL DEFAULT 0,
  `authentication` tinyint(1) NOT NULL DEFAULT 0,
  `change_email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `uploads` int(11) NOT NULL DEFAULT 0,
  `upload_limit` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `user_id`, `username`, `email`, `ip`, `password`, `first_name`, `last_name`, `gender`, `token`, `live_key`, `language`, `dark`, `avatar`, `cover`, `watermark`, `country`, `date_birthday`, `about`, `mail_contact`, `facebook`, `twitter`, `instagram`, `status`, `role`, `verified`, `cover_changed`, `age_changed`, `user_changed`, `authentication`, `change_email`, `uploads`, `upload_limit`, `time`) VALUES
(1, 'AekYR3PaZQLg9lBw', 'admin', 'admin@demo.com', '::1', '7c222fb2927d828af22f592134e8932480637c0d', '', '', 1, 'd951d7d8ed2fbe7a8f8f6e09e52514cd1d5691b1', 'live_X9mwZE4y5JhF3HeV2Cvo8tlsNnS0pz', 'en', 0, 'images/default-avatar.jpg', 'images/default-cover.jpg', NULL, 0, 1622505600, NULL, '', '', '', '', 1, 1, 0, 0, 0, 0, 0, NULL, 0, '0', 1611596407);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `video_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `by_id` int(11) NOT NULL,
  `short_id` varchar(10) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(32) NOT NULL DEFAULT 'others',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `likes` int(11) NOT NULL DEFAULT 0,
  `dislikes` int(11) NOT NULL DEFAULT 0,
  `privacy` tinyint(1) NOT NULL DEFAULT 0,
  `size` int(50) NOT NULL DEFAULT 0,
  `duration` varchar(12) CHARACTER SET latin1 NOT NULL DEFAULT '00:00',
  `approved` tinyint(1) NOT NULL DEFAULT 1,
  `converted` tinyint(1) NOT NULL DEFAULT 1,
  `adults_only` tinyint(1) NOT NULL DEFAULT 1,
  `broadcast` tinyint(1) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0,
  `path` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `download_key` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `animation` varchar(148) DEFAULT NULL,
  `thumbnail` varchar(128) CHARACTER SET latin1 DEFAULT 'themes/default/images/thumbnail.jpg',
  `thumbnail_draft` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `thumbnail_1` varchar(128) CHARACTER SET latin1 DEFAULT 'themes/default/images/thumbnail.jpg',
  `thumbnail_2` varchar(128) CHARACTER SET latin1 DEFAULT 'themes/default/images/thumbnail.jpg',
  `thumbnail_3` varchar(128) CHARACTER SET latin1 DEFAULT 'themes/default/images/thumbnail.jpg',
  `144p` tinyint(1) NOT NULL DEFAULT 0,
  `240p` tinyint(1) NOT NULL DEFAULT 0,
  `360p` tinyint(1) NOT NULL DEFAULT 0,
  `480p` tinyint(1) NOT NULL DEFAULT 0,
  `720p` tinyint(1) NOT NULL DEFAULT 0,
  `1080p` tinyint(1) NOT NULL DEFAULT 0,
  `1440p` tinyint(1) NOT NULL DEFAULT 0,
  `2160p` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `views`
--

CREATE TABLE `views` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `fingerprint` varchar(40) DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `watch_later`
--

CREATE TABLE `watch_later` (
  `id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `words`
--

CREATE TABLE `words` (
  `word` varchar(160) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `en` text DEFAULT NULL,
  `ar` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `nl` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `fr` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `an` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ru` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `es` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `tr` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `words`
--

INSERT INTO `words` (`word`, `en`, `ar`, `nl`, `fr`, `an`, `ru`, `es`, `tr`) VALUES
('404_description', 'The page you were looking for doesn\'t exist.', 'الصفحة التي كنت تبحث عنها غير موجودة.', 'De pagina die u zocht, bestaat niet.', 'La page que vous recherchiez n\'existe pas.', 'Die Seite, die Sie gesucht haben, existiert nicht.', 'Страница, которую вы искали, не существует.', 'La página que buscabas no existe.', 'Aradığınız sayfa mevcut değil.'),
('404_title', '404, page not found', '404، لم يتم العثور على الصفحة', '404 pagina niet gevonden', '404 Page non trouvée', '404 Seite nicht gefunden', '404 Страница не найдена', '404 Pagina no encontrada', '404 Sayfa Bulunamadı'),
('about_us', 'About us', 'معلومات عنا', 'Over ons', 'À propos de nous', 'Über uns', 'О нас', 'Sobre nosotros', 'Hakkımızda'),
('access_denied', 'Access Denied!', 'تم الرفض!', 'Toegang geweigerd!', 'Accès refusé!', 'Zugriff verweigert!', 'Доступ не разрешен!', '¡Acceso denegado!', 'Accès refusé!'),
('account_is_not_active', 'Your account is not active yet, please confirm your E-mail.', 'حسابك غير نشط حتى الآن، يرجى تأكيد بريدك الإلكتروني.', 'Uw account is nog niet actief, bevestig alstublieft uw e-mail.', 'Votre compte n\'est pas encore actif, confirmez votre e-mail.', 'Ihr Konto ist noch nicht aktiv, bitte bestätigen Sie Ihre E-Mail.', 'Ваша учетная запись еще не активирована, пожалуйста, подтвердите свой E-mail.', 'Su correo aún no se ha podido verificar, por favor confirme su email.', 'Hesabınız henüz aktif değil, lütfen e-postanızı onaylayın.'),
('account_settings', 'Account settings', 'إعدادت الحساب', 'Account instellingen', 'Paramètres du compte', 'Kontoeinstellungen', 'Настройки аккаунта', 'Ajustes de la cuenta', 'Настройки аккаунта'),
('account_was_deactivated_owner_email_related', 'This account was deactivated by the owner of the email related to this account.', 'تم إلغاء تنشيط هذا الحساب من قبل مالك البريد الإلكتروني المرتبط بهذا الحساب.', 'Dit account is gedeactiveerd door de eigenaar van de e-mail met betrekking tot dit account.', 'Ce compte a été désactivé par le propriétaire de l\'e-mail associé à ce compte.', 'Dieses Konto wurde vom Eigentümer der mit diesem Konto verbundenen E-Mail deaktiviert.', 'Эта учетная запись была деактивирована владельцем электронной почты, связанной с этой учетной записью.', 'Esta cuenta fue desactivada por el propietario del correo relacionado a esta cuenta.', 'Bu hesap, bu hesapla ilgili e-postanın sahibi tarafından devre dışı bırakıldı.'),
('action', 'Action', 'عمل', 'actie', 'Action', 'Aktion', 'действие', 'Acción', 'eylem'),
('activated', 'Activated', 'تنشيط', 'Geactiveerd', 'Sur', 'Auf', 'активированный', 'Activada', 'Aktive'),
('active', 'Active', 'نشيط', 'Actief', 'actif', 'Aktiv', 'активный', 'Activo', 'Aktif'),
('add_language', 'Add Language', 'إضافة لغة', 'Taal toevoegen', 'Ajouter une langue', 'Sprache hinzufügen', 'Добавить язык', 'Agregar idioma', 'Dil Ekle'),
('add_language_words', 'Add language and words', 'أضف اللغة والكلمات', 'Voeg taal en woorden toe', 'Ajouter une langue et des mots', 'Fügen Sie Sprache und Wörter hinzu', 'Добавить язык и слова', 'Agregar idioma y palabras', 'Dil ve kelime ekleyin'),
('add_new_category', 'Add new category', 'إضافة فئة جديدة', 'Voeg een nieuwe categorie toe', 'Ajouter une nouvelle catégorie', 'Neue Kategorie hinzufügen', 'Добавить новую категорию', 'Agregar nueva categoría', 'Yeni Kategori Ekle'),
('add_new_language', 'Add New Language', 'أضف لغة جديدة', 'Nieuwe taal toevoegen', 'Ajouter une nouvelle langue', 'Neue Sprache hinzufügen', 'Добавить новый язык', 'Agregar nuevo idioma', 'Yeni Dil Ekle'),
('add_new_language_website_make', 'Add a new language to your website and make it easier for more people to access', 'أضف لغة جديدة إلى موقع الويب الخاص بك واجعل من السهل على المزيد من الأشخاص الوصول إليه', 'Voeg een nieuwe taal toe aan uw website en maak het gemakkelijker voor meer mensen om toegang te krijgen', 'Ajoutez une nouvelle langue à votre site Web et facilitez l\'accès d\'un plus grand nombre de personnes', 'Fügen Sie Ihrer Website eine neue Sprache hinzu und erleichtern Sie mehr Personen den Zugriff', 'Добавьте новый язык на свой сайт и упростите доступ большему количеству людей', 'Agrega un nuevo lenguaje para tu sitio web y facilita el acceso a más personas', 'Web sitenize yeni bir dil ekleyin ve daha fazla kişinin erişmesini kolaylaştırın'),
('add_new_word', 'Add new word', 'أضف كلمة جديدة', 'Voeg een nieuw woord toe', 'Ajouter un nouveau mot', 'Neues Wort hinzufügen', 'Добавить новое слово', 'Agregar nueva palabra', 'Добавить новое слово'),
('add_new_word_languages', 'Add a new word to your languages', 'أضف كلمة جديدة إلى لغاتك', 'Voeg een nieuw woord toe aan uw talen', 'Ajoutez un nouveau mot à vos langues', 'Fügen Sie Ihren Sprachen ein neues Wort hinzu', 'Добавьте новое слово в свои языки', 'Agrega una nueva palabra a tus languages', 'Dillerinize yeni bir kelime ekleyin'),
('add_to', 'Keep in...', 'حفظ إلى...', 'Opslaan in...', 'Enregistrer dans...', 'Speichern in...', 'Сохранить в...', 'Guardar en...', 'Kaydet...'),
('add_word', 'Add word', 'أضف كلمة', 'Woord toevoegen', 'Ajouter un mot', 'Wort hinzufügen', 'Добавить слово', 'Agregar palabra', 'Kelime ekleyin'),
('added_to', 'Added to', 'تمت الإضافة إلى', 'Lagt til', 'Ajouté à', 'Hinzugefügt zu', 'Добавлено в', 'Agregado a', 'Eklendi'),
('adding_watermark_videos_great_step_increase', 'Adding a watermark to your videos is a great step to publicize your brand and increase the recognition of your channel', 'تعد إضافة علامة مائية إلى مقاطع الفيديو الخاصة بك خطوة رائعة لنشر علامتك التجارية وزيادة التعرف على قناتك', 'Het toevoegen van een watermerk aan je video\'s is een geweldige stap om je merk bekend te maken en de herkenning van je kanaal te vergroten', 'L\'ajout d\'un filigrane à vos vidéos est une excellente étape pour faire connaître votre marque et augmenter la reconnaissance de votre chaîne', 'Das Hinzufügen eines Wasserzeichens zu Ihren Videos ist ein großartiger Schritt, um Ihre Marke bekannt zu machen und die Bekanntheit Ihres Kanals zu erhöhen', 'Добавление водяного знака к вашим видео - отличный шаг к рекламе вашего бренда и повышению узнаваемости вашего канала.', 'Añadir una marca de agua a tus videos es un gran paso para dar a conocer tu marca y aumentar el reconocimiento de tu canal', 'Videolarınıza filigran eklemek, markanızı tanıtmak ve kanalınızın tanınırlığını artırmak için harika bir adımdır.'),
('admin', 'Admin', 'مشرف', 'beheerder', 'Admin', 'Administrator', 'Администратор', 'Administración', 'yönetim'),
('administration_panel', 'Administration panel', 'لوحة الإدارة', 'Administratie paneel', 'Panneau d\'administration', 'Administrationsbereich', 'Панель администратора', 'Panel de administración', 'Yönetim paneli'),
('ads', 'Advertising', 'إعلان', 'Advertising', 'Publicité', 'Werbung', 'реклама', 'Publicidad', 'reklâm'),
('alert_watermark_file', 'PNG or GIF format, 150x150 pixels, 1 MB (or less). Images with one or two colors and a transparent background work best.', 'تنسيق PNG أو GIF ، 150 × 150 بكسل ، 1 ميجابايت (أو أقل). تعمل الصور ذات اللون أو اللونين والخلفية الشفافة بشكل أفضل.', 'PNG- of GIF-indeling, 150x150 pixels, 1 MB (of minder). Afbeeldingen met een of twee kleuren en een transparante achtergrond werken het beste.', 'Format PNG ou GIF, 150x150 pixels, 1 Mo (ou moins). Les images avec une ou deux couleurs et un fond transparent fonctionnent mieux.', 'PNG- oder GIF-Format, 150 x 150 Pixel, 1 MB (oder weniger). Bilder mit einer oder zwei Farben und einem transparenten Hintergrund funktionieren am besten.', 'Формат PNG или GIF, 150x150 пикселей, 1 МБ (или меньше). Лучше всего подойдут изображения с одним или двумя цветами и с прозрачным фоном.', 'Formato PNG o GIF, 150x150 píxeles, 1 MB (o menos). Las imágenes con uno o dos colores y con un fondo transparente funcionan mejor.', 'PNG veya GIF biçimi, 150x150 piksel, 1 MB (veya daha az). Bir veya iki renkli ve şeffaf bir arka plana sahip resimler en iyi sonucu verir.'),
('all', 'All', 'الكل', 'Allemaal', 'Tout', 'Alles', 'Все', 'Todos', 'Herşey'),
('all_languages', 'All languages', 'كل اللغات', 'Alle talen', 'Toutes les langues', 'Alle Sprachen', 'Все языки', 'Todos los idiomas', 'Toutes les langues'),
('all_rights_reserved', 'All rights reserved', 'كل الحقوق محفوظة', 'Alle rechten voorbehouden', 'Tous droits réservés', 'Alle Rechte vorbehalten', 'Все права защищены', 'Todos los derechos reservados', 'Tüm hakları Saklıdır'),
('all_users', 'All users', 'جميع المستخدمين', 'Alle gebruikers', 'Tous les utilisateurs', 'Alle Nutzer', 'Все пользователи', 'Todos los usuarios', 'Tüm kullanıcılar'),
('all_video', 'All video', 'كل الفيديو', 'Alle video', 'Toutes les vidéos', 'Alle Videos', 'Все видео', 'Todo el video', 'Tüm video'),
('all_videos', 'All Videos', 'Toutes les vidéos', 'Alle video\'s', 'Toutes les vidéos', 'Alle Videos', 'Все видео', 'Todos los videos', 'Tüm videolar'),
('already_have_account', 'Already have an account?', 'هل لديك حساب بالفعل؟', 'Heeft u al een account?', 'Vous avez déjà un compte?', 'Hast du schon ein Konto?', 'У вас уже есть учетная запись?', '¿Ya tienes una cuenta?', 'Zaten hesabınız var mı?'),
('already_logged_in', 'Already logged in', 'قمت بتسجيل الدخول بالفعل', 'Al ingelogd', 'Déjà connecté', 'Bereits angemeldet', 'Уже авторизован', 'Ya ha iniciado sesión', 'Zaten giriş yapılmış'),
('analytics', 'Analytics', 'تحليلات', 'Analytics', 'Analytique', 'Analytics', 'аналитика', 'Analítica', 'analitik'),
('animated_thumbnail', 'Animated thumbnail', 'صورة مصغرة متحركة', 'Geanimeerde miniatuur', 'Vignette animée', 'Animiertes Miniaturbild', 'Анимированная миниатюра', 'Miniatura animada', 'Animasyonlu küçük resim'),
('answers', 'answers', 'إجابات', 'antwoorden', 'réponses', 'Antworten', 'ответы', 'respuestas', 'ответы'),
('applied_locks', 'Applied locks', 'الأقفال التطبيقية', 'Verrous appliqués', 'Verrous appliqués', 'Angewandte Schlösser', 'Прикладные замки', 'Bloqueos aplicados', 'Uygulanan kilitler'),
('apply_for', 'apply for', 'التقدم بطلب للحصول', 'solliciteren naar', 'demander', 'bewerben für', 'подать заявку на', 'solicitar', 'için başvur'),
('approve_videos_before_publishing', 'Approve videos before publishing', 'الموافقة على مقاطع الفيديو قبل النشر', 'Keur video\'s goed voordat ze worden gepubliceerd', 'Approuver les vidéos avant la publication', 'Genehmigen Sie Videos vor dem Veröffentlichen', 'Утвердить видео перед публикацией', 'Aprobar videos antes de publicarlos', 'Yayınlamadan önce videoları onaylayın'),
('approved', 'Approved', 'وافق', 'Goedgekeurd', 'Approuvé', 'Genehmigt', 'Утверждено', 'Aprobado', 'Onaylandı'),
('april', 'April', 'أبريل', 'April', 'Avril', 'April', 'апреля', 'Abril', 'Nisan'),
('are_you_remove_watermark', 'Are you sure you want to remove your watermark?', 'هل أنت متأكد أنك تريد إزالة العلامة المائية الخاصة بك؟', 'Weet u zeker dat u uw watermerk wilt verwijderen?', 'Voulez-vous vraiment supprimer votre filigrane?', 'Möchten Sie Ihr Wasserzeichen wirklich entfernen?', 'Вы уверены, что хотите удалить водяной знак?', '¿Seguro que deseas eliminar tu marca de agua?', 'Filigranınızı kaldırmak istediğinizden emin misiniz?'),
('are_you_sure_you_want_delete_chat', 'Are you sure that you want to delete the conversation?', 'هل تريد بالتأكيد حذف المحادثة؟', 'Weet je zeker dat je het gesprek wilt verwijderen?', 'Êtes-vous sûr de vouloir supprimer la conversation?', 'Möchten Sie die Unterhaltung wirklich löschen?', 'Вы уверены, что хотите удалить разговор?', '¿Estás seguro de que quieres eliminar la conversación?', 'Sohbeti silmek istediğinizden emin misiniz?'),
('arent_trying_access', 'Aren\'t you the one trying to access?', 'ألست أنت من يحاول الوصول؟', 'Ben jij niet degene die toegang probeert te krijgen?', 'N\'êtes-vous pas celui qui essaie d\'accéder?', 'Versuchen Sie nicht, darauf zuzugreifen?', 'Разве это не вы пытаетесь получить доступ?', '¿No eres tú quien intenta acceder?', 'Erişmeye çalışan sen değil misin?'),
('august', 'August', 'أغسطس', 'Augustus', 'Août', 'August', 'августейший', 'Agosto', 'Ağustos'),
('authentication', 'Two-factor authentication', 'توثيق ذو عاملين', 'Twee-factor-authenticatie', 'Authentification à deux facteurs', 'Zwei-Faktor-Authentifizierung', 'Двухфакторная аутентификация', 'Autenticación de dos factores', 'İki faktörlü kimlik doğrulama'),
('autoplay', 'Autoplay', 'تشغيل تلقائي', 'Automatisch afspelen', 'Lecture automatique', 'Automatisches Abspielen', 'Автовоспроизведение', 'Reproducción Automática', 'Otomatik oynatma'),
('autoplay_message', 'If auto play is enabled, then a suggested video will play.', 'إذا تم تمكين التشغيل التلقائي ، فسيتم تشغيل الفيديو المقترح.', 'Als automatisch afspelen is ingeschakeld, wordt een voorgestelde video afgespeeld.', 'Si la lecture automatique est activée, une vidéo suggérée sera lue.', 'Wenn die automatische Wiedergabe aktiviert ist, wird ein vorgeschlagenes Video abgespielt.', 'Если автоматическое воспроизведение включено, воспроизводится предложенное видео.', 'Si la reproducción automática está habilitada, a continuación se reproducirá un video sugerido.', 'Otomatik oynatma etkinse, önerilen bir video oynatılır.'),
('avatar', 'Avatar', 'الصورة الرمزية', 'avatar', 'Avatar', 'Benutzerbild', 'Аватар', 'Foto', 'Avatar'),
('best_comments', 'Best comments', 'أفضل التعليقات', 'Beste opmerkingen', 'Meilleurs commentaires', 'Beste Kommentare', 'Лучшие комментарии', 'Mejores comentarios', 'En iyi yorumlar'),
('binary_path', 'Path of the FFMPEG and FFPROBE binaries', 'مسار الثنائيات FFMPEG و FFPROBE', 'Pad van de binaire bestanden FFMPEG en FFPROBE', 'Chemin des binaires FFMPEG et FFPROBE', 'Pfad der FFMPEG- und FFPROBE-Binärdateien', 'Путь к двоичным файлам FFMPEG и FFPROBE', 'Ruta de los archivos binarios FFMPEG y FFPROBE', 'FFMPEG ve FFPROBE ikili dosyalarının yolu'),
('block', 'Block', 'منع', 'Blok', 'Bloc', 'Block', 'блок', 'Bloquear', 'Blok'),
('block_user_ip', 'Block user IP', 'منع المستخدم IP', 'Blokkeer het IP-adres van de gebruiker', 'Bloquer l\'IP de l\'utilisateur', 'Benutzer-IP blockieren', 'Заблокировать IP-адрес пользователя', 'Bloquear IP del usuario', 'Kullanıcı IP\'sini engelle'),
('blocked_users', 'Blocked Users', 'مستخدمين محجوبين', 'Geblokkeerde gebruikers', 'Utilisateurs bloqués', 'Blockierte Benutzer', 'Заблокированные пользователи', 'Usuarios bloqueados', 'Engellenmiş kullanıcılar'),
('broadcast_live', 'Broadcast live', 'بث مباشر', 'Live uitzending', 'En direct', 'Liveübertragung', 'В прямом эфире', 'Transmitir en vivo', 'Canlı yayın'),
('browser', 'Browser', 'المتصفح', 'browser', 'Navigateur', 'Browser', 'браузер', 'Navegador', 'Tarayıcı'),
('by_comma', 'Separated by comma,', 'مفصولة بفاصلة ،', 'Gescheiden door komma,', 'Séparé par une virgule,', 'Durch Komma getrennt,', 'Разделенные запятой,', 'Separados por comas,', 'Virgülle ayrılmış,'),
('can_broadcast_seen_children', 'Can this broadcast be seen by children?', 'هل يمكن مشاهدة هذا البث من قبل الأطفال؟', 'Is deze uitzending zichtbaar voor kinderen?', 'Cette émission peut-elle être vue par les enfants?', 'Kann diese Sendung von Kindern gesehen werden?', 'Могут ли дети смотреть эту трансляцию?', '¿Esta transmisión puede ser vista por niños?', 'Bu yayın çocuklar tarafından izlenebilir mi?'),
('can_only_change_age_once', 'You can only change your age once', 'يمكنك تغيير عمرك مرة واحدة فقط', 'U kunt uw leeftijd maar één keer wijzigen', 'Vous ne pouvez changer votre âge qu\'une seule fois', 'Sie können Ihr Alter nur einmal ändern', 'Вы можете изменить свой возраст только один раз', 'Sólo puedes cambiar tu edad una vez', 'Yaşınızı yalnızca bir kez değiştirebilirsiniz'),
('can_video_seen_children', 'Can this video be seen by children?', 'هل يمكن أن يرى الأطفال هذا الفيديو؟', 'Kan deze video door kinderen worden gezien?', 'Cette vidéo peut-elle être vue par des enfants?', 'Kann dieses Video von Kindern gesehen werden?', 'Могут ли дети увидеть это видео?', '¿Este video puede ser visto por niños?', 'Bu videoyu çocuklar izleyebilir mi?'),
('cancel', 'Cancel', 'إلغاء', 'Avbryt', 'Annuler', 'Abbrechen', 'отменить', 'Cancelar', 'iptal'),
('cancel_subscription', 'Cancel subscription', 'إلغاء الاشتراك', 'Unsubscribe', 'Désinscrire', 'Abbestellen', 'Oтказаться от подписки', 'Cancelar suscripción', 'bonelik iptali'),
('carousel_configuration', 'Carousel configuration', 'تكوين الرف الدائري', 'Carrousel configuratie', 'Configuration du carrousel', 'Karussellkonfiguration', 'Конфигурация карусели', 'Configuración del carrusel', 'Atlı karınca yapılandırması'),
('categories', 'Categories', 'الاقسام', 'Categorieën', 'Catégories', 'Kategorien', 'категории', 'Categorías', 'Kategoriler'),
('category', 'Category', 'فئة', 'categorie', 'Catégorie', 'Kategorie', 'категория', 'Categoría', 'kategori'),
('category_added_successfully', 'Category added successfully', 'تمت إضافة الفئة بنجاح', 'Categorie succesvol toegevoegd', 'Catégorie ajoutée avec succès', 'Kategorie erfolgreich hinzugefügt', 'Категория успешно добавлена', 'Categoría agregada con éxito', 'Kategori başarıyla eklendi'),
('category_name', 'Category Name', 'اسم التصنيف', 'categorie naam', 'Nom de catégorie', 'Kategoriename', 'Название категории', 'Nombre de la categoría', 'Kategori adı'),
('category_removed_successfully', 'Category removed successfully', 'تمت إزالة الفئة بنجاح', 'تمت إزالة الفئة بنجاح', 'Catégorie supprimée avec succès', 'Kategorie erfolgreich entfernt', 'Категория успешно удалена', 'Categoría eliminada con éxito', 'Kategori başarıyla kaldırıldı'),
('censor', 'Censored Words', 'الكلمات الخاضعة للرقابة', 'Gecensureerde woorden', 'Mots censurés', 'Zensierte Wörter', 'Цензура Слова', 'Palabras censuradas', 'Sansürlü Kelimeler'),
('change', 'Change', 'تغيير', 'Verandering', 'Changer', 'Ändern Sie', 'изменение', 'Cambiar', 'Değişim'),
('change_general_data_account', 'Change the general data of your account', 'قم بتغيير البيانات العامة لحسابك', 'Wijzig de algemene gegevens van uw account', 'Modifier les données générales de votre compte', 'Ändern Sie die allgemeinen Daten Ihres Kontos', 'Измените общие данные вашего аккаунта', 'Cambia los datos generales de tu cuenta', 'Hesabınızın genel verilerini değiştirin'),
('change_identification_data_account', 'Change the identification data of your account', 'قم بتغيير بيانات التعريف الخاصة بحسابك', 'Wijzig de identificatiegegevens van uw account', 'Modifier les données d\'identification de votre compte', 'Ändern Sie die Identifikationsdaten Ihres Kontos', 'Измените идентификационные данные своей учетной записи', 'Cambia los datos de identificación de tu cuenta', 'Hesabınızın kimlik verilerini değiştirin'),
('change_log', 'Change Log', 'سجل التغيير', 'Wijzigingslogboek', 'Journal des modifications', 'Änderungsprotokoll', 'Журнал изменений', 'Registro de cambios', 'Günlüğü Değiştir'),
('change_password', 'Change Password', 'تغيير كلمة المرور', 'Verander wachtwoord', 'Changer le mot de passe', 'Passwort ändern', 'Сменить пароль', 'Cambiar contraseña', 'Parolayı değiştir'),
('change_your_password', 'change your password', 'غير كلمة المرور الخاصة بك', 'Wijzig uw wachtwoord', 'changez votre mot de passe', 'Ändern Sie Ihr Passwort', 'Изменить пароль', 'cambiar tu contraseña', 'Şifreni değiştir'),
('change_your_password_settings', 'change your password', 'غير كلمة المرور الخاصة بك', 'Wijzig uw wachtwoord', 'changez votre mot de passe', 'Ändern Sie Ihr Passwort', 'Измените свой пароль', 'Cambia tu contraseña', 'Şifreni değiştir'),
('changes_saved_successfully', 'Your changes were saved successfully', 'تم حفظ التغييرات الخاصة بك بنجاح', 'Uw wijzigingen zijn succesvol opgeslagen', 'Vos modifications ont été enregistrées avec succès', 'Ihre Änderungen wurden erfolgreich gespeichert', 'Ваши изменения были успешно сохранены', 'Sus cambios se guardaron con éxito', 'Değişiklikleriniz başarıyla kaydedildi'),
('check_your_email', 'Check your email', 'تحقق من بريدك الالكتروني', 'Controleer uw e-mail', 'Vérifiez votre email', 'Check deine E-Mails', 'Проверьте свою электронную почту', 'Verifica tu correo', 'E-postanı kontrol et'),
('check_your_new_email', 'Check your new email', 'Yeni e-postanızı kontrol edin', 'Controleer uw nieuwe e-mail', 'Vérifiez votre nouveau courriel', 'Überprüfen Sie Ihre neue E-Mail', 'Проверьте свою новую электронную почту', 'Verifica tu nuevo correo electrónico', 'Yeni e-postanızı kontrol edin'),
('choose_image', 'Choose image', 'اختر صورة', 'Kies afbeelding', 'Choisissez une image', 'Bild auswählen', 'Выбрать изображение', 'Elegir imagen', 'Resmi seçin'),
('cine_mode', 'Cinema mode', 'وضع السينما', 'Bioscoopmodus', 'Mode cinéma', 'Kinomodus', 'Режим кино', 'Modo cine', 'Sinema modu'),
('close', 'Close', 'قريب', 'Lukk', 'Fermer', 'Schließen', 'Закрыть', 'Cerrar', 'yakın'),
('comment', 'Comment', 'التعليق على', 'Reageren op', 'Commentaire sur', 'Kommentar zu', 'Комментировать', 'Comentar', 'Açıklama'),
('comment_deleted', 'Comment deleted', 'التعليق المحذوفة', 'Reactie verwijderd', 'Commentaire supprimé', 'Kommentar gelöscht', 'Комментарий удален', 'Comentario eliminado', 'Yorum silindi'),
('comment_has_been_removed', 'The comment has been removed', 'تم حذف التعليق', 'De opmerking is verwijderd', 'Le commentaire a été supprimé', 'Der Kommentar wurde entfernt', 'Комментарий удален', 'El comentario se ha eliminado', 'Yorum kaldırıldı'),
('comment_modified', 'Comment modified', 'تم تعديل التعليق', 'Reactie gewijzigd', 'Commentaire modifié', 'Kommentar geändert', 'Комментарий изменен', 'Comentario modificado', 'Yorum değiştirildi'),
('comment_pinned', 'Comment posted', 'تم نشر التعليق', 'Reactie geplaatst', 'Commentaire publié', 'Kommentar gepostet', 'Комментарий размещен', 'Se fijó el comentario', 'Yorum gönderildi'),
('comment_unpinned', 'Comment is no longer posted.', 'لم يعد يتم نشر التعليق.', 'Reactie is niet langer geplaatst.', 'Le commentaire n\'est plus publié.', 'Kommentar wird nicht mehr gepostet.', 'Комментарий больше не публикуется.', 'El comentario ya no está fijado', 'Yorum artık yayınlanmadı.'),
('comment_was_modified', 'Your comment was successfully modified!', 'تم تعديل تعليقك بنجاح!', 'Uw reactie is succesvol gewijzigd!', 'Votre commentaire a été modifié', 'Votre commentaire a été modifié avec succès!', 'Ваш комментарий был успешно изменен!', '¡Su comentario se modificó con exito!', 'Yorumunuz başarıyla değiştirildi!'),
('commentary', 'Commentary', 'تعليق', 'Commentaar', 'Commentaire', 'Kommentar', 'Комментарий', 'Comentario', 'Yorum'),
('commented_on_your_video', 'commented on your video', 'علق على الفيديو التابع لك', 'kommenterte videoen din', 'a commenté votre vidéo', 'hat dein Video kommentiert', 'прокомментировал ваше видео', 'comentó tu video', 'videonuza yorum yaptı'),
('comments', 'Comments', 'تعليقات', 'Comments', 'commentaires', 'Bemerkungen', 'Комментарии', 'Comentarios', 'Yorumlar'),
('comments_ad', 'Comments Ad', 'إعلان التعليقات', 'Reacties Ad', 'Reacties Ad', 'Kommentare Ad', 'Комментарии Ad', 'Anuncio de comentarios', 'Yorumlar Reklam'),
('compared_day', 'compared to yesterday', 'مقارنة بالأمس', 'vergeleken met gisteren', 'par rapport à hier', 'im Vergleich zu gestern', 'по сравнению со вчерашним днем', 'en comparación con el día de ayer', 'düne kıyasla'),
('compared_day_lastweek', 'compared to the same day last week', 'مقارنة مع نفس اليوم في الأسبوع الماضي', 'vergeleken met dezelfde dag vorige week', 'par rapport au même jour la semaine dernière', 'verglichen mit dem gleichen Tag der letzten Woche', 'по сравнению с тем же днем ​​на прошлой неделе', 'en comparación con el mismo día de la semana pasada', 'geçen hafta aynı güne kıyasla'),
('compared_week', 'Compared to the same period last week', 'مقارنة بنفس الفترة من الأسبوع الماضي', 'Vergeleken met dezelfde periode vorige week', 'Par rapport à la même période la semaine dernière', 'Im Vergleich zum gleichen Zeitraum der letzten Woche', 'По сравнению с аналогичным периодом на прошлой неделе', 'en comparación con el mismo periodo de la semana pasada', 'Geçen hafta aynı döneme kıyasla'),
('configuracion_otras_caracteristicas_de_tu_sitio_web', 'Configure other features of your website', 'تكوين الميزات الأخرى لموقع الويب الخاص بك', 'Configureer andere functies van uw website', 'Configurer d\'autres fonctionnalités de votre site Web', 'Konfigurieren Sie andere Funktionen Ihrer Website', 'Настройте другие функции вашего сайта', 'Configuración otras características de tu sitio web', 'Web sitenizin diğer özelliklerini yapılandırın'),
('configure_customize_player_carousel', 'Configure and customize your player carousel', 'تكوين وتخصيص دائرة لاعب الخاص بك', 'Configureer en pas uw spelerscarrousel aan', 'Configurez et personnalisez votre carrousel de lecteur', 'Konfigurieren und passen Sie Ihr Spielerkarussell an', 'Сконфигурируйте и настройте карусель вашего плеера', 'Configura y personaliza tu carrusel de reproductores', 'Oynatıcı karuselinizi yapılandırın ve özelleştirin'),
('configure_some_options_user', 'Configure some options for the user', 'تكوين بعض الخيارات للمستخدم', 'Configureer enkele opties voor de gebruiker', 'Configurer certaines options pour l\'utilisateur', 'Konfigurieren Sie einige Optionen für den Benutzer', 'Настроить некоторые параметры для пользователя', 'Configura algunas opciones para el usuario', 'Kullanıcı için bazı seçenekleri yapılandırın'),
('configure_your_mail_server', 'Configure your mail server', 'تكوين خادم البريد الخاص بك', 'Configureer uw mailserver', 'Configurez votre serveur de messagerie', 'Konfigurieren Sie Ihren Mailserver', 'Настройте свой почтовый сервер', 'Configura tu servidor de correos', 'Posta sunucunuzu yapılandırın'),
('configure_your_sitemap', 'Configure your sitemap', 'تكوين خريطة الموقع الخاصة بك', 'Configureer uw sitemap', 'Configurez votre plan de site', 'Konfigurieren Sie Ihre Sitemap', 'Настройте карту сайта', 'Configura tu sitemap', 'Site haritanızı yapılandırın'),
('confirm_are_who_trying_enter', 'Confirm that you are the one who is trying to enter.', 'تأكد من أنك الشخص الذي يحاول الدخول.', 'Bevestig dat jij degene bent die probeert binnen te komen.', 'Confirmez que vous êtes bien celui qui essaie d\'entrer.', 'Bestätigen Sie, dass Sie derjenige sind, der versucht einzutreten.', 'Подтвердите, что вы пытаетесь войти.', 'Confirma que eres tú quien intenta ingresar.', 'Girmeye çalışan kişi olduğunuzu onaylayın.'),
('confirm_code', 'Enter your confirmation code', 'أدخل رمز التأكيد الخاص بك', 'Voer uw bevestigingscode in', 'Entrez votre code de confirmation', 'Geben Sie Ihren Bestätigungscode ein', 'Введите Ваш код подтверждения', 'Introduce tu código de confirmación', 'Doğrulama Kodunuzu Girin'),
('confirm_delist', 'Are you sure you want to delete this PlayList?', 'هل تريد بالتأكيد حذف قائمة التشغيل هذه؟', 'Er du sikker på at du vil slette denne PlayList?', 'Êtes-vous sûr de vouloir supprimer cette PlayList?', 'Möchten Sie diese PlayList wirklich löschen?', 'Вы действительно хотите удалить этот список воспроизведения?', '¿Estás seguro de que deseas eliminar esta playlist?', 'Bu Oynatma Listesini silmek istediğinizden emin misiniz?'),
('confirm_new_password', 'Confirm new password', 'تأكيد كلمة المرور الجديدة', 'Bevestig nieuw wachtwoord', 'Confirmer le nouveau mot de passe', 'Bestätige neues Passwort', 'Подтвердите новый пароль', 'Confirmar contraseña nueva', 'Yeni şifreyi onayla'),
('confirm_password', 'Confirm Password', 'تأكيد كلمة المرور', 'bevestig wachtwoord', 'Confirmez le mot de passe', 'Bestätige das Passwort', 'Подтвердите Пароль', 'Confirmar contraseña', 'Şifreyi Onayla'),
('confirmation', 'Confirmation!', 'تأكيد!', 'Bekreftelse!', 'Confirmation!', 'Bestätigung!', 'Подтверждение!', '¡Confirmación!', 'Onay!'),
('connection_successfully_established', 'Connection successfully established!', 'تم الاتصال بنجاح!', 'Verbinding tot stand gebracht!', 'Connexion établie avec succès!', 'Verbindung erfolgreich hergestellt!', 'Соединение успешно установлено!', '¡Conexión establecida con éxito!', 'Bağlantı başarıyla kuruldu!'),
('contact', 'Contact', 'اتصل', 'Contact', 'Contact', 'Kontakt', 'контакт', 'Contacto', 'İletişim'),
('contact_our_helpdesk', 'contact our helpdesk.', 'اتصل بمكتب المساعدة الخاص بنا.', 'neem contact op met onze helpdesk.', 'contactez notre helpdesk.', 'Kontaktieren Sie unseren Helpdesk.', 'свяжитесь с нашей службой поддержки.', 'ponte en contacto con nuestro servicio de asistencia.', 'yardım masamızla iletişime geçin.'),
('contact_us', 'Contact us', 'اتصل بنا', 'Neem contact met ons op', 'Contactez nous', 'Kontaktiere uns', 'Свяжитесь с нами', 'Contáctenos', 'Bizimle iletişime geçin'),
('content_longer_available_because_removed', 'It seems that this content is no longer available, because it was removed by the author :(', 'يبدو أن هذا المحتوى لم يعد متاحًا ، لأنه تمت إزالته بواسطة المؤلف :(', 'Het lijkt erop dat deze inhoud niet langer beschikbaar is, omdat deze is verwijderd door de auteur :(', 'Il semble que ce contenu ne soit plus disponible, car il a été supprimé par l\'auteur :(', 'Es scheint, dass dieser Inhalt nicht mehr verfügbar ist, da er vom Autor entfernt wurde :(', 'Похоже, что этот контент больше недоступен, потому что он был удален автором :(', 'Parece que este contenido ya no está disponible, porque fue eliminado por el autor :(', 'Yazar tarafından kaldırıldığı için bu içeriğin artık mevcut olmadığı anlaşılıyor :('),
('continue', 'Continue', 'استمر', 'Doorgaan met', 'Continuer', 'Fortsetzen', 'Продолжать', 'Continuar', 'Devam et'),
('contribute_to_the_community', 'If you want to contribute to the community you can upload a video', 'إذا كنت ترغب في المساهمة في المجتمع ، يمكنك تحميل الفيديو', 'Als je wilt bijdragen aan de community kun je een video uploaden', 'Si vous souhaitez contribuer à la communauté, vous pouvez télécharger une vidéo', 'Wenn Sie zur Community beitragen möchten, können Sie ein Video hochladen', 'Если вы хотите внести свой вклад в сообщество, вы можете загрузить видео', 'Si deseas contribuir con la comunidad puedes subir un vídeo', 'Topluluğa katkıda bulunmak istiyorsanız bir video yükleyebilirsiniz'),
('convert_video_speed', 'Convert video speed', 'تحويل سرعة الفيديو', 'Converteer videosnelheid', 'Convertir la vitesse vidéo', 'Videogeschwindigkeit konvertieren', 'Преобразование скорости видео', 'Convertir velocidad de video', 'Video hızını dönüştür'),
('copy', 'Copy', 'نسخ', 'Kopiëren', 'Copier', 'Kopieren', 'Копировать', 'Copiar', 'Kopyala'),
('copy_embed_code', 'Copy embed code', 'نسخة تضمين قانون', 'Kopieer de embed code', 'Copier le code d\'intégration', 'Einbettungscode kopieren', 'Скопировать код для вставки', 'Copiar código de inserción', 'Gömme kodunu kopyala'),
('copy_link', 'The link has been copied to the clipboard', 'تم نسخ الرابط إلى الحافظة', 'De link is naar het klembord gekopieerd', 'Le lien a été copié dans le presse-papiers', 'Der Link wurde in die Zwischenablage kopiert', 'Ссылка была скопирована в буфер обмена', 'El enlace se ha copiado en el portapapeles', 'Bağlantı panoya kopyalandı'),
('copy_url_and_configure_cron_task_necessary', 'Copy this url and use it to configure your Cron task, if necessary', 'انسخ عنوان url هذا واستخدمه لتكوين مهمة Cron ، إذا لزم الأمر', 'Kopieer deze url en gebruik deze om uw Cron-taak, indien nodig, te configureren', 'Copiez cette URL et utilisez-la pour configurer votre tâche Cron, si nécessaire', 'Kopieren Sie diese URL und konfigurieren Sie gegebenenfalls Ihre Cron-Aufgabe', 'Скопируйте этот URL-адрес и при необходимости используйте его для настройки задачи Cron.', 'Copia esta url y usala para configurar tu tarea Cron, de ser necesario', 'Bu url\'yi kopyalayın ve gerekirse Cron görevinizi yapılandırmak için kullanın'),
('copy_url_at_current_time', 'Copy video URL at current time', 'انسخ رابط الفيديو في الوقت الحالي', 'Kopieer de video-URL op dit moment', 'Copier l\'URL de la vidéo à l\'heure actuelle', 'Kopiere Video URL zur aktuellen Zeit', 'Скопировать URL видео в текущий момент времени', 'Copiar URL del video en el momento actual', 'Video URL\'sini geçerli zamanda kopyala'),
('copy_video_url', 'Copy the video URL', 'انسخ عنوان URL للفيديو', 'Kopieer de video-URL', 'Copiez l\'URL de la vidéo', 'Kopieren Sie die Video-URL', 'Скопируйте URL видео', 'Copiar la URL del vídeo', 'Video URL\'sini kopyalayın'),
('copyright', 'Copyright © {$year_now} {$settings->name}.', 'حقوق الطبع والنشر © {$year_now} {$settings->name}.', 'Copyright © {$year_now} {$settings->name}.', 'Copyright © {$year_now} {$settings->name}.', 'Copyright © {$year_now} {$settings->name}.', 'Авторские права © {$year_now} {$settings->name}.', 'Copyright © {$year_now} {$settings->name}.', 'Telif hakkı © {$year_now} {$settings->name}.'),
('copyright_', 'Copyright', 'حقوق النشر', 'auteursrechten', 'Droit d\'auteur', 'Urheberrechte ©', 'авторское право', 'Derechos de autor', 'Telif hakkı'),
('could_not_send_message_error', 'Could not send message, error', 'لا يمكن إرسال الرسالة ، خطأ', 'Kan bericht niet verzenden, fout', 'Impossible d\'envoyer le message, erreur', 'Nachricht konnte nicht gesendet werden, Fehler', 'Не удалось отправить сообщение, ошибка', 'No se pudo enviar el mensaje, error', 'Mesaj gönderilemedi, hata'),
('country', 'Country', 'بلد', 'land', 'Pays', 'Land', 'Страна', 'País', 'ülke'),
('cover', 'Cover page', 'جبهة', 'Voorzijde', 'Couverture', 'Cover', 'Обложка', 'Portada', 'Kapak sayfası'),
('create', 'Create', 'خلق', 'Opprett', 'Créer', 'Erstellen', 'создать', 'Crear', 'oluşturmak'),
('create_new_playlist', 'Create a new playlist', 'إنشاء قائمة تشغيل جديدة', 'Opprett ny spilleliste', 'Créer une nouvelle playlist', 'Neue Playlist erstellen', 'Создать новый плейлист', 'Crea una nueva playlist', 'Yeni çalma listesi oluştur'),
('created', 'Created', 'خلقت', 'Gemaakt', 'Créé', 'Erstellt', 'Создано', 'Creada', 'Oluşturuldu'),
('creators', 'Creators', 'المبدعين', 'Makers', 'Créateurs', 'Schöpfer', 'Создатели', 'Creadores', 'Yaratıcılar'),
('cron_job', 'Cron job', 'وظيفة كرون', 'Cron-baan', 'Tâche planifiée', 'Cron-Job', 'Cron работа', 'Cron Job', 'Cron işi'),
('current_password', 'Current password', 'كلمة المرور الحالي', 'Huidig ​​wachtwoord', 'mot de passe actuel', 'Jetziges Passwort', 'Текущий пароль', 'Contraseña actual', 'Şimdiki Şifre'),
('current_password_dont_match', 'Current password doesn\'t match.', 'كلمة المرور الحالية غير متطابقة.', 'Huidig ​​wachtwoord komt niet overeen.', 'Le mot de passe actuel ne correspond pas.', 'Aktuelles Passwort stimmt nicht überein.', 'Текущий пароль не соответствует.', 'La contraseña actual no coincide.', 'Geçerli şifre uyuşmuyor.'),
('custom_start_time', 'Custom start time', 'وقت بدء مخصص', 'Aangepaste starttijd', 'Heure de début personnalisée', 'Benutzerdefinierte Startzeit', 'Настраиваемое время начала', 'Personalizar hora de inicio', 'Özel başlangıç ​​zamanı'),
('customize_watermark_displayed_videos', 'Customize when the watermark should be displayed on your videos.', 'قم بتخصيص وقت عرض العلامة المائية على مقاطع الفيديو الخاصة بك.', 'Pas aan wanneer het watermerk op uw video\'s moet worden weergegeven.', 'Personnalisez le moment où le filigrane doit être affiché sur vos vidéos.', 'Passen Sie an, wann das Wasserzeichen in Ihren Videos angezeigt werden soll.', 'Настройте, когда водяной знак должен отображаться на ваших видео.', 'Personaliza el momento en que se debe mostrar la marca de agua en tus videos.', 'Filigranın videolarınızda ne zaman görüntüleneceğini özelleştirin.'),
('darg_drop_video', 'Drag and drop the file you want to upload.', 'قم بسحب وإسقاط الملف الذي تريد تحميله.', 'Sleep het bestand dat u wilt uploaden.', 'Faites glisser et déposez le fichier que vous souhaitez télécharger.', 'Ziehen Sie die hochzuladende Datei per Drag & Drop.', 'Перетащите файл, который вы хотите загрузить.', 'Arrastra y suelta el archivo que quieras subir.', 'Yüklemek istediğiniz dosyayı sürükleyip bırakın.'),
('dark_theme', 'Dark theme', 'مظهر داكن', 'Donker thema', 'Thème sombre', 'Dunkles Thema', 'Темная тема', 'Tema oscuro', 'Koyu tema'),
('dashboard', 'Dashboard', 'لوحة القيادة', 'Dashboard', 'Tableau de bord', 'Instrumententafel', 'Приборная доска', 'Panel de control', 'gösterge paneli'),
('data_updated_every_15_minutes', 'Data is updated every 15 minutes', 'يتم تحديث البيانات كل 15 دقيقة', 'De gegevens worden elke 15 minuten bijgewerkt', 'Les données sont mises à jour toutes les 15 minutes', 'Die Daten werden alle 15 Minuten aktualisiert', 'Данные обновляются каждые 15 минут', 'Los datos se actualizan cada 15 minutos', 'Veriler her 15 dakikada bir güncellenir'),
('date', 'Date', 'تاريخ', 'Datum', 'Date', 'Datum', 'Дата', 'Fecha', 'Tarih'),
('date_of_birth', 'Date of birth', 'تاريخ الولادة', 'Geboortedatum', 'Date de naissance', 'Geburtsdatum', 'Дата рождения', 'Fecha de nacimiento', 'Doğum tarihi'),
('day', 'day', 'يوم', 'dag', 'journée', 'Tag', 'день', 'día', 'gün'),
('days', 'days', 'أيام', 'dagen', 'journées', 'Tage', 'дней', 'días', 'günler'),
('december', 'December', 'ديسمبر', 'December', 'Décembre', 'Dezember', 'декабрь', 'Diciembre', 'Aralık'),
('default_language', 'Default Language', 'اللغة الافتراضية', 'Standaard taal', 'Langage par défaut', 'Standardsprache', 'Язык по умолчанию', 'Idioma predeterminado', 'Varsayılan dil'),
('delete', 'Delete', 'حذف', 'Slett', 'Supprimer', 'Löschen', 'удалять', 'Eliminar', 'silmek'),
('delete_account', 'Delete account', 'حذف الحساب', 'Account verwijderen', 'Supprimer le compte', 'Konto löschen', 'Удалить аккаунт', 'Eliminar cuenta', 'Hesabı sil'),
('delete_comment', 'Delete comment', 'حذف تعليق', 'Opmerking verwijderen', 'Supprimer le commentaire', 'Kommentar löschen', 'Удалить комментарий', 'Eliminar comentario', 'Yorumu sil'),
('delete_confirmation', 'Are you sure you want to delete your comment?', 'هل تريد بالتأكيد حذف تعليقك؟', 'Weet u zeker dat u uw reactie wilt verwijderen?', 'Êtes-vous sûr de vouloir supprimer votre commentaire?', 'Bist du sicher, dass du deinen Kommentar löschen möchtest?', 'Вы уверены, что хотите удалить свой комментарий?', '¿Seguro que quieres eliminar tu comentario?', 'Yorumu silmek istediğinizden emin misiniz?'),
('delete_report', 'Delete report', 'حذف التقرير', 'Rapport verwijderen', 'Supprimer le rapport', 'Bericht löschen', 'Удалить отчет', 'Eliminar reporte', 'Raporu sil'),
('delete_video_confirmation', 'Are you sure you want to delete this video? This action can\'t be undo', 'هل تريد بالتأكيد حذف هذا الفيديو؟ لا يمكن التراجع عن هذا الإجراء', 'Weet u zeker dat u deze video wilt verwijderen? Deze actie kan niet worden ongedaan gemaakt', 'Êtes-vous sûr de vouloir supprimer cette vidéo? Cette action ne peut pas annuler', 'Sind Sie sicher, dass Sie dieses Video löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden', 'Вы действительно хотите удалить это видео? Это действие не может быть отменено', '¿Seguro que quieres eliminar este vídeo? Esta acción no se puede deshacer', 'Bu videoyu silmek istediğinizden emin misiniz? Bu işlem geri alınamaz'),
('delete_videos', 'Delete video', 'حذف الفيديو', 'Video verwijderen', 'Supprimer la vidéo', 'Video löschen', 'Удалить видео', 'Eliminar vídeo', 'Videoyu sil'),
('delete_word', 'Delete word', 'حذف كلمة', 'Woord verwijderen', 'Supprimer le mot', 'Wort löschen', 'Удалить слово', 'Eliminar palabra', 'Kelimeyi sil'),
('delete_your_account', 'Do you really want to delete your account and thus irretrievably lose all your content?', 'هل تريد حقًا حذف حسابك وبالتالي تفقد المحتوى الخاص بك بشكل لا رجعة فيه؟', 'Wilt u echt uw account verwijderen en zo al uw inhoud onherstelbaar verliezen?', 'Voulez-vous vraiment supprimer votre compte et ainsi perdre irrémédiablement tout votre contenu?', 'Möchten Sie Ihren Account wirklich löschen und damit Ihren gesamten Inhalt unwiederbringlich verlieren?', 'Вы действительно хотите удалить свой аккаунт и таким образом безвозвратно потерять весь ваш контент?', '¿Realmente desea eliminar su cuenta y así perder irremediablemente todo su contenido?', 'Hesabınızı gerçekten silmek ve böylece tüm içeriğinizi geri alınamaz bir şekilde kaybetmek istiyor musunuz?'),
('deleted', 'Deleted!', 'حذف!', 'Slettet!', 'Supprimé!', 'Gelöscht!', 'Удаляется!', '¡Eliminado!', 'Silinen!'),
('desactivated', 'Deactivated', 'غير مفحوص', 'Ongehinderd', 'Désactivée', 'Deaktiviert', 'Hепроверенный', 'Desactivada', 'Kontrolsüz'),
('description', 'Description', 'وصف', 'beschrijving', 'Description', 'Beschreibung', 'Описание', 'Descripción', 'tanım'),
('details', 'Details', 'تفاصيل', 'Gegevens', 'Détails', 'Einzelheiten', 'детали', 'Detalles', 'Ayrıntılar'),
('did_want_reset_password', 'Do you want to reset your password?', 'هل تريد إعادة تعيين كلمة المرور الخاصة بك؟', 'Wil je het wachtwoord resetten?', 'Souhaitez-vous réinitialiser le mot de passe?', 'Wollten Sie das Passwort zurücksetzen?', 'Вы хотели сбросить пароль?', '¿querías restablecer la contraseña?', 'Parolayı sıfırlamak mı istediniz?'),
('didnt_create_this_account', 'Didn\'t create this account?', 'لم تنشئ هذا الحساب؟', 'Heeft u dit account niet aangemaakt?', 'Vous n\'avez pas créé ce compte?', 'Haben Sie dieses Konto nicht erstellt?', 'Не создавали эту учетную запись?', '¿No creaste esta cuenta?', 'Bu hesabı oluşturmadınız mı?'),
('disable', 'Disable', 'تعطيل', 'Disable', 'Désactiver', 'Deaktivieren', 'запрещать', 'Inhabilitar', 'Devre dışı'),
('disabled', 'Disabled', 'معاق', 'invalide', 'Désactivé', 'Deaktiviert', 'Выключено', 'Desactivado', 'engelli'),
('disapprove_video', 'Disapprove video', 'فيديو رفض', 'Video afkeuren', 'Désapprouver la vidéo', 'Video ablehnen', 'Отклонить видео', 'Desaprobar vídeo', 'Videoyu reddet'),
('disliked_comment', 'disliked your comment', 'لم يعجبك تعليقك', 'Mislikte din kommentar', 'n\'aimait pas ton commentaire', 'hat deinen Kommentar nicht gefallen', 'не понравился ваш комментарий', 'no le gusto tu comentario', 'yorumunu beğenmedi'),
('disliked_video', 'disliked your video', 'لم يعجبك الفيديو', 'Mislikte videoen din', 'n\'aimait pas votre vidéo', 'hat dein Video nicht gefallen', 'не понравилось ваше видео', 'no le gusto tu vídeo', 'videonun beğenilmediğini'),
('dislikes', 'Dislikes', 'يكره', 'Houdt niet van', 'N\'aime pas', 'Abneigungen', 'Не нравится', 'No me gustas', 'Beğenmeme'),
('display_time', 'Display time', 'وقت العرض', 'Tijd weergeven', 'Temps d\'affichage', 'Anzeigezeit', 'Время отображения', 'Tiempo de visualización', 'Görüntüleme süresi'),
('do_really_want_change_picture', 'Do you really want to change the photo of your channel?', 'هل تريد حقًا تغيير صورة قناتك؟', 'Wil je echt de foto van je kanaal wijzigen?', 'Voulez-vous vraiment changer la photo de votre chaîne?', 'Möchten Sie das Foto Ihres Kanals wirklich ändern?', 'Вы действительно хотите изменить фотографию своего канала?', '¿Realmente quieres cambiar la foto de tu canal?', 'Kanalınızın fotoğrafını gerçekten değiştirmek istiyor musunuz?'),
('do_want_activate_deactivate_chat', 'Do you want to activate or deactivate the chat?', 'هل تريد تنشيط أو إلغاء تنشيط الدردشة؟', 'Wil je de chat activeren of deactiveren?', 'Voulez-vous activer ou désactiver le chat?', 'Möchten Sie den Chat aktivieren oder deaktivieren?', 'Вы хотите активировать или деактивировать чат?', '¿Deseas activar o desactivar el chat?', 'Sohbeti etkinleştirmek mi yoksa devre dışı bırakmak mı istiyorsunuz?'),
('do_you_like_this_video', 'Do you like this video?', 'هل تحب هذا الفيديو؟', 'Vind je deze video leuk?', 'Aimez-vous cette vidéo?', 'Gefällt dir dieses Video?', 'Вам нравится это видео?', '¿Te gusta este vídeo?', 'Bu videoyu beğendiniz mi?'),
('do_you_want_download', 'Do you want to download this video?', 'هل تريد تنزيل هذا الفيديو؟', 'Wil je deze video downloaden?', 'Voulez-vous télécharger cette vidéo?', 'Möchten Sie dieses Video herunterladen?', 'Вы хотите скачать это видео?', '¿Quieres descargar este vídeo?', 'Bu videoyu indirmek istiyor musunuz?'),
('do_you_want_post_cmt', 'If you have already posted a comment, it will replace it', 'إذا كنت قد نشرت تعليقًا بالفعل ، فسيحل محله', 'Als u al een opmerking hebt geplaatst, wordt deze vervangen', 'Si vous avez déjà posté un commentaire, il le remplacera', 'Wenn Sie bereits einen Kommentar gepostet haben, wird dieser ersetzt', 'Если вы уже оставили комментарий, он заменит его', 'Si ya has fijado un comentario, este lo sustituirá', 'Daha önce bir yorum gönderdiyseniz, yorum değiştirilir'),
('do_you_want_report', 'Do you want to report the video?', 'هل تريد الإبلاغ عن الفيديو؟', 'Wil je de video melden?', 'Voulez-vous signaler la vidéo?', 'Möchten Sie das Video melden?', 'Вы хотите сообщить о видео?', '¿Quieres denunciar este vídeo?', 'Videoyu rapor etmek ister misiniz?'),
('documentation', 'Documentation', 'توثيق', 'Documentatie', 'Documentation', 'Dokumentation', 'Документация', 'Documentación', 'Dokümantasyon'),
('does', 'ago', 'منذ', 'geleden', 'depuis', 'vor', 'тому назад', 'hace', 'önce'),
('download', 'Download', 'تحميل', 'Download', 'Télécharger', 'Herunterladen', 'Скачать', 'Descargar', 'İndir'),
('download_type', 'Download type', 'نوع التنزيل', 'Download type', 'Type de téléchargement', 'Download-Typ', 'Тип загрузки', 'Tipo de descarga', 'Deşarj türü'),
('downloads', 'Downloads', 'التحميلات', 'Downloads', 'Téléchargements', 'Downloads', 'загрузки', 'Descargas', 'İndirilenler'),
('edit', 'Edit', 'تصحيح', 'Bewerk', 'modifier', 'Bearbeiten', 'редактировать', 'Editar', 'Düzenleme'),
('edit_category', 'Edit category', 'تحرير الفئة', 'Bewerk categorie', 'Modifier la catégorie', 'Kategorie bearbeiten', 'Изменить категорию', 'Editar categoría', 'Kategoriyi düzenleyin'),
('edit_comment', 'Edit Comment', 'تعديل التعليق', 'Commentaar bewerken', 'Modifier le commentaire', 'Kommentar bearbeiten', 'Редактировать комментарий', 'Editar comentario', 'Yorumu Düzenle'),
('edit_language', 'Edit language', 'تحرير اللغة', 'Bewerk taal', 'Modifier la langue', 'Sprache bearbeiten', 'Изменить язык', 'Editar lenguaje', 'Dili düzenleyin'),
('edit_pages', 'Edit Pages', 'تحرير الصفحات', 'Pagina\'s bewerken', 'Modifier les pages', 'Seiten bearbeiten', 'Редактировать страницы', 'Edit Pages', 'Sayfaları Düzenle'),
('edit_profile_picture', 'Edit profile picture', 'تحرير صورة الملف الشخصي', 'Profielfoto bewerken', 'Modifier la photo de profil', 'Profilbild bearbeiten', 'Изменить изображение профиля', 'Editar la foto de perfil', 'Profil resmini düzenle'),
('edit_video', 'Edit video', 'تحرير الفيديو', 'Bewerk video', 'Éditer vidéo', 'Video bearbeiten', 'Редактировать видео', 'Editar video', 'Videoyu düzenle'),
('edit_word', 'Edit word', 'تحرير كلمة', 'Bewerk woord', 'Modifier le mot', 'Wort bearbeiten', 'Редактировать слово', 'Editar palabra', 'Kelimeyi düzenle'),
('email', 'E-mail', 'البريد الإلكتروني', 'E-mail', 'E-mail', 'E-mail', 'E-mail', 'Correo', 'E-mail'),
('email_address', 'E-mail address', 'عنوان البريد الإلكتروني', 'E-mailadres', 'Adresse e-mail', 'E-Mail-Addresse', 'Адрес электронной почты', 'Dirección de correo electrónico', 'E'),
('email_exists', 'This e-mail is already in use', 'هذا البريد استخدم من قبل', 'Deze email is al in gebruik', 'Cet e-mail est déjà utilisée', 'Diese E-Mail-Adresse wird schon verwendet', 'Этот электронный адрес уже используется', 'Este correo electrónico ya está en uso', 'Bu e-posta zaten kullanılıyor'),
('email_invalid_characters', 'E-mail is invalid', 'البريد الإلكتروني غير صالح', 'Email is ongeldig', 'Le courriel est invalide', 'E-Mail ist ungültig', 'Недействительный адрес электронной почты', 'El correo electrónico es invalido', 'E-posta geçersiz'),
('email_not_exist', 'E-mail not exist', 'البريد الإلكتروني غير موجود', 'E-mail bestaat niet', 'E-mail n\'existe pas', 'E-Mail existiert nicht', 'Электронная почта не существует', 'Este correo no existe', 'E-posta yok'),
('email_provider_banned', 'The email provider is blacklisted and not allowed, please choose another email provider.', 'مزود البريد الإلكتروني مدرج في القائمة السوداء وغير مسموح به ، يرجى اختيار مزود بريد إلكتروني آخر.', 'De e-mailprovider staat op de zwarte lijst en is niet toegestaan, kies een andere e-mailprovider.', 'Le fournisseur de messagerie est sur la liste noire et non autorisé. Veuillez choisir un autre fournisseur de messagerie.', 'Der E-Mail-Anbieter ist auf der schwarzen Liste und nicht zulässig. Bitte wählen Sie einen anderen E-Mail-Anbieter.', 'Поставщик электронной почты находится в черном списке и не допускается, выберите другого поставщика электронной почты.', 'El proveedor de correo electrónico está en la lista negra y no está permitido, elija otro proveedor de correo electrónico.', 'E-posta sağlayıcı kara listeye alındı ve izin verilmedi, lütfen başka bir e-posta sağlayıcısı seçin.'),
('email_sent', 'E-mail sent successfully', 'تم إرسال البريد الإلكتروني بنجاح', 'E-mail is succesvol verzonden', 'E-mail envoyé avec succès', 'Email wurde erfolgreich Versendet', 'Письмо успешно отправлено', 'Correo enviado correctamente', 'E-posta başarıyla gönderildi'),
('email_settings', 'Email Settings', 'إعدادات البريد الإلكتروني', 'Email instellingen', 'Paramètres de messagerie', 'Email Einstellungen', 'Настройки электронной почты', 'Ajustes del correo electrónico', 'e mail ayarları'),
('embed', 'Embed', 'تضمين', 'insluiten', 'Intégrer', 'Einbetten', 'встраивать', 'Insertar', 'gömmek'),
('embed_video', 'Embed Video', 'تضمين الفيديو', 'Video insluiten', 'Intégrer la vidéo', 'Video einbetten', 'Встроенное видео', 'Insertar video', 'Video Yerleştir'),
('empty_subscribers', 'No subscribers', 'لا مشتركين', 'Geen abonnees', 'Aucun abonné', 'Keine Abonnenten', 'Нет подписчиков', 'Sin suscriptores', 'Abone yok'),
('enable', 'Enable', 'مكن', 'In staat stellen', 'Activer', 'Aktivieren', 'включить', 'Habilitar', 'Etkinleştirmek'),
('enabled', 'Enabled', 'تمكين', 'ingeschakeld', 'Activé', 'Aktiviert', 'Включено', 'Activado', 'Etkin'),
('end_of_video', 'End of video', 'نهاية الفيديو', 'Einde van de video', 'Fin de la vidéo', 'Ende des Videos', 'Конец видео', 'Fin del video', 'Videonun sonu'),
('enter_code', 'Enter code', 'ادخل الرمز', 'Voer code in', 'Entrez le code', 'Code eingeben', 'Введите код', 'Ingresar código', 'Kodu girin');
INSERT INTO `words` (`word`, `en`, `ar`, `nl`, `fr`, `an`, `ru`, `es`, `tr`) VALUES
('enter_email', 'Enter the email address with which you registered the first time.', 'أدخل عنوان البريد الإلكتروني الذي قمت بالتسجيل به لأول مرة.', 'Voer het e-mailadres in waarmee u de eerste keer bent geregistreerd.', 'Saisissez l\'adresse e-mail avec laquelle vous vous êtes inscrit la première fois.', 'Geben Sie die E-Mail-Adresse ein, mit der Sie sich zum ersten Mal registriert haben.', 'Введите адрес электронной почты, с которым вы зарегистрировались в первый раз.', 'Introduce la dirección de correo con la que te registraste la primera vez.', 'İlk kez kaydolduğunuz e-posta adresini girin.'),
('error', 'Oops! An error has occurred', 'عفوا! لقد حدث خطأ', 'Oops! er is een fout opgetreden', 'Oups! une erreur s\'est produite', 'Hoppla! Ein Fehler ist aufgetreten', 'К сожалению! произошла ошибка', '¡Ups! ha ocurrido un error', 'Hata! bir hata oluştu'),
('error_1009_access_denied', 'Error 1009 Access denied!', 'خطأ 1009 تم رفض الوصول!', 'Fout 1009 Toegang geweigerd!', 'Erreur 1009 Accès refusé!', 'Fehler 1009 Zugriff verweigert!', 'Ошибка 1009 Доступ запрещен!', 'Error 1009 ¡Acceso denegado!', 'Erreur 1009 Accès refusé!'),
('error_occurred_to_send_mail', 'Oops an error occurred while trying to send the mail. Try it again later.', 'عفوًا ، حدث خطأ أثناء محاولة إرسال البريد. حاول مرة أخرى لاحقًا.', 'Oeps, er is een fout opgetreden bij het verzenden van de e-mail. Probeer het later nog eens.', 'Oups, une erreur s\'est produite lors de la tentative d\'envoi du courrier. Réessayez plus tard.', 'Hoppla, beim Versuch, die E-Mail zu senden, ist ein Fehler aufgetreten. Versuchen Sie es später noch einmal.', 'К сожалению, при отправке письма произошла ошибка. Повторите попытку позже.', 'Ups ocurrio un error al intentar enviar el correo. Intentelo de nuevo mas tarde.', 'Maalesef postayı göndermeye çalışırken bir hata oluştu. Daha sonra tekrar deneyin.'),
('errors', 'Errors', 'أخطاء', 'Fouten', 'les erreurs', 'Fehler', 'Ошибки', 'Errores', 'Hatalar'),
('facebook', 'Facebook', 'فيس بوك', 'Facebook', 'Facebook', 'Facebook', 'facebook', 'Facebook', 'Facebook'),
('featured_answer', 'Featured answer', 'إجابة مميزة', 'Aanbevolen antwoord', 'Réponse en vedette', 'Empfohlene Antwort', 'Рекомендуемый ответ', 'Respuesta destacada', 'Öne çıkan cevap'),
('featured_commentary', 'Featured Commentary', 'تعليق مميز', 'Aanbevolen commentaar', 'Commentaire présenté', 'Ausgewählter Kommentar', 'Избранные комментарии', 'Comentario destacado', 'Öne Çıkan Yorumlar'),
('features', 'Features', 'المميزات', 'Kenmerken', 'fonctionnalités', 'Eigenschaften', 'функции', 'Características', 'özellikleri'),
('february', 'February', 'فبراير', 'Februari', 'Fevrier', 'Februar', 'февраль', 'Febrero', 'Şubat'),
('female', 'Female', 'إناثا', 'Vrouw', 'Femelle', 'Weiblich', 'женский', 'Femenino', 'Kadın'),
('ffmpeg_ffprobe_configuration', 'Configuración de FFMPEG y FFPROBE', 'تكوين FFMPEG و FFPROBE', 'FFMPEG- en FFPROBE-configuratie', 'Configuration FFMPEG et FFPROBE', 'FFMPEG- und FFPROBE-Konfiguration', 'Конфигурация FFMPEG и FFPROBE', 'Configuración de FFMPEG y FFPROBE', 'FFMPEG ve FFPROBE yapılandırması'),
('file_is_too_large_the_maximum', 'File is too big, Max upload size is', 'الملف كبير جدا، الحد الأقصى لحجم التحميل هو', 'Bestand is te groot, Max upload grootte is', 'Le fichier est trop grand, la taille maximale de téléchargement est', 'Datei ist zu groß, Max Upload-Größe ist', 'Файл слишком большой, максимальный размер загрузки', 'El archivo es demasiado grande, el tamaño máximo de carga es', 'Dosya çok büyük, Maks. Yükleme boyutu'),
('file_not_supported', 'Invalid file format.', 'تنسيق ملف غير صالح.', 'Ongeldige bestandsindeling.', 'Format de fichier non valide.', 'Ungültiges Dateiformat.', 'Неверный формат файла.', 'El formato del archivo no es válido', 'Geçersiz dosya biçimi.'),
('filter', 'Filter', 'فلتر', 'Filter', 'Filtrer', 'Filter', 'фильтр', 'Filtrar', 'Filtre'),
('first_name', 'First Name', 'الاسم الاول', 'Voornaam', 'Prénom', 'Vorname', 'Имя', 'Primer Nombre', 'İsim'),
('follow', 'Follow', 'إتبع', 'Volgen', 'Suivre', 'Folgen', 'следить', 'Seguir', 'Takip et'),
('forgot_your_password', 'Forgot your password?', 'نسيت رقمك السري؟', 'Je wachtwoord vergeten?', 'Mot de passe oublié?', 'Haben Sie Ihr Passwort vergessen?', 'Забыли пароль?', '¿Olvidaste tu contraseña?', 'Parolanızı mı unuttunuz?'),
('forgotten_password', 'have you forgotten your password?', 'هل نسيت كلمة مرورك؟', 'Ben je je wachtwoord vergeten?', 'Vous avez oublié votre mot de passe?', 'Haben Sie Ihr Passwort vergessen?', 'Вы забыли свой пароль?', '¿Has olvidado tu contraseña?', 'Parolanızı mı unuttunuz?'),
('friday', 'Friday', 'يوم الجمعة', 'Vrijdag', 'Vendredi', 'Freitag', 'пятница', 'Viernes', 'Cuma'),
('fullscreen', 'Fullscreen', 'تكبير الشاشة', 'Volledig scherm', 'Plein écran', 'Ganzer Bildschirm', 'Полноэкранный', 'Pantalla completa', 'Tam ekran'),
('gaming', 'Gaming', 'لعبة فيديو', 'Videospel', 'Jeux video', 'Videospiel', 'Видео игра', 'Videojuegos', 'Video oyunu'),
('gender', 'Gender', 'جنس', 'Geslacht', 'Le genre', 'Geschlecht', 'Пол', 'Género', 'Cinsiyet'),
('gender_is_invalid', 'Gender is invalid', 'الجنس غير صالح', 'Geslacht is ongeldig', 'Le sexe n\'est pas valable', 'Geschlecht ist ungültig', 'Пол недействителен', 'Género no válido', 'Cinsiyet geçersiz'),
('general', 'General', 'جنرال لواء', 'Algemeen', 'Général', 'General', 'Генеральная', 'General', 'Genel'),
('general_reports', 'General Reports', 'التقارير العامة', 'Algemene rapporten', 'Rapports généraux', 'Allgemeine Berichte', 'Общие отчеты', 'Informes generales', 'Genel Raporlar'),
('general_settings', 'General adjustments', 'التعديلات العامة', 'Algemene aanpassingen', 'Ajustements généraux', 'Allgemeine Anpassungen', 'Общие корректировки', 'Ajustes generales', 'Genel düzenlemeler'),
('general_website_settings', 'General website settings', 'إعدادات الموقع العامة', 'Algemene website-instellingen', 'Paramètres généraux du site Web', 'Allgemeine Website-Einstellungen', 'Общие настройки сайта', 'Configura generales del sitio web', 'Genel web sitesi ayarları'),
('got_your_password', 'Got your password?', 'هل حصلت على كلمة المرور؟', 'Heb je je wachtwoord ontvangen?', 'Vous avez votre mot de passe?', 'Haben Sie Ihr Passwort?', 'Получил пароль?', '¿Tienes tu contraseña?', 'Şifreniz var mı?'),
('has_been_disabled_for_security_reasons', 'has been disabled for security reasons, please contact your host provider to enable it. exec() is required to enable this system', 'تم تعطيله لأسباب أمنية ، يرجى الاتصال بمزود المضيف الخاص بك لتمكينه. مطلوب exec() لتمكين هذا النظام', 'is om veiligheidsredenen uitgeschakeld, neem dan contact op met uw hostprovider om dit in te schakelen. exec() is vereist om dit systeem in te schakelen', 'a été désactivé pour des raisons de sécurité, veuillez contacter votre hébergeur pour l\'activer. exec() est requis pour activer ce système', 'Wurde aus Sicherheitsgründen deaktiviert, wenden Sie sich an Ihren Host-Anbieter, um die Aktivierung zu aktivieren. exec() ist erforderlich, um dieses System zu aktivieren', 'был отключен по соображениям безопасности, обратитесь к своему хост-провайдеру, чтобы включить его. exec() требуется для включения этой системы', 'se ha desactivado por motivos de seguridad, póngase en contacto con su proveedor de alojamiento para activarlo. Se requiere exec() para habilitar este sistema', 'güvenlik nedeniyle devre dışı bırakıldı, lütfen etkinleştirmek için ana bilgisayar sağlayıcınızla iletişime geçin. Bu sistemi etkinleştirmek için exec() gereklidir'),
('have_already_received_request_verify_account', 'We have already received your request to verify your {$settings->title} account, you will hear from us very soon. Please wait while we make a decision.', 'لقد تلقينا بالفعل طلبك للتحقق من حساب {$settings->title} الخاص بك ، وسوف تسمع منا قريبًا جدًا. من فضلك انتظر ريثما نتخذ القرار', 'We hebben uw verzoek om uw {$settings->title}-account te verifiëren al ontvangen, u zult zeer binnenkort van ons horen. Een ogenblik geduld terwijl we een beslissing nemen.', 'Nous avons déjà reçu votre demande de vérification de votre compte {$settings->title}, nous vous répondrons très bientôt. Veuillez patienter pendant que nous prenons une décision.', 'Wir haben bereits Ihre Anfrage zur Überprüfung Ihres {$settings->title}-Kontos erhalten. Sie werden sehr bald von uns hören. Bitte warten Sie, während wir eine Entscheidung treffen.', 'Мы уже получили ваш запрос на подтверждение вашей учетной записи {$settings->title}, вы скоро получите от нас известие. Подождите, пока мы примем решение.', 'Ya hemos recibido tu solicitud para verificar tu cuenta de {$settings->title}, muy pronto tendrás noticias de nosotros. Aguarda mientras tomamos una decisión.', '{$settings->title} hesabınızı doğrulama talebinizi zaten aldık, çok yakında bizden haber alacaksınız. Lütfen biz karar verirken bekleyin.'),
('have_reached_subscribers_now_can', 'You have reached {$settings->verification_subscribers_cap} subscribers! Now you can', 'لقد وصلت إلى {$settings->verification_subscribers_cap} مشترك! الآن انت تستطيع', 'Je hebt {$settings->verification_subscribers_cap} abonnees bereikt! Nu kan je', 'Vous avez atteint {$settings->verification_subscribers_cap} abonnés! Maintenant vous pouvez', 'Sie haben {$settings->verification_subscribers_cap} Abonnenten erreicht! Jetzt kannst du', 'Вы достигли {$settings->verification_subscribers_cap} подписчиков! Теперь вы можете', '¡Alcanzaste los {$settings->verification_subscribers_cap} suscriptores! Ahora puedes', '{$settings->verification_subscribers_cap} aboneye ulaştınız! Şimdi yapabilirsin'),
('header_ad', 'Header Ad', 'إعلان الرأس', 'Kopadvertentie', 'Annonce d\'en-tête', 'Header-Anzeige', 'Заголовок объявления', 'Anuncio de encabezado', 'Başlık Reklamı'),
('hello', 'Hello {$username}!', 'مرحبًا {$username}!', 'Hallo {$username}!', 'Bonjour {$username}!', 'Hallo {$username}!', 'Здравствуйте, {$username}!', '¡Hola {$username}!', 'Merhaba {$username}!'),
('here_all_changes_updates_have_applied_script', 'Here you will see all the changes or updates that have been applied to the script', 'هنا سترى جميع التغييرات أو التحديثات التي تم تطبيقها على البرنامج النصي', 'Hier ziet u alle wijzigingen of updates die op het script zijn toegepast', 'Ici, vous verrez toutes les modifications ou mises à jour qui ont été appliquées au script', 'Hier sehen Sie alle Änderungen oder Aktualisierungen, die auf das Skript angewendet wurden', 'Здесь вы увидите все изменения или обновления, которые были применены к скрипту.', 'Aquí podrás ver todas los cambios o actualizaciones que se han aplicado en el script', 'Burada komut dosyasına uygulanan tüm değişiklikleri veya güncellemeleri göreceksiniz'),
('here_save_notify', 'Your notifications are saved here', 'يتم حفظ الإشعارات الخاصة بك هنا', 'Uw meldingen worden hier opgeslagen', 'Vos notifications sont enregistrées ici', 'Ihre Benachrichtigungen werden hier gespeichert', 'Ваши уведомления сохранены здесь', 'Aquí se guardan tus notificaciones', 'Bildirimleriniz buraya kaydedilir'),
('hide', 'Hide', 'إخفاء', 'Verbergen', 'Cacher', 'Verbergen', 'Спрятать', 'Ocultar', 'Saklamak'),
('history', 'History', 'التاريخ', 'Geschiedenis', 'Histoire', 'Geschichte', 'история', 'Historial', 'tarih'),
('history_item_removed', 'History item removed', 'تمت إزالة عنصر السجل', 'Geschiedenisitem verwijderd', 'Élément d\'historique supprimé', 'Verlaufselement entfernt', 'Элемент истории удален', 'Se quitó el elemento del historial', 'Geçmiş öğesi kaldırıldı'),
('home', 'Home', 'الصفحة الرئيسية', 'Huis', 'Accueil', 'Zuhause', 'Главная', 'Inicio', 'Ev'),
('hour', 'hour', 'ساعة', 'uur', 'heure', 'Stunde', 'час', 'hora', 'saat'),
('hours', 'hours', 'ساعات', 'uur', 'heures', 'Std.', 'часов', 'horas', 'saatler'),
('i_dont_like', 'I don\'t like', 'لا يعجبني', 'Ik hou niet van', 'Je n\'aime pas', 'Ich mag nicht', 'Мне не нравится', 'No me gusta', 'Sevmiyorum'),
('i_dont_like_it_enymore', 'I do not like it anymore', 'أنا لا أحب ذلك بعد الآن', 'Ik vind het niet meer leuk', 'Je ne l&#039;aime plus', 'Ich mag es nicht mehr', 'Мне это больше не нравится', 'Ya no me gusta', 'Artık sevmiyorum'),
('i_like_it', 'I like it', 'احب', 'Ik hou van', 'J\'aime', 'Ich mag', 'Мне нравится', 'Me gusta', 'Severim'),
('icon_displayed_in_the_browser_window', 'Icon displayed in the browser window (16x16)', 'يتم عرض الرمز في نافذة المتصفح (16 × 16)', 'Pictogram weergegeven in het browservenster (16x16)', 'Icône affichée dans la fenêtre du navigateur (16x16)', 'Im Browserfenster angezeigtes Symbol (16x16)', 'Значок отображается в окне браузера (16x16)', 'Icono que se muestra en la ventana del navegador (16x16)', 'Tarayıcı penceresinde görüntülenen simge (16x16)'),
('id', 'ID', 'هوية شخصية', 'ID kaart', 'ID', 'ICH WÜRDE', 'Я БЫ', 'ID', 'İD'),
('identity_fraud', 'Identity fraud', 'انتحال', 'spoofing', 'Usurpation d\'identité', 'Identitätswechsel', 'Подделка', 'Suplantación de identidad', 'Sızdırma'),
('if_add_another_category_here', 'If you want to add another category, you can do it here:', 'إذا كنت ترغب في إضافة فئة أخرى ، يمكنك القيام بذلك هنا:', 'Als u nog een categorie wilt toevoegen, kunt u dit hier doen:', 'Si vous souhaitez ajouter une autre catégorie, vous pouvez le faire ici:', 'Wenn Sie eine weitere Kategorie hinzufügen möchten, können Sie dies hier tun:', 'Если вы хотите добавить еще одну категорию, вы можете сделать это здесь:', 'Si quieres añadir otra categoria, puedes hacerlo aqui:', 'Başka bir kategori eklemek istiyorsanız, buradan yapabilirsiniz:'),
('if_you_need_more_help', 'If you need more help,', 'إذا كنت بحاجة إلى مزيد من المساعدة ،', 'Als u meer hulp nodig heeft,', 'Si vous avez besoin de plus d\'aide,', 'Wenn Sie weitere Hilfe benötigen,', 'Если вам нужна дополнительная помощь,', 'Si necesitas más ayuda, ', 'Daha fazla yardıma ihtiyacın olursa,'),
('if_you_still_cant_install_ffmpeg_please_contact', 'If you still can\'t install FFMPEG, please contact your hosting provider, and they shall install it', 'إذا كنت لا تزال غير قادر على تثبيت FFMPEG ، فيرجى الاتصال بمزود الاستضافة الخاص بك ، وسيقومون بتثبيته', 'Als je FFMPEG nog steeds niet kunt installeren, neem dan contact op met je hostingprovider, en zij zullen het installeren', 'Si vous ne parvenez toujours pas à installer FFMPEG, veuillez contacter votre hébergeur et il l\'installera', 'Wenn Sie FFMPEG immer noch nicht installieren können, wenden Sie sich bitte an Ihren Hosting-Anbieter, der es installieren soll', 'Если вы по-прежнему не можете установить FFMPEG, обратитесь к своему хостинг-провайдеру, и он установит его.', 'Si aún no puede instalar FFMPEG, comuníquese con su proveedor de alojamiento y ellos lo instalarán', 'FFMPEG\'i hala yükleyemiyorsanız, lütfen barındırma sağlayıcınızla iletişime geçin;'),
('if_you_want_remove_account_from', 'If you want to remove your account from {$settings->name}, you can do it from the page', 'إذا كنت تريد إزالة حسابك من {$settings->name} ، فيمكنك القيام بذلك من الصفحة', 'Als u uw account uit {$settings->name} wilt verwijderen, kunt u dit vanaf de pagina doen', 'Si vous souhaitez supprimer votre compte de {$settings->name}, vous pouvez le faire à partir de la page', 'Wenn Sie Ihr Konto aus {$settings->name} entfernen möchten, können Sie dies von der Seite aus tun', 'Если вы хотите удалить свой аккаунт из {$settings->name}, вы можете сделать это со страницы', 'Si quieres eliminar tu cuenta de {$settings->name}, puedes hacerlo desde la página', 'Hesabınızı {$settings->name} sayfasından kaldırmak istiyorsanız, bunu sayfadan yapabilirsiniz'),
('in', 'In', 'في', 'In', 'Dans', 'Im', 'В', 'En', 'İçinde'),
('in_loop', 'In loop', 'في الحلقة', 'In lus', 'En boucle', 'In Schleife', 'В цикле', 'En bucle', 'Döngüde'),
('inactive', 'Inactive', 'غير نشط', 'Inactief', 'Inactif', 'Inaktiv', 'Неактивный', 'Inactivo', 'etkisiz'),
('include', 'Incluir', 'تضمن', 'Inclusief', 'Inclusief', 'Einschließen', 'Включают', 'Incluir', 'Dahil etmek'),
('incorporation_date', 'Incorporation date', 'تاريخ التأسيس', 'Oprichtingsdatum', 'Date de constitution', 'Gründungsdatum', 'Дата регистрации', 'Fecha de incorporación', 'Kuruluş Tarihi'),
('incorrect_password', 'Incorrect password', 'كلمة مرور خاطئة', 'Onjuist wachtwoord', 'Mot de passe incorrect', 'Falsches Passwort', 'Неверный пароль', 'Contraseña incorrecta', 'Yanlış şifre'),
('information_provided_correct_email_could_information_correct', 'The information you have provided is not correct so an email could not be sent, please make sure the information is correct', 'المعلومات التي قدمتها غير صحيحة ، لذا لا يمكن إرسال بريد إلكتروني ، يرجى التأكد من صحة المعلومات', 'De informatie die u heeft opgegeven is niet correct, dus er kan geen e-mail worden verzonden. Controleer of de informatie correct is', 'Les informations que vous avez fournies ne sont pas correctes, donc un e-mail n\'a pas pu être envoyé, veuillez vous assurer que les informations sont correctes', 'Die von Ihnen angegebenen Informationen sind nicht korrekt, sodass keine E-Mail gesendet werden konnte. Stellen Sie sicher, dass die Informationen korrekt sind', 'Информация, которую вы предоставили, неверна, поэтому электронное письмо не может быть отправлено, убедитесь, что информация верна', 'La información que ha proporcionado no es correcta, por lo que no se ha podido enviar un correo electrónico,  asegúrese de que la información es correcta', 'Sağladığınız bilgiler doğru değil, bu nedenle bir e-posta gönderilemedi, lütfen bilgilerin doğru olduğundan emin olun'),
('infringes_on_my_copyright', 'Infringes on my copyright', 'ينتهك حقوق النشر الخاصة بي', 'Maakt inbreuk op mijn auteursrecht', 'Viole mes droits d\'auteur', 'Verstößt gegen mein Urheberrecht', 'Нарушает мои авторские права', 'Infringe mis derechos de autor', 'Telif hakkımı ihlal ediyor'),
('instagram', 'Instagram', 'إينستاجرام', 'Instagram', 'Instagram', 'Instagram', 'Instagram', 'Instagram', 'Instagram'),
('invalid_custom_start_time', 'Invalid custom start time', 'وقت بدء مخصص غير صالح', 'Ongeldige aangepaste starttijd', 'Heure de début personnalisée non valide', 'Ungültige benutzerdefinierte Startzeit', 'Недействительное настраиваемое время начала', 'Hora de inicio personalizada no válida', 'Geçersiz özel başlangıç ​​zamanı'),
('invalid_password', 'Password is incorrect. Try again.', 'كلمة المرور غير صحيحة. حاول مجددا.', 'Wachtwoord is niet correct. Probeer het opnieuw.', 'Le mot de passe est incorrect. Essaye à nouveau.', 'Das Passwort ist inkorrekt. Versuch es noch einmal.', 'Неверный пароль. Попробуй еще раз.', 'La contraseña es incorrecta. Inténtalo de nuevo.', 'Şifre yanlış. Tekrar deneyin.'),
('invalid_request', 'Invalid request', 'طلب غير صالح', 'Ongeldig verzoek', 'Requête invalide', 'Ungültige Anfrage', 'Неверный запрос', 'Solicitud no válida', 'Geçersiz istek'),
('invalid_username', 'This username does not exist.', 'اسم المستخدم هذا غير موجود.', 'Deze gebruikersnaam bestaat niet.', 'Ce nom d\'utilisateur n\'existe pas.', 'Dieser Benutzername existiert nicht.', 'Это имя пользователя не существует.', 'Este nombre de usuario no existe.', 'Bu kullanıcı adı mevcut değil.'),
('invasion_privacy', 'Invasion of my privacy', 'غزو ​​خصوصيتي', 'Inbreuk op mijn privacy', 'Invasion de ma vie privée', 'Verletzung meiner Privatsphäre', 'Нарушение моей конфиденциальности', 'Invasión a mi privacidad', 'Gizliliğimin istilası'),
('ip_address', 'IP Address', 'عنوان IP', 'IP adres', 'Adresse IP', 'IP Adresse', 'Айпи адрес', 'Dirección IP', 'IP adresi'),
('item_removed_from_videos', 'The item was removed from your videos', 'تمت إزالة العنصر من مقاطع الفيديو الخاصة بك', 'Het item is verwijderd uit je video\'s', 'L\'élément a été supprimé de vos vidéos', 'Der Artikel wurde aus deinen Videos entfernt', 'Элемент был удален из ваших видео', 'Se quitó el elemento de tus videos', 'Öğe videolarınızdan kaldırıldı'),
('its_okay', 'It\'s okay', 'جيد', 'Het is in orde', 'C\'est bien', 'Es ist in Ordnung', 'Все в порядке', 'Está bien', 'Sorun değil'),
('january', 'January', 'يناير', 'Januari', 'Janvier', 'Januar', 'январь', 'Enero', 'Ocak'),
('july', 'July', 'يوليو', 'Juli', 'Juillet', 'Juli', 'июль', 'Julio', 'Temmuz'),
('june', 'June', 'يونيو', 'Juni', 'Juin', 'Juni', 'июнь', 'Junio', 'Haziran'),
('just_finished_live_page_reload_in', 'You just finished the live, the page will reload in:', 'لقد انتهيت للتو من البث المباشر ، ستتم إعادة تحميل الصفحة في:', 'Je hebt net de live beëindigd, de pagina wordt opnieuw geladen in:', 'Vous venez de terminer le live, la page se rechargera dans:', 'Wenn Sie gerade das Live beendet haben, wird die Seite neu geladen in:', 'Вы только что закончили лайв, страница перезагрузится:', 'Acabas de finalizar el directo, la pagina se recargara en:', 'Yayını yeni bitirdiniz, sayfa yeniden yüklenecek:'),
('just_ignore_this_message', 'just ignore this message', 'فقط تجاهل هذه الرسالة', 'negeer dit bericht gewoon', 'ignore juste ce message', 'Ignoriere einfach diese Nachricht', 'просто проигнорируйте это сообщение', 'solamente ignora este mensaje', 'sadece bu mesajı görmezden gel'),
('key_name', 'Key Name', 'اسم المفتاح', 'Sleutelnaam', 'Nom de la clé', 'Schlüsselname', 'Имя ключа', 'Nombre clave', 'Anahtar Adı'),
('language', 'Language', 'لغة', 'Taal', 'La langue', 'Sprache', 'язык', 'Idioma', 'Dil'),
('language_key', 'Language key', 'مفتاح اللغة', 'Taaltoets', 'Clé de langue', 'Sprachschlüssel', 'Языковой ключ', 'Clave del idioma', 'Dil anahtarı'),
('language_name', 'Language Name', 'اسم اللغة', 'Taalnaam', 'Nom de la langue', 'Sprache Name', 'Название языка', 'Nombre del lenguaje', 'dil adı'),
('language_settings', 'Language settings', 'اعدادات اللغة', 'Taal instellingen', 'Paramètres de langue', 'Dil ayarları', 'Языковые настройки', 'Configuración de lenguajes', 'Dil ayarları'),
('language_successfully_added', 'Language successfully added', 'تمت إضافة اللغة بنجاح', 'Taal succesvol toegevoegd', 'Langue ajoutée avec succès', 'Sprache erfolgreich hinzugefügt', 'Язык успешно добавлен', 'Idioma agregado correctamente', 'Dil başarıyla eklendi'),
('language_successfully_deleted', 'Language successfully deleted', 'تم حذف اللغة بنجاح', 'Taal succesvol verwijderd', 'Langue supprimée avec succès', 'Sprache erfolgreich gelöscht', 'Язык успешно удален', 'Idioma eliminado correctamente', 'Dil başarıyla silindi'),
('languages', 'Languages', 'اللغات', 'Talen', 'langues', 'Sprachen', 'Языки', 'Idiomas', 'Diller'),
('last_hour', 'Last hour', 'الساعة الأخيرة', 'Laatste uur', 'Dernière heure', 'Letzte Stunde', 'Последний час', 'Última hora', 'Son saat'),
('last_name', 'Surname', 'اللقب الأول', 'Voornaam', 'Prénom', 'Vorname', 'Первая фамилия', 'Primer Apellido', 'İlk Soyadı'),
('last_update', 'Last update', 'اخر تحديث', 'Laatste update', 'Dernière mise à jour', 'Letztes Update', 'Последнее обновление', 'Última actualización', 'Son Güncelleme'),
('latest_comments', 'Latest Comments', 'أحدث تعليقات', 'laatste Reacties', 'Derniers Commentaires', 'neueste Kommentare', 'Последние комментарии', 'Últimos comentarios', 'son Yorumlar'),
('learning', 'Learning', 'تعلم', 'Aan het leren', 'Apprentissage', 'Lernen', 'Обучение', 'Aprendizaje', 'Öğrenme'),
('let_us_know', 'let us know', 'دعنا نعرف', 'laat het ons weten', 'Faites le nous savoir', 'Lass uns wissen', 'дайте нам знать', 'avísanos', 'bilmemize izin ver'),
('lets_get_started', 'Create an account!', 'انشاء حساب!', 'Maak een account aan!', 'Créez un compte!!', 'Erstellen Sie ein Konto!', 'Создайте аккаунт!', '¡Crea una cuenta!', 'Kayıt ol!'),
('liked_comment', 'liked your comment', 'أحب تعليقك', 'likte din kommentar', 'aimé ton commentaire', 'mochte deinen Kommentar', 'понравился ваш комментарий', 'le gustó tu comentario', 'Yorumu beğeniyor'),
('liked_video', 'liked your video', 'أعجبك الفيديو', 'likte videoen din', 'aimé votre vidéo', 'mochte dein Video', 'понравилось ваше видео', 'le gustó tu vídeo', 'videonuzu beğendim'),
('liked_videos', 'Liked videos', 'أعجبت مقاطع الفيديو', 'Verwachte video\'s', 'Vidéos aimées', 'Mochte Videos', 'Понравившиеся видео', 'Videos que me gustaron', 'Beğenilen videolar'),
('likes', 'Likes', 'الإعجابات', 'sympathieën', 'Aime', 'Likes', 'Нравится', 'Me gustas', 'Seviyor'),
('limit_settings', 'Limit Settings', 'إعدادات الحد', 'Limietinstellingen', 'Limiter les paramètres', 'Einstellungen begrenzen', 'Настройки лимита', 'Configuración de límites', 'Sınır Ayarları'),
('links', 'Links', 'الروابط', 'Links', 'Liens', 'Links', 'связи', 'Enlaces', 'Bağlantılar'),
('live_broadcast', 'Live', 'حي', 'Leven', 'Vivre', 'Leben', 'Прямой эфир', 'En vivo', 'Canlı'),
('live_broadcast_title', 'Live broadcast', 'بث مباشر', 'Live uitzending', 'Diffusion en direct', 'Live-Übertragung', 'Прямая трансляция', 'Transmisión en vivo', 'Canlı yayın'),
('live_chat', 'Live chat', 'دردشة مباشرة', 'Live chat', 'Chat en direct', 'Live-Chat', 'Живой чат', 'Chat en vivo', 'Canlı sohbet'),
('live_ended_author_decided_not_save', 'The live has ended, but the author has decided not to save it :(', 'انتهى البث المباشر ، لكن المؤلف قرر عدم حفظه :(', 'De live is afgelopen, maar de auteur heeft besloten het niet op te slaan :(', 'Le live est terminé, mais l\'auteur a décidé de ne pas le sauvegarder :(', 'Das Live ist beendet, aber der Autor hat beschlossen, es nicht zu speichern :(', 'Кончился лайв, но автор решил не сохранять :(', 'El directo ha finalizado, pero el autor ha decidido no guardarlo :(', 'Canlı yayın sona erdi, ancak yazar onu kaydetmemeye karar verdi :('),
('load_more', 'Load more', 'تحميل المزيد', 'Last mer', 'Charger plus', 'Mehr laden', 'Показать больше', 'Carga más', 'Daha fazla yükle'),
('location', 'Location', 'موقع', 'Plaats', 'Emplacement', 'Lage', 'место', 'Ubicación', 'Konum'),
('lock_account', 'Lock account', 'حساب قفل', 'Account vergrendelen', 'Verrouiller le compte', 'Account sperren', 'Заблокировать аккаунт', 'Bloquear cuenta', 'Kilitli hesap'),
('locks', 'Locks', 'أقفال', 'Sloten', 'Serrures', 'Schlösser', 'Замки', 'Bloqueos', 'Kilitler'),
('log_in_your_account', 'Access your {$settings->name} account', 'الوصول إلى حساب {$settings->name} الخاص بك', 'Toegang tot uw {$settings->name}-account', 'Accédez à votre compte {$settings->name}', 'Greifen Sie auf Ihr {$settings->name}-Konto zu', 'Доступ к вашей учетной записи {$settings->name}', 'Accede a tu cuenta {$settings->name}', '{$settings->name} hesabınıza erişin'),
('log_out', 'Log out', 'الخروج', 'Uitloggen', 'Connectez - Out', 'Ausloggen', 'Выйти', 'Cerrar sesión', 'Çıkış Yap'),
('logged_do_you_want_download', 'Sign in so you can download this video.', 'تسجيل الدخول حتى تتمكن من تحميل هذا الفيديو.', 'Log in zodat je deze video kunt downloaden.', 'Connectez-vous pour télécharger cette vidéo.', 'Melden Sie sich an, damit Sie dieses Video herunterladen können.', 'Войдите, чтобы скачать это видео.', 'Inicia sesión para que puedas descargar este vídeo.', 'Bu videoyu indirebilmek için oturum açın.'),
('logged_do_you_want_repor', 'Sign in to report inappropriate content or violation of rights.', 'سجّل الدخول للإبلاغ عن محتوى غير لائق أو انتهاك للحقوق.', 'Log in om ongepaste inhoud of schending van rechten te melden.', 'Connectez-vous pour signaler un contenu inapproprié ou une violation des droits.', 'Melden Sie sich an, um unangemessene Inhalte oder Rechtsverletzungen zu melden.', 'Войдите, чтобы сообщить о неприемлемом контенте или нарушении прав.', 'Inicia sesión para denunciar contenido inapropiado o violación de derechos.', 'Uygunsuz içeriği veya hak ihlallerini bildirmek için oturum açın.'),
('logged_for_dislike', 'You do not like this video?', 'أنت لا تحب هذا الفيديو؟', 'Vind je deze video niet leuk?', 'Vous n&#039;aimez pas cette vidéo?', 'Dir gefällt dieses Video nicht?', 'Вам не нравится это видео?', '¿No te gusta este vídeo?', 'Bu videoyu beğenmedin mi?'),
('logged_for_like', 'Sign in to have your opinion in mind.', 'تسجيل الدخول ليكون رأيك في الاعتبار.', 'Log in om uw mening te onthouden.', 'Connectez-vous pour avoir votre opinion en tête.', 'Melden Sie sich an, um Ihre Meinung zu berücksichtigen.', 'Войдите, чтобы иметь свое мнение.', 'Inicia sesión para que tengamos en cuenta tu opinión.', 'Görüşünüzü akılda tutmak için oturum açın.'),
('login', 'Login', 'تسجيل الدخول', 'Log in', 'S\'identifier', 'Anmeldung', 'Авторизоваться', 'Iniciar sesión', 'Oturum aç'),
('login_', 'Log In', 'تسجيل الدخول', 'Log in', 'S\'identifier', 'Einloggen', 'Авторизоваться', 'Acceder', 'Oturum aç'),
('login_confirm_age', 'Login to confirm your age', 'تسجيل الدخول لتأكيد عمرك', 'Log in om uw leeftijd te bevestigen', 'Connectez-vous pour confirmer votre âge', 'Melden Sie sich an, um Ihr Alter zu bestätigen', 'Войдите, чтобы подтвердить свой возраст', 'Accede para confirmar tu edad', 'Yaşınızı onaylamak için giriş yapın'),
('looks_like_were_blocked_site_administrator', 'It looks like you were blocked from ({$text}) by a site administrator', 'يبدو أنه تم منعك من ({$text}) بواسطة مسؤول الموقع', 'Het lijkt erop dat u bent geblokkeerd voor ({$text}) door een sitebeheerder', 'Il semble que vous avez été bloqué ({$text}) par un administrateur du site', 'Es sieht so aus, als ob Sie von einem Site-Administrator blockiert wurden ({$text})', 'Похоже, вы были заблокированы от ({$text}) администратором сайта', 'Parece que fuiste bloqueado desde ({$text}) por un administrador del sitio', 'Görünüşe göre site yöneticisi tarafından ({$text}) adresinden engellenmişsiniz'),
('mail_contact', 'Contact email', 'الاتصال البريد الإلكتروني', 'Contact e-mail', 'Courriel de contact', 'Kontakt-E-Mail', 'Контактный адрес электронной почты', 'Correo de contacto', 'İletişim e-postası+'),
('main_settings', 'Main Settings', 'الإعدادات الرئيسية', 'Belangrijkste instellingen', 'Réglages principaux', 'Haupteinstellungen', 'Основные настройки', 'Ajustes principales', 'Ana ayarlar'),
('make_sure_configure_cron_job_server', 'Make sure to configure your cron job on your server', 'تأكد من تكوين مهمة cron الخاصة بك على الخادم الخاص بك', 'Zorg ervoor dat u uw cron-taak op uw server configureert', 'Assurez-vous de configurer votre tâche cron sur votre serveur', 'Stellen Sie sicher, dass Sie Ihren Cron-Job auf Ihrem Server konfigurieren', 'Обязательно настройте задание cron на своем сервере', 'Asegúrese de configurar su trabajo cron en su servidor', 'Sunucunuzda cron işinizi yapılandırdığınızdan emin olun'),
('make_sure_to_save_your_watermark_first', 'Make sure to save your watermark first', 'تأكد من حفظ العلامة المائية الخاصة بك أولاً', 'Zorg ervoor dat u eerst uw watermerk opslaat', 'Assurez-vous d\'abord d\'enregistrer votre filigrane', 'Stellen Sie sicher, dass Sie zuerst Ihr Wasserzeichen speichern', 'Обязательно сначала сохраните водяной знак', 'Asegúrate de guardar tu marca de agua primero', 'Önce filigranınızı kaydettiğinizden emin olun'),
('male', 'Male', 'الذكر', 'Mannetje', 'Mâle', 'Männlich', 'мужчина', 'Masculino', 'Erkek'),
('manage', 'Manage', 'يدير', 'beheren', 'Gérer', 'Verwalten', 'управлять', 'Gestionar', 'yönetme'),
('manage_all_registered_users_perform_actions', 'Manage all registered users, you can perform actions on them', 'إدارة جميع المستخدمين المسجلين ، يمكنك تنفيذ الإجراءات عليهم', 'Beheer alle geregistreerde gebruikers, u kunt er acties op uitvoeren', 'Gérez tous les utilisateurs enregistrés, vous pouvez effectuer des actions sur eux', 'Verwalten Sie alle registrierten Benutzer, Sie können Aktionen für sie ausführen', 'Управляйте всеми зарегистрированными пользователями, вы можете выполнять над ними действия', 'Administra todos los usuarios registrados, puedes realizar acciones sobre ellos', 'Tüm kayıtlı kullanıcıları yönetin, üzerlerinde işlem yapabilirsiniz'),
('manage_all_verification_requests_made', 'Manage all verification requests made', 'إدارة جميع طلبات التحقق المقدمة', 'Beheer alle gemaakte verificatieverzoeken', 'Gérer toutes les demandes de vérification effectuées', 'Verwalten Sie alle gestellten Überprüfungsanforderungen', 'Управляйте всеми сделанными запросами на верификацию', 'Administra todas las solicitudes de verificación realizadas', 'Yapılan tüm doğrulama isteklerini yönetin'),
('manage_all_videos_uploaded', 'Manage all videos uploaded to {$settings->name}', 'إدارة جميع مقاطع الفيديو التي تم تحميلها إلى {$settings->name}', 'Beheer alle video\'s die zijn geüpload naar {$settings->name}', 'Gérer toutes les vidéos mises en ligne sur {$settings->name}', 'Verwalten Sie alle Videos, die auf {$settings->name} hochgeladen wurden.', 'Управляйте всеми видео, загруженными на {$settings->name}', 'Administra todos los videos subidos a {$settings->name}', '{$settings->name} \'e yüklenen tüm videoları yönetin'),
('manage_categories', 'Manage Categories', 'إدارة الفئات', 'Beheer Categorieën', 'Gérer les catégories', 'Kategorien verwalten', 'Управление категориями', 'Administrar categorías', 'Kategorileri Yönetin'),
('manage_comments', 'Manage Comments', 'إدارة التعليقات', 'Beheer opmerkingen', 'Gérer les commentaires', 'Kommentare verwalten', 'Управление комментариями', 'Administrar comentarios', 'Yorumları Yönet'),
('manage_comments_made', 'Manage all comments made in {$settings->name}', 'إدارة كل التعليقات التي تم إجراؤها في {$settings->name}', 'Beheer alle opmerkingen die zijn gemaakt in {$settings->name}', 'Gérer tous les commentaires faits dans {$settings->name}', 'Verwalten Sie alle Kommentare in {$settings->name}', 'Управляйте всеми комментариями, сделанными в {$settings->name}', 'Administra todos los comentarios realizados en {$settings->name}', '{$settings->name} ile yapılan tüm yorumları yönetin'),
('manage_delete_users_email_providers_ips', 'Manage, add or delete users, email providers or IPs', 'إدارة أو إضافة أو حذف المستخدمين أو موفري البريد الإلكتروني أو عناوين IP', 'Beheer, voeg gebruikers, e-mailproviders of IP\'s toe of verwijder ze', 'Gérer, ajouter ou supprimer des utilisateurs, des fournisseurs de messagerie ou des adresses IP', 'Verwalten, Hinzufügen oder Löschen von Benutzern, E-Mail-Anbietern oder IPs', 'Управление, добавление или удаление пользователей, провайдеров электронной почты или IP-адресов', 'Administra, agrega o elimina usuarios, proveedores de correo o IPs', 'Kullanıcıları, e-posta sağlayıcılarını veya IP\'leri yönetin, ekleyin veya silin'),
('manage_edit_languages', 'Manage & Edit Languages', 'إدارة وتحرير اللغات', 'Talen beheren en bewerken', 'Gérer et modifier les langues', 'Sprachen verwalten und bearbeiten', 'Управление и редактирование языков', 'Administrar y editar idiomas', 'Dilleri Yönetin ve Düzenleyin'),
('manage_edit_locks', 'Manage and edit locks', 'إدارة وتحرير الأقفال', 'Beheer en bewerk sloten', 'Gérer et modifier les verrous', 'Sperren verwalten und bearbeiten', 'Управление и редактирование замков', 'Administrar y editar bloqueos', 'Kilitleri yönetin ve düzenleyin'),
('manage_edit_users', 'Manage & Edit Users', 'إدارة وتحرير المستخدمين', 'Beheer en bewerk gebruikers', 'Gérer et modifier les utilisateurs', 'Benutzer verwalten und bearbeiten', 'Управление и редактирование пользователей', 'Administrar y editar usuarios', 'Kullanıcıları Yönetin ve Düzenleyin'),
('manage_edit_videos', 'Manage & Edit Videos', 'إدارة وتحرير مقاطع الفيديو', 'Beheer en bewerk video\'s', 'Gérer et modifier des vidéos', 'Videos verwalten und bearbeiten', 'Управление и редактирование видео', 'Administrar y editar videos', 'Videoları Yönetin ve Düzenleyin'),
('manage_languages', 'Manage languages', 'إدارة اللغات', 'Beheer talen', 'Gérer les langues', 'Sprachen verwalten', 'Управлять языками', 'Administrar languages', 'Dilleri yönetin'),
('manage_languages_modify_correct_one_more_words', 'Manage your languages, you can modify them to correct one or more words', 'إدارة اللغات الخاصة بك ، يمكنك تعديلها لتصحيح كلمة واحدة أو أكثر', 'Beheer uw talen, u kunt ze aanpassen om een ​​of meer woorden te corrigeren', 'Gérez vos langues, vous pouvez les modifier pour corriger un ou plusieurs mots', 'Verwalten Sie Ihre Sprachen, Sie können sie ändern, um ein oder mehrere Wörter zu korrigieren', 'Управляйте своими языками, вы можете изменить их, чтобы исправить одно или несколько слов', 'Administre sus idiomas, puede modificarlos para corregir una o más palabras', 'Dillerinizi yönetin, bir veya daha fazla kelimeyi düzeltmek için bunları değiştirebilirsiniz'),
('manage_modify_categories_website', 'Manage and modify the categories of your website', 'إدارة وتعديل فئات موقع الويب الخاص بك', 'Beheer en wijzig de categorieën van uw website', 'Gérez et modifiez les catégories de votre site Web', 'Verwalten und ändern Sie die Kategorien Ihrer Website', 'Управляйте категориями вашего сайта и изменяйте их', 'Administra y modifica las categorías de tu sitio web', 'Web sitenizin kategorilerini yönetin ve değiştirin'),
('manage_my_videos', 'Manage My Videos', 'إدارة مقاطع الفيديو الخاصة بي', 'Beheer mijn video\'s', 'Gérer mes vidéos', 'Verwalten Sie meine Videos', 'Управление видео', 'Mis vídeos', 'Videolarımı Yönet'),
('manage_reports_made_platform_take_actions', 'Manage the reports made on your platform and take actions on them', 'إدارة التقارير التي تم إجراؤها على النظام الأساسي الخاص بك واتخاذ إجراءات بشأنها', 'Beheer de rapporten die op uw platform zijn gemaakt en onderneem acties op hen', 'Gérez les rapports réalisés sur votre plateforme et prenez des mesures à leur sujet', 'Verwalten Sie die auf Ihrer Plattform erstellten Berichte und ergreifen Sie entsprechende Maßnahmen', 'Управляйте отчетами, созданными на вашей платформе, и принимайте меры по ним', 'Administra los reportes realizados en tu plataforma y toma acciones sobre ellos', 'Platformunuzda yapılan raporları yönetin ve bunlarla ilgili işlem yapın'),
('manage_streaming', 'Manage Streaming', 'إدارة البث', 'Beheer streaming', 'Gérer le streaming', 'Streaming verwalten', 'Управление потоковой передачей', 'Administrar transmisión', 'Akışı Yönetin'),
('manage_users', 'Manage users', 'ادارة المستخدمين', 'Gebruikers beheren', 'Gérer les utilisateurs', 'Benutzer verwalten', 'Управление пользователями', 'Administrar usuarios', 'Kullanıcıları Yönet'),
('manage_verification_requests', 'Manage Verification Requests', 'إدارة طلبات التحقق', 'Beheer verificatieverzoeken', 'Gérer les demandes de vérification', 'Bestätigungsanforderungen verwalten', 'Управление запросами на подтверждение', 'Administrar solicitudes de verificación', 'Doğrulama Taleplerini Yönetin'),
('manage_video_reports', 'Manage Video Reports', 'إدارة تقارير الفيديو', 'Beheer videorapporten', 'Gérer les rapports vidéo', 'Videoberichte verwalten', 'Управление видеоотчетами', 'Administrar informes de video', 'Video Raporlarını Yönetin'),
('manage_videos', 'Manage Videos', 'إدارة مقاطع الفيديو', 'Beheer video\'s', 'Gérer les vidéos', 'Videos verwalten', 'Управление видео', 'Administrar vídeos', 'Videoları Yönet'),
('march', 'March', 'مارس', 'Maart', 'Mars', 'März', 'марш', 'Marzo', 'Mart'),
('max_process_in_same_time', 'Max process in same time  (0 to unlimited)', 'ماكس العملية في نفس الوقت (0 إلى غير محدود)', 'Max proces in dezelfde tijd (0 tot onbeperkt)', 'Processus max en même temps (0 à illimité)', 'Maximaler Prozess in derselben Zeit (0 bis unbegrenzt)', 'Максимальный процесс за одно и то же время (от 0 до неограниченно)', 'Proceso máximo en el mismo tiempo (0 a ilimitado)', 'Aynı anda maksimum işlem (0 - sınırsız)'),
('may', 'May', 'مايو', 'Mei', 'Mai', 'Mai', 'может', 'Mayo', 'Mayıs'),
('message', 'Message', 'رسالة', 'Bericht', 'message', 'Botschaft', 'Сообщение', 'Mensaje', 'İleti'),
('messages', 'Messages', 'رسائل', 'berichten', 'messages', 'Mitteilungen', 'Сообщения', 'Mensajes', 'Mesajlar'),
('minute', 'minute', 'اللحظة', 'minuut', 'minute', 'Minute', 'минут', 'minuto', 'dakika'),
('minutes', 'minutes', 'الدقائق', 'notulen', 'minutes', 'Protokoll', 'минут', 'minutos', 'dakika'),
('moderator', 'Moderator', 'مشرف', 'Moderator', 'Modérateur', 'Moderator', 'Модератор', 'Moderador', 'Moderatör'),
('moderator_panel', 'Moderator panel', 'لوحة الوسيط', 'Moderatorpaneel', 'Panneau du modérateur', 'Moderatorpanel', 'Панель модератора', 'Panel de moderador', 'Moderatör paneli'),
('modify_channel_layout', 'Modify channel layout', 'تعديل تخطيط القناة', 'Wijzig de kanaallay-out', 'Modifier la disposition des canaux', 'Ändern Sie das Kanallayout', 'Изменить макет канала', 'Modificar diseño del canal', 'Kanal düzenini değiştirme'),
('modify_design_position', 'Modify design position', 'تعديل موضع التصميم', 'Wijzig ontwerppositie', 'Modifier la position de conception', 'Ändern Sie die Designposition', 'Изменить дизайн позиции', 'Modificar posicion del diseño', 'Tasarım konumunu değiştirme'),
('monday', 'Monday', 'الإثنين', 'Maandag', 'Lundi', 'Montag', 'понедельник', 'Lunes', 'Pazartesi'),
('month', 'month', 'شهر', 'maand', 'mois', 'Monat', 'месяц', 'mes', 'ay'),
('months', 'months', 'الشهور', 'maanden', 'mois', 'Monate', 'месяцы', 'meses', 'ay'),
('more_info', 'More Info', 'مزيد من المعلومات', 'Meer info', 'Plus d\'infos', 'Mehr Infos', 'Дополнительная информация', 'Más información', 'Daha Fazla Bilgi'),
('more_options', 'More options', 'المزيد من الخيارات', 'Meer opties', 'Plus d\'options', 'Weitere Optionen', 'Больше вариантов', 'Más opciones', 'Diğer seçenekler'),
('music', 'Music', 'موسيقى', 'Muziek', 'Musique', 'Musik', 'Музыка', 'Música', 'Müzik'),
('mute', 'Mute', 'الصمت', 'Stilte', 'Silence', 'Stille', 'безмолвие', 'Silenciar', 'Sessizlik'),
('name', 'Name', 'اسم', 'Naam', 'nom', 'Name', 'имя', 'Nombre', 'İsim'),
('new_email_verified', 'Congratulations, your email is verified. ', 'تهانينا، يتم التحقق من بريدك الإلكتروني.', 'Gefeliciteerd, uw e-mail geverifieerd.', 'Félicitations, votre e-mail est vérifiée.', 'Gratulation, Ihre E-Mail bestätigt.', 'Поздравляем, ваша электронная почта проверяется.', 'Felicitaciones, su correo electrónico se verificó.', 'Tebrikler, e-posta doğrulandı.'),
('new_here', 'New here?', 'جديد هنا؟', 'Nieuw hier?', 'Nouveau ici?', 'Neu hier?', 'Новенький тут?', '¿Nuevo aquí?', 'Burada yeni?'),
('new_list', 'New list', 'قائمة جديدة', 'Nieuwe lijst', 'Nouvelle liste', 'Neue Liste', 'Новый список', 'Nueva lista', 'Yeni liste'),
('new_password', 'New password', 'كلمة مرور جديدة', 'Nieuw paswoord', 'Nouveau mot de passe', 'Neues Passwort', 'Новый пароль', 'Nueva contraseña', 'Yeni Şifre'),
('new_password_dont_match', 'New password doesn\'t match.', 'كلمة المرور الجديدة غير متطابقة.', 'Nieuw wachtwoord komt niet overeen.', 'Le nouveau mot de passe ne correspond pas.', 'Neues Passwort stimmt nicht überein.', 'Новый пароль не соответствует.', 'La nueva contraseña no coincide.', 'Yeni şifre uyuşmuyor.'),
('news', 'News', 'أخبار', 'Nieuws', 'informations', 'Nachrichten', 'Новости', 'Noticias', 'Haberler'),
('next_endvideo', 'Next', 'أدناه', 'Hieronder', 'Ensuite', 'Unten', 'после', 'A continuación', 'Sonra'),
('no', 'No', 'لا', 'Nee', 'Non', 'Nein', 'нет', 'No', 'Yok hayır'),
('no_categories_found_so_far', 'No categories found so far', 'لا توجد فئات حتى الآن', 'Tot nu toe geen categorieën gevonden', 'Aucune catégorie trouvée pour le moment', 'Bisher wurden keine Kategorien gefunden', 'Категории пока не найдены', 'No se encontraron categorías hasta ahora', 'Şimdiye kadar kategori bulunamadı'),
('no_comments_found', 'No comments found', 'لم يتم العثور على تعليقات', 'Geen reacties gevonden', 'Aucun commentaire trouvé', 'Keine Kommentare gefunden', 'Комментариев нет', 'No se encontraron comentarios', 'Hiçbir yorum bulunamadı'),
('no_comments_found_for_now', 'You have no comments so far.', 'ليس لديك أي تعليقات حتى الآن.', 'Je hebt nog geen reacties.', 'Vous n\'avez aucun commentaire pour l\'instant.', 'Sie haben noch keine Kommentare.', 'У вас пока нет комментариев.', 'No tienes ningún comentario hasta ahora.', 'Şu ana kadar hiç yorumunuz yok.'),
('no_internet_access', 'No Internet access', 'لا يوجد اتصال بالإنترنت', 'Geen toegang tot het internet', 'Pas d\'accès Internet', 'Kein Internetzugang', 'Нет доступа в Интернет', 'Sin acceso a internet', 'İnternet erişimi yok'),
('no_lists_found', 'No PlayLists Found!', 'لم يتم العثور على قوائم تشغيل!', 'Ingen spillelister funnet!', 'Aucune liste de lecture trouvée!', 'Keine PlayLists gefunden!', 'Нет списков воспроизведения!', 'No se encontraron listas de reproducción', 'Hiç PlayList Bulunamadı!'),
('no_locks_applied_so_far_found', 'No locks applied so far found', 'لم يتم العثور على أي أقفال مطبقة حتى الآن', 'Tot nu toe geen sloten gevonden', 'Aucun verrou appliqué jusqu\'à présent trouvé', 'Bisher wurden keine Sperren angewendet', 'Блокировок пока не найдено', 'No se encontraron bloqueos aplicados hasta ahora', 'Şu ana kadar uygulanmış kilit bulunamadı'),
('no_logged_add_playlist', 'Sign in to add this video to a playlist.', 'تسجيل الدخول لإضافة هذا الفيديو إلى قائمة التشغيل.', 'Log in om deze video aan een afspeellijst toe te voegen.', 'Connectez-vous pour ajouter cette vidéo à une playlist.', 'Melde dich an, um dieses Video zu einer Wiedergabeliste hinzuzufügen.', 'Войдите, чтобы добавить это видео в плейлист.', 'Inicia sesión para añadir este vídeo a una lista de reproducción.', 'Bu videoyu bir oynatma listesine eklemek için oturum açın.'),
('no_logins_found', 'No logins found', 'لم يتم العثور على عمليات تسجيل دخول', 'Geen logins gevonden', 'Aucune connexion trouvée', 'Keine Anmeldungen gefunden', 'Логинов не найдено', 'No se encontraron inicios de sesión', 'Giriş bulunamadı'),
('no_match_found', 'No match found', 'لا يوجد تطابق', 'Geen overeenkomst gevonden', 'Pas de résultat trouvé', 'Keine Übereinstimmung gefunden', 'Не найдено совпадений', 'No se encontraron coincidencias', 'Eşleşme bulunamadı'),
('no_messages_found_channel', 'No messages were found, please choose a channel to chat.', 'لم يتم العثور على أية رسائل، يرجى اختيار قناة للدردشة.', 'Er zijn geen berichten gevonden. Kies een kanaal om te chatten.', 'Aucun message trouvé, choisissez un canal pour discuter.', 'Es wurden keine Nachrichten gefunden. Wähle einen Chat-Kanal aus.', 'Сообщения не найдены, выберите канал для чата.', 'No se encontraron mensajes, elija un canal para chatear.', 'Hiçbir mesaj bulunamadı, lütfen sohbet etmek için bir kanal seçin.'),
('no_messages_found_hi', 'No messages were found, say Hi!', 'لم يتم العثور على رسائل، ويقول مرحبا!', 'Er zijn geen berichten gevonden, bijvoorbeeld Hallo!', 'Aucun message n\'a été trouvé, brisez la glace et écrivez un bonjour!', 'Keine Nachrichten gefunden, sagen Hallo!', 'Сообщения не найдены, скажите Привет!', 'No se encontraron mensajes, ¡rompe el hielo y escribe un hola!', 'Mesaj bulunamadı, merhaba deyin!'),
('no_messages_found_within_chat_yet', 'No messages found within the chat yet', 'لم يتم العثور على رسائل داخل الدردشة حتى الآن', 'Er zijn nog geen berichten gevonden in de chat', 'Aucun message trouvé dans le chat pour le moment', 'Es wurden noch keine Nachrichten im Chat gefunden', 'В чате пока нет сообщений', 'Aún no se han encontrado mensajes dentro del chat', 'Sohbette henüz mesaj bulunamadı'),
('no_notifications', 'Subscribe to your favorite channels to receive notifications every time new videos upload.', 'اشترك في قنواتك المفضلة لتلقي الإشعارات في كل مرة يتم فيها تحميل مقاطع فيديو جديدة.', 'Abonneer je op je favoriete kanalen om meldingen te ontvangen telkens wanneer nieuwe video&#039;s worden geüpload.', 'Abonnez-vous à vos chaînes préférées pour recevoir des notifications chaque fois que de nouvelles vidéos sont téléchargées.', 'Abonniere deine Lieblingskanäle und erhalte jedes Mal Benachrichtigungen, wenn neue Videos hochgeladen werden.', 'Подпишитесь на ваши любимые каналы, чтобы получать уведомления каждый раз при загрузке новых видео.', 'Suscríbete a tus canales favoritos para recibir notificaciones cada vez que suban nuevos vídeos.', 'Bir tus canales favoritos para recibir notificaciones cada vez que suban nuevos vídeos.'),
('no_reports_found_so_far', 'No reports found so far', 'لم يتم العثور على تقارير حتى الآن', 'Tot dusver geen rapporten gevonden', 'Aucun rapport trouvé jusqu\'à présent', 'Bisher wurden keine Berichte gefunden', 'Пока отчетов не найдено', 'No se encontraron reportes hasta ahora', 'Şu ana kadar hiçbir rapor bulunamadı'),
('no_requests_found_so_far', 'No requests found so far', 'لم يتم العثور على طلبات حتى الآن', 'Tot dusver geen verzoeken gevonden', 'Aucune demande trouvée jusqu\'à présent', 'Bisher wurden keine Anfragen gefunden', 'Bisher wurden keine Anfragen gefunden', 'No se encontraron solicitudes hasta ahora', 'Пока запросов не найдено'),
('no_result_for', 'Sorry, no results found for', 'آسف، لا توجد نتائج ل', 'Beklager, ingen resultater funnet for', 'Désolé, aucun résultat trouvé pour', 'Leider wurden keine Ergebnisse für', 'Извините, результатов не найдено', 'Lo sentimos, no se encontraron resultados para', 'Üzgünüz, bunun hakkında bir sonuç yok'),
('no_results_found', 'No results found', 'لم يتم العثور على نتائج', 'geen resultaten gevonden', 'Aucun résultat trouvé', 'keine Ergebnisse gefunden', 'результаты не найдены', 'No se encontraron resultados', 'Sonuç bulunamadı'),
('no_users_found', 'No users found', 'لم يتم العثور على أي مستخدم', 'Geen gebruikers gevonden', 'Aucun utilisateur trouvé', 'Keine Benutzer gefunden', 'Пользователи не найдены', 'No se encontraron usuarios', 'Kullanıcı bulunamadı'),
('no_video_play', 'The video does not play', 'لا يتم تشغيل الفيديو', 'De video wordt niet afgespeeld', 'La vidéo ne joue pas', 'Das Video wird nicht abgespielt', 'Видео не проигрывается', 'El vídeo no se reproduce', 'Video oynatılmıyor'),
('no_videos_found_for_now', 'No videos found for now!', 'لم يتم العثور على مقاطع فيديو في الوقت الحالي!', 'Er zijn nog geen video\'s gevonden!', 'Aucune vidéo trouvée pour l\'instant!', 'Bisher keine Videos gefunden!', 'Видео не найдено пока!', '¡No se encontraron vídeos por ahora!', 'Şuan için bir video bulunamadı!'),
('no_videos_found_history', 'No videos found, watch to get started!', 'لم يتم العثور على مقاطع فيديو، يمكنك مشاهدة الخطوات الأولى!', 'Geen video\'s gevonden, kijk om te beginnen!', 'Aucune vidéo n\'a été trouvée, regardez pour commencer!', 'Keine Videos gefunden, schau, um loszulegen!', 'Видео не найдено, следите, чтобы начать!', 'No se han encontrado vídeos, ¡mira para empezar!', 'Hiçbir video bulunamadı, başlamak için izleyin!');
INSERT INTO `words` (`word`, `en`, `ar`, `nl`, `fr`, `an`, `ru`, `es`, `tr`) VALUES
('no_videos_found_subs', 'No videos found, subscribe to get started!', 'لم يتم العثور على مقاطع فيديو، اشترك في الخطوات الأولى!', 'Geen video\'s gevonden, schrijf je in om te beginnen!', 'Aucune vidéo n\'a été trouvée, inscrivez-vous pour commencer!', 'Keine Videos gefunden, abonnieren, um loszulegen!', 'Видео не найдено, подписаться, чтобы начать работу!', 'No videos encontrados, suscríbase para empezar!', 'Hiçbir video bulunamadı, başlamak için abone olun!'),
('no_words_found_so_far', 'No words found so far', 'لا توجد كلمات حتى الآن', 'Tot dusver geen woorden gevonden', 'Aucun mot trouvé pour l\'instant', 'Bisher keine Worte gefunden', 'Пока слов не найдено', 'No se encontraron palabras hasta ahora', 'Şimdiye kadar hiçbir kelime bulunamadı'),
('normal', 'Normal', 'عادي', 'Normaal', 'Ordinaire', 'Normal', 'Нормальный', 'Normal', 'Normal'),
('not_create_account_associated_email', 'If you did not create this account associated with this email {$email}, please let us know so that we can take appropriate action to disable this account.', 'إذا لم تقم بإنشاء هذا الحساب المرتبط بهذا البريد الإلكتروني {$email} ، فيرجى إخبارنا حتى نتمكن من اتخاذ الخطوات المناسبة لتعطيل هذا الحساب.', 'Als u dit account niet heeft gemaakt dat is gekoppeld aan deze e-mail {$email}, laat het ons dan weten zodat we de nodige stappen kunnen ondernemen om dit account uit te schakelen.', 'Si vous n\'avez pas créé ce compte associé à cet e-mail {$email}, veuillez nous en informer afin que nous puissions prendre les mesures appropriées pour désactiver ce compte.', 'Wenn Sie dieses mit dieser E-Mail verknüpfte Konto {$email} nicht erstellt haben, teilen Sie uns dies bitte mit, damit wir die entsprechenden Schritte zum Deaktivieren dieses Kontos ausführen können.', 'Если вы не создавали этот аккаунт, связанный с этим адресом электронной почты {$email}, сообщите нам об этом, чтобы мы могли предпринять соответствующие шаги для отключения этого аккаунта.', 'Si no creó esta cuenta asociada con este correo electrónico {$email}, háganoslo saber para que podamos tomar las medidas adecuadas para deshabilitar esta cuenta.', 'Bu e-posta {$email} ile ilişkili bu hesabı siz oluşturmadıysanız, lütfen bize bildirin, böylece bu hesabı devre dışı bırakmak için uygun adımları atabiliriz.'),
('not_verified', 'Not verified', 'لم يتم التحقق منه', 'niet geverifieerd', 'non vérifié', 'Nicht verifiziert', 'не подтверждено', 'No verificado', 'Doğrulanmadı'),
('note_may_take_minutes', 'Note: This may take up to 5 minutes.', 'ملاحظة: قد يستغرق هذا ما يصل إلى 5 دقائق.', 'Opmerking: dit kan tot 5 minuten duren.', 'Remarque: cela peut prendre jusqu\'à 5 minutes.', 'Hinweis: Dies kann bis zu 5 Minuten dauern.', 'Примечание. Это может занять до 5 минут.', 'Nota: esto puede tardar hasta 5 minutos.', 'Not: Bu 5 dakika kadar sürebilir.'),
('notifications', 'Notifications', 'إخطارات', 'Varsler', 'Notifications', 'Benachrichtigungen', 'Уведомления', 'Notificaciones', 'Bildirimler'),
('noting_reply', 'None of the above options corresponds to my problem.', 'أي من الخيارات المذكورة أعلاه يتوافق مع مشكلتي.', 'Geen van de bovenstaande opties komt overeen met mijn probleem.', 'Aucune des options ci-dessus ne correspond à mon problème.', 'Keine der obigen Optionen entspricht meinem Problem.', 'Ни один из вышеперечисленных вариантов не соответствует моей проблеме.', 'Ninguna de las opciones anteriores corresponde con mi problema.', 'Yukarıdaki seçeneklerin hiçbiri sorunuma karşılık gelmiyor.'),
('november', 'November', 'تشرين الثاني', 'november', 'Novembre', 'November', 'ноябрь', 'Noviembre', 'Kasım'),
('now', 'Now', 'الآن', 'Nu', 'Maintenant', 'Jetzt', 'Сейчас', 'Ahora', 'şimdi'),
('number_default_comments', 'Number of default comments', 'عدد التعليقات الافتراضية', 'Aantal standaardopmerkingen', 'Nombre de commentaires par défaut', 'Anzahl der Standardkommentare', 'Количество комментариев по умолчанию', 'Cantidad de comentarios predeterminados', 'Varsayılan yorumların sayısı'),
('october', 'October', 'أكتوبر', 'oktober', 'Octobre', 'Oktober', 'октября', 'Octubre', 'Ekim'),
('of', 'of', 'من', 'van', 'de', 'von', 'из', 'de', 'nın-nin'),
('one_who_has_registered_this_account', 'If you are not the one who has registered this account', 'إذا لم تكن أنت من قام بتسجيل هذا الحساب', 'Als u niet degene bent die dit account heeft geregistreerd', 'Si vous n\'êtes pas celui qui a enregistré ce compte', 'Wenn Sie nicht derjenige sind, der dieses Konto registriert hat', 'Если вы не тот, кто зарегистрировал эту учетную запись', 'Si no has sido tú quien ha registrado esta cuenta', 'Bu hesabı kaydeden siz değilseniz'),
('only_english_letters_no_spaces_allowed_example_english', 'Use only English letters, no spaces are allowed. Example: English', 'استخدم الأحرف الإنجليزية فقط ، ولا يسمح باستخدام مسافات. مثال: English', 'Gebruik alleen Engelse letters, spaties zijn niet toegestaan. Voorbeeld: English', 'Utilisez uniquement des lettres anglaises, aucun espace n\'est autorisé. Exemple: English', 'Verwenden Sie nur englische Buchstaben, Leerzeichen sind nicht zulässig. Beispiel: English', 'Используйте только английские буквы, без пробелов. Пример: English', 'Utiliza solo letras en inglés, no se permiten espacios. Ejemplo: English', 'Yalnızca İngilizce harfler kullanın, boşluklara izin verilmez. Örnek: English'),
('only_english_letters_no_spaces_allowed_example_key', 'Use only english letters, no spaces allowed, example: this_is_a_key', 'استخدم الأحرف الإنجليزية فقط ، ولا يسمح بمسافات ، على سبيل المثال: this_is_a_key', 'Gebruik alleen Engelse letters, geen spaties toegestaan, voorbeeld: this_is_a_key', 'Utilisez uniquement des lettres anglaises, aucun espace autorisé, exemple: this_is_a_key', 'Verwenden Sie nur englische Buchstaben, keine Leerzeichen erlaubt, Beispiel: this_is_a_key', 'Используйте только английские буквы, без пробелов, например: this_is_a_key', 'Use solo letras en inglés, no se permiten espacios, ejemplo: this_is_a_key', 'Yalnızca İngilizce harfler kullanın, boşluklara izin verilmez, örnek: this_is_a_key'),
('only_two_letters_lowercase_spaces_allowed', 'Use only two letters and lowercase, no spaces are allowed. Example: en', 'استخدم حرفين فقط وحروف صغيرة ، ولا يُسمح باستخدام مسافات. مثال: en', 'Gebruik slechts twee letters en kleine letters, spaties zijn niet toegestaan. Voorbeeld: en', 'N\'utilisez que deux lettres et minuscules, aucun espace n\'est autorisé. Exemple: en', 'Verwenden Sie nur zwei Buchstaben und Kleinbuchstaben, Leerzeichen sind nicht zulässig. Beispiel: en', 'Используйте только две буквы и строчные буквы, пробелы не допускаются. Пример: en', 'Utiliza solo dos letras y en minúsculas, no se permiten espacios. Ejemplo: en', 'Yalnızca iki harf ve küçük harf kullanın, boşluklara izin verilmez. Misal: en'),
('oops', 'Oops', 'وجه الفتاة', 'Oops', 'Oops', 'Hoppla', 'Ой', '¡Uy!', 'Hata'),
('operating_system', 'Operating system', 'نظام التشغيل', 'Besturingssysteem', 'Système opérateur', 'Betriebssystem', 'Операционная система', 'Sistema operativo', 'İşletim sistemi'),
('optional', 'Optional', 'اختياري', 'Optioneel', 'Optionnel', 'Optional', 'Необязательный', 'Opcional', 'İsteğe bağlı'),
('or', 'or', 'أو', 'of', 'ou', 'oder', 'или', 'o', 'veya'),
('other', 'Other', 'آخر', 'Andere', 'Autre', 'Andere', 'разное', 'Otro', 'Diğer'),
('other_settings', 'Other Settings', 'اعدادات اخرى', 'Andere instellingen', 'Autres réglages', 'Andere Einstellungen', 'Другие настройки', 'Otros ajustes', 'Diğer ayarlar'),
('others', 'Others', 'الآخرين', 'Anderen', 'Autres', 'Andere', 'Другие', 'Otros', 'Diğerleri'),
('page_limit_for_tables', 'Page limit for tables', 'حد الصفحة للجداول', 'Paginalimiet voor tabellen', 'Limite de pages pour les tableaux', 'Seitenlimit für Tabellen', 'Лимит страниц для таблиц', 'Límite de páginas para tablas', 'Tablolar için sayfa sınırı'),
('pages', 'Pages', 'الصفحات', 'Pagina\'s', 'pages', 'Seiten', 'страницы', 'Páginas', 'Sayfaları'),
('password', 'Password', 'كلمه السر', 'Wachtwoord', 'Mot de passe', 'Passwort', 'пароль', 'Contraseña', 'Parola'),
('password_is_short', 'Password is too short', 'كلمة المرور قصيرة جدا', 'Wachtwoord is te kort', 'Le mot de passe est trop court', 'Das Passwort ist zu kurz', 'Пароль слишком короткий', 'La contraseña es demasiado corta', 'Şifre çok kısa'),
('password_not_match', 'Password not match', 'كلمة المرور غير متطابقة', 'Wachtwoord niet overeenkomen', 'Le mot de passe ne correspond pas', 'Passwort nicht übereinstimmen', 'Пароль не соответствует', 'La contraseña no coincide', 'Şifre eşleşmiyor'),
('password_settings', 'Password Settings', 'إعدادات كلمة المرور', 'Wachtwoordinstellingen', 'Paramètres du mot de passe', 'Kennworteinstellungen', 'Настройки пароля', 'Configuración de la contraseña', 'Şifre Ayarları'),
('pause', 'Pause', 'وقفة', 'Pauze', 'Pause', 'Pause', 'Пауза', 'Pausa', 'Duraklat'),
('pending', 'Pending', 'ريثما', 'in afwachting van', 'en attente', 'anstehend', 'в ожидании', 'Pendiente', 'kadar'),
('pending_apporval', 'Pending Approval', 'ما زال يحتاج بتصدير', 'In afwachting van goedkeuring', 'En attente de validation', 'Ausstehende Genehmigung', 'В ожидании утверждения', 'Aprobación pendiente', 'Onay bekleyen'),
('pin_up', 'Pin up', 'الإصلاح', 'Fix', 'Ensemble', 'Einstellen', 'исправление', 'Fijar', 'Düzeltme'),
('play', 'Play', 'لعب', 'Speel', 'Jouer', 'Abspielen', 'играть', 'Reproducir', 'Oyna'),
('play_all', 'Play all', 'لعب الكل', 'Speel alles', 'Tout jouer', 'Alles spielen', 'Воспроизвести все', 'Reproducir todo', 'Tümünü oynat'),
('playback_history', 'Playback history', 'سجل التشغيل', 'Afspeelgeschiedenis', 'Historique de lecture', 'Wiedergabeverlauf', 'История воспроизведения', 'Historial de reproducciones', 'Oynatma geçmişi'),
('playlist_name', 'Playlist name', 'اسم قائمة التشغيل', 'Spilleliste navn', 'Nom de la liste de lecture', 'Playlist-Name', 'Название плейлиста', 'Nombre de la lista de reproducción', 'Çalma listesi adı'),
('playlist_next', 'Next (SHIFT + N)', 'التالي (SHIFT + N)', 'Volgende (SHIFT + N)', 'Suivant (MAJ + N)', 'Weiter (UMSCHALT + N)', 'Далее (SHIFT + N)', 'Siguiente (SHIFT+N)', 'Sonraki (ÜST KARAKTER + N)'),
('playlist_prev', 'Previous (SHIFT + P)', 'السابق (SHIFT + P)', 'Vorige (SHIFT + P)', 'Précédent (SHIFT + P)', 'Zurück (UMSCHALT + P)', 'Предыдущая (SHIFT + P)', 'Anterior (SHIFT+P)', 'Önceki (SHIFT + P)'),
('playlists', 'PlayLists', 'قوائم التشغيل', 'spillelister', 'PlayLists', 'PlayLists', 'плейлисты', 'Listas de reproducción', 'çalma'),
('please_contact', 'please contact us', 'الرجاء التواصل معنا', 'Gelieve ons te contacteren', 'Contactez nous s\'il vous plait', 'bitte kontaktieren Sie uns', 'пожалуйста свяжитесь с нами', 'Por favor contáctenos', 'lütfen bizimle iletişime geçin'),
('please_enter_valid_date', 'Introduce una fecha válida', 'ارجوك ادخل تاريخ صحيح', 'Gelieve een correcte datum in te voeren', 'veuillez entrer une date valide', 'Bitte gib ein korrektes Datum an', 'Пожалуйста, введите правильную дату', 'Introduce una fecha válida', 'Lütfen geçerli bir tarih giriniz'),
('please_wait', 'Please wait..', 'أرجو الإنتظار..', 'Even geduld aub..', 'S\'il vous plaît, attendez..', 'Warten Sie mal..', 'Пожалуйста, подождите..', 'Por favor espera..', 'Lütfen bekle..'),
('porcessing_video', 'Processing your video - this may take a few minutes', 'معالجة الفيديو - قد يستغرق ذلك بضع دقائق', 'Je video verwerken - dit kan een paar minuten duren', 'Traitement de votre vidéo - cela peut prendre quelques minutes', 'Verarbeitung deines Videos - dies kann einige Minuten dauern', 'Обработка вашего видео - это может занять несколько минут', 'Procesando su video: esto puede demorar unos minutos', 'Videonuzu işlemek - bu birkaç dakika alabilir'),
('preferences', 'Preferences', 'التفضيلات', 'Voorkeuren', 'Préférences', 'Einstellungen', 'предпочтения', 'Preferencias', 'Tercihleri'),
('privacy', 'Privacy', 'خصوصية', 'personvern', 'Confidentialité', 'Privatsphäre', 'Приватность', 'Privacidad', 'gizlilik'),
('privacy_policy', 'Privacy Policy', 'سياسة الخصوصية', 'Privacybeleid', 'Politique de confidentialité', 'Datenschutz-Bestimmungen', 'политика конфиденциальности', 'Política de privacidad', 'Gizlilik Politikası'),
('private', 'Private', 'خاص', 'privat', 'Privé', 'Privat', 'Приватный', 'Privado', 'özel'),
('private_video', 'Private video', 'فيديو خاص', 'Privé video', 'Vidéo privée', 'Privates Video', 'Частное видео', 'Vídeo privado', 'Özel video'),
('processing_the_video', 'Processing the video', 'معالجة الفيديو', 'De video wordt verwerkt', 'Traitement de la vidéo', 'Das Video wird verarbeitet', 'Обработка видео', 'Procesando el vídeo', 'Video işleniyor'),
('profile', 'Profile', 'الملف الشخصي', 'Profiel', 'Profil', 'Profil', 'Профиль', 'Perfil', 'Profil'),
('public', 'Public', 'جمهور', 'offentlig', 'Public', 'Öffentlichkeit', 'Публичный', 'Público', 'kamu'),
('publish', 'Publish', 'نشر', 'Publiceren', 'Publier', 'Veröffentlichen', 'Публиковать', 'Publicar', 'yayınlamak'),
('published_on', 'Published on ', 'نشرت في', 'gepubliceerd op', 'Publié le', 'Veröffentlicht auf', 'Опубликован в', 'Publicado el', 'yayınlandı'),
('quality', 'Quality', 'جودة', 'Kwaliteit', 'Qualité', 'Qualität', 'Качественный', 'Calidad', 'Kalite'),
('reason', 'Reason', 'السبب', 'Reden', 'Raison', 'Grund', 'Причина', 'Motivo', 'Nedeni'),
('reCaptcha_error', 'Please Check the re-captcha.', 'يرجى التحقق من إعادة كابتشا.', 'Controleer alstublieft de re-captcha.', 'Vérifiez le re-captcha.', 'Bitte überprüfen Sie das Re-Captcha.', 'Проверьте перехват.', 'Por favor, compruebe la re-captcha.', 'Lütfen yeniden captcha\'yı kontrol edin.'),
('recaptcha_key', 'Recaptcha Key', 'مفتاح Recaptcha', 'Recaptcha-toets', 'Recaptcha Key', 'Recaptcha-Schlüssel', 'Ключ рекапчи', 'Llave Recaptcha', 'Recaptcha Anahtarı'),
('receive_message_can_see_tray_because_everything_working_very_well', 'If you receive this message and you can see it in your tray, it is because everything is working very well.', 'إذا تلقيت هذه الرسالة ويمكنك رؤيتها في درجتك ، فذلك لأن كل شيء يعمل بشكل جيد للغاية.', 'Als u dit bericht ontvangt en u kunt het in uw lade zien, komt dat omdat alles erg goed werkt.', 'Si vous recevez ce message et que vous pouvez le voir dans votre barre d\'état, c\'est que tout fonctionne très bien.', 'Wenn Sie diese Nachricht erhalten und sie in Ihrem Fach sehen können, liegt dies daran, dass alles sehr gut funktioniert.', 'Если вы получили это сообщение и видите его на панели задач, это значит, что все работает очень хорошо.', 'Si recibes este mensaje y puedes verlo en tu bandeja , es por que todo esta trabajando muy bien.', 'Bu mesajı alırsanız ve tepsinizde görürseniz, bunun nedeni her şeyin çok iyi çalıştığıdır.'),
('redirection_notice', 'Redirection notice', 'إشعار إعادة التوجيه', 'Omleidingsbericht', 'Avis de redirection', 'Weiterleitungshinweis', 'Уведомление о перенаправлении', 'Aviso de redirección', 'Yeniden yönlendirme bildirimi'),
('register', 'Register', 'تسجيل', 'Registreren', 'registre', 'Neu registrieren', 'регистр', 'Registro', 'Kayıt olmak'),
('registered_users', 'Registered users', 'المستخدمون المسجلون', 'Geregistreerde gebruikers', 'Utilisateurs enregistrés', 'Utilisateurs enregistrés', 'Зарегистрированные пользователи', 'Usuarios registrados', 'Kayıtlı kullanıcılar'),
('reject_', 'Reject', 'رفض', 'Weigeren', 'Rejeter', 'Ablehnen', 'Отклонить', 'Rechazar', 'Reddet'),
('reject_request', 'Reject request', 'رفض الطلب', 'Verzoek afwijzen', 'Rejeter la demande', 'Anfrage ablehnen', 'Отклонить запрос', 'Rechazar solicitud', 'İsteği reddet'),
('rejected', 'Rejected', 'مرفوض', 'verworpen', 'Rejeté', 'Abgelehnt', 'отвергнуто', 'Rechazado', 'reddedilen'),
('related_videos', 'Related Videos', 'فيديوهات ذات علاقة', 'Relaterte videoer', 'Vidéos connexes', 'Ähnliche Videos', 'Похожие видео', 'Videos relacionados', 'İlgili videolar'),
('remember_not_violate_terms_accepted', 'Remember not to violate any of the terms accepted when you created your account', 'تذكر عدم انتهاك أي من الشروط المقبولة عند إنشاء حسابك', 'Denk eraan om geen enkele van de voorwaarden te schenden die werden geaccepteerd bij het aanmaken van uw account', 'N\'oubliez pas de ne violer aucune des conditions acceptées lors de la création de votre compte', 'Denken Sie daran, keine der Bedingungen zu verletzen, die bei der Erstellung Ihres Kontos akzeptiert wurden', 'Помните, что нельзя нарушать ни одно из условий, принятых при создании учетной записи.', 'Recuerda no infringir ninguno de los términos aceptados cuando creaste tu cuenta', 'Hesabınızı oluştururken kabul edilen şartların hiçbirini ihlal etmemeyi unutmayın.'),
('remove_account_from', 'Remove your account from {$settings->name}', 'إزالة حسابك من {$settings->name}', 'Verwijder uw account uit {$settings->name}', 'Supprimer votre compte de {$settings->name}', 'Entfernen Sie Ihr Konto aus {$settings->name}', 'Удалите свой аккаунт из {$settings->name}', 'Elimina tu cuenta de {$settings->name}', 'Hesabınızı {$settings->name} \'ndan kaldırın'),
('remove_category', 'Remove category', 'إزالة الفئة', 'Verwijder categorie', 'Supprimer la catégorie', 'Kategorie entfernen', 'Удалить категорию', 'Eliminar categoría', 'Kategoriyi kaldır'),
('remove_from_watch_history', 'Remove from watch history', 'حذف من سجل المشاهدة', 'Verwijderen uit kijkgeschiedenis', 'Supprimer de l\'historique de surveillance', 'Aus dem Uhrenverlauf löschen', 'Удалить из истории просмотра', 'Eliminar de historial de reproducciones', 'İzleme geçmişinden sil'),
('remove_i_dont_like', 'Remove ‘I don&#039;t like it’', 'أزل &quot;أنا لا أحب ذلك&quot;', 'Verwijderen &#039;Ik vind het niet leuk&#039;', 'Supprimer &quot;Je n&#039;aime pas ça&quot;', 'Entfernen Sie &quot;Ich mag es nicht&quot;', 'Удалить &quot;Мне не нравится&quot;', 'Quitar ‘No me gusta’', '‘Hoşuma gitmedi’ öğesini kaldır'),
('remove_language', 'Remove language', 'إزالة اللغة', 'Verwijder taal', 'Supprimer la langue', 'Sprache entfernen', 'Удалить язык', 'Eliminar lenguaje', 'Dili kaldır'),
('remove_lock', 'Remove lock', 'قم بإزالة القفل', 'Slot verwijderen', 'Retirer le verrou', 'Schloss entfernen', 'Снять блокировку', 'Eliminar bloqueo', 'Kilidi kaldır'),
('removed_from', 'Removed from', 'تمت الإزالة من', 'Fjernet fra', 'Retiré de', 'Entfernt von', 'Удалено из', 'Eliminado de', 'Kaldırıldı'),
('removed_history', 'All views in this video have been removed from the history', 'تمت إزالة جميع المشاهدات في هذا الفيديو من السجل', 'Alle weergaven in deze video zijn verwijderd uit de geschiedenis', 'Toutes les vues de cette vidéo ont été supprimées de l\'historique', 'Alle Ansichten in diesem Video wurden aus dem Verlauf entfernt', 'Все просмотры в этом видео были удалены из истории', 'Se quitaron del historial todas las vistas de este video', 'Bu videodaki tüm görüntülemeler geçmişten kaldırıldı'),
('removed_item_comments', 'Item removed from your comments', 'تمت إزالة العنصر من تعليقاتك', 'Item verwijderd uit uw opmerkingen', 'Élément supprimé de vos commentaires', 'Artikel aus Ihren Kommentaren entfernt', 'Предмет удален из ваших комментариев', 'Se quitó el elemento de tus comentarios', 'Öğe yorumlarınızdan kaldırıldı'),
('replace', 'Replace', 'يحل محل', 'Vervangen', 'Remplacer', 'Ersetzen', 'Заменить', 'Reemplazar', 'Değiştir'),
('replay', 'Replay', 'إعادة', 'Herhaling', 'Rejouer', 'Wiederholung', 'переигровка', 'Volver a reproducir', 'Tekrar'),
('replied_to_your_comment', 'replied to your comment', 'أجاب على تعليقك', 'svarte på din kommentar', 'a répondu à votre commentaire', 'antwortete auf deinen Kommentar', 'ответил на ваш комментарий', 'respondió a tu comentario', 'senin yorumuna cevap verdi'),
('reply', 'Reply', 'الرد', 'Antwoord', 'Répondre', 'Antworten', 'Ответить', 'Responder', 'Cevap'),
('reply_deleted', 'Reply deleted', 'تم حذف الرد', 'Antwoord verwijderd', 'Réponse supprimée', 'Antwort gelöscht', 'Ответ удален', 'Respuesta eliminada', 'Yanıt silindi'),
('report', 'Report', 'أبلغ عن', 'Rapportere', 'rapport', 'Bericht', 'Отчет', 'Denunciar', 'Rapor'),
('report_been_deleted_successfully', 'The report has been deleted successfully', 'تمت إزالة التقرير بنجاح', 'Het rapport is succesvol verwijderd', 'Le rapport a été supprimé avec succès', 'Der Bericht wurde erfolgreich gelöscht', 'Отчет успешно удален', 'El reporte se ha eliminado con éxito', 'Rapor başarıyla kaldırıldı'),
('report_marked_reviewed', 'The report was marked as reviewed', 'تم وضع علامة على التقرير كمراجع', 'Het rapport is gemarkeerd als beoordeeld', 'Le rapport a été marqué comme révisé', 'Der Bericht wurde als überprüft markiert', 'Отчет отмечен как просмотренный.', 'El reporte fue marcado como revisado', 'Rapor incelendi olarak işaretlendi'),
('report_this_account', 'Report this account', 'أبلغ عن هذا الحساب', 'Rapporteer dit account', 'Signaler ce compte', 'Dieses Konto melden', 'Пожаловаться на этот аккаунт', 'Reportar esta cuenta', 'Bu hesabı bildirin'),
('report_this_user', 'Report this user', 'الإبلاغ عن هذا المستخدم', 'Rapporteer deze gebruiker', 'Signaler cet utilisateur', 'Diesen Nutzer melden', 'Сообщить об этом пользователе', 'Denuncia este usuario', 'Bu kullanıcıyı rapor et'),
('report_this_video', 'Report this video', 'ابلغ عن هذا الفيديو', 'Rapporter denne videoen', 'Signaler cette vidéo', 'Melde dieses Video', 'Сообщить об этом видео', 'Denuncia este vídeo', 'Bu videoyu rapor et'),
('reported', 'Reported', 'ذكرت', 'Gemeld', 'Signalé', 'Berichtet', 'Сообщается', 'Reportado', 'Bildirildi'),
('reported_content', 'Reported content', 'المحتوى المبلغ عنه', 'Gerapporteerde inhoud', 'Contenu signalé', 'Gemeldeter Inhalt', 'Сообщенный контент', 'Contenido reportado', 'Bildirilen içerik'),
('reports', 'Reports', 'التقارير', 'Rapporten', 'Rapports', 'Berichte', 'Отчеты', 'Informes', 'Raporlar'),
('representative_image_of_the_site', 'Representative image of the site', 'صورة تمثيلية للموقع', 'Representatief beeld van de site', 'Image représentative du site', 'Repräsentatives Bild der Website', 'Репрезентативное изображение сайта', 'Imagen representativa del sitio', 'Sitenin temsili görüntüsü'),
('request_new_password', 'Send an e-mail', 'إرسال البريد الإلكتروني', 'Verzend e-mail', 'Envoyer un e-mail', 'E-Mail senden', 'Отправить письмо', 'Enviar correo electrónico', 'E-posta Gönder'),
('request_not_found', 'Request not found', 'طلب غير موجود', 'Verzoek niet gevonden', 'Demande introuvable', 'Anfrage nicht gefunden', 'Запрос не найден', 'Solicitud no encontrada', 'İstek bulunamadı'),
('resend_code', 'Resend code', 'أعد إرسال الرمز', 'Code nogmaals versturen', 'Renvoyer le code', 'Code erneut senden', 'Отправить код еще раз', 'Reenviar código', 'Yeniden gönderme kodu'),
('resend_email', 'Resend E-mail', 'إعادة إرسال البريد الإلكتروني', 'Email opnieuw verzenden', 'Ré-envoyer l\'email', 'E-Mail zurücksenden', 'Повторно отправить E-mail', 'Reenviar email', 'Elektronik postayı tekrar gönder'),
('reset_your_password', 'Reset your password', 'اعد ضبط كلمه السر', 'Stel je wachtwoord opnieuw in', 'réinitialisez votre mot de passe', 'Setze dein Passwort zurück', 'Сбросить пароль', 'Restablece tu contraseña', 'Şifrenizi sıfırlayın'),
('restore', 'Restore', 'استعادة', 'Herstellen', 'Restaurer', 'Wiederherstellen', 'Восстановить', 'Restablecer', 'Onarmak'),
('return_to', 'Return to {$settings->name}', 'عودة إلى {$settings->name}', 'Ga terug naar {$settings->name}', 'Revenir à {$settings->name}', 'Kehren Sie zu {$settings->name} zurück', 'Вернуться к {$settings->name}', 'Regresar a {$settings->name}', '{$settings->name} \'na dön'),
('saturday', 'Saturday', 'السبت', 'Zaterdag', 'Samedi', 'Samstag', 'суббота', 'Sabado', 'Cumartesi'),
('save', 'Save', 'حفظ', 'Save', 'sauvegarder', 'sparen', 'Сохранить', 'Guardar', 'Kayıt etmek'),
('save_live_stream_when_finished', 'Save live stream when finished', 'حفظ البث المباشر عند الانتهاء', 'Sla de livestream op als u klaar bent', 'Enregistrer la diffusion en direct une fois terminé', 'Speichern Sie den Live-Stream, wenn Sie fertig sind', 'Сохраните прямую трансляцию, когда закончите', 'Guardar la transmisión en vivo al finalizar', 'Bitirdiğinizde canlı yayını kaydedin'),
('search_keyword', 'Search', 'بحث', 'Zoeken', 'Chercher', 'Suche', 'поиск', 'Buscar', 'Arama'),
('second', 'second', 'ثانيا', 'tweede', 'seconde', 'zweite', 'второй', 'segundo', 'ikinci'),
('seconds', 'seconds', 'ثواني', 'seconden', 'secondes', 'Sekunden', 'секунд', 'segundos', 'saniye'),
('security', 'Security', 'أمن', 'Veiligheid', 'Sécurité', 'Sicherheit', 'безопасность', 'Seguridad', 'Güvenlik'),
('see', 'See', 'نرى', 'Zien', 'Regarder', 'Sehen', 'Видеть', 'Ver', 'Görmek'),
('see_answers', 'See {$all_r} answers', 'انظر إجابات {$all_r}', 'Zie {$all_r} antwoorden', 'Voir les {$all_r} réponses', 'Siehe {$all_r} Antworten', 'Смотрите {$all_r} ответы', 'Ver {$all_r} respuestas', '{$all_r} yanıtına bakın'),
('see_more', 'See more', 'المزيد', 'Zie meer', 'Voir plus', 'Mehr sehen', 'Увидеть больше', 'Ver más', 'Daha fazlasını görün'),
('select_a_file', 'Select a file', 'حدد ملف', 'Selecteer een bestand', 'Sélectionner un fichier', 'Wählen Sie eine Datei aus', 'Выберите файл', 'Seleccionar un archivo', 'Bir dosya seçin'),
('send', 'Send', 'إرسال', 'Sturen', 'Envoyer', 'Senden', 'Страница, которую вы искали, не существует.', 'Enviar', 'Gönder'),
('september', 'September', 'أيلول', 'September', 'Septembre', 'September', 'сентябрь', 'Septiembre', 'Eylül'),
('server_type', 'Server type', 'نوع الخادم', 'Server type', 'Type de serveur', 'Server Typ', 'Тип сервера', 'Tipo de servidor', 'Sunucu tipi'),
('set_by', 'Set by', 'تعيين بواسطة', 'Ingesteld door', 'Défini par', 'Set von', 'Устанавливается', 'Fijado por', 'Ayarlayan'),
('set_the_limit_of_some_items', 'Set the limit of some items', 'تعيين حد لبعض العناصر', 'Stel de limiet van enkele items in', 'Définir la limite de certains éléments', 'Legen Sie das Limit für einige Elemente fest', 'Установить лимит некоторых предметов', 'Configura el límite de algunos items', 'Bazı öğelerin sınırını ayarlayın'),
('set_up_your_website_information', 'Set up your website information', 'قم بإعداد معلومات موقع الويب الخاص بك', 'Stel uw website-informatie in', 'Configurer les informations de votre site Web', 'Richten Sie Ihre Website-Informationen ein', 'Настройте информацию о своем веб-сайте', 'Configura la información de tu sitio web', 'Web sitesi bilgilerinizi ayarlayın'),
('set_website_ads_here', 'Set up your website ads here', 'قم بإعداد إعلانات موقع الويب الخاص بك هنا', 'Stel hier uw website-advertenties in', 'Configurez les annonces de votre site Web ici', 'Richten Sie hier Ihre Website-Anzeigen ein', 'Настройте рекламу своего веб-сайта здесь', 'Configura aquí los anuncios de tu sitio web', 'Web sitesi reklamlarınızı burada ayarlayın'),
('set_website_pages', 'Set up your website pages', 'قم بإعداد صفحات موقع الويب الخاص بك', 'Stel uw webpagina’s op', 'Configurez les pages de votre site Web', 'Richten Sie Ihre Webseiten ein', 'Настройте страницы своего сайта', 'Configura las páginas de tu sitio web', 'Web sitenizin sayfalarını ayarlayın'),
('setting_updated', 'Updated configuration!', 'التكوين المحدث!', 'Bijgewerkte configuratie!', 'Configuration mise à jour!', 'Konfiguration aktualisiert!', 'Обновленная конфигурация!', '¡Configuración actualizada!', 'Обновленная конфигурация!'),
('settings', 'Settings', 'إعدادات', 'instellingen', 'Paramètres', 'Einstellungen', 'настройки', 'Ajustes', 'Ayarlar'),
('settings_', 'Settings', 'التكوينات', 'Configuraties', 'Configurations', 'Konfigurationen', 'Конфигурации', 'Configuraciones', 'yapılandırmalar'),
('sexual_content', 'Sexual content', 'المحتوى الجنسي', 'Seksuele inhoud', 'Contenu sexuel', 'Sexueller Inhalt', 'Сексуальный контент', 'Contenido sexual', 'Cinsel içerik'),
('share', 'Share', 'شارك', 'Delen', 'Partager', 'Aktie', 'Поделиться', 'Compartir', 'Pay'),
('show_less', 'Show less', 'عرض أقل', 'Vis mindre', 'Montre moins', 'Zeige weniger', 'Показывай меньше', 'Mostrar menos', 'Daha az göster'),
('show_more', 'Show more', 'أظهر المزيد', 'Laat meer zien', 'Montre plus', 'Zeig mehr', 'Показать больше', 'Mostrar más', 'Daha fazla göster'),
('show_more_answers', 'Show more answers', 'إظهار المزيد من الإجابات', 'Toon meer antwoorden', 'Afficher plus de réponses', 'Weitere Antworten anzeigen', 'Показать больше ответов', 'Mostrar más respuestas', 'Daha fazla cevap göster'),
('sidebar_ad', 'Sidebar Ad', 'إعلان الشريط الجانبي', 'Zijbalkadvertentie', 'Annonce de la barre latérale', 'Seitenleistenanzeige', 'Боковое объявление', 'Anuncio de barra lateral', 'Kenar Çubuğu Reklamı'),
('sign_in', 'Sign in', 'تسجيل الدخول', 'Aanmelden', 'se connecter', 'Anmelden', 'войти в систему', 'Accede', 'oturum aç'),
('sign_up', 'Sign Up!', 'سجل!', 'Aanmelden!', 'S\'inscrire!', 'Anmelden!', 'Зарегистрироваться!', '¡Regístrate!', 'Kaydol!'),
('site_logo', 'Site logo', 'شعار الموقع', 'Logo van de site', 'Logo du site', 'Site-Logo', 'Логотип сайта', 'Logo del sitio', 'Site logosu'),
('site_pages', 'Site Pages', 'صفحات الموقع', 'Site pagina\'s', 'Pages du site', 'Seiten', 'Страницы сайта', 'Páginas del sitio', 'Site Sayfaları'),
('sitemap', 'Site Map', 'خريطة الموقع', 'Sitemap', 'Plan du site', 'Seitenverzeichnis', 'Карта сайта', 'Mapa del sitio', 'Site Haritası'),
('sitemap_being_generated_may_take_few_minutes', 'The sitemap is being generated, it may take a few minutes', 'يتم إنشاء خريطة الموقع ، وقد يستغرق الأمر بضع دقائق', 'De sitemap wordt gegenereerd, dit kan enkele minuten duren', 'Le plan du site est en cours de génération, cela peut prendre quelques minutes', 'Die Sitemap wird erstellt. Dies kann einige Minuten dauern', 'Карта сайта создается, это может занять несколько минут', 'El mapa del sitio se esta generando, quizá tome algunos minutos', 'Site haritası oluşturuluyor, birkaç dakika sürebilir'),
('slower_setting_gives_better_compression', 'A slower setting gives you better compression and a faster setting gives you worse compression', 'يمنحك الإعداد الأبطأ ضغطًا أفضل والإعداد الأسرع يمنحك ضغطًا أسوأ', 'Een langzamere instelling geeft u een betere compressie en een snellere instelling geeft u een slechtere compressie', 'Un réglage plus lent vous donne une meilleure compression et un réglage plus rapide vous donne une compression plus mauvaise', 'Eine langsamere Einstellung führt zu einer besseren Komprimierung und eine schnellere Einstellung zu einer schlechteren Komprimierung', 'Более медленный параметр дает лучшее сжатие, а более быстрый параметр дает худшее сжатие', 'Un ajuste más lento le da una mejor compresión y un ajuste más rápido le brindan una peor compresión', 'Daha yavaş bir ayar size daha iyi sıkıştırma sağlar ve daha hızlı bir ayar daha kötü sıkıştırma sağlar'),
('smtp_encryption', 'SMTP Encryption', 'تشفير SMTP', 'SMTP-versleuteling', 'Chiffrement SMTP', 'SMTP-Verschlüsselung', 'Шифрование SMTP', 'Cifrado SMTP', 'SMTP Şifreleme'),
('smtp_host', 'SMTP Host', 'مضيف SMTP', 'SMTP-host', 'Hôte SMTP', 'SMTP-Host', 'SMTP-хост', 'Host SMTP', 'SMTP Ana Bilgisayarı'),
('smtp_password', 'SMTP Password', 'كلمة مرور SMTP', 'SMTP-wachtwoord', 'Mot de passe SMTP', 'SMTP-Passwort', 'SMTP пароль', 'Contraseña SMTP', 'SMTP Şifresi'),
('smtp_port', 'SMTP Port', 'منفذ SMTP', 'SMTP-Poort', 'Port SMTP', 'SMTP-Port', 'SMTP порт', 'Puerto SMTP', 'SMTP bağlantı noktası'),
('smtp_username', 'SMTP Username', 'اسم مستخدم SMTP', 'SMTP-gebruikersnaam', 'Nom d\'utilisateur SMTP', 'SMTP-Benutzername', 'Имя пользователя SMTP', 'Nombre de usuario SMTP', 'SMTP Kullanıcı Adı'),
('some_settings_were_not_saved', 'Some settings were not saved', 'لم يتم حفظ بعض الإعدادات', 'Sommige instellingen zijn niet opgeslagen', 'Certains paramètres n\'ont pas été enregistrés', 'Einige Einstellungen wurden nicht gespeichert', 'Некоторые настройки не были сохранены', 'Algunas configuraciones no se guardaron', 'Bazı ayarlar kaydedilmedi'),
('someone_has_reset_password', 'Someone (hopefully you) has asked us to reset your {$settings->title} account password. Click the button below to do so. If you did not request to reset your password, you can ignore this message.', 'طلب منا شخص ما (نأمل أنت) إعادة تعيين كلمة المرور لحساب {$settings->title} الخاص بك. انقر فوق الزر أدناه للقيام بذلك. إذا لم تطلب إعادة تعيين كلمة المرور الخاصة بك ، فيمكنك تجاهل هذه الرسالة.', 'Iemand (u hopelijk) heeft ons gevraagd het wachtwoord voor uw {$settings->title} - account opnieuw in te stellen. Klik hiervoor op onderstaande knop. Als u niet heeft gevraagd om uw wachtwoord opnieuw in te stellen, kunt u dit bericht negeren.', 'Quelqu\'un (nous espérons que vous) nous a demandé de réinitialiser le mot de passe de votre compte {$settings->title}. Cliquez sur le bouton ci-dessous pour ce faire. Si vous n\'avez pas demandé la réinitialisation de votre mot de passe, vous pouvez ignorer ce message.', 'Jemand (hoffentlich Sie) hat uns gebeten, das Passwort für Ihr {$settings->title} Konto zurückzusetzen. Klicken Sie dazu auf die Schaltfläche unten. Wenn Sie nicht aufgefordert haben, Ihr Kennwort zurückzusetzen, können Sie diese Meldung ignorieren.', 'Кто-то (надеюсь, вы) попросил нас сбросить пароль для вашей учетной записи {$settings->title}. Для этого нажмите кнопку ниже. Если вы не запрашивали сброс пароля, проигнорируйте это сообщение.', 'Alguien (esperemos que tú) nos ha solicitado restablecer la contraseña de tu cuenta de {$settings->title}. Haz clic en el botón siguiente para hacerlo. Si no solicitaste restablecer la contraseña, puedes ignorar este mensaje.', 'Birisi (umarım siz) {$settings->title} hesabınızın şifresini sıfırlamamızı istedi. Bunu yapmak için aşağıdaki düğmeyi tıklayın. Şifrenizi sıfırlama talebinde bulunmadıysanız, bu mesajı göz ardı edebilirsiniz.'),
('something_went_wrong', 'Something went wrong?', 'هناك خطأ ما؟', 'Er is iets fout gegaan?', 'Un problème est survenu?', 'Etwas ist schief gelaufen?', 'Что-то пошло не так?', '¿Algo salió mal?', 'Bir şeyler yanlış gitti?'),
('sort_by', 'Sort By', 'ترتيب حسب', 'Sorteer op', 'Trier par', 'Sortiere nach', 'Сортировать по', 'Ordenar por', 'Göre sırala'),
('sort_comments', 'Sort comments', 'فرز التعليقات', 'Sorteer opmerkingen', 'Trier les commentaires', 'Kommentare sortieren', 'Сортировать комментарии', 'Ordenar comentarios', 'Yorumları sırala'),
('spam', 'Spam', 'بريد مؤذي', 'Spam', 'Spam', 'Spam', 'Спам', 'Spam', 'çöp'),
('speed', 'Speed', 'سرعة', 'Snelheid', 'La vitesse', 'Geschwindigkeit', 'скорость', 'Velocidad', 'hız'),
('sports', 'Sports', 'رياضات', 'Sport', 'Des sports', 'Sport', 'виды спорта', 'Deportes', 'Spor Dalları'),
('start_enjoying', 'Start enjoying a complete experience', 'ابدأ في الاستمتاع بتجربة كاملة', 'Geniet van een complete ervaring', 'Commencez à vivre une expérience complète', 'Fangen Sie an, ein komplettes Erlebnis zu genießen', 'Начните наслаждаться полным опытом', 'Comienza a disfrutar de una experiencia completa', 'Eksiksiz bir deneyim yaşamaya başlayın'),
('start_time_has_been_updated', 'Start time has been updated', 'تم تحديث وقت البدء', 'Starttijd is bijgewerkt', 'L\'heure de début a été mise à jour', 'Die Startzeit wurde aktualisiert', 'Время начала обновлено', 'Se actualizó el tiempo de inicio', 'Başlangıç ​​zamanı güncellendi'),
('statistics', 'Statistics', 'إحصائيات', 'Statistiek', 'Statistiques', 'Statistik', 'Cтатистика', 'Estadísticas', 'Istatistik'),
('status', 'Status', 'وضع', 'staat', 'Statut', 'Status', 'статус', 'Estado', 'durum'),
('stay_safe_changing_password_periodically', 'Stay safe by changing your password periodically', 'حافظ على أمانك من خلال تغيير كلمة المرور الخاصة بك بشكل دوري', 'Blijf veilig door uw wachtwoord regelmatig te wijzigen', 'Blijf veilig door uw wachtwoord regelmatig te wijzigen', 'Bleiben Sie sicher, indem Sie Ihr Passwort regelmäßig ändern', 'Будьте в безопасности, периодически меняя пароль', 'Mantente seguro cambiando tu contraseña periodicamente', 'Parolanızı düzenli aralıklarla değiştirerek güvende kalın'),
('stir', 'Stir', 'يقلب', 'Roeren', 'Remuer', 'Rühren', 'Размешивать', 'Remover', 'Karıştırmak'),
('stream_start_sending_streaming_software', 'To stream, start sending us the video from the streaming software', 'لبدء البث ، ابدأ في إرسال الفيديو إلينا من برنامج البث', 'Om te streamen, stuur ons de video van de streaming-software', 'Pour diffuser, commencez à nous envoyer la vidéo à partir du logiciel de streaming', 'Senden Sie uns zum Streamen das Video von der Streaming-Software', 'Для потоковой передачи начните присылать нам видео из программы для потоковой передачи.', 'Para transmitir, empieza a enviarnos el video desde el software de transmisión', 'Akış yapmak için akış yazılımından bize videoyu göndermeye başlayın'),
('stream_url', 'Stream URL', 'دفق URL', 'Stream-URL', 'URL streamen', 'URL streamen', 'URL-адрес потока', 'URL de transmisión', 'Akış URL\'si'),
('subject', 'Subject', 'الموضوع', 'Affaire', 'Affaire', 'Affäre', 'Дело', 'Asunto', 'Mesele'),
('submit_verif_request_error', 'You can not submit verification request until the previous requests has been accepted / rejected', 'لا يمكنك إرسال طلب التحقق إلى أن يتم قبول الطلبات السابقة أو رفضها', 'U kunt geen verificatieverzoek indienen totdat de vorige verzoeken zijn geaccepteerd / afgewezen', 'Vous ne pouvez pas soumettre de demande de vérification tant que les demandes précédentes n\'ont pas été acceptées / rejetées', 'Sie können keine Überprüfungsanfrage senden, bis die vorherigen Anforderungen akzeptiert / abgelehnt wurden.', 'Вы не можете отправить запрос на подтверждение до тех пор, пока предыдущие запросы не будут приняты / отклонены', 'No puede enviar solicitud de verificación hasta que las anteriores hayan obtenido respuesta', 'Önceki istekler kabul / reddedilene kadar doğrulama isteği gönderemezsiniz.'),
('subscribe', 'Subscribe', 'الاشتراك', 'abonneren', 'Souscrire', 'Abonnieren', 'Подписывайся', 'Suscribirse', 'Abone ol'),
('subscribed', 'Subscribed', 'المشترك', 'geabonneerd', 'Souscrit', 'Gezeichnet', 'подписной', 'Suscrito', 'Abone'),
('subscribed_', 'subscribed to your channel', 'مشترك في قناتك', 'abonnerer på kanalen din', 'abonné à votre chaîne', 'abonniert deinen Kanal', 'подписался на ваш канал', 'se suscribió a tu canal', 'kanalınıza abone'),
('subscriber_cap_to_request_verification', 'Subscriber cap to request verification', 'الحد الأقصى للمشتركين لطلب التحقق', 'Abonneebeperking om verificatie aan te vragen', 'Limite d\'abonné pour demander une vérification', 'Abonnentenobergrenze, um eine Bestätigung anzufordern', 'Ограничение количества подписчиков для запроса проверки', 'Límite de suscriptores para solicitar verificación', 'Doğrulama istemek için abone sınırı'),
('subscribers', 'Subscribers', 'مشتركين', 'abonnees', 'Les abonnés', 'Abonnenten', 'Подписчики', 'Suscriptores', 'Aboneler'),
('subscriptions', 'Subscriptions', 'الاشتراكات', 'abonnementen', 'Abonnements', 'Abonnements', 'Подписки', 'Suscripciones', 'Abonelikler'),
('successful_connection_now_you_are_broadcasting', 'Successful connection, now you are broadcasting', 'اتصال ناجح ، أنت الآن تبث', 'Geslaagde verbinding, nu zendt u uit', 'Connexion réussie, maintenant vous diffusez', 'Erfolgreiche Verbindung, jetzt senden Sie', 'Успешное соединение, теперь вы ведете трансляцию', 'Conexión exitosa, ahora estas transmitiendo', 'Başarılı bağlantı, şimdi yayın yapıyorsunuz'),
('successfully_joined_desc', 'Registration successful! We have sent you an email, Please check your inbox/spam to verify your account.', 'التسجيل بنجاح! لقد أرسلنا إليك رسالة إلكترونية، يرجى التحقق من البريد الوارد / الرسائل غير المرغوب فيها للتحقق من حسابك.', 'Registratie gelukt! Wij hebben u een email gestuurd, Controleer uw inbox / spam om uw account te verifiëren.', 'Inscription réussi! Nous vous avons envoyé un courriel, vérifiez votre boîte de réception / spam pour vérifier votre compte.', 'Registrierung erfolgreich! Wir haben Ihnen eine E-Mail geschickt, bitte überprüfen Sie Ihren Posteingang / Spam, um Ihr Konto zu bestätigen.', 'Регистрация прошла успешно! Мы отправили вам электронное письмо. Пожалуйста, проверьте свой почтовый ящик / спам, чтобы подтвердить свою учетную запись.', '¡Registro exitoso! Te hemos enviado un correo electrónico, verifica tu bandeja de entrada o spam para verificar tu cuenta.', 'Kayıt başarılı! Size bir e-posta gönderdik. Hesabınızı doğrulamak için lütfen gelen kutunuzu / spam\'inizi kontrol edin.'),
('successfully_uplaoded', 'successfully uploaded.', 'تم تحميلها بنجاح.', 'succesvol geüpload.', 'téléchargé avec succès.', 'erfolgreich hochgeladen', 'успешно загружен.', 'Cargado con éxito.', 'başarıyla yüklendi.'),
('sunday', 'Sunday', 'الأحد', 'Zondag', 'Dimanche', 'Sonntag', 'воскресенье', 'Domingo', 'Pazar'),
('sure_want_change_icon_site', 'Are you sure you want to change the icon of your website?', 'هل أنت متأكد أنك تريد تغيير رمز موقع الويب الخاص بك؟', 'Weet u zeker dat u het pictogram van uw website wilt wijzigen?', 'Voulez-vous vraiment changer l\'icône de votre site Web?', 'Möchten Sie das Symbol Ihrer Website wirklich ändern?', 'Вы уверены, что хотите изменить значок своего сайта?', '¿Seguro desea cambiar el icono de su sitio web?', 'Web sitenizin simgesini değiştirmek istediğinizden emin misiniz?'),
('sure_want_change_website_logo', 'Are you sure you want to change your website logo?', 'هل أنت متأكد أنك تريد تغيير شعار موقع الويب الخاص بك؟', 'Weet u zeker dat u het logo van uw website wilt wijzigen?', 'Voulez-vous vraiment modifier le logo de votre site Web?', 'Möchten Sie Ihr Website-Logo wirklich ändern?', 'Вы уверены, что хотите изменить логотип своего сайта?', '¿Seguro desea cambiar el logo de su sitio web?', 'Web sitenizin logosunu değiştirmek istediğinizden emin misiniz?'),
('sure_want_delete_category', 'Are you sure you want to delete this category?', 'هل أنت متأكد أنك تريد حذف هذه الفئة؟', 'Weet u zeker dat u deze categorie wilt verwijderen?', 'Êtes-vous sûr de vouloir supprimer cette catégorie?', 'Möchten Sie diese Kategorie wirklich löschen?', 'Вы уверены, что хотите удалить эту категорию?', '¿Está seguro de que desea eliminar esta categoría?', 'Bu kategoriyi silmek istediğinizden emin misiniz?'),
('sure_want_delete_this_comment', 'Are you sure you want to delete this comment?', 'هل أنت متأكد أنك تريد حذف هذا التعليق؟', 'Weet u zeker dat u deze opmerking wilt verwijderen?', 'êtes-vous sûr de vouloir supprimer ce commentaire?', 'Möchten Sie diesen Kommentar wirklich löschen?', 'Вы уверенны, что хотите удалить этот комментарий?', '¿Seguro que desea eliminar este comentario?', 'Bu yorumu silmek istediğinizden emin misiniz?'),
('sure_want_reject_verification_request', 'Are you sure you want to reject this verification request?', 'هل أنت متأكد أنك تريد رفض طلب التحقق هذا؟', 'Weet u zeker dat u dit verificatieverzoek wilt afwijzen?', 'Bu doğrulama talebini reddetmek istediğinizden emin misiniz?', 'Möchten Sie diese Bestätigungsanfrage wirklich ablehnen?', 'Вы уверены, что хотите отклонить этот запрос на подтверждение?', '¿Seguro quieres rechazar esta solicitud de verificación?', 'Bu doğrulama talebini reddetmek istediğinizden emin misiniz?'),
('sure_want_remove_language', 'Are you sure you want to remove this language?', 'هل أنت متأكد أنك تريد إزالة هذه اللغة؟', 'Weet u zeker dat u deze taal wilt verwijderen?', 'Voulez-vous vraiment supprimer cette langue?', 'Möchten Sie diese Sprache wirklich entfernen?', 'Вы уверены, что хотите удалить этот язык?', '¿Está seguro que desea eliminar este lenguaje?', 'Bu dili kaldırmak istediğinizden emin misiniz?'),
('sure_you_wan_log_out', 'Are you sure you want to log out of this device?', 'هل أنت متأكد أنك تريد تسجيل الخروج من هذا الجهاز؟', 'Weet u zeker dat u zich bij dit apparaat wilt afmelden?', 'Voulez-vous vraiment vous déconnecter de cet appareil?', 'Möchten Sie sich sicher von diesem Gerät abmelden?', 'Вы уверены, что хотите выйти из системы?', '¿Está seguro que desea cerrar sesión en este dispositivo?', 'Bu cihazdan çıkış yapmak istediğinizden emin misiniz?'),
('system_will_compress_convert_optimize_videos_m3u8', 'This system will compress, convert, and optimize videos to m3u8. This system require \"ffmpeg\" to be installed in your server', 'سيقوم هذا النظام بضغط مقاطع الفيديو وتحويلها وتحسينها إلى تنسيق m3u8. يتطلب هذا النظام تثبيت \"ffmpeg\" في الخادم الخاص بك', 'Dit systeem comprimeert, converteert en optimaliseert video\'s naar m3u8. Dit systeem vereist dat \"ffmpeg\" op uw server is geïnstalleerd', 'Ce système compressera, convertira et optimisera les vidéos en m3u8. Ce système nécessite que «ffmpeg» soit installé sur votre serveur', 'Dieses System komprimiert, konvertiert und optimiert Videos in m3u8. Für dieses System muss \"ffmpeg\" auf Ihrem Server installiert sein', 'Эта система будет сжимать, конвертировать и оптимизировать видео в m3u8. Эта система требует, чтобы на вашем сервере был установлен \"ffmpeg\".', 'Este sistema comprimirá, convertirá y optimizará videos a m3u8. Este sistema requiere que \"ffmpeg\" esté instalado en su servidor', 'Bu sistem videoları sıkıştıracak, dönüştürecek ve m3u8\'e optimize edecektir. Bu sistem, sunucunuza \"ffmpeg\" yüklenmesini gerektirir'),
('tags', 'Tags', 'الكلمات', 'Tags', 'Mots clés', 'Tags', 'Теги', 'Etiquetas', 'Etiketler'),
('take_action_on_this_content', 'Take action on this content', 'اتخاذ إجراء بشأن هذا المحتوى', 'Onderneem actie tegen deze inhoud', 'Agissez sur ce contenu', 'Ergreifen Sie Maßnahmen in Bezug auf diesen Inhalt', 'Принять меры к этому содержанию', 'Toma acciones sobre este contenido', 'Bu içerikle ilgili işlem yapın'),
('terms_agreement', 'By creating your account, you agree to our', 'عن طريق إنشاء حسابك ، فإنك توافق على', 'Door uw account aan te maken, gaat u akkoord met onze', 'En créant votre compte, vous acceptez notre', 'Mit der Erstellung Ihres Benutzerkontos stimmen Sie unseren Nutzungsbedingungen zu', 'Создав свою учетную запись, вы соглашаетесь с нашими', 'Al crear su cuenta, usted acepta nuestra', 'Hesabınızı oluşturarak,'),
('terms_of_service', 'Terms of Service', 'شروط الخدمة', 'Servicevoorwaarden', 'Conditions d\'utilisation', 'Nutzungsbedingungen', 'Условия использования', 'Términos del servicio', 'Kullanım Şartları'),
('terms_of_use', 'Terms of use', 'تعليمات الاستخدام', 'Gebruiksvoorwaarden', 'Conditions d\'utilisation', 'Nutzungsbedingungen', 'Условия эксплуатации', 'Términos de Uso', 'Kullanım Şartları'),
('test_connection', 'Test connection', 'اختبار الاتصال', 'Test verbinding', 'Tester la connexion', 'Testverbindung', 'Тестовое соединение', 'Probar conexión', 'Test bağlantısı'),
('thanks', 'Thank you', 'شكرا لكم', 'Takk skal du ha', 'Je vous remercie', 'Vielen Dank', 'Спасибо', 'Gracias', 'teşekkür ederim'),
('thanks_for_letting_us_know', 'Thanks for letting us know. We have disabled the account.', 'شكرا لإعلامنا. لقد قمنا بتعطيل الحساب.', 'Bedankt dat je het ons hebt laten weten. We hebben het account uitgeschakeld.', 'Merci de nous en informer. Nous avons désactivé le compte.', 'Vielen Dank, dass Sie uns informiert haben. Wir haben das Konto deaktiviert.', 'Спасибо за то, что дали нам знать. Мы отключили учетную запись.', 'Gracias por informarnos. Hemos deshabilitado la cuenta.', 'Bize bildirdiğiniz için teşekkür ederiz. Hesabı devre dışı bıraktık.'),
('the_account_been_reported', 'The account has been reported.', 'تم الإبلاغ عن الحساب.', 'Het account is gerapporteerd.', 'Le compte a été signalé.', 'Das Konto wurde gemeldet.', 'Об аккаунте было сообщено.', 'La cuenta ha sido denunciada.', 'Hesap rapor edildi.'),
('the_dark_theme_settings_will_apply_only', 'The dark theme settings will apply only to this browser.', 'سيتم تطبيق إعدادات المظهر الداكن على هذا المتصفح فقط.', 'De donkere thema-instellingen zijn alleen van toepassing op deze browser.', 'Les paramètres du thème sombre ne s\'appliqueront qu\'à ce navigateur.', 'Die Einstellungen für dunkle Designs gelten nur für diesen Browser.', 'Настройки темной темы будут применяться только к этому браузеру.', 'La configuración del tema oscuro se aplicará únicamente a este navegador.', 'Koyu tema ayarları yalnızca bu tarayıcıya uygulanacaktır.'),
('the_dark_theme_the_lightest_parts', 'With the dark theme, the lighter areas of the page are darkened. It is perfect for watching videos at night! Do you want to try it?', 'باستخدام النسق الداكن ، يتم تعتيم المساحات الفاتحة من الصفحة. إنه مثالي لمشاهدة مقاطع الفيديو في الليل! هل تريد أن تجربه؟', 'Met het donkere thema worden de lichtere delen van de pagina donkerder gemaakt. Het is perfect om \'s nachts video\'s te bekijken! Wil je het proberen?', 'Avec le thème sombre, les zones plus claires de la page sont assombries. Il est parfait pour regarder des vidéos la nuit! Tu veux l\'essayer?', 'Mit dem dunklen Thema werden die helleren Bereiche der Seite abgedunkelt. Es ist perfekt, um nachts Videos anzusehen! Willst du es versuchen?', 'В темной теме светлые области страницы затемняются. Идеально подходит для просмотра видео ночью! Хочешь попробовать?', 'Con el tema oscuro se oscurecen las zonas más claras de la página. ¡Es perfecto para ver vídeos por la noche! ¿Quieres probarlo?', 'Koyu tema ile sayfanın daha açık alanları koyulaştırılır. Geceleri video izlemek için mükemmel! Denemek ister misin'),
('the_description_is_too_long', 'The description is too long', 'الوصف طويل جدًا', 'De beschrijving is te lang', 'La description est trop longue', 'Die Beschreibung ist zu lang', 'Описание слишком длинное', 'La descripción es demasiada larga', 'Açıklama çok uzun'),
('the_lock_was_added_successfully', 'The lock was added successfully', 'تم إضافة القفل بنجاح', 'Het slot is succesvol toegevoegd', 'Le verrou a été ajouté avec succès', 'Die Sperre wurde erfolgreich hinzugefügt', 'Замок был успешно добавлен', 'El bloqueo fue agregado con éxito', 'Kilit başarıyla eklendi'),
('the_lock_was_removed', 'The lock was removed', 'تمت إزالة القفل', 'Het slot is verwijderd', 'Le verrou a été supprimé', 'Das Schloss wurde entfernt', 'Замок был снят', 'El bloqueo fue removido', 'Kilit kaldırıldı'),
('the_name_playlist_required', 'The name of the playlist is required.', 'اسم قائمة التشغيل مطلوب.', 'Spillelistenavn er nødvendig.', 'Le nom de la liste de lecture est requis.', 'Der Name der Wiedergabeliste ist erforderlich.', 'Введите имя плейлиста', 'Se requiere el nombre de la lista de reproducción.', 'Çalma listesi adı gerekiyor.');
INSERT INTO `words` (`word`, `en`, `ar`, `nl`, `fr`, `an`, `ru`, `es`, `tr`) VALUES
('the_operation_canceled_connection_interrupted', 'The operation was canceled or the connection was interrupted', 'تم إلغاء العملية أو قطع الاتصال', 'De bewerking is geannuleerd of de verbinding is onderbroken', 'L\'opération a été annulée ou la connexion a été interrompue', 'Der Vorgang wurde abgebrochen oder die Verbindung unterbrochen', 'Операция была отменена или соединение прервано', 'La operación se canceló o la conexión fue interrumpida', 'İşlem iptal edildi veya bağlantı kesildi'),
('the_playlist_created_successfully', 'The playlist was created successfully!', 'تمت إضافة قائمة التشغيل بنجاح!', 'Spilleliste ble lagt til!', 'PlayList a été ajouté avec succès!', 'PlayList wurde erfolgreich hinzugefügt!', 'Плейлист был успешно добавлен!', '¡La lista de reproducción se creado con éxito!', 'Çalma listesi başarıyla eklendi!'),
('the_playlist_saved_successfully', 'The playlist was saved successfully!', 'تم حفظ قائمة التشغيل بنجاح!', 'Spillelisten ble lagret!', 'PlayList a été enregistré avec succès!', 'PlayList wurde erfolgreich gespeichert!', 'Плейлист был успешно сохранен!', '¡La lista de reproducción se guardó con éxito!', 'Çalma listesi başarıyla kaydedildi!'),
('the_title_is_too_long', 'The title is too long', 'العنوان طويل جدًا', 'De titel is te lang', 'Le titre est trop long', 'Der Titel ist zu lang', 'Название слишком длинное', 'El título es demasiado largo', 'Başlık çok uzun'),
('the_video_is_in_queue', 'The video is queue', 'الفيديو في قائمة الانتظار', 'De video staat in de wachtrij', 'La vidéo est mise en file d\'attente', 'Das Video befindet sich in der Warteschlange', 'Видео поставлено в очередь', 'El video está en cola', 'Video sıraya alındı'),
('the_word_was_successfully_modified', 'The word was successfully modified', 'تم تعديل الكلمة بنجاح', 'Het woord is met succes gewijzigd', 'Le mot a été modifié avec succès', 'Das Wort wurde erfolgreich geändert', 'Слово было успешно изменено', 'La palabra se modificó con éxito', 'Le mot a été modifié avec succès'),
('there_error_uploading_file', 'There was an error uploading the file', 'حدث خطأ أثناء تحميل الملف', 'Er is een fout opgetreden bij het uploaden van het bestand', 'Une erreur s\'est produite lors du téléchargement du fichier', 'Beim Hochladen der Datei ist ein Fehler aufgetreten', 'При загрузке файла произошла ошибка', 'Hubo un error al subir el archivo', 'Dosyayı yüklerken bir hata oluştu'),
('there_problems_with_some_fields', 'There are problems with some fields', 'هناك مشاكل مع بعض المجالات', 'Er zijn problemen met sommige velden', 'Il y a des problèmes avec certains champs', 'Es gibt Probleme mit einigen Feldern', 'Есть проблемы с некоторыми полями', 'Hay problemas con algunos campos', 'Bazı alanlarda sorunlar var'),
('this_account_is_suspended', 'This account is suspended', 'تم تعليق هذا الحساب', 'Dit account is opgeschort', 'Ce compte est suspendu', 'Dieses Konto ist gesperrt', 'Этот аккаунт заблокирован', 'Esta cuenta está suspendida', 'Bu hesap askıya alındı'),
('this_direct_ended', 'This direct ended', 'هذا انتهى مباشرة', 'Deze directe eindigde', 'Ce direct s\'est terminé', 'Dieser direkte endete', 'Этот прямой конец', 'Este directo finalizó', 'Bu doğrudan bitti'),
('this_field_is_empty', 'This field is empty', 'هذا الحقل فارغ', 'Dit veld is leeg', 'Ce champ est vide', 'Dieses Feld ist leer', 'Это поле пустое', 'Este campo está vacío', 'Bu alan boş'),
('this_image_not_valid_watermark', 'This image is not valid to be your watermark, do you want to continue?', 'هذه الصورة غير صالحة لتكون علامتك المائية ، هل تريد المتابعة؟', 'Deze afbeelding is niet geldig als watermerk, wil je doorgaan?', 'Cette image n\'est pas valide pour être votre filigrane, voulez-vous continuer?', 'Dieses Bild ist nicht als Wasserzeichen gültig. Möchten Sie fortfahren?', 'Это изображение не может быть вашим водяным знаком, вы хотите продолжить?', 'Esta imagen no es valida para ser tu marca de agua, ¿Deseas seguir?', 'Bu görsel filigranınız olmak için geçerli değil, devam etmek istiyor musunuz?'),
('this_is_my_account', 'This is my account', 'هذا حسابي', 'Dit is mijn account', 'C\'est mon compte', 'Das ist mein Konto', 'Это мой аккаунт', 'Esta es mi cuenta', 'Bu benim hesabım'),
('this_is_your_public_transmission_key', 'This is your public transmission key', 'هذا هو مفتاح الإرسال العام الخاص بك', 'Dit is uw openbare transmissiesleutel', 'Ceci est votre clé de transmission publique', 'Dies ist Ihr öffentlicher Übertragungsschlüssel', 'Это ваш открытый ключ передачи', 'Esta es tu clave de transmisión pública', 'Bu sizin genel iletim anahtarınızdır'),
('this_live_has_already_ended_watch', 'This live has already ended, you can watch it again in a few minutes', 'انتهى هذا البث المباشر بالفعل ، يمكنك مشاهدته مرة أخرى في غضون بضع دقائق', 'Deze live is al afgelopen, je kunt hem over een paar minuten opnieuw bekijken', 'Ce live est déjà terminé, vous pouvez le revoir dans quelques minutes', 'Dieses Live ist bereits beendet, Sie können es in wenigen Minuten wieder ansehen', 'Эта прямая трансляция уже закончилась, вы можете посмотреть ее снова через несколько минут.', 'Este directo ya ha finalizado, podrás volver a verlo en unos minutos', 'Bu canlı yayın zaten sona erdi, birkaç dakika sonra tekrar izleyebilirsiniz'),
('this_lock_has_already_been_applied', 'This lock has already been applied', 'تم تطبيق هذا القفل بالفعل', 'Dit slot is al aangebracht', 'Ce verrou a déjà été appliqué', 'Diese Sperre wurde bereits angewendet', 'Эта блокировка уже применена', 'Este bloqueo ya fue aplicado', 'Bu kilit zaten uygulanmış'),
('this_month', 'This month', 'هذا الشهر', 'Deze maand', 'Ce mois-ci', 'Diesen Monat', 'Этот месяц', 'Este mes', 'Bu ay'),
('this_option_activated_live_be_saved', 'If this option is activated, your live will be saved and your subscribers will be able to see it again', 'إذا تم تنشيط هذا الخيار ، فسيتم حفظ البث المباشر الخاص بك وسيتمكن المشتركون لديك من رؤيته مرة أخرى', 'Als deze optie is geactiveerd, wordt uw leven opgeslagen en kunnen uw abonnees het opnieuw zien', 'Si cette option est activée, votre live sera sauvegardé et vos abonnés pourront le revoir', 'Wenn diese Option aktiviert ist, wird Ihr Live gespeichert und Ihre Abonnenten können es wieder sehen', 'Если эта опция активирована, ваша трансляция будет сохранена, и ваши подписчики смогут увидеть ее снова.', 'Si esta opcion está activada, se guardara tu directo y tus suscriptores podran verlo de nuevo', 'Bu seçenek etkinleştirilirse, yayınınız kaydedilecek ve aboneleriniz bunu tekrar görebilecek.'),
('this_option_is_activated_the_chat', 'If this option is activated, the chat will be activated and users will be able to interact with you and others.', 'إذا تم تنشيط هذا الخيار ، فسيتم تنشيط الدردشة وسيتمكن المستخدمون من التفاعل معك ومع الآخرين.', 'Als deze optie is geactiveerd, wordt de chat geactiveerd en kunnen gebruikers met jou en anderen communiceren.', 'Si cette option est activée, le chat sera activé et les utilisateurs pourront interagir avec vous et les autres.', 'Wenn diese Option aktiviert ist, wird der Chat aktiviert und Benutzer können mit Ihnen und anderen interagieren.', 'Если эта опция активирована, чат будет активирован, и пользователи смогут общаться с вами и другими.', 'Si esta opcion está activada, se activara el chat y los usuarios podran interactuar contigo y los demas.', 'Bu seçenek etkinleştirilirse, sohbet etkinleştirilecek ve kullanıcılar sizinle ve başkalarıyla etkileşime girebilecek.'),
('this_video_being_converted_few_resolutions', 'This video is being converted to few resolutions, it may take a few minutes.', 'يتم تحويل الفيديو، قد يستغرق بضع دقائق', 'Video wordt geconverteerd, dit kan enkele minuten duren', 'La vidéo est convertie, cela peut prendre quelques minutes', 'Video wird konvertiert, es kann einige Minuten dauern', 'Видео конвертируется, это может занять несколько минут', 'El vídeo se esta procesando, esto puede tomar unos minutos', 'Video dönüştürülür, birkaç dakika sürebilir.'),
('this_video_porcessing', 'This video is being processed, please come back in few minutes', 'تتم معالجة هذا الفيديو، يرجى العودة في غضون بضع دقائق', 'Deze video wordt verwerkt. Kom over een paar minuten terug', 'Cette vidéo est en cours de traitement, revenez dans quelques minutes', 'Dieses Video wird gerade bearbeitet. Bitte kommen Sie in ein paar Minuten zurück', 'Это видео обрабатывается, пожалуйста, вернитесь через несколько минут', 'Este video está siendo procesado, vuelve en unos minutos', 'Bu video işleniyor, lütfen birkaç dakika içinde geri dönün'),
('this_video_queue', 'This video is being added to queue, please check back in few minutes.', 'تتم إضافة هذا الفيديو إلى قائمة الانتظار ، يرجى التحقق مرة أخرى في غضون بضع دقائق.', 'Deze video wordt aan de wachtrij toegevoegd. Probeer het over enkele minuten opnieuw.', 'Cette vidéo est en train d\'être ajoutée à la file d\'attente. Veuillez vérifier à nouveau dans quelques minutes.', 'Dieses Video wird zur Warteschlange hinzugefügt. Bitte versuchen Sie es in wenigen Minuten noch einmal.', 'Это видео добавляется в очередь, пожалуйста, зайдите через несколько минут.', 'Este video se está agregando a la cola, por favor revise de nuevo en unos minutos.', 'Bu video kuyruğa ekleniyor, lütfen birkaç dakika içinde tekrar kontrol edin.'),
('this_video_was_successfully_approved', 'This video was successfully approved', 'تمت الموافقة على هذا الفيديو بنجاح', 'Deze video is met succes goedgekeurd', 'Cette vidéo a été approuvée avec succès', 'Dieses Video wurde erfolgreich genehmigt', 'Это видео было успешно одобрено', 'Este video se aprobó con exito', 'Bu video başarıyla onaylandı'),
('this_week', 'This week', 'هذا الاسبوع', 'Deze week', 'Cette semaine', 'Diese Woche', 'На этой неделе', 'Esta semana', 'Bu hafta'),
('this_word_already_use_another', 'This word is already in use, use another', 'هذه الكلمة قيد الاستخدام بالفعل ، استخدم أخرى', 'Dit woord is al in gebruik, gebruik een ander woord', 'Ce mot est déjà utilisé, utilisez-en un autre', 'Dieses Wort wird bereits verwendet, verwenden Sie ein anderes', 'Это слово уже используется, используйте другое', 'Esta palabra ya está en uso, utilice otra', 'Bu kelime zaten kullanılıyor, başka bir tane kullan'),
('this_word_is_already_used', 'This lang is already used', 'هذه اللغة مستخدمة بالفعل', 'Deze taal wordt al gebruikt', 'Cette langue est déjà utilisée', 'Diese Sprache wird bereits verwendet', 'Этот язык уже используется', 'Este idioma ya esta en uso', 'Bu dil zaten kullanılıyor'),
('this_year', 'This year', 'هذا العام', 'Dit jaar', 'Cette année', 'Dieses Jahr', 'В этом году', 'Este año', 'Bu yıl'),
('thumbnail', 'Thumbnail', 'صورة مصغرة', 'thumbnail', 'La vignette', 'Miniaturansicht', 'Thumbnail', 'Miniatura', 'başparmak tırnağı'),
('thursday', 'Thursday', 'الخميس', 'Donderdag', 'Jeudi', 'Donnerstag', 'Четверг', 'Jueves', 'Perşembe'),
('time_fast_back', 'Go back 10 seconds', 'ارجع بمقدار 10 ثوانٍ', 'Ga 10 seconden terug', 'Reculer de 10 secondes', 'Gehe 10 Sekunden zurück', 'Вернуться на 10 секунд', 'Retroceder 10 segundos', '10 saniye ileri sar'),
('time_fast_forward', 'Fast forward 10 seconds', 'تقديم سريع بمقدار 10 ثوانٍ', '10 seconden vooruitspoelen', 'Avance rapide de 10 secondes', 'Schneller Vorlauf 10 Sekunden', 'Перемотка вперед на 10 секунд', 'Adelantar 10 segundos', '10 saniye ileri sar'),
('time_slider', 'Slider control', 'التحكم في التمرير', 'Schuifbediening', 'Contrôle du curseur', 'Schieberegler', 'Ползунок управления', 'Control deslizante', 'Kaydırıcı kontrolü'),
('title', 'Title', 'عنوان', 'Titel', 'Titre', 'Titel', 'заглавие', 'Título', 'Başlık'),
('title_no_logged_add_playlist', 'Do you want to see him again later?', 'هل تريد رؤيته مرة أخرى لاحقًا؟', 'Wil je hem later weer zien?', 'Voulez-vous le revoir plus tard?', 'Willst du ihn später wiedersehen?', 'Вы хотите увидеть его позже?', '¿Quieres volver a verlo más tarde?', 'Onu daha sonra tekrar görmek ister misin?'),
('to_update', 'To update', 'للتحديث', 'Updaten', 'Mettre à jour', 'Aktualisieren', 'Обновить', 'Actualizar', 'Güncellemek için'),
('to_use_the_chat', 'to use the chat', 'لاستخدام الدردشة', 'om de chat te gebruiken', 'utiliser le chat', 'um den Chat zu nutzen', 'использовать чат', 'para utilizar el chat', 'sohbeti kullanmak'),
('to_visit', 'To visit', 'قم بزيارة', 'Bezoek', 'Bezoek', 'Besuchen Sie', 'визит', 'Visitar', 'Ziyaret'),
('today', 'Today', 'اليوم', 'Vandaag', 'Aujourd\'hui', 'Heute', 'сегодня', 'Hoy', 'Bugün'),
('total_comments_month', 'Comments This Month', 'مجموع التعليقات هذا الشهر', 'Totaal aantal reacties deze maand', 'Total des commentaires ce mois-ci', 'Kommentare insgesamt in diesem Monat', 'Всего комментариев в этом месяце', 'Comentarios este mes', 'Bu Ayın Toplam Yorumu'),
('total_comments_today', 'Comments Today', 'مجموع التعليقات اليوم', 'Totaal aantal reacties vandaag', 'Total des commentaires aujourd\'hui', 'Kommentare heute insgesamt', 'Всего комментариев сегодня', 'Comentarios hoy', 'Bugün Toplam Yorum'),
('total_comments_year', 'Comments This Year', 'مجموع التعليقات هذا العام', 'Totaal aantal reacties dit jaar', 'Total des commentaires cette année', 'Kommentare insgesamt in diesem Jahr', 'Всего комментариев в этом году', 'Comentarios de este año', 'Bu Yanda Toplam Yorum'),
('transmission_key', 'Transmission key', 'مفتاح النقل', 'Transmissiesleutel', 'Clé de transmission', 'Übertragungsschlüssel', 'Ключ трансмиссии', 'Clave de transmisión', 'İletim anahtarı'),
('transmission_key_do_not_share_anyone', 'This is your transmission key, do not share it with anyone', 'هذا هو مفتاح الإرسال الخاص بك ، لا تشاركه مع أي شخص', 'Dit is uw transmissiesleutel, deel deze met niemand', 'Ceci est votre clé de transmission, ne la partagez avec personne', 'Dies ist Ihr Übertragungsschlüssel. Teilen Sie ihn niemandem mit', 'Это ваш ключ передачи, никому его не сообщайте', 'Esta es tu clave de transmisión, no la compartas con nadie', 'Bu sizin iletim anahtarınızdır, kimseyle paylaşmayın'),
('transmitted_the', 'Transmitted the', 'يحول ال', 'Heeft het', 'Transmis le', 'Übertragen die', 'Передал', 'Transmitió el', 'İletildi'),
('trending', 'Trending', 'الشائع', 'Trending', 'Tendances', 'Trending', 'Trending', 'Tendencias', 'Trend'),
('try_again', 'Try again', 'حاول مرة أخرى', 'Probeer het opnieuw', 'Essayer à nouveau', 'Versuchen Sie es noch einmal', 'Попробуй еще раз', 'Intentar de nuevo', 'Tekrar dene'),
('tuesday', 'Tuesday', 'الثلاثاء', 'Dinsdag', 'Mardi', 'Dienstag', 'вторник', 'Martes', 'Salı'),
('twitter', 'Twitter', 'تغريد', 'tjilpen', 'Gazouillement', 'Twitter', 'щебет', 'Twitter', 'heyecan'),
('type', 'Type', 'اكتب', 'Type', 'Type', 'Art', 'Тип', 'Tipo', 'Tip'),
('unblock', 'Unblock', 'رفع الحظر', 'deblokkeren', 'Débloquer', 'Entsperren', 'открыть', 'Desbloquear', 'engeli kaldırmak'),
('unknown_error', 'Error: an unknown error occurred. Please try again later', 'خطأ: حدث خطأ غير معروف. الرجاء معاودة المحاولة في وقت لاحق', 'Fout: er is een onbekende fout opgetreden. Probeer het later opnieuw', 'Erreur: une erreur inconnue s\'est produite. Veuillez réessayer plus tard', 'Fehler: Ein unbekannter Fehler ist aufgetreten. Bitte versuche es später noch einmal', 'Ошибка: произошла неизвестная ошибка. Пожалуйста, повторите попытку позже', 'Error: se produjo un error desconocido. Por favor, inténtelo de nuevo más tarde', 'Hata: Bilinmeyen bir hata oluştu. Lütfen daha sonra tekrar deneyiniz'),
('unlimited', 'Unlimited', 'غير محدود', 'Onbeperkt', 'Illimité', 'Unbegrenzt', 'Безлимитный', 'Ilimitado', 'Sınırsız'),
('unlisted', 'Unlisted', 'غير مدرج', 'geheim', 'Non listé', 'Nicht gelistet', 'Unlisted', 'Sin listar', 'Liste dışı'),
('unmute', 'Unmute', 'تنشيط الصوت', 'Activeer geluid', 'Activer le son', 'Sound aktivieren', 'Активировать звук', 'Activar sonido', 'Sesi etkinleştir'),
('unpin_up', 'Unpin', 'إزالة التثبيت', 'Losmaken', 'Détacher', 'Unpin', 'Oткрепить', 'No fijar', 'Sabitlemesini'),
('unsubscribe', 'Unsubscribe', 'إلغاء الاشتراك', 'Afmelden', 'Se désinscrire', 'Abbestellen', 'Отказаться от подписки', 'Anular tu suscripción', 'Aboneliğini'),
('unsubscribe_from_channel', 'Are you sure you want to cancel your subscription?', 'هل أنت متأكد من أنك تريد إلغاء اشتراكك؟', 'Weet je zeker dat je je abonnement wilt annuleren?', 'Voulez-vous vraiment annuler votre abonnement?', 'Möchten Sie Ihr Abonnement wirklich kündigen?', 'Вы уверены, что хотите отменить свою подписку?', '¿Seguro que deseas cancelar tu suscripción?', 'Aboneliğinizi iptal etmek istediğinizden emin misiniz?'),
('unsubscribed', 'unsubscribed from your channel', 'غير مشترك من قناتك', 'avmeldt fra kanalen din', 'désabonné de votre chaîne', 'von deinem Kanal abgemeldet', 'отписался с вашего канала', 'anulo la suscripción de tu canal', 'kanalınızdan aboneliğiniz iptal edildi'),
('unverified', 'Unverified', 'غير مثبت عليه', 'geverifieerde', 'Non vérifié', 'Ungeprüft', 'непроверенный', 'Sin verificar', 'doğrulanmamış'),
('up_next', 'Up next', 'التالي', 'Volgende', 'Suivant', 'Als nächstes', 'Следующий', 'Siguiente', 'Bir sonraki'),
('update_every_12_hours_indication_only_valid', 'Update every 12 hours (Indication only valid, if the Cron Task is not modified).', 'قم بالتحديث كل 12 ساعة (الإشارة صالحة فقط ، إذا لم يتم تعديل مهمة Cron).', 'Update elke 12 uur (indicatie alleen geldig, als de Cron-taak niet is gewijzigd).', 'Mise à jour toutes les 12 heures (Indication valide uniquement, si la tâche Cron n\'est pas modifiée).', 'Aktualisierung alle 12 Stunden (Angabe nur gültig, wenn die Cron-Task nicht geändert wurde).', 'Обновлять каждые 12 часов (Индикация действительна, только если задача Cron не изменена).', 'Actualizacion cada 12 horas (Indicacion solo valida, si no se modifica la Tarea Cron).', 'Her 12 saatte bir güncelleme (Gösterge yalnızca Cron Görevi değiştirilmezse geçerlidir).'),
('upload', 'Upload', 'تحميل', 'Uploaden', 'Télécharger', 'Hochladen', 'Загрузить', 'Subir', 'Yükleme'),
('upload_date', 'Upload Date', 'تاريخ الرفع', 'Upload datum', 'Date de dépôt', 'Hochladedatum', 'Дата загрузки', 'Fecha de carga', 'Yükleme tarihi'),
('upload_limit', 'Upload limit', 'حد التحميل', 'Uploadlimiet', 'Limite de téléchargement', 'Upload-Limit', 'Лимит загрузки', 'Límite de carga', 'Yükleme sınırı'),
('upload_limit_per_user', 'Upload limit per user', 'حد التحميل لكل مستخدم', 'Uploadlimiet per gebruiker', 'Limite de téléchargement par utilisateur', 'Upload-Limit pro Benutzer', 'Лимит загрузки на пользователя', 'Límite de carga por usuario', 'Kullanıcı başına yükleme sınırı'),
('upload_limit_per_video', 'Upload limit per video', 'حد التحميل لكل فيديو', 'Uploadlimiet per video', 'Limite de téléversement par vidéo', 'Upload-Limit pro Video', 'Лимит загрузки на видео', 'Límite de carga por video', 'Video başına yükleme sınırı'),
('upload_limit_reached', 'You have reached your upload limit', 'لقد وصلت إلى حد التحميل', 'Du har nådd opplastingsgrensen din', 'Vous avez atteint votre limite de téléchargement', 'Du hast dein Upload-Limit erreicht', 'Вы достигли предела загрузки', 'Has alcanzado tu límite de carga', 'Yükleme limitinize ulaştınız.'),
('upload_new_video', 'Upload new video', 'تحميل فيديو جديد', 'Nieuwe video uploaden', 'Télécharger une nouvelle vidéo', 'Neues Video hochladen', 'Загрузить новое видео', 'Subir nuevo video', 'Yeni video yükle'),
('upload_successfully', 'Process finished, your video was successfully uploaded', 'عملية مفتوحة، تم تحميل الفيديو بنجاح', 'Proces voltooid, uw video is succesvol geüpload', 'processus ended, votre vidéo a été téléchargé avec succès', 'ended Prozess wurde das Video hochgeladen erfolgreich', 'Процесс завершен, ваше видео успешно загружено', 'Proceso finalizado, su vídeo fue subido exitosamente', 'uçlu bir süreç, video başarıyla yüklendiği'),
('upload_thumbnail', 'Upload thumbnail', 'تحميل الصورة المصغرة', 'Miniatuur uploaden', 'Importer une miniature', 'Vorschaubild hochladen', 'Загрузить миниатюру', 'Subir miniatura', 'Küçük resim yükle'),
('upload_video', 'Upload video', 'تحميل الفيديو', 'Upload een video', 'Télécharger une vidéo', 'Ein Video hochladen', 'Загрузите видео', 'Subir vídeo', 'Video yüklemek'),
('upload_videos_share_friends_community', 'Upload videos and share them with your friends and the community', 'تحميل مقاطع الفيديو ومشاركتها مع أصدقائك والمجتمع', 'Upload video\'s en deel ze met je vrienden en de gemeenschap', 'Téléchargez des vidéos et partagez-les avec vos amis et la communauté', 'Laden Sie Videos hoch und teilen Sie sie mit Ihren Freunden und der Communityr', 'Загружайте видео и делитесь ими с друзьями и сообществом', 'Sube videos y compartelos tus amigos y la comunidad', 'Videoları yükleyin ve arkadaşlarınızla ve toplulukla paylaşın'),
('url_contains_private_transmission_not_share_anyone', 'This url contains your private transmission key, do not share it with anyone', 'يحتوي عنوان url هذا على مفتاح الإرسال الخاص بك ، فلا تشاركه مع أي شخص', 'Deze url bevat uw persoonlijke transmissiesleutel, deel deze met niemand', 'Cette URL contient votre clé de transmission privée, ne la partagez avec personne', 'Diese URL enthält Ihren privaten Übertragungsschlüssel. Geben Sie ihn nicht an Dritte weiter', 'Этот URL-адрес содержит ваш закрытый ключ передачи, никому его не сообщайте', 'Esta url contiene tu clave de transmisión privada, no la compartas con nadie', 'Bu url, özel iletim anahtarınızı içerir, kimseyle paylaşmayın'),
('user', 'User', 'المستعمل', 'Gebruiker', 'Utilisateur', 'Benutzer', 'пользователь', 'Usuario', 'kullanıcı'),
('user_email_verification', 'User Email Verification', 'التحقق من البريد الإلكتروني للمستخدم', 'E-mailverificatie van gebruiker', 'Vérification de l\'adresse e-mail de l\'utilisateur', 'Benutzer-E-Mail-Überprüfung', 'Подтверждение электронной почты пользователя', 'Verificación de correo electrónico del usuario', 'Kullanıcı E-posta Doğrulaması'),
('user_has_been_verified_successfully', 'User has been verified successfully', 'تم التحقق من المستخدم بنجاح', 'De gebruiker is succesvol geverifieerd', 'L\'utilisateur a été vérifié avec succès', 'Benutzer wurde erfolgreich verifiziert', 'Пользователь успешно подтвержден', 'El usuario se ha verificado con éxito', 'Kullanıcı başarıyla doğrulandı'),
('user_not_exists', 'User is not exist', 'المستخدم غير موجود', 'Gebruiker bestaat niet', 'L\'utilisateur n\'est pas exister', 'Benutzer ist nicht vorhanden', 'Пользователь не существует', 'El usuario no existe', 'Kullanıcı mevcut değil'),
('user_reached_upload_limit', 'You have reached your maximum upload limit, if you wish to increase it', 'لقد وصلت إلى الحد الأقصى للتحميل ، إذا كنت ترغب في زيادته', 'U heeft uw maximale uploadlimiet bereikt, als u deze wilt verhogen', 'Vous avez atteint votre limite maximale de téléchargement, si vous souhaitez l\'augmenter', 'Sie haben Ihr maximales Upload-Limit erreicht, wenn Sie es erhöhen möchten', 'Вы достигли максимального лимита загрузки, если хотите увеличить его', 'Has alcanzado tu límite máximo de carga, si deseas aumentarlo', 'Artırmak isterseniz, maksimum yükleme sınırınıza ulaştınız'),
('user_registration', 'User Registration', 'تسجيل المستخدم', 'Gebruikersregistratie', 'Enregistrement de l\'utilisateur', 'Benutzer Registration', 'Регистрация пользователя', 'registro de usuario', 'Kullanıcı Kaydı'),
('user_settings', 'User Settings', 'إعدادات المستخدم', 'Gebruikersinstellingen', 'Paramètres utilisateur', 'Benutzereinstellungen', 'Пользовательские настройки', 'Ajustes de usuario', 'Kullanıcı ayarları'),
('user_verification_badge', 'User verification badge', 'شارة التحقق من المستخدم', 'Gebruikersverificatiebadge', 'Badge de vérification de l\'utilisateur', 'Benutzerüberprüfungsausweis', 'Значок подтверждения пользователя', 'Insignia de verificación de usuario', 'Kullanıcı doğrulama rozeti'),
('username', 'Username', 'اسم المستخدم', 'Gebruikersnaam', 'Nom d\'utilisateur', 'Benutzername', 'имя пользователя', 'Nombre de usuario', 'Kullanıcı adı'),
('username_characters_length', 'Username must be between 4/25', 'يجب أن يكون اسم المستخدم بين 4/25', 'Gebruikersnaam moet tussen 4/25 zijn', 'Le nom d\'utilisateur doit être compris entre 4/25', 'Benutzername muss zwischen 4/25 liegen', 'Имя пользователя должно быть от 4/25', 'El nombre de usuario debe estar entre 4/25', 'Kullanıcı adı 4/25 arasında olmalıdır'),
('username_invalid_characters', 'Invalid username characters', 'أحرف اسم المستخدم غير صالحة', 'Ongeldige gebruikersnaam karakters', 'Caractères d\'identifiant non valides', 'Ungültige Benutzernamen', 'Неверные символы имени пользователя', 'Caracteres de usuario no válidos', 'Geçersiz kullanıcı adı karakterleri'),
('username_is_taken', 'Username is taken', 'اسم المستخدم ماخوذ', 'Gebruikersnaam is in gebruik', 'Le nom d\'utilisateur est pris', 'Benutzername ist vergeben', 'Имя пользователя', 'El nombre de usuario se ha tomado', 'kullanıcı adı alınmış'),
('users', 'Users', 'المستخدمون', 'Gebruikers', 'Utilisateurs', 'Benutzer', 'Пользователи', 'Usuarios', 'Kullanıcılar'),
('value', 'Value', 'القيمة', 'Waarde', 'Valeur', 'Wert', 'Значение', 'Valor', 'Değer'),
('verif_request_received', 'Your request was received and is pending approval, we will send you an email when we review it.', 'تم استلام طلبك وهو في انتظار الموافقة ، وسوف نرسل لك بريدًا إلكترونيًا عند مراجعته.', 'Uw verzoek is ontvangen en wacht op goedkeuring. We sturen u een e-mail wanneer we het beoordelen.', 'Votre demande a été reçue et est en attente d\'approbation, nous vous enverrons un e-mail lorsque nous l\'examinerons.', 'Ihre Anfrage wurde empfangen und muss noch genehmigt werden. Wir senden Ihnen eine E-Mail, wenn wir sie überprüfen.', 'Ваш запрос был получен и ожидает одобрения, мы отправим вам электронное письмо, когда рассмотрим его.', 'Tu solicitud fue recibida y está pendiente de aprobación, te enviaremos un correo electrónico cuando la revisemos.', 'İsteğiniz alındı ​​ve onay bekliyor, incelendiğinde size bir e-posta göndereceğiz.'),
('verif_request_sent', 'Your request was sent and will be reviewed as soon as possible.', 'تم إرسال طلبك وسيتم مراجعته في أقرب وقت ممكن.', 'Uw aanvraag is verzonden en zal zo snel mogelijk worden beoordeeld.', 'Votre demande a été envoyée et sera examinée dès que possible.', 'Ihre Anfrage wurde gesendet und wird so schnell wie möglich bearbeitet.', 'Ваш запрос отправлен и будет рассмотрен в ближайшее время.', 'Su solicitud fue enviada y será revisada lo mas pronto posible.', 'İsteğiniz gönderildi ve en kısa zamanda incelenecek.'),
('verification', 'Verify', 'التحقق', 'Verificatie', 'Vérification', 'Überprüfung', 'ПРОВЕРКИ', 'Verificación', 'DOĞRULAMA'),
('verification_accepted', 'Your verification request has been approved!', 'تمت الموافقة على طلب التحقق الخاص بك!', 'Uw verificatieverzoek is goedgekeurd!', 'Votre demande de vérification a été approuvée!', 'Ihre Bestätigungsanfrage wurde genehmigt!', 'Ваш запрос на подтверждение был одобрен!', '¡Su solicitud de verificación ha sido aprobada!', 'Doğrulama isteğiniz onaylandı!'),
('verification_rejected', 'Your verification request has been rejected!', 'تم رفض طلب التحقق الخاص بك!', 'Uw verificatieverzoek is afgewezen!', 'Votre demande de vérification a été rejetée!', 'Ihre Bestätigungsanfrage wurde abgelehnt!', 'Ваш запрос на подтверждение был отклонен!', '¡Su solicitud de verificación ha sido rechazada!', 'Doğrulama isteğiniz reddedildi!'),
('verification_request_been_rejected', 'Verification request has been rejected', 'تم رفض طلب التحقق', 'Verificatieverzoek is afgewezen', 'La demande de vérification a été rejetée', 'Überprüfungsanforderung wurde abgelehnt', 'Запрос на подтверждение был отклонен', 'La solicitud de verificación ha sido rechazada', 'La solicitud de verificación ha sido rechazada'),
('verification_request_was_received', 'Your verification request was received', 'تم استلام طلب التحقق الخاص بك', 'Uw verificatieverzoek is ontvangen', 'Votre demande de vérification a été reçue', 'Ihre Bestätigungsanfrage wurde empfangen', 'Ваш запрос на подтверждение был получен', 'Su solicitud de verificación fue recibida', 'Doğrulama talebiniz alındı'),
('verification_requests', 'Verification Requests', 'طلبات التحقق', 'Verificatieverzoeken', 'Demandes de vérification', 'Überprüfungsanfragen', 'Запросы на подтверждение', 'Solicitudes de verificación', 'Doğrulama Talepleri'),
('verification_your_channel', 'the verification of your channel', 'التحقق من قناتك', 'de verificatie van je kanaal', 'la vérification de votre chaîne', 'die Überprüfung Ihres Kanals', 'проверка вашего канала', 'la verificación de tu canal', 'kanalınızın doğrulanması'),
('verified', 'Verified', 'التحقق', 'geverifieerd', 'Vérifié', 'Verifiziert', 'проверенный', 'Verificado', 'Doğrulanmış'),
('verified_account', 'Verified account', 'حساب تم التحقق منه', 'Geverifieerd account', 'Compte vérifié', 'Verifiziertes Konto', 'Подтвержденный аккаунт', 'Cuenta verificada', 'Onaylanmış Hesap'),
('verify_your_account', 'Verify your account', 'تحقق من حسابك', 'Verifieer uw account', 'Vérifiez votre compte', 'Überprüfen Sie Ihr Konto', 'подтвердите ваш аккаунт', 'Verifica tu cuenta', 'Hesabınızı doğrulayın'),
('video', 'Video', 'فيديو', 'Video', 'Vidéo', 'Video', 'видео', 'Vídeo', 'Video'),
('video_approve_text', 'This video is being reviewed, please check back later.', 'هذا الفيديو قيد المراجعة ، يرجى معاودة التحقق لاحقًا.', 'Deze video wordt beoordeeld. Kom later nog eens terug.', 'Cette vidéo est en cours de révision, veuillez vérifier plus tard.', 'Dieses Video wird gerade überprüft. Bitte schauen Sie später noch einmal vorbei.', 'Это видео просматривается, пожалуйста, зайдите позже.', 'Este video está siendo revisado, por favor revise más tarde.', 'Bu video inceleniyor, lütfen daha sonra tekrar kontrol edin.'),
('video_deleted', 'Video deleted', 'تم حذف الفيديو', 'Video verwijderd', 'Vidéo supprimée', 'Video gelöscht', 'Видео удалено', 'Video eliminado', 'Video silindi'),
('video_deleted_once_click_send', 'The video will be deleted once you click on \"Send\"', 'سيتم حذف الفيديو بمجرد النقر فوق \"إرسال\"', 'De video wordt verwijderd zodra je op \"Verzenden\" klikt', 'La vidéo sera supprimée une fois que vous aurez cliqué sur «Envoyer»', 'Das Video wird gelöscht, sobald Sie auf \"Senden\" klicken.', 'Видео будет удалено, как только вы нажмете «Отправить».', 'El video sera eliminado una vez que pulses en \"Enviar\"', '\"Gönder\" i tıkladığınızda video silinecektir'),
('video_description', 'Video Description', 'وصف الفيديو', 'video beschrijving', 'description de vidéo', 'Videobeschreibung', 'описание видео', 'Descripción del video', 'Video Açıklaması'),
('video_has_been_deprecated', 'Video has been deprecated', 'تم إهمال الفيديو', 'Video is verouderd', 'La vidéo est obsolète', 'Video ist veraltet', 'Видео устарело', 'El video se ha desaprobado', 'Video kullanımdan kaldırıldı'),
('video_has_been_removed_successfully', 'Video has been removed successfully', 'تمت إزالة الفيديو بنجاح', 'Video is succesvol verwijderd', 'La vidéo a été supprimée avec succès', 'Video wurde erfolgreich entfernt', 'Видео было успешно удалено', 'El video se ha eliminado con éxito', 'Video başarıyla kaldırıldı'),
('video_not_approved', 'Video not approved', 'الفيديو غير معتمد', 'Video niet goedgekeurd', 'Vidéo non approuvée', 'Video nicht freigegeben', 'Видео не одобрено', 'Vídeo sin aprobar', 'Video onaylanmadı'),
('video_not_found_please_try_again', 'Video not found, please refresh the page and try again.', 'لم يتم العثور على الفيديو، يرجى تحديث الصفحة وإعادة المحاولة.', 'Video niet gevonden, vernieuw de pagina en probeer het opnieuw.', 'Vidéo non trouvée, actualisez la page et réessayez.', 'Video nicht gefunden, bitte aktualisieren Sie die Seite und versuchen Sie es erneut.', 'Видео не найдено, обновите страницу и повторите попытку.', 'No se encontró el video, actualice la página e inténtelo de nuevo.', 'Video bulunamadı, lütfen sayfayı yenileyin ve tekrar deneyin.'),
('video_preparing_for_processing', 'Your video is being added to queue, please check back in few minutes.', 'تتم إضافة الفيديو الخاص بك إلى قائمة الانتظار ، يرجى التحقق مرة أخرى في غضون بضع دقائق.', 'Je video wordt aan de wachtrij toegevoegd. Probeer het over enkele minuten opnieuw.', 'Votre vidéo est en train d\'être ajoutée à la file d\'attente. Veuillez vérifier dans quelques minutes.', 'Ihr Video wird zur Warteschlange hinzugefügt. Bitte versuchen Sie es in wenigen Minuten noch einmal.', 'Ваше видео добавляется в очередь, пожалуйста, проверьте его через несколько минут.', 'Su vídeo se está preparando para procesar, por favor, vuelva en unos minutos.', 'Videonuz sıraya ekleniyor, lütfen birkaç dakika içinde tekrar kontrol edin.'),
('video_private_text', 'This is a private video, only the author can see it', 'هذا فيديو خاص ، فقط المؤلف يمكنه مشاهدته', 'Dit is een privévideo, alleen de auteur kan deze zien', 'Ceci est une vidéo privée, seul l\'auteur peut la voir', 'Dies ist ein privates Video, das nur der Autor sehen kann', 'Это личное видео, его может видеть только автор', 'Este es un video privado, solo el autor puede verlo', 'Bu özel bir videodur, sadece yazar görebilir'),
('video_restricted_under_18_years_age', 'This video is age restricted for viewers under +18', 'هذا الفيديو مقيّد بالعمر للمشاهدين تحت +18', 'Deze video is leeftijdsbeperkend voor kijkers onder +18', 'Cette vidéo est limitée à l’âge pour les téléspectateurs de moins de 18 ans.', 'Dieses Video ist für Zuschauer unter +18 Altersbeschränkung', 'Это видео ограничено для зрителей под +18', 'Este video está restringido para menores de 18 años.', 'Bu video, +18 yaşın altındaki görüntüleyenler için kısıtlanmış'),
('video_saved', 'Video successfully updated', 'تم تحديث الفيديو بنجاح', 'Video is succesvol bijgewerkt', 'Vidéo mise à jour avec succès', 'Video erfolgreich aktualisiert', 'Видео успешно обновлено', 'Video actualizado correctamente', 'Video başarıyla güncellendi'),
('video_studio', 'Video studio', 'ستوديو فيديو', 'Videostudio', 'Studio vidéo', 'Videostudio', 'Видео студия', 'Estudio de video', 'Video stüdyosu'),
('video_title', 'Video Title', 'عنوان مقطع الفيديو', 'Video Titel', 'titre de la vidéo', 'Videotitel', 'Название видео', 'Titulo del Video', 'video başlığı'),
('video_uploaded', 'added a new video', 'نشر فيديو جديد', 'nieuwe video toegevoegd', 'Ajout d&#039;une nouvelle vidéo', 'neues Video hinzugefügt', 'добавлено новое видео', 'subió un nuevo vídeo', 'yeni video eklendi'),
('video_will_published', 'The video will be published as soon as you finish.', 'سيتم نشر الفيديو بمجرد الانتهاء.', 'De video wordt gepubliceerd zodra je klaar bent.', 'La vidéo sera publiée dès que vous aurez terminé.', 'Das Video wird veröffentlicht, sobald Sie fertig sind.', 'Видео будет опубликовано, как только вы закончите.', 'El vídeo será publicado en cuanto termines.', 'Video biter bitmez yayınlanacaktır.'),
('videos', 'Videos', 'أشرطة فيديو', 'Videos', 'Vidéos', 'Videos', 'Видео', 'Videos', 'Videolar'),
('view_count', 'View count', 'مشاهدة العد', 'Kijkcijfers', 'Nombre de vues', 'Anzahl anzeigen', 'Количество просмотров', 'Recuento de vistas', 'Sayıyı görüntüle'),
('viewers', 'Viewers', 'مشاهدون', 'Kijkers', 'Les spectateurs', 'Zuschauer', 'Зрителей', 'Espectadores', 'Görüntüleyenler'),
('views', 'views', 'المشاهدات', 'bekeken', 'vues', 'ansichten', 'просмотры', 'vistas', 'görünümler'),
('visualizations', 'Visualizations', 'المشاهدات', 'Bekeken', 'Visualisations', 'Visualisierungen', 'мнения', 'Visualizaciones', 'Görünümler'),
('was_deleted', 'has been deleted!', 'تم حذف!', 'har blitt slettet!', 'a été supprimé!', 'wurde gelöscht!', 'был удален!', 'Ha sido eliminado', 'silindi!'),
('watch_later', 'Watch later', 'سأشاهده لاحقا', 'Se senere', 'Regarder plus tard', 'Später ansehen', 'Посмотреть позже', 'Ver mas tarde', 'Daha sonra izle'),
('watermark', 'Watermark', 'علامة مائية', 'Watermerk', 'Marque d\'eau', 'Wasserzeichen', 'водяной знак', 'Marca de agua', 'Filigran'),
('watermark_appear_all_videos', 'This watermark will appear on all your videos', 'ستظهر هذه العلامة المائية على جميع مقاطع الفيديو الخاصة بك', 'Dit watermerk verschijnt op al je video\'s', 'Ce filigrane apparaîtra sur toutes vos vidéos', 'Dieses Wasserzeichen wird auf allen Ihren Videos angezeigt', 'Этот водяной знак будет отображаться на всех ваших видео.', 'Esta marca de agua aparecerá en todos tus videos', 'Bu filigran tüm videolarınızda görünecek'),
('watermark_appear_videos_bottom_right_player', 'This watermark will appear on your videos at the bottom right of the player.', 'ستظهر هذه العلامة المائية على مقاطع الفيديو الخاصة بك في الجزء السفلي الأيمن من المشغل.', 'Dit watermerk wordt rechtsonder in de speler op je video\'s weergegeven.', 'Ce filigrane apparaîtra sur vos vidéos en bas à droite du lecteur.', 'Dieses Wasserzeichen wird in Ihren Videos unten rechts im Player angezeigt.', 'Этот водяной знак будет отображаться на ваших видео в правом нижнем углу проигрывателя.', 'Esta marca de agua aparecera sobre tus videos en la parte inferior derecha del reproductor.', 'Bu filigran, oynatıcının sağ altında videolarınızda görünecektir.'),
('watermarks_15_seconds_less_video', 'Watermarks appear for 15 seconds or less depending on the length of the video.', 'تظهر العلامات المائية لمدة 15 ثانية أو أقل حسب طول الفيديو.', 'Watermerken worden 15 seconden of minder weergegeven, afhankelijk van de lengte van de video.', 'Les filigranes apparaissent pendant 15 secondes ou moins en fonction de la durée de la vidéo.', 'Wasserzeichen werden je nach Länge des Videos maximal 15 Sekunden lang angezeigt.', 'Водяные знаки появляются на 15 секунд или меньше в зависимости от длины видео.', 'Las marcas de agua aparecen por 15 segundos o menos según la duración del video.', 'Filigranlar, videonun uzunluğuna bağlı olarak 15 saniye veya daha az görünür.'),
('we_have_received_complaint', 'We have received your report!', 'لقد تلقينا تقريرك!', 'Vi har mottatt din rapport!', 'Nous avons reçu votre rapport!', 'Wir haben Ihren Bericht erhalten!', 'Мы получили ваш отчет!', '¡Hemos recibido su denuncia!', 'Raporunuzu aldık!'),
('we_have_sent_code', 'We have sent a 6 digit code to your email', 'لقد أرسلنا رمزًا مكونًا من 6 أرقام إلى بريدك الإلكتروني', 'We hebben een 6-cijferige code naar uw e-mail gestuurd', 'Nous avons envoyé un code à 6 chiffres à votre adresse e-mail', 'Wir haben einen 6-stelligen Code an Ihre E-Mail gesendet', 'Мы отправили на вашу электронную почту шестизначный код', 'Hemos enviado un código de 6 dígitos a su correo electrónico', 'E-postanıza 6 haneli bir kod gönderdik'),
('we_recommend_you', 'we recommend you', 'نوصيك', 'wij raden u aan', 'nous vous recommandons', 'Wir empfehlen Sie', 'мы рекомендуем вам', 'te recomendamos', 'size tavsiye ediyoruz'),
('website', 'Website', 'موقع الكتروني', 'Website', 'Site Web', 'Webseite', 'Веб-сайт', 'Sitio Web', 'İnternet sitesi'),
('website_ads', 'Website Ads', 'إعلانات الموقع', 'Website-advertenties', 'Annonces de site Web', 'Website-Anzeigen', 'Реклама на веб-сайтах', 'Anuncios del sitio web', 'Web Sitesi Reklamları'),
('website_cookies_ensure_best_experience_browsing', 'This website uses cookies to ensure that you get the best experience when browsing.', 'يستخدم موقع الويب هذا ملفات تعريف الارتباط لضمان حصولك على أفضل تجربة عند التصفح.', 'Deze website maakt gebruik van cookies om ervoor te zorgen dat u de beste surfervaring krijgt.', 'Ce site Web utilise des cookies pour vous garantir la meilleure expérience de navigation.', 'Diese Website verwendet Cookies, um sicherzustellen, dass Sie beim Surfen die bestmögliche Erfahrung machen.', 'Этот веб-сайт использует файлы cookie, чтобы обеспечить вам максимальное удобство при просмотре.', 'Este sitio web utiliza cookies para garantizar que obtenga la mejor experiencia al navegar por el.', 'Bu web sitesi, gezinirken en iyi deneyimi yaşamanızı sağlamak için çerezler kullanır.'),
('website_description', 'Description of the website', 'وصف الموقع', 'Beschrijving van de website', 'Description du site Web', 'Beschreibung der Website', 'Описание сайта', 'Descripción del sitio web', 'Web sitesinin açıklaması'),
('website_email', 'Website Email', 'البريد الإلكتروني للموقع', 'Website E-mail', 'Courriel du site Web', 'Website-E-Mail', 'Электронная почта веб-сайта', 'Correo electrónico del sitio web', 'Web Sitesi E-postası'),
('website_keywords', 'Website keywords', 'كلمات الموقع', 'Website trefwoorden', 'Mots-clés du site Web', 'Website-Schlüsselwörter', 'Ключевые слова веб-сайта', 'Palabras clave del sitio web', 'Web sitesi anahtar kelimeleri'),
('website_name', 'Website Name', 'اسم الموقع', 'website naam', 'Nom du site Web', 'Webseiten-Name', 'Имя веб-сайта', 'Nombre del Sitio Web', 'Web Sitesi Adı'),
('website_settings', 'Website Settings', 'إعدادات موقع الويب', 'Website-instellingen', 'Paramètres du site Web', 'Website-Einstellungen', 'Настройки веб-сайта', 'Configuración del sitio web', 'Web Sitesi Ayarları'),
('website_title', 'Website Title', 'عنوان الموقع', 'Website titel', 'Titre du site Web', 'Webseitentitel', 'Заголовок веб-сайта', 'Título de la página', 'Web Sitesi Başlığı'),
('wednesday', 'Wednesday', 'الأربعاء', 'Woensdag', 'Mercredi', 'Mittwoch', 'Cреда', 'Miércoles', 'Çarşamba'),
('what_happened', 'What happened?', 'ماذا حدث؟', 'Wat is er gebeurd?', 'Qu\'est-il arrivé?', 'Was ist passiert?', 'Что случилось?', '¿Que pasó?', 'Ne oldu?'),
('word_successfully_added', 'Word successfully added', 'تمت إضافة Word بنجاح', 'Word succesvol toegevoegd', 'Word ajouté avec succès', 'Word erfolgreich hinzugefügt', 'Слово успешно добавлено', 'Palabra agregada correctamente', 'Kelime başarıyla eklendi'),
('write_message', 'Write your message and hit enter..', 'اكتب رسالتك واضغط على إنتر ..', 'Schrijf je bericht en druk op enter ..', 'Rédigez votre message et appuyez sur Entrée.', 'Schreibe deine Nachricht und drücke Enter.', 'Напишите свое сообщение и нажмите enter.', 'Escriba su mensaje y presione enter ...', 'Mesajınızı yazın ve enter tuşuna basın ..'),
('write_your_comment', 'Write your comment..', 'اكتب تعليقك ..', 'Schrijf je reactie ..', 'Écrivez votre commentaire ..', 'Schreiben Sie Ihren Kommentar ..', 'Написать комментарий ..', 'Escriba su comentario ..', 'Yorumunuzu yazın ..'),
('wrong_confirm_code', 'Wrong confirmation code', 'رمز تأكيد خطأ', 'Foutieve bevestigingscode', 'Mauvais code de confirmation', 'Falscher Bestätigungscode', 'Неверный код подтверждения', 'Código de confirmación incorrecto', 'Yanlış onay kodu'),
('year', 'year', 'عام', 'jaar', 'an', 'Jahr', 'год', 'año', 'yıl'),
('years', 'years', 'سنوات', 'jaar', 'années', 'Jahre', 'лет', 'años', 'yıl'),
('yes', 'Yes', 'نعم فعلا', 'Ja', 'Oui', 'Ja', 'да', 'Sí', 'Evet'),
('yes_delete_it', 'Yes, delete it!', 'نعم، حذفه!', 'Ja, slett det!', 'Oui, supprimez-le!', 'Ja, löschen Sie es!', 'Да, удалите его!', 'Sí, ¡bórralo!', 'Evet, sil şunu!'),
('yesterday', 'Yesterday', 'أمس', 'Gisteren', 'Hier', 'Gestern', 'вчера', 'Ayer', 'dün'),
('you_can_update_your_username_again_in', 'You can update your username again in', 'يمكنك تحديث اسم المستخدم الخاص بك مرة أخرى في', 'U kunt uw gebruikersnaam opnieuw bijwerken in', 'Vous pouvez à nouveau mettre à jour votre nom d\'utilisateur dans', 'Sie können Ihren Benutzernamen in erneut aktualisieren', 'Вы можете снова обновить свое имя пользователя в', 'Podrás volver a actualizar tu nombre de usuario en', 'Kullanıcı adınızı tekrar güncelleyebilirsiniz'),
('you_replace_it_pin', 'Do you want to post this comment?', 'هل تريد نشر هذا التعليق؟', 'Wil je deze reactie plaatsen?', 'Voulez-vous publier ce commentaire?', 'Möchten Sie diesen Kommentar veröffentlichen?', 'Вы хотите оставить этот комментарий?', '¿Quieres fijar este comentario?', 'Bu yorumu göndermek istiyor musunuz?'),
('you_still_dont_meet_equirements', 'You still don\'t meet the requirements', 'ما زلت لا تفي بالمتطلبات', 'U voldoet nog steeds niet aan de eisen', 'Vous ne répondez toujours pas aux exigences', 'Sie erfüllen die Anforderungen immer noch nicht', 'Вы все еще не соответствуете требованиям', 'Aún no cumple con los requisitos', 'Hala gereksinimleri karşılamıyorsun'),
('you_sure_delete_report', 'Are you sure to delete this report?', 'هل أنت متأكد من حذف هذا التقرير؟', 'Weet u zeker dat u dit rapport wilt verwijderen?', 'Êtes-vous sûr de vouloir supprimer ce rapport?', 'Sind Sie sicher, diesen Bericht zu löschen?', 'Вы уверены, что хотите удалить этот отчет?', '¿Esta seguro de eliminar este reporte?', 'Bu raporu silmek istediğinizden emin misiniz?'),
('you_want_to_disapprove_video', 'Are you sure you want to disapprove of this video?', 'هل أنت متأكد أنك تريد رفض هذا الفيديو؟', 'Weet je zeker dat je deze video wilt afkeuren?', 'Voulez-vous vraiment refuser cette vidéo?', 'Möchten Sie dieses Video wirklich ablehnen?', 'Вы действительно хотите отклонить это видео?', '¿Seguro que desea desaprovar este video?', 'Bu videoyu reddetmek istediğinizden emin misiniz?'),
('you_want_to_remove_this_lock', 'Are you sure you want to remove this lock?', 'هل أنت متأكد أنك تريد إزالة هذا القفل؟', 'Weet u zeker dat u deze vergrendeling wilt verwijderen?', 'Voulez-vous vraiment supprimer ce verrou?', 'Möchten Sie dieses Schloss wirklich entfernen?', 'Вы уверены, что хотите снять эту блокировку?', '¿Seguro que desea quitar este bloqueo?', 'Bu kilidi kaldırmak istediğinizden emin misiniz?'),
('you_watching_replay_chat', 'You are watching the replay of the chat', 'أنت تشاهد إعادة الدردشة', 'U bekijkt de herhaling van de chat', 'Vous regardez la rediffusion du chat', 'Sie sehen sich die Wiederholung des Chats an', 'Вы смотрите повтор чата', 'Estas viendo la repetición del chat', 'Sohbetin tekrarını izliyorsunuz'),
('you_will_redirected_verify_email_continue', 'You will be redirected to verify your email, do you want to continue?', 'ستتم إعادة توجيهك للتحقق من بريدك الإلكتروني ، هل تريد المتابعة؟', 'U wordt doorgestuurd om uw e-mail te verifiëren. Wilt u doorgaan?', 'Vous serez redirigé pour vérifier votre adresse e-mail, souhaitez-vous continuer?', 'Sie werden weitergeleitet, um Ihre E-Mail-Adresse zu bestätigen. Möchten Sie fortfahren?', 'Вы будете перенаправлены на подтверждение своей электронной почты, вы хотите продолжить?', 'Serás redireccionado para verificar tu correo, ¿Deseas seguir?', 'E-postanızı doğrulamak için yönlendirileceksiniz, devam etmek istiyor musunuz?'),
('you_write_verification_code', 'You can also write this verification code:', 'يمكنك أيضًا كتابة رمز التحقق هذا:', 'U kunt ook deze verificatiecode schrijven:', 'Vous pouvez également écrire ce code de vérification:', 'Sie können diesen Bestätigungscode auch schreiben:', 'Вы также можете написать этот проверочный код:', 'También puedes escribir este código de verificación:', 'Bu doğrulama kodunu da yazabilirsiniz:'),
('your_account_was_deleted', 'Your account was deleted', 'تم حذف حسابك', 'Uw account is verwijderd', 'Votre compte a été supprimé', 'Ihr Konto wurde gelöscht', 'Ваша учетная запись была удалена', 'Se ha eliminado tu cuenta.', 'Hesabınız silindi'),
('your_email_address', 'Enter your email address.', 'أدخل عنوان بريدك الالكتروني.', 'Vul je e-mailadres in.', 'Entrez votre adresse email.', 'Geben sie ihre E-Mailadresse ein.', 'Введите ваш адрес электронной почты.', 'Introduce tu dirección de correo electrónico.', 'E-posta adresinizi giriniz.'),
('your_transmission_key_was_successfully_changed', 'Your transmission key was successfully changed', 'تم تغيير مفتاح الإرسال الخاص بك بنجاح', 'Uw transmissiesleutel is succesvol gewijzigd', 'Votre clé de transmission a été modifiée avec succès', 'Ihr Übertragungsschlüssel wurde erfolgreich geändert', 'Ваш ключ передачи был успешно изменен', 'Su clave de transmisión se cambio con éxito', 'İletim anahtarınız başarıyla değiştirildi'),
('your_watermark_was_removed', 'Your watermark was removed', 'تمت إزالة العلامة المائية الخاصة بك', 'Je watermerk is verwijderd', 'Votre filigrane a été supprimé', 'Ihr Wasserzeichen wurde entfernt', 'Ваш водяной знак был удален', 'Su marca de agua fue eliminada', 'Filigranınız kaldırıldı'),
('youre_not_old_enough_yet', 'You\'re not old enough yet', 'أنت لست كبيرًا بما يكفي بعد', 'Je bent nog niet oud genoeg', 'Tu n\'es pas encore assez vieux', 'Du bist noch nicht alt genug', 'Ты еще не достаточно взрослый', 'Aún no tienes la edad suficiente', 'Henüz yeterince yaşlı değilsin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `banned`
--
ALTER TABLE `banned`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `blocked`
--
ALTER TABLE `blocked`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to_id` (`to_id`) USING BTREE,
  ADD KEY `by_id` (`by_id`) USING BTREE;

--
-- Indices de la tabla `broadcasts`
--
ALTER TABLE `broadcasts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `by_id` (`by_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indices de la tabla `broadcasts_chat`
--
ALTER TABLE `broadcasts_chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `by_id` (`by_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indices de la tabla `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `by_id` (`by_id`) USING BTREE;

--
-- Indices de la tabla `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `by_id` (`by_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indices de la tabla `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `list_id` (`list_id`),
  ADD KEY `privacy` (`privacy`),
  ADD KEY `by_id` (`by_id`) USING BTREE;

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `seen` (`seen`),
  ADD KEY `time` (`time`),
  ADD KEY `to_id` (`to_id`) USING BTREE,
  ADD KEY `from_id` (`from_id`) USING BTREE;

--
-- Indices de la tabla `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `list_id` (`list_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indices de la tabla `progress`
--
ALTER TABLE `progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `by_id` (`by_id`) USING BTREE;

--
-- Indices de la tabla `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `processing` (`processing`),
  ADD KEY `video_res` (`video_width`);

--
-- Indices de la tabla `reactions`
--
ALTER TABLE `reactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `by_id` (`by_id`) USING BTREE,
  ADD KEY `to_id` (`to_id`) USING BTREE;

--
-- Indices de la tabla `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `by_id` (`by_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indices de la tabla `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `by_id` (`by_id`) USING BTREE,
  ADD KEY `to_id` (`to_id`) USING BTREE;

--
-- Indices de la tabla `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time` (`time`),
  ADD KEY `session_id` (`session_id`) USING BTREE,
  ADD KEY `by_id` (`by_id`) USING BTREE;

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`name`);

--
-- Indices de la tabla `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriber_id` (`subscriber_id`),
  ADD KEY `by_id` (`by_id`) USING BTREE;

--
-- Indices de la tabla `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `type` (`type`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `email` (`email`),
  ADD KEY `password` (`password`),
  ADD KEY `role` (`role`) USING BTREE,
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`) USING BTREE;

--
-- Indices de la tabla `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `views` (`views`),
  ADD KEY `time` (`time`),
  ADD KEY `240p` (`240p`),
  ADD KEY `360p` (`360p`),
  ADD KEY `480p` (`480p`),
  ADD KEY `720p` (`720p`),
  ADD KEY `1080p` (`1080p`),
  ADD KEY `privacy` (`privacy`),
  ADD KEY `short_id` (`short_id`),
  ADD KEY `approved` (`approved`),
  ADD KEY `category` (`category`) USING BTREE,
  ADD KEY `adults_only` (`adults_only`) USING BTREE,
  ADD KEY `by_id` (`by_id`) USING BTREE,
  ADD KEY `video_id` (`video_id`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `144p` (`144p`),
  ADD KEY `2160p` (`2160p`) USING BTREE,
  ADD KEY `1440p` (`1440p`) USING BTREE;
ALTER TABLE `videos` ADD FULLTEXT KEY `description` (`description`);
ALTER TABLE `videos` ADD FULLTEXT KEY `title` (`title`);
ALTER TABLE `videos` ADD FULLTEXT KEY `tags` (`tags`);

--
-- Indices de la tabla `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `fingerprint` (`fingerprint`),
  ADD KEY `time` (`time`),
  ADD KEY `by_id` (`by_id`) USING BTREE;

--
-- Indices de la tabla `watch_later`
--
ALTER TABLE `watch_later`
  ADD PRIMARY KEY (`id`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `by_id` (`by_id`) USING BTREE;

--
-- Indices de la tabla `words`
--
ALTER TABLE `words`
  ADD PRIMARY KEY (`word`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `banned`
--
ALTER TABLE `banned`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `blocked`
--
ALTER TABLE `blocked`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `broadcasts`
--
ALTER TABLE `broadcasts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `broadcasts_chat`
--
ALTER TABLE `broadcasts_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lists`
--
ALTER TABLE `lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `progress`
--
ALTER TABLE `progress`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reactions`
--
ALTER TABLE `reactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

--
-- AUTO_INCREMENT de la tabla `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `views`
--
ALTER TABLE `views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `watch_later`
--
ALTER TABLE `watch_later`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
