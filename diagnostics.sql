-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Copiando estrutura para tabela perfil-cultura.diagnostics
CREATE TABLE IF NOT EXISTS `diagnostics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela perfil-cultura.diagnostics: ~10 rows (aproximadamente)
INSERT IGNORE INTO `diagnostics` (`id`, `question`, `created_at`, `updated_at`) VALUES
	(1, 'Você está participando de um projeto em equipe e tem uma ideia diferente de como resolver uma parte complexa. Como você age?', '2025-07-25 21:32:38', '2025-07-25 21:32:38'),
	(2, 'Ao receber um feedback que contraria sua percepção sobre um trabalho que entregou, o que costuma fazer?', '2025-07-25 21:32:38', '2025-07-25 21:32:38'),
	(3, 'Você recebe uma tarefa numa área que não domina. Como você reage?', '2025-07-25 21:32:38', '2025-07-25 21:32:38'),
	(4, 'Uma entrega precisa ser finalizada rapidamente, mas você percebe pontos que poderiam ser melhorados. O que faz?', '2025-07-25 21:32:38', '2025-07-25 21:32:38'),
	(5, 'Um colega está tendo dificuldades em uma entrega que também impacta seu trabalho. O que você faz?', '2025-07-25 21:32:38', '2025-07-25 21:32:38'),
	(6, 'Durante um período mais leve de trabalho, você:', '2025-07-25 21:32:38', '2025-07-25 21:32:38'),
	(7, 'Diante de uma crítica recebida de maneira pública, o que costuma sentir?', '2025-07-25 21:32:38', '2025-07-25 21:32:38'),
	(8, 'Em um ambiente com muitas tarefas pequenas e repetitivas, como você lida?', '2025-07-25 21:32:38', '2025-07-25 21:32:38'),
	(9, 'Em reuniões ou brainstorms, seu estilo costuma ser:', '2025-07-25 21:32:38', '2025-07-25 21:32:38'),
	(10, 'Quando um colega comete um erro, o que você faz?', '2025-07-25 21:32:38', '2025-07-25 21:32:38');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
