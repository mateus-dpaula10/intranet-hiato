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

-- Copiando estrutura para tabela perfil-cultura.options
CREATE TABLE IF NOT EXISTS `options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `diagnostic_id` bigint(20) unsigned NOT NULL,
  `text` varchar(255) NOT NULL,
  `points` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `options_diagnostic_id_foreign` (`diagnostic_id`),
  CONSTRAINT `options_diagnostic_id_foreign` FOREIGN KEY (`diagnostic_id`) REFERENCES `diagnostics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela perfil-cultura.options: ~40 rows (aproximadamente)
INSERT IGNORE INTO `options` (`id`, `diagnostic_id`, `text`, `points`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Apresento a ideia de forma aberta ao grupo e ouço sugestões. (Criatividade + Trabalho em equipe)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(2, 1, 'Anoto a ideia, mas só compartilho se alguém perguntar. (Falta de iniciativa + Possível insegurança)', 2, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(3, 1, 'Coloco em prática e vejo se funciona, depois compartilho o resultado. (Criatividade + Falta de alinhamento coletivo)', 1, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(4, 1, 'Verifico os impactos e proponho formalmente. (Atenção aos detalhes + Qualidade)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(5, 2, 'Escuto com atenção, agradeço e reflito sobre o ponto. (Gostar de feedback + Lidar bem com críticas)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(6, 2, 'Tento entender melhor os critérios antes de qualquer conclusão. (Autodesenvolvimento + Curiosidade saudável)', 2, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(7, 2, 'Avalio se o feedback faz sentido para mim, caso contrário, sigo como antes. (Resistência velada)', 1, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(8, 2, 'Explico meu ponto de vista para justificar as escolhas feitas. (Desvio: defensividade disfarçada)', 0, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(9, 3, 'Procuro estudar e entender antes de começar. (Autodesenvolvimento)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(10, 3, 'Pergunto para alguém que entenda e sigo as instruções. (Trabalho em equipe + Curva de aprendizado)', 2, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(11, 3, 'Tento fazer do meu jeito, sem depender dos outros. (Excesso de autonomia sem base)', 1, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(12, 3, 'Peço para trocar a tarefa por algo mais dentro da minha experiência. (Fuga da zona de desenvolvimento)', 0, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(13, 4, 'Ajusto o que for possível sem comprometer o prazo. (Organização + Qualidade + Responsabilidade)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(14, 4, 'Prioritariamente cumpro o prazo, mesmo que sacrifique detalhes. (Pontualidade acima da qualidade)', 2, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(15, 4, 'Entrego como está para não parecer perfeccionista demais. (Desvio: comodismo disfarçado)', 1, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(16, 4, 'Alinho com o responsável que talvez valha a pena rever o prazo. (Trabalho em equipe + Comprometimento com qualidade)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(17, 5, 'Me ofereço para ajudar e garantir que ambos cumpram seus prazos. (Trabalho em equipe + Proatividade)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(18, 5, 'Acompanho de longe, caso ele precise, estou por perto. (Solidariedade passiva)', 2, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(19, 5, 'Sigo focado no meu, para não comprometer minha produtividade. (Desvio sutil: individualismo produtivo)', 1, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(20, 5, 'Comento com o gestor sobre o risco do atraso. (Falta de empatia direta + foco em processo)', 0, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(21, 6, 'Aproveita para propor melhorias nos processos ou organizar pendências. (Criatividade + Organização)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(22, 6, 'Ajuda o time em tarefas que possam ter ficado acumuladas. (Trabalho em equipe + Bom senso colaborativo)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(23, 6, 'Usa o tempo para descansar um pouco mais — afinal, já fez sua parte. (Desvio sutil: foco individual)', 1, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(24, 6, 'Mantém a rotina e evita sobrecarregar ninguém, mesmo tendo tempo livre. (Conservadorismo produtivo)', 2, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(25, 7, 'Encaro como oportunidade de crescer, mesmo se desconfortável. (Maturidade emocional + Lidar bem com críticas)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(26, 7, 'Prefiro críticas em particular, mas consigo lidar. (Preferência pessoal com resiliência)', 2, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(27, 7, 'Acho que críticas públicas desmotivam e geram constrangimento. (Sensibilidade elevada à forma)', 1, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(28, 7, 'Costumo questionar se o momento e tom foram apropriados. (Desvio disfarçado de assertividade)', 0, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(29, 8, 'Sigo processos bem definidos para manter qualidade. (Atenção aos detalhes + Compromisso com excelência)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(30, 8, 'Tento terminar rápido para focar em tarefas mais estratégicas. (Proatividade com risco à qualidade)', 2, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(31, 8, 'Dou meu melhor, mas às vezes acabo deixando passar alguns detalhes. (Sinceridade com distração operacional)', 1, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(32, 8, 'Reorganizo as tarefas para manter o foco no que gera mais impacto. (Criatividade + Visão de eficiência)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(33, 9, 'Compartilhar ideias e construir sobre as dos outros. (Criatividade + Trabalho em equipe)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(34, 9, 'Ouvir mais do que falar, absorvendo o que é dito. (Perfil observador + Autodesenvolvimento)', 2, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(35, 9, 'Falar quando tem certeza da ideia, para não correr risco de errar. (Autoproteção criativa)', 1, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(36, 9, 'Sugerir apenas ideias práticas, não gosto de pensar fora do padrão. (Falta de criatividade e adaptabilidade)', 0, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(37, 10, 'Apoio a correção e ajudo sem expor a pessoa. (Trabalho em equipe + Feedback com empatia)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(38, 10, 'Sinalizo com cuidado, sugerindo formas de evitar no futuro. (Lidar bem com críticas + Preocupação com qualidade)', 3, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(39, 10, 'Faço comentários sutis para que ele perceba por conta própria. (Desvio sutil: passividade e julgamento velado)', 2, '2025-07-25 21:33:40', '2025-07-25 21:33:40'),
	(40, 10, 'Corrijo sem rodeios, acredito que as pessoas precisam ouvir a verdade. (Desvio disfarçado de sinceridade)', 1, '2025-07-25 21:33:40', '2025-07-25 21:33:40');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
