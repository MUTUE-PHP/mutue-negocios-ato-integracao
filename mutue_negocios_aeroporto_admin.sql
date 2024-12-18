-- --------------------------------------------------------
-- Anfitrião:                    127.0.0.1
-- Versão do servidor:           5.7.24 - MySQL Community Server (GPL)
-- SO do servidor:               Win64
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.activacao_licencas
CREATE TABLE IF NOT EXISTS `activacao_licencas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `licenca_id` int(10) unsigned NOT NULL,
  `empresa_id` int(10) unsigned NOT NULL,
  `pagamento_id` int(10) unsigned DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `data_activacao` date DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `operador` varchar(255) DEFAULT NULL,
  `canal_id` int(10) unsigned NOT NULL,
  `status_licenca_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_rejeicao` timestamp NULL DEFAULT NULL,
  `observacao` text,
  `data_notificaticao` date DEFAULT NULL,
  `notificacaoFimLicenca` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_activacao_licencas` (`licenca_id`),
  KEY `FK_activacao_empresa` (`empresa_id`),
  KEY `FK_activacao_user` (`user_id`),
  KEY `FK_activacao_canal` (`canal_id`),
  KEY `FK_activacao_licencas_status_licencas` (`status_licenca_id`),
  KEY `FK_activacao_licencas_pagamentos` (`pagamento_id`),
  CONSTRAINT `FK_activacao_canal` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_activacao_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`),
  CONSTRAINT `FK_activacao_licencas` FOREIGN KEY (`licenca_id`) REFERENCES `licencas` (`id`),
  CONSTRAINT `FK_activacao_licencas_pagamentos` FOREIGN KEY (`pagamento_id`) REFERENCES `pagamentos` (`id`),
  CONSTRAINT `FK_activacao_licencas_status_licencas` FOREIGN KEY (`status_licenca_id`) REFERENCES `status_licencas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.activacao_licencas: ~1 rows (aproximadamente)
INSERT INTO `activacao_licencas` (`id`, `licenca_id`, `empresa_id`, `pagamento_id`, `data_inicio`, `data_fim`, `data_activacao`, `user_id`, `operador`, `canal_id`, `status_licenca_id`, `created_at`, `updated_at`, `data_rejeicao`, `observacao`, `data_notificaticao`, `notificacaoFimLicenca`) VALUES
	(140, 2, 167, NULL, '2024-01-23', '2024-01-23', '2024-01-23', NULL, NULL, 2, 1, '2024-01-23 16:10:54', '2024-12-06 07:50:02', NULL, 'Ativação da licença definitiva', '2024-01-24', '2024-12-06'),
	(141, 2, 167, NULL, '2024-12-05', '2025-12-05', '2024-12-05', NULL, NULL, 2, 1, '2024-01-23 16:10:54', '2024-12-06 07:50:02', NULL, 'Ativação da licença definitiva', '2024-01-24', '2024-12-06');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.anuncios
CREATE TABLE IF NOT EXISTS `anuncios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_inicio` datetime NOT NULL,
  `data_final` datetime NOT NULL,
  `descricao` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `titulo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.anuncios: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.armazems
CREATE TABLE IF NOT EXISTS `armazems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_sistema_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_armazems_estado_sistemas` (`estado_sistema_id`),
  CONSTRAINT `FK_armazems_estado_sistemas` FOREIGN KEY (`estado_sistema_id`) REFERENCES `estado_sistemas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.armazems: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.bancos
CREATE TABLE IF NOT EXISTS `bancos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(145) NOT NULL,
  `titular` varchar(255) DEFAULT NULL,
  `sigla` varchar(20) NOT NULL,
  `uuid` varchar(255) DEFAULT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `canal_id` int(10) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_bancos_status_gerais` (`status_id`),
  KEY `FK_bancos_canais_comunicacoes` (`canal_id`),
  KEY `FK_bancos_users` (`user_id`),
  CONSTRAINT `FK_bancos_canais_comunicacoes` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_bancos_status_gerais` FOREIGN KEY (`status_id`) REFERENCES `status_gerais` (`id`),
  CONSTRAINT `FK_bancos_users` FOREIGN KEY (`user_id`) REFERENCES `users_admin` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.bancos: ~3 rows (aproximadamente)
INSERT INTO `bancos` (`id`, `designacao`, `titular`, `sigla`, `uuid`, `status_id`, `canal_id`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'BANCO ECONÓMICO', 'MUTUE SOLUÇÕES TECNOLÓGICAS INTELIGENTES LDA', 'BE', '60b8333b-832f-406b-a791-8d30cfb5e159', 2, 3, 1, '2020-05-29 07:07:38', '2023-07-12 09:36:55', NULL),
	(2, 'BANCO ANGOLANO DE INVESTIMENTOS', 'MUTUE SOLUÇÕES TECNOLÓGICAS INTELIGENTES LDA', 'BAI', '9789be23-475c-4d22-aa16-9246fef1471c', 2, 3, 1, '2023-07-12 09:42:50', '2023-07-12 09:42:50', NULL),
	(6, 'BANCO DE FOMENTO ANGOLA', 'MUTUE SOLUÇÕES TECNOLÓGICAS INTELIGENTES LDA', 'BFA', '1be612e3-11d3-484e-aa24-fcea3df85d9b', 1, 3, 1, '2023-07-12 09:42:50', '2023-07-12 09:42:50', NULL);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.black_list
CREATE TABLE IF NOT EXISTS `black_list` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.black_list: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.canais_comunicacoes
CREATE TABLE IF NOT EXISTS `canais_comunicacoes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.canais_comunicacoes: ~4 rows (aproximadamente)
INSERT INTO `canais_comunicacoes` (`id`, `designacao`) VALUES
	(1, 'BD'),
	(2, 'Portal Cliente'),
	(3, 'Portal Admin'),
	(4, 'Mobile');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.comprovativos_facturas
CREATE TABLE IF NOT EXISTS `comprovativos_facturas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `factura_id` int(11) NOT NULL,
  `comprovativo_pgt_recibos` varchar(255) DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1' COMMENT '1=>Pendente, 1=>aceite, 3=>rejeitado',
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `numero_operacao_bancaria` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comprovativos_facturas_facturas_users_adicionais` (`factura_id`),
  KEY `FK_comprovativos_facturas_users_admin` (`user_id`),
  CONSTRAINT `FK_comprovativos_facturas_facturas_users_adicionais` FOREIGN KEY (`factura_id`) REFERENCES `facturas_users_adicionais` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_comprovativos_facturas_users_admin` FOREIGN KEY (`user_id`) REFERENCES `users_admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.comprovativos_facturas: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.contactos
CREATE TABLE IF NOT EXISTS `contactos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_contacto_id` int(10) unsigned NOT NULL,
  `designacao` varchar(45) NOT NULL,
  `empresa_id` int(10) unsigned NOT NULL,
  `canal_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_contactos_empresa` (`empresa_id`),
  KEY `FK_contactos_tipo_contacto` (`tipo_contacto_id`),
  KEY `FK_contactos_canal` (`canal_id`),
  CONSTRAINT `FK_contactos_canal` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_contactos_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`),
  CONSTRAINT `FK_contactos_tipo_contacto` FOREIGN KEY (`tipo_contacto_id`) REFERENCES `tipos_contactos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.contactos: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.coordenadas_bancarias
CREATE TABLE IF NOT EXISTS `coordenadas_bancarias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num_conta` varchar(45) NOT NULL,
  `iban` varchar(45) DEFAULT NULL,
  `banco_id` int(10) unsigned NOT NULL,
  `canal_id` int(10) unsigned NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_coordenadas_bancarias_bancos` (`banco_id`),
  KEY `FK_coordenadas_bancarias_canais_comunicacoes` (`canal_id`),
  KEY `FK_coordenadas_bancarias_status_gerais` (`status_id`),
  KEY `FK_coordenadas_bancarias_users` (`user_id`),
  CONSTRAINT `FK_coordenadas_bancarias_canais_comunicacoes` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_coordenadas_bancarias_status_gerais` FOREIGN KEY (`status_id`) REFERENCES `status_gerais` (`id`),
  CONSTRAINT `FK_coordenadas_bancarias_users` FOREIGN KEY (`user_id`) REFERENCES `users_admin` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.coordenadas_bancarias: ~3 rows (aproximadamente)
INSERT INTO `coordenadas_bancarias` (`id`, `num_conta`, `iban`, `banco_id`, `canal_id`, `status_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(9, '273036373 30 001', 'AO06 0006.0000.7303.6373.3014.3', 6, 3, 1, 1, '2023-07-12 09:42:50', '2023-07-12 09:42:50'),
	(10, '03179526626', 'AO06 0045.0951.0317.9526.6262.8', 1, 3, 2, 1, '2023-07-12 09:42:50', '2023-07-12 09:42:50'),
	(11, '226474907 10 001', 'AO06 0040.0000.2647.4907.1015.0', 2, 3, 2, 1, '2023-07-12 09:42:50', '2023-07-12 09:42:50');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.empresas
CREATE TABLE IF NOT EXISTS `empresas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `pessoal_Contacto` varchar(145) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `pais_id` int(10) unsigned NOT NULL,
  `saldo` double DEFAULT NULL,
  `nif` varchar(45) NOT NULL,
  `gestor_cliente_id` int(10) unsigned DEFAULT NULL,
  `tipo_cliente_id` int(10) unsigned NOT NULL,
  `tipo_regime_id` int(10) unsigned DEFAULT NULL,
  `logotipo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email` varchar(145) DEFAULT NULL,
  `referencia` varchar(145) DEFAULT NULL,
  `pessoa_de_contacto` varchar(145) DEFAULT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `canal_id` int(10) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cidade` varchar(255) DEFAULT NULL,
  `file_alvara` varchar(255) DEFAULT NULL,
  `file_nif` varchar(255) DEFAULT NULL,
  `licenca` varchar(255) DEFAULT NULL,
  `venda_online` enum('Y','N') DEFAULT 'N',
  `ultimo_acesso` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_empresas_pais` (`pais_id`),
  KEY `FK_empresas_canal` (`canal_id`),
  KEY `FK_empresas_status` (`status_id`),
  KEY `FK_empresas_tipo` (`tipo_cliente_id`),
  KEY `FK_empresas_users` (`user_id`),
  KEY `FK_empresas_gestor_clientes` (`gestor_cliente_id`),
  KEY `FK_empresas_tipos_regimes` (`tipo_regime_id`),
  CONSTRAINT `FK_empresas_canal` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_empresas_gestor_clientes` FOREIGN KEY (`gestor_cliente_id`) REFERENCES `gestor_clientes` (`id`),
  CONSTRAINT `FK_empresas_pais` FOREIGN KEY (`pais_id`) REFERENCES `paises` (`id`),
  CONSTRAINT `FK_empresas_status` FOREIGN KEY (`status_id`) REFERENCES `status_gerais` (`id`),
  CONSTRAINT `FK_empresas_tipo` FOREIGN KEY (`tipo_cliente_id`) REFERENCES `tipos_clientes` (`id`),
  CONSTRAINT `FK_empresas_tipos_regimes` FOREIGN KEY (`tipo_regime_id`) REFERENCES `tipos_regimes` (`id`),
  CONSTRAINT `FK_empresas_users` FOREIGN KEY (`user_id`) REFERENCES `users_admin` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.empresas: ~2 rows (aproximadamente)
INSERT INTO `empresas` (`id`, `nome`, `pessoal_Contacto`, `endereco`, `empresa_id`, `pais_id`, `saldo`, `nif`, `gestor_cliente_id`, `tipo_cliente_id`, `tipo_regime_id`, `logotipo`, `website`, `email`, `referencia`, `pessoa_de_contacto`, `status_id`, `canal_id`, `user_id`, `created_at`, `updated_at`, `cidade`, `file_alvara`, `file_nif`, `licenca`, `venda_online`, `ultimo_acesso`) VALUES
	(1, 'MUTUE SOLUÇÕES TECNOLÓGICAS INTELIGENTES LDA', '922969192', 'RUA NOSSA SENHORA DA MUXIMA, Nº 10-8º ANDAR', NULL, 1, 0, '5000977381', 1, 2, 3, 'admin/UMA.jpg', 'mutue.net', 'geral@mutue.net', '78500', NULL, 1, 3, 1, '2020-11-15 20:41:28', '2022-06-13 13:43:28', 'Luanda', NULL, NULL, NULL, 'N', NULL),
	(167, 'Airport Temporary Operator & Operacional Consulting SA (ATO & OC, SA)', '999999999', 'Estrada nacional 230, km 42 - Municipio do Icolo e Bengo, Distrito  do Bom Jesus, Luanda-Angola', NULL, 1, 0, '5002084546', 1, 1, 1, 'utilizadores/cliente/2k2yXEAb2Qz7fhk7S3CpKBdJvj9Q2NU4xNRuHNmV.png', 'ato.ao', 'info@ato.ao', '4EEJFPK', NULL, 1, 3, NULL, '2024-01-23 16:10:54', '2024-12-09 10:55:28', 'Luanda', NULL, NULL, 'ativo', 'N', '2024-12-09 11:55:28');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.facturas
CREATE TABLE IF NOT EXISTS `facturas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `total_preco_factura` double DEFAULT NULL,
  `valor_entregue` double DEFAULT NULL,
  `total_sem_imposto` double DEFAULT NULL,
  `valor_a_pagar` double DEFAULT NULL,
  `precoLicenca` double DEFAULT NULL,
  `troco` double DEFAULT NULL,
  `valor_extenso` varchar(345) DEFAULT NULL,
  `codigo_moeda` int(10) unsigned DEFAULT NULL,
  `desconto` double DEFAULT NULL,
  `total_iva` double DEFAULT NULL,
  `multa` double DEFAULT NULL,
  `nome_do_cliente` varchar(145) DEFAULT NULL,
  `telefone_cliente` varchar(145) DEFAULT NULL,
  `nif_cliente` varchar(145) DEFAULT NULL,
  `statusFactura` enum('1','2') DEFAULT '1' COMMENT '1=>divida; 2=>Pago',
  `email_cliente` varchar(145) DEFAULT NULL,
  `endereco_cliente` varchar(145) DEFAULT NULL,
  `numeroItems` int(10) unsigned DEFAULT NULL,
  `licenca_id` int(10) unsigned DEFAULT NULL,
  `tipo_documento` enum('FACTURA','FACTURA PROFORMA','FACTURA RECIBO') DEFAULT 'FACTURA',
  `venda_online` enum('Y','N') DEFAULT 'N',
  `observacao` text,
  `retencao` double DEFAULT NULL,
  `nextFactura` varchar(45) DEFAULT NULL,
  `faturaReference` varchar(45) DEFAULT NULL,
  `numSequenciaFactura` int(10) unsigned DEFAULT '0',
  `numeracaoFactura` varchar(255) DEFAULT NULL,
  `tipoFolha` enum('A4','A5','TICKET') DEFAULT NULL,
  `hashValor` text,
  `formas_pagamento_id` int(10) unsigned DEFAULT NULL,
  `retificado` enum('Sim','Nao') DEFAULT 'Nao',
  `descricao` varchar(255) DEFAULT NULL,
  `empresa_id` int(10) unsigned DEFAULT NULL,
  `canal_id` int(10) unsigned DEFAULT NULL,
  `status_id` int(10) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_vencimento` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_facturas_canal` (`canal_id`),
  KEY `FK_facturas_empresa` (`empresa_id`),
  KEY `FK_facturas_status` (`status_id`),
  KEY `FK_facturas_user` (`user_id`),
  KEY `FK_facturas_formas_pagamentos` (`formas_pagamento_id`),
  KEY `FK_facturas_moedas` (`codigo_moeda`),
  KEY `FK_facturas_licencas` (`licenca_id`),
  CONSTRAINT `FK_facturas_canal` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_facturas_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`),
  CONSTRAINT `FK_facturas_formas_pagamentos` FOREIGN KEY (`formas_pagamento_id`) REFERENCES `formas_pagamentos` (`id`),
  CONSTRAINT `FK_facturas_licencas` FOREIGN KEY (`licenca_id`) REFERENCES `licencas` (`id`),
  CONSTRAINT `FK_facturas_moedas` FOREIGN KEY (`codigo_moeda`) REFERENCES `moedas` (`id`),
  CONSTRAINT `FK_facturas_status` FOREIGN KEY (`status_id`) REFERENCES `status_gerais` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.facturas: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.facturas_users_adicionais
CREATE TABLE IF NOT EXISTS `facturas_users_adicionais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total_preco_factura` double NOT NULL DEFAULT '0',
  `troco` double NOT NULL DEFAULT '0',
  `valor_entregue` double NOT NULL DEFAULT '0',
  `valor_a_pagar` double NOT NULL DEFAULT '0',
  `desconto` int(11) NOT NULL DEFAULT '0',
  `retencao` int(11) NOT NULL DEFAULT '0',
  `total_iva` int(11) NOT NULL DEFAULT '0',
  `nome_do_cliente` varchar(255) NOT NULL,
  `valor_extenso` varchar(255) NOT NULL,
  `telefone_cliente` varchar(255) NOT NULL,
  `endereco_cliente` varchar(255) NOT NULL,
  `nif_cliente` varchar(255) NOT NULL,
  `observacao` text,
  `email_cliente` varchar(255) NOT NULL,
  `statusFactura` int(11) NOT NULL DEFAULT '1' COMMENT '1=>divida; 2=>Pago',
  `numeracaoFactura` varchar(50) NOT NULL,
  `hashValor` varchar(250) NOT NULL,
  `text_hash` varchar(250) NOT NULL,
  `empresa_id` int(11) unsigned NOT NULL,
  `canal_id` int(11) NOT NULL DEFAULT '2',
  `licenca_id` int(11) NOT NULL,
  `valor_licenca` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL,
  `operador` varchar(250) NOT NULL,
  `user_id_adicionado` int(11) NOT NULL,
  `nome_utilizador_adicionado` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_vencimento` date NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_facturas_users_adicionais_empresas` (`empresa_id`),
  CONSTRAINT `FK_facturas_users_adicionais_empresas` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.facturas_users_adicionais: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.factura_items
CREATE TABLE IF NOT EXISTS `factura_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descricao_produto` varchar(250) DEFAULT '0',
  `preco_produto` double NOT NULL DEFAULT '0',
  `quantidade_produto` int(10) unsigned NOT NULL,
  `total_preco_produto` double NOT NULL DEFAULT '0',
  `licenca_id` int(10) unsigned NOT NULL,
  `factura_id` int(10) unsigned NOT NULL,
  `desconto_produto` double DEFAULT '0',
  `retencao_produto` double DEFAULT '0',
  `incidencia_produto` double DEFAULT NULL,
  `iva_produto` double DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_factura_items_factura` (`factura_id`),
  KEY `FK_factura_items_licencas` (`licenca_id`),
  CONSTRAINT `FK_factura_items_factura` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`),
  CONSTRAINT `FK_factura_items_licencas` FOREIGN KEY (`licenca_id`) REFERENCES `licencas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.factura_items: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.failed_jobs: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.formas_pagamentos
CREATE TABLE IF NOT EXISTS `formas_pagamentos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.formas_pagamentos: ~4 rows (aproximadamente)
INSERT INTO `formas_pagamentos` (`id`, `descricao`) VALUES
	(1, 'TPA'),
	(2, 'DEPÓSITO'),
	(3, 'TRANSFERÊNCIA'),
	(4, 'MULTICAIXA');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.gestor_clientes
CREATE TABLE IF NOT EXISTS `gestor_clientes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(145) NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `canal_id` int(10) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_gestor_clientes_status_gerais` (`status_id`),
  KEY `FK_gestor_clientes_canais_comunicacoes` (`canal_id`),
  KEY `FK_gestor_clientes_users` (`user_id`),
  CONSTRAINT `FK_gestor_clientes_canais_comunicacoes` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_gestor_clientes_status_gerais` FOREIGN KEY (`status_id`) REFERENCES `status_gerais` (`id`),
  CONSTRAINT `FK_gestor_clientes_users` FOREIGN KEY (`user_id`) REFERENCES `users_admin` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.gestor_clientes: ~0 rows (aproximadamente)
INSERT INTO `gestor_clientes` (`id`, `nome`, `status_id`, `canal_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 'RAMOSSOFT TECNOLOGIAS LDA', 1, 3, 1, '2020-05-17 16:50:14', '2020-05-17 16:50:15');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.idiomas
CREATE TABLE IF NOT EXISTS `idiomas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.idiomas: ~2 rows (aproximadamente)
INSERT INTO `idiomas` (`id`, `designacao`) VALUES
	(1, 'Portugues'),
	(2, 'Inglês');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.jobs: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.licencas
CREATE TABLE IF NOT EXISTS `licencas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_licenca_id` int(10) unsigned NOT NULL,
  `designacao` varchar(345) NOT NULL,
  `uuid` varchar(345) DEFAULT NULL,
  `status_licenca_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `canal_id` int(10) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `descricao` text,
  `valor` double NOT NULL,
  `tipo_taxa_id` int(11) unsigned NOT NULL,
  `limite_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_licencas_tipo` (`tipo_licenca_id`),
  KEY `FK_licencas_status` (`status_licenca_id`),
  KEY `FK_licencas_canal` (`canal_id`),
  KEY `FK_licencas_users` (`user_id`),
  KEY `FK_licencas_tipotaxa` (`tipo_taxa_id`),
  CONSTRAINT `FK_licencas_canal` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_licencas_status_gerais` FOREIGN KEY (`status_licenca_id`) REFERENCES `status_gerais` (`id`),
  CONSTRAINT `FK_licencas_tipo` FOREIGN KEY (`tipo_licenca_id`) REFERENCES `tipos_licencas` (`id`),
  CONSTRAINT `FK_licencas_tipotaxa` FOREIGN KEY (`tipo_taxa_id`) REFERENCES `tipotaxa` (`codigo`),
  CONSTRAINT `FK_licencas_users` FOREIGN KEY (`user_id`) REFERENCES `users_admin` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.licencas: ~4 rows (aproximadamente)
INSERT INTO `licencas` (`id`, `tipo_licenca_id`, `designacao`, `uuid`, `status_licenca_id`, `created_at`, `updated_at`, `canal_id`, `user_id`, `descricao`, `valor`, `tipo_taxa_id`, `limite_usuario`) VALUES
	(1, 1, 'Grátis', NULL, 1, '2021-01-31 12:37:11', '2021-01-31 15:52:37', 3, 1, 'Plano Grátis', 0, 2, 2),
	(2, 2, 'Mensal', NULL, 1, '2021-01-31 12:38:02', '2021-01-31 15:49:18', 3, 1, 'Plano Mensal', 9025, 2, 2),
	(3, 3, 'Anual', NULL, 1, '2021-01-31 12:38:41', '2021-04-28 15:23:10', 3, 1, 'Plano Anual', 108300, 2, 2),
	(4, 4, 'Definitivo', NULL, 1, '2021-01-31 13:16:17', '2021-01-31 15:50:11', 3, 1, 'Plano Definitivo', 216600, 2, 2);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.licenca_empresa
CREATE TABLE IF NOT EXISTS `licenca_empresa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `licenca_id` int(10) unsigned NOT NULL,
  `empresa_id` int(10) unsigned NOT NULL,
  `valor` double unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_licenca_empresa_licencas` (`licenca_id`),
  KEY `FK_licenca_empresa_empresas` (`empresa_id`),
  CONSTRAINT `FK_licenca_empresa_empresas` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`),
  CONSTRAINT `FK_licenca_empresa_licencas` FOREIGN KEY (`licenca_id`) REFERENCES `licencas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.licenca_empresa: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.logsupdatepassword
CREATE TABLE IF NOT EXISTS `logsupdatepassword` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` int(11) DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.logsupdatepassword: ~2 rows (aproximadamente)
INSERT INTO `logsupdatepassword` (`id`, `empresa_id`, `users_id`, `created_at`, `updated_at`, `password`) VALUES
	(2, 38, 1, '2024-01-05 10:48:58', '2024-01-05 10:48:58', 'mutue123'),
	(3, 133, 1, '2023-08-21 14:15:46', '2023-08-21 14:15:46', 'mutue123');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.logs_acessos
CREATE TABLE IF NOT EXISTS `logs_acessos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) DEFAULT NULL,
  `maquina` varchar(45) DEFAULT NULL,
  `browser` text,
  `user_id` bigint(20) unsigned NOT NULL,
  `descricao` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `canal_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.logs_acessos: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.maquinas
CREATE TABLE IF NOT EXISTS `maquinas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_sistema_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_maquinas_estado_sistemas` (`estado_sistema_id`),
  CONSTRAINT `FK_maquinas_estado_sistemas` FOREIGN KEY (`estado_sistema_id`) REFERENCES `estado_sistemas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.maquinas: ~3 rows (aproximadamente)
INSERT INTO `maquinas` (`id`, `designacao`, `estado_sistema_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'M-01', 7, '2021-01-27 12:15:55', '2021-01-27 12:17:45', NULL),
	(2, 'M-02', 7, '2021-01-27 12:16:21', '2021-01-27 12:16:21', NULL),
	(5, 'M-03', 7, '2021-01-29 06:43:52', '2021-01-29 06:43:52', NULL);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.materia_especies
CREATE TABLE IF NOT EXISTS `materia_especies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.materia_especies: ~17 rows (aproximadamente)
INSERT INTO `materia_especies` (`id`, `tipo`, `cor`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Pau-Rosa', 'Rosa', '2021-01-28 16:43:56', NULL, NULL),
	(7, 'Tolas Branca', 'Branca', '2021-01-28 16:44:30', NULL, NULL),
	(8, 'Chinfuta', NULL, '2021-01-28 16:45:45', NULL, NULL),
	(9, 'Lifuma', NULL, '2021-01-28 16:46:06', NULL, NULL),
	(10, 'Kali', NULL, '2021-01-28 16:46:23', NULL, NULL),
	(11, 'Kâmbala', NULL, '2021-01-28 16:46:38', NULL, NULL),
	(12, 'Ndola', NULL, '2021-01-28 16:46:53', NULL, NULL),
	(13, 'Livuite', NULL, '2021-01-28 16:47:08', NULL, NULL),
	(14, 'Pau-Preto', 'Preta', '2021-01-28 16:47:24', NULL, NULL),
	(15, 'Kungulo', NULL, '2021-01-28 16:47:44', NULL, NULL),
	(16, 'Limba', NULL, '2021-01-28 16:48:00', NULL, NULL),
	(17, 'Vuku', NULL, '2021-01-28 16:48:55', NULL, NULL),
	(18, 'Wamba', NULL, '2021-01-28 16:49:11', NULL, NULL),
	(19, 'Banzala', NULL, '2021-01-28 16:49:33', NULL, NULL),
	(20, 'Takula', NULL, '2021-01-28 16:49:53', NULL, NULL),
	(21, 'Numbi', NULL, '2021-01-28 16:50:34', NULL, NULL),
	(22, 'Padouk', NULL, '2021-02-01 08:22:44', NULL, NULL);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.migrations: ~3 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_100000_create_password_resets_table', 1),
	(2, '2019_08_19_000000_create_failed_jobs_table', 1),
	(3, '2020_05_19_211825_create_permission_tables', 1);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.model_has_permissions: ~38 rows (aproximadamente)
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\admin\\User', 1),
	(2, 'App\\Models\\admin\\User', 1),
	(3, 'App\\Models\\admin\\User', 1),
	(4, 'App\\Models\\admin\\User', 1),
	(6, 'App\\Models\\admin\\User', 1),
	(4, 'App\\Models\\admin\\User', 26),
	(7, 'App\\Models\\admin\\User', 26),
	(3, 'App\\Models\\empresa\\User', 88),
	(6, 'App\\Models\\empresa\\User', 88),
	(7, 'App\\Models\\empresa\\User', 88),
	(10, 'App\\Models\\empresa\\User', 88),
	(3, 'App\\Models\\empresa\\User', 91),
	(6, 'App\\Models\\empresa\\User', 91),
	(7, 'App\\Models\\empresa\\User', 91),
	(10, 'App\\Models\\empresa\\User', 91),
	(11, 'App\\Models\\empresa\\User', 91),
	(1, 'App\\Models\\empresa\\User', 92),
	(2, 'App\\Models\\empresa\\User', 92),
	(3, 'App\\Models\\empresa\\User', 92),
	(4, 'App\\Models\\empresa\\User', 92),
	(6, 'App\\Models\\empresa\\User', 92),
	(7, 'App\\Models\\empresa\\User', 92),
	(8, 'App\\Models\\empresa\\User', 92),
	(9, 'App\\Models\\empresa\\User', 92),
	(10, 'App\\Models\\empresa\\User', 92),
	(11, 'App\\Models\\empresa\\User', 92),
	(3, 'App\\Models\\empresa\\User', 93),
	(6, 'App\\Models\\empresa\\User', 93),
	(7, 'App\\Models\\empresa\\User', 93),
	(10, 'App\\Models\\empresa\\User', 93),
	(3, 'App\\Models\\empresa\\User', 94),
	(6, 'App\\Models\\empresa\\User', 94),
	(7, 'App\\Models\\empresa\\User', 94),
	(10, 'App\\Models\\empresa\\User', 94),
	(3, 'App\\Models\\empresa\\User', 95),
	(6, 'App\\Models\\empresa\\User', 95),
	(7, 'App\\Models\\empresa\\User', 95),
	(10, 'App\\Models\\empresa\\User', 95);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8 NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.model_has_roles: ~230 rows (aproximadamente)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\admin\\User', 1),
	(2, 'App\\Models\\admin\\User', 1),
	(3, 'App\\Models\\admin\\User', 1),
	(3, 'App\\Models\\admin\\User', 26),
	(2, 'App\\Models\\admin\\User', 31),
	(3, 'App\\Models\\admin\\User', 38),
	(2, 'App\\Models\\admin\\User', 39),
	(3, 'App\\Models\\admin\\User', 40),
	(3, 'App\\Models\\admin\\User', 41),
	(3, 'App\\Models\\admin\\User', 42),
	(3, 'App\\Models\\admin\\User', 43),
	(3, 'App\\Models\\admin\\User', 44),
	(3, 'App\\Models\\admin\\User', 45),
	(3, 'App\\Models\\admin\\User', 46),
	(3, 'App\\Models\\admin\\User', 47),
	(3, 'App\\Models\\admin\\User', 48),
	(3, 'App\\Models\\admin\\User', 49),
	(3, 'App\\Models\\admin\\User', 50),
	(3, 'App\\Models\\admin\\User', 51),
	(3, 'App\\Models\\admin\\User', 52),
	(3, 'App\\Models\\admin\\User', 53),
	(3, 'App\\Models\\admin\\User', 54),
	(3, 'App\\Models\\admin\\User', 55),
	(3, 'App\\Models\\admin\\User', 56),
	(3, 'App\\Models\\admin\\User', 57),
	(3, 'App\\Models\\admin\\User', 58),
	(3, 'App\\Models\\admin\\User', 59),
	(3, 'App\\Models\\admin\\User', 60),
	(3, 'App\\Models\\admin\\User', 61),
	(3, 'App\\Models\\admin\\User', 62),
	(3, 'App\\Models\\admin\\User', 63),
	(3, 'App\\Models\\admin\\User', 64),
	(3, 'App\\Models\\admin\\User', 68),
	(3, 'App\\Models\\admin\\User', 70),
	(3, 'App\\Models\\admin\\User', 72),
	(3, 'App\\Models\\admin\\User', 74),
	(3, 'App\\Models\\admin\\User', 75),
	(3, 'App\\Models\\admin\\User', 76),
	(3, 'App\\Models\\admin\\User', 78),
	(3, 'App\\Models\\admin\\User', 79),
	(3, 'App\\Models\\admin\\User', 80),
	(3, 'App\\Models\\admin\\User', 82),
	(3, 'App\\Models\\admin\\User', 83),
	(3, 'App\\Models\\admin\\User', 87),
	(3, 'App\\Models\\admin\\User', 90),
	(3, 'App\\Models\\admin\\User', 92),
	(3, 'App\\Models\\admin\\User', 95),
	(3, 'App\\Models\\admin\\User', 96),
	(3, 'App\\Models\\admin\\User', 97),
	(3, 'App\\Models\\admin\\User', 99),
	(3, 'App\\Models\\admin\\User', 101),
	(3, 'App\\Models\\admin\\User', 102),
	(3, 'App\\Models\\admin\\User', 103),
	(3, 'App\\Models\\admin\\User', 104),
	(3, 'App\\Models\\admin\\User', 106),
	(3, 'App\\Models\\admin\\User', 107),
	(3, 'App\\Models\\admin\\User', 108),
	(3, 'App\\Models\\admin\\User', 111),
	(3, 'App\\Models\\admin\\User', 112),
	(3, 'App\\Models\\admin\\User', 113),
	(3, 'App\\Models\\admin\\User', 114),
	(3, 'App\\Models\\admin\\User', 115),
	(3, 'App\\Models\\admin\\User', 151),
	(3, 'App\\Models\\admin\\User', 153),
	(3, 'App\\Models\\admin\\User', 156),
	(3, 'App\\Models\\admin\\User', 160),
	(3, 'App\\Models\\admin\\User', 167),
	(3, 'App\\Models\\admin\\User', 168),
	(3, 'App\\Models\\admin\\User', 170),
	(3, 'App\\Models\\admin\\User', 172),
	(3, 'App\\Models\\admin\\User', 174),
	(3, 'App\\Models\\admin\\User', 178),
	(3, 'App\\Models\\admin\\User', 179),
	(3, 'App\\Models\\admin\\User', 180),
	(3, 'App\\Models\\admin\\User', 181),
	(3, 'App\\Models\\admin\\User', 182),
	(3, 'App\\Models\\admin\\User', 186),
	(3, 'App\\Models\\admin\\User', 187),
	(3, 'App\\Models\\admin\\User', 188),
	(3, 'App\\Models\\admin\\User', 191),
	(3, 'App\\Models\\admin\\User', 195),
	(3, 'App\\Models\\admin\\User', 198),
	(3, 'App\\Models\\admin\\User', 199),
	(3, 'App\\Models\\admin\\User', 200),
	(3, 'App\\Models\\admin\\User', 203),
	(3, 'App\\Models\\admin\\User', 207),
	(3, 'App\\Models\\admin\\User', 213),
	(3, 'App\\Models\\admin\\User', 214),
	(3, 'App\\Models\\admin\\User', 215),
	(3, 'App\\Models\\admin\\User', 218),
	(3, 'App\\Models\\admin\\User', 222),
	(3, 'App\\Models\\admin\\User', 224),
	(3, 'App\\Models\\admin\\User', 228),
	(3, 'App\\Models\\admin\\User', 232),
	(3, 'App\\Models\\admin\\User', 233),
	(3, 'App\\Models\\admin\\User', 234),
	(3, 'App\\Models\\admin\\User', 235),
	(3, 'App\\Models\\admin\\User', 236),
	(3, 'App\\Models\\admin\\User', 237),
	(3, 'App\\Models\\admin\\User', 238),
	(3, 'App\\Models\\admin\\User', 239),
	(3, 'App\\Models\\admin\\User', 240),
	(3, 'App\\Models\\admin\\User', 242),
	(3, 'App\\Models\\admin\\User', 243),
	(3, 'App\\Models\\admin\\User', 244),
	(3, 'App\\Models\\admin\\User', 245),
	(3, 'App\\Models\\admin\\User', 246),
	(3, 'App\\Models\\admin\\User', 249),
	(3, 'App\\Models\\admin\\User', 250),
	(3, 'App\\Models\\admin\\User', 251),
	(3, 'App\\Models\\admin\\User', 252),
	(3, 'App\\Models\\admin\\User', 253),
	(3, 'App\\Models\\admin\\User', 254),
	(3, 'App\\Models\\admin\\User', 255),
	(3, 'App\\Models\\admin\\User', 256),
	(3, 'App\\Models\\admin\\User', 257),
	(3, 'App\\Models\\admin\\User', 258),
	(3, 'App\\Models\\admin\\User', 259),
	(3, 'App\\Models\\admin\\User', 260),
	(3, 'App\\Models\\admin\\User', 261),
	(3, 'App\\Models\\admin\\User', 263),
	(3, 'App\\Models\\admin\\User', 264),
	(3, 'App\\Models\\admin\\User', 265),
	(3, 'App\\Models\\admin\\User', 266),
	(3, 'App\\Models\\admin\\User', 267),
	(3, 'App\\Models\\admin\\User', 268),
	(3, 'App\\Models\\admin\\User', 269),
	(3, 'App\\Models\\admin\\User', 271),
	(3, 'App\\Models\\admin\\User', 275),
	(3, 'App\\Models\\admin\\User', 276),
	(3, 'App\\Models\\admin\\User', 277),
	(3, 'App\\Models\\admin\\User', 278),
	(3, 'App\\Models\\admin\\User', 279),
	(3, 'App\\Models\\admin\\User', 280),
	(3, 'App\\Models\\admin\\User', 281),
	(3, 'App\\Models\\admin\\User', 282),
	(3, 'App\\Models\\admin\\User', 283),
	(3, 'App\\Models\\admin\\User', 284),
	(3, 'App\\Models\\admin\\User', 285),
	(3, 'App\\Models\\admin\\User', 286),
	(3, 'App\\Models\\admin\\User', 288),
	(3, 'App\\Models\\admin\\User', 289),
	(3, 'App\\Models\\admin\\User', 290),
	(3, 'App\\Models\\admin\\User', 291),
	(3, 'App\\Models\\admin\\User', 292),
	(3, 'App\\Models\\admin\\User', 293),
	(3, 'App\\Models\\admin\\User', 294),
	(3, 'App\\Models\\admin\\User', 295),
	(3, 'App\\Models\\admin\\User', 296),
	(3, 'App\\Models\\admin\\User', 297),
	(3, 'App\\Models\\admin\\User', 298),
	(3, 'App\\Models\\admin\\User', 299),
	(3, 'App\\Models\\admin\\User', 307),
	(3, 'App\\Models\\admin\\User', 308),
	(3, 'App\\Models\\admin\\User', 309),
	(3, 'App\\Models\\admin\\User', 310),
	(3, 'App\\Models\\admin\\User', 311),
	(3, 'App\\Models\\admin\\User', 312),
	(3, 'App\\Models\\admin\\User', 313),
	(3, 'App\\Models\\admin\\User', 314),
	(3, 'App\\Models\\admin\\User', 315),
	(3, 'App\\Models\\admin\\User', 316),
	(3, 'App\\Models\\admin\\User', 317),
	(3, 'App\\Models\\admin\\User', 318),
	(3, 'App\\Models\\admin\\User', 319),
	(3, 'App\\Models\\admin\\User', 320),
	(3, 'App\\Models\\admin\\User', 321),
	(3, 'App\\Models\\admin\\User', 322),
	(3, 'App\\Models\\admin\\User', 323),
	(3, 'App\\Models\\admin\\User', 324),
	(3, 'App\\Models\\admin\\User', 325),
	(3, 'App\\Models\\admin\\User', 326),
	(3, 'App\\Models\\admin\\User', 327),
	(3, 'App\\Models\\admin\\User', 328),
	(3, 'App\\Models\\admin\\User', 329),
	(3, 'App\\Models\\admin\\User', 330),
	(3, 'App\\Models\\admin\\User', 331),
	(3, 'App\\Models\\admin\\User', 332),
	(3, 'App\\Models\\admin\\User', 333),
	(3, 'App\\Models\\admin\\User', 334),
	(3, 'App\\Models\\admin\\User', 335),
	(3, 'App\\Models\\admin\\User', 336),
	(3, 'App\\Models\\admin\\User', 337),
	(3, 'App\\Models\\admin\\User', 338),
	(3, 'App\\Models\\admin\\User', 339),
	(3, 'App\\Models\\admin\\User', 340),
	(3, 'App\\Models\\admin\\User', 341),
	(3, 'App\\Models\\admin\\User', 342),
	(3, 'App\\Models\\admin\\User', 343),
	(3, 'App\\Models\\admin\\User', 344),
	(3, 'App\\Models\\admin\\User', 345),
	(3, 'App\\Models\\admin\\User', 346),
	(3, 'App\\Models\\admin\\User', 347),
	(3, 'App\\Models\\admin\\User', 348),
	(3, 'App\\Models\\admin\\User', 349),
	(3, 'App\\Models\\admin\\User', 350),
	(3, 'App\\Models\\admin\\User', 351),
	(3, 'App\\Models\\admin\\User', 352),
	(3, 'App\\Models\\admin\\User', 353),
	(3, 'App\\Models\\admin\\User', 354),
	(3, 'App\\Models\\admin\\User', 355),
	(3, 'App\\Models\\admin\\User', 356),
	(3, 'App\\Models\\admin\\User', 357),
	(3, 'App\\Models\\admin\\User', 358),
	(3, 'App\\Models\\admin\\User', 359),
	(3, 'App\\Models\\admin\\User', 360),
	(3, 'App\\Models\\admin\\User', 361),
	(3, 'App\\Models\\admin\\User', 362),
	(3, 'App\\Models\\admin\\User', 363),
	(3, 'App\\Models\\admin\\User', 364),
	(3, 'App\\Models\\admin\\User', 365),
	(3, 'App\\Models\\admin\\User', 366),
	(3, 'App\\Models\\admin\\User', 369),
	(3, 'App\\Models\\admin\\User', 371),
	(3, 'App\\Models\\admin\\User', 372),
	(3, 'App\\Models\\admin\\User', 373),
	(3, 'App\\Models\\admin\\User', 374),
	(3, 'App\\Models\\admin\\User', 375),
	(3, 'App\\Models\\admin\\User', 376),
	(3, 'App\\Models\\admin\\User', 377),
	(3, 'App\\Models\\admin\\User', 378),
	(3, 'App\\Models\\admin\\User', 379),
	(3, 'App\\Models\\admin\\User', 380),
	(3, 'App\\Models\\admin\\User', 381),
	(3, 'App\\Models\\admin\\User', 382),
	(3, 'App\\Models\\admin\\User', 383),
	(3, 'App\\Models\\admin\\User', 384),
	(3, 'App\\Models\\admin\\User', 385),
	(3, 'App\\Models\\admin\\User', 386),
	(3, 'App\\Models\\admin\\User', 387);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.moedas
CREATE TABLE IF NOT EXISTS `moedas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `cambio` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.moedas: ~0 rows (aproximadamente)
INSERT INTO `moedas` (`id`, `designacao`, `codigo`, `cambio`) VALUES
	(1, 'AKZ', 'AOA', 1);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.motivo
CREATE TABLE IF NOT EXISTS `motivo` (
  `codigo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigoMotivo` varchar(50) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `codigoStatus` int(10) unsigned NOT NULL DEFAULT '0',
  `canal_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `status_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `empresa_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `FK_motivo_canais_comunicacoes` (`canal_id`),
  KEY `FK_motivo_users` (`user_id`),
  KEY `FK_motivo_status_gerais` (`status_id`),
  CONSTRAINT `FK_motivo_canais_comunicacoes` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_motivo_status_gerais` FOREIGN KEY (`status_id`) REFERENCES `status_gerais` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.motivo: ~39 rows (aproximadamente)
INSERT INTO `motivo` (`codigo`, `codigoMotivo`, `descricao`, `codigoStatus`, `canal_id`, `user_id`, `status_id`, `created_at`, `updated_at`, `empresa_id`) VALUES
	(7, 'M04', 'IVA – Regime de não sujeição', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(8, 'M02', 'Transmissão de bens e serviço não sujeita', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(9, 'M00', 'Regime Transitório', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(10, 'M10', 'Isento nos termos da alínea a) do nº1 do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(11, 'M11', 'Isento nos termos da alínea b) do nº1 do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(12, 'M12', 'Isento nos termos da alínea c) do nº1 do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(13, 'M13', 'Isento nos termos da alínea d) do nº1 do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(14, 'M14', 'Isento nos termos da alínea e) do nº1 do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(15, 'M15', 'Isento nos termos da alínea f) do nº1 do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(16, 'M16', 'Isento nos termos da alínea g) do nº1 do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(17, 'M17', 'Isento nos termos da alínea h) do nº1 do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(18, 'M18', 'Isento nos termos da alínea i) do nº1 artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(19, 'M19', 'Isento nos termos da alínea j) do nº1 do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(20, 'M20', '0 Isento nos termos da alínea k) do nº1 do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(21, 'M21', 'Isento nos termos da alínea l) do nº1 do artigo 12.º do CIVA\r\n', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(22, 'M22', 'Isento nos termos da alínea m) do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(23, 'M23', 'Isento nos termos da alínea n) do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(24, 'M24', 'Isento nos termos da alínea 0) do artigo 12.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(25, 'M80', 'Isento nos termos da alínea a) do nº1 do artigo 14.º ', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(26, 'M81', 'Isento nos termos da alínea b) do nº1 do artigo 14.º ', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(27, 'M82', 'Isento nos termos da alínea c) do nº1 do artigo 14.º ', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(28, 'M83', 'Isento nos termos da alínea d) do nº1 do artigo 14.º ', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(29, 'M84', 'Isento nos termos da alínea e) do nº1 do artigo 14.º ', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(30, 'M85', 'Isento nos termos da alínea a) do nº2 do artigo 14.º', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(31, 'M86', 'Isento nos termos da alínea b) do nº2 do artigo 14.º', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(32, 'M30', 'Isento nos termos da alínea a) do artigo 15.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(33, 'M31', 'Isento nos termos da alínea b) do artigo 15.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(34, 'M32', 'Isento nos termos da alínea c) do artigo 15.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(35, 'M33', 'Isento nos termos da alínea d) do artigo 15.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(36, 'M34', 'Isento nos termos da alínea e) do artigo 15.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(37, 'M35', 'Isento nos termos da alínea f) do artigo 15.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(38, 'M36', 'Isento nos termos da alínea g) do artigo 15.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(39, 'M37', 'Isento nos termos da alínea h) do artigo 15.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(40, 'M38', 'Isento nos termos da alínea i) do artigo 15.º do CIVA', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(41, 'M90', 'Isento nos termos da alínea a) do nº1 do artigo 16.º', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(42, 'M91', 'Isento nos termos da alínea b) do nº1 do artigo 16.º', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(43, 'M92', 'Isento nos termos da alínea c) do nº1 do artigo 16.º', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(44, 'M93', 'Isento nos termos da alínea d) do nº1 do artigo 16.º', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL),
	(45, 'M94', 'Isento nos termos da alínea e) do nº1 do artigo 16.º', 1, 1, 1, 1, '2020-04-23 20:56:46', '2020-04-23 20:56:46', NULL);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.notificacoes_sistema
CREATE TABLE IF NOT EXISTS `notificacoes_sistema` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descricao` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `empresa_id` int(10) unsigned NOT NULL,
  `canal_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_notificacoes_empresa` (`empresa_id`),
  KEY `FK_notificacoes_canal` (`canal_id`),
  CONSTRAINT `FK_notificacoes_canal` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_notificacoes_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.notificacoes_sistema: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `canal_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.notifications: ~4 rows (aproximadamente)
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`, `empresa_id`, `canal_id`) VALUES
	('1890bdea-7d21-468b-812a-aaaf1b7e1115', 'App\\Notifications\\CadastroEmpresaNotificacao', 'App\\Models\\admin\\Empresa', 126, '{"notificacao":{"id":141,"nome":"Pontes Perfumes","pessoal_Contacto":"933604248","telefone1":null,"telefone2":null,"endereco":"Camama","pais_id":1,"saldo":0,"canal_id":3,"status_id":1,"nif":"006424499LA0","gestor_cliente_id":1,"tipo_cliente_id":2,"tipo_regime_id":1,"logotipo":"utilizadores\\/cliente\\/avatarEmpresa.png","website":"p-perfumes.co.ao","email":"pontes787@hotmail.com","referencia":"ORF1TPG","pessoa_de_contacto":null,"created_at":"2022-01-08 12:04:06","updated_at":"2022-01-08 12:04:06","cidade":"Luanda","file_alvara":null,"file_nif":null},"empresa":"Pontes Perfumes","mensagem":"A empresa Pontes Perfumes foi cadastrado na aplica\\u00e7\\u00e3o","descricao":"A empresa Pontes Perfumes fez o seu cadastro na aplica\\u00e7\\u00e3o Mutue Neg\\u00f3cios. O cadastro foi efectuado no dia 08-01-2022"}', NULL, '2022-01-08 13:04:08', '2022-01-08 13:04:08', NULL, NULL),
	('5c75f068-3916-442a-b287-501c32adf787', 'App\\Notifications\\CadastroEmpresaNotificacao', 'App\\Models\\admin\\Empresa', 125, '{"notificacao":{"id":140,"nome":"JAH - Food & Drink","pessoal_Contacto":"947659174","telefone1":null,"telefone2":null,"endereco":"M\\u00e1rtires, Rua 16","pais_id":1,"saldo":0,"canal_id":3,"status_id":1,"nif":"5000568490","gestor_cliente_id":1,"tipo_cliente_id":3,"tipo_regime_id":2,"logotipo":"utilizadores\\/cliente\\/avatarEmpresa.png","website":null,"email":"jahfooddrinks@gmail.com","referencia":"B635C8G","pessoa_de_contacto":null,"created_at":"2022-01-07 12:55:59","updated_at":"2022-01-07 12:55:59","cidade":"Luanda","file_alvara":null,"file_nif":null},"empresa":"JAH - Food & Drink","mensagem":"A empresa JAH - Food & Drink foi cadastrado na aplica\\u00e7\\u00e3o","descricao":"A empresa JAH - Food & Drink fez o seu cadastro na aplica\\u00e7\\u00e3o Mutue Neg\\u00f3cios. O cadastro foi efectuado no dia 07-01-2022"}', NULL, '2022-01-07 13:56:01', '2022-01-07 13:56:01', NULL, NULL),
	('bdfb1cc9-bed6-4055-b184-1bd199024896', 'App\\Notifications\\CadastroEmpresaNotificacao', 'App\\Models\\admin\\Empresa', 100, '{"notificacao":{"id":115,"nome":"Bluexpress Lda","pessoal_Contacto":"945102900","endereco":"Vila Alice","pais_id":1,"saldo":0,"canal_id":3,"status_id":1,"nif":"5480015281","gestor_cliente_id":1,"tipo_cliente_id":3,"tipo_regime_id":1,"logotipo":"utilizadores\\/cliente\\/avatarEmpresa.png","website":null,"email":"bluexpress.geral@gmail.com","referencia":"X77D027","pessoa_de_contacto":null,"created_at":"2021-08-29 23:11:35","updated_at":"2021-08-29 23:11:35","cidade":"Luanda","file_alvara":null,"file_nif":null},"empresa":"Bluexpress Lda","mensagem":"A empresa Bluexpress Lda foi cadastrado na aplica\\u00e7\\u00e3o","descricao":"A empresa Bluexpress Lda fez o seu cadastro na aplica\\u00e7\\u00e3o Mutue Neg\\u00f3cios. O cadastro foi efectuado no dia 29-08-2021"}', NULL, '2021-08-30 00:11:38', '2021-08-30 00:11:38', NULL, NULL),
	('d50d35d4-c994-4fbb-b33a-ca09becc88a8', 'App\\Notifications\\CadastroEmpresaNotificacao', 'App\\Models\\admin\\Empresa', 122, '{"notificacao":{"id":137,"nome":"nicentech","pessoal_Contacto":"923722209","telefone1":null,"telefone2":null,"endereco":"prenda rua da 8 Esquadra","pais_id":1,"saldo":0,"canal_id":3,"status_id":1,"nif":"5417522627","gestor_cliente_id":1,"tipo_cliente_id":3,"tipo_regime_id":1,"logotipo":"utilizadores\\/cliente\\/avatarEmpresa.png","website":null,"email":"nicentech@gmail.com","referencia":"1Y92SRB","pessoa_de_contacto":null,"created_at":"2021-12-07 09:24:18","updated_at":"2021-12-07 09:24:18","cidade":"Luanda","file_alvara":null,"file_nif":null},"empresa":"nicentech","mensagem":"A empresa nicentech foi cadastrado na aplica\\u00e7\\u00e3o","descricao":"A empresa nicentech fez o seu cadastro na aplica\\u00e7\\u00e3o Mutue Neg\\u00f3cios. O cadastro foi efectuado no dia 07-12-2021"}', NULL, '2021-12-07 10:24:20', '2021-12-07 10:24:20', NULL, NULL);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.pagamentos
CREATE TABLE IF NOT EXISTS `pagamentos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `valor_depositado` double NOT NULL DEFAULT '0',
  `quantidade` int(10) NOT NULL DEFAULT '0',
  `totalPago` double NOT NULL DEFAULT '0',
  `data_pago_banco` date NOT NULL,
  `numero_operacao_bancaria` varchar(50) NOT NULL,
  `numeracao_recibo` varchar(50) DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `texto_hash` varchar(255) DEFAULT NULL,
  `valor_extenso` varchar(255) DEFAULT NULL,
  `numSequenciaRecibo` int(10) DEFAULT NULL,
  `forma_pagamento_id` int(10) unsigned NOT NULL,
  `conta_movimentada_id` int(10) unsigned NOT NULL,
  `referenciaFactura` varchar(50) NOT NULL,
  `comprovativo_bancario` varchar(145) NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `factura_id` int(10) unsigned NOT NULL,
  `empresa_id` int(10) unsigned NOT NULL,
  `canal_id` int(10) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `status_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_validacao` date DEFAULT NULL,
  `data_rejeitacao` date DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `descricao` varchar(250) DEFAULT NULL,
  `nFactura` varchar(100) DEFAULT NULL,
  `status` enum('PENDENTE','VÁLIDO') DEFAULT 'PENDENTE',
  PRIMARY KEY (`id`),
  KEY `FK_pagamentos_formas_pagamentos` (`forma_pagamento_id`),
  KEY `FK_pagamentos_bancos` (`conta_movimentada_id`),
  KEY `FK_pagamentos_facturas` (`factura_id`),
  KEY `FK_pagamentos_empresas` (`empresa_id`),
  KEY `FK_pagamentos_canais_comunicacoes` (`canal_id`),
  KEY `FK_pagamentos_users` (`user_id`),
  KEY `FK_pagamentos_status_gerais` (`status_id`),
  KEY `numero_operacao_bancaria` (`numero_operacao_bancaria`),
  KEY `referenciaFactura` (`referenciaFactura`),
  CONSTRAINT `FK_pagamentos_canais_comunicacoes` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_pagamentos_empresas` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`),
  CONSTRAINT `FK_pagamentos_facturas` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`),
  CONSTRAINT `FK_pagamentos_formas_pagamentos` FOREIGN KEY (`forma_pagamento_id`) REFERENCES `formas_pagamentos` (`id`),
  CONSTRAINT `FK_pagamentos_status_gerais` FOREIGN KEY (`status_id`) REFERENCES `status_gerais` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.pagamentos: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.paises
CREATE TABLE IF NOT EXISTS `paises` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  `sigla` varchar(45) DEFAULT NULL,
  `indicativo` varchar(45) DEFAULT NULL,
  `moeda_id` int(10) unsigned DEFAULT NULL,
  `idioma_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=248 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.paises: ~245 rows (aproximadamente)
INSERT INTO `paises` (`id`, `designacao`, `sigla`, `indicativo`, `moeda_id`, `idioma_id`) VALUES
	(1, 'Angola', 'ANG', '+244', 1, 1),
	(2, 'Argélia', NULL, NULL, NULL, NULL),
	(3, 'Brasil', NULL, NULL, NULL, NULL),
	(4, 'Alemanha', NULL, NULL, NULL, NULL),
	(5, 'Dinamarques', NULL, NULL, NULL, NULL),
	(6, 'França', NULL, NULL, NULL, NULL),
	(7, 'Canadá', NULL, NULL, NULL, NULL),
	(8, 'Itália', NULL, NULL, NULL, NULL),
	(9, 'Holanda', NULL, NULL, NULL, NULL),
	(10, 'Bélgica', NULL, NULL, NULL, NULL),
	(11, 'África do Sul', NULL, NULL, NULL, NULL),
	(12, 'Espanha', NULL, NULL, NULL, NULL),
	(13, 'Venezuela', NULL, NULL, NULL, NULL),
	(15, 'Grã-Bretanha', NULL, NULL, NULL, NULL),
	(16, 'Irlanda', NULL, NULL, NULL, NULL),
	(17, 'Moçambique', NULL, NULL, NULL, NULL),
	(18, 'Áustria', NULL, NULL, NULL, NULL),
	(19, 'Costa Rica', NULL, NULL, NULL, NULL),
	(21, 'Marrocos', NULL, NULL, NULL, NULL),
	(22, 'Afeganistão', NULL, NULL, NULL, NULL),
	(23, 'Albania', NULL, NULL, NULL, NULL),
	(24, 'Andorra', NULL, NULL, NULL, NULL),
	(25, 'Angola', NULL, NULL, NULL, NULL),
	(26, 'Anguila', NULL, NULL, NULL, NULL),
	(27, 'Antárctica', NULL, NULL, NULL, NULL),
	(28, 'Antígua e Barbuda', NULL, NULL, NULL, NULL),
	(29, 'Antilhas holandesas', NULL, NULL, NULL, NULL),
	(30, 'Arábia Saudita', NULL, NULL, NULL, NULL),
	(31, 'Argentina', NULL, NULL, NULL, NULL),
	(32, 'Arménia', NULL, NULL, NULL, NULL),
	(33, 'Aruba', NULL, NULL, NULL, NULL),
	(34, 'Austrália', NULL, NULL, NULL, NULL),
	(35, 'Azerbaijão', NULL, NULL, NULL, NULL),
	(36, 'Bahamas', NULL, NULL, NULL, NULL),
	(37, 'Bangladesh', NULL, NULL, NULL, NULL),
	(38, 'Barbados', NULL, NULL, NULL, NULL),
	(39, 'Barém', NULL, NULL, NULL, NULL),
	(40, 'Belize', NULL, NULL, NULL, NULL),
	(41, 'Benin', NULL, NULL, NULL, NULL),
	(42, 'Bermuda', NULL, NULL, NULL, NULL),
	(43, 'Bielorrússia', NULL, NULL, NULL, NULL),
	(44, 'Bolívia', NULL, NULL, NULL, NULL),
	(45, 'Bósnia e Herzegovina', NULL, NULL, NULL, NULL),
	(46, 'Botswana', NULL, NULL, NULL, NULL),
	(47, 'Brunei Darussalam', NULL, NULL, NULL, NULL),
	(48, 'Bulgária', NULL, NULL, NULL, NULL),
	(49, 'Burkina Faso', NULL, NULL, NULL, NULL),
	(50, 'Burundi', NULL, NULL, NULL, NULL),
	(51, 'Butão', NULL, NULL, NULL, NULL),
	(52, 'Cabo Verde', NULL, NULL, NULL, NULL),
	(53, 'Camarões', NULL, NULL, NULL, NULL),
	(54, 'Camboja', NULL, NULL, NULL, NULL),
	(55, 'Catar', NULL, NULL, NULL, NULL),
	(56, 'Cazaquistão', NULL, NULL, NULL, NULL),
	(57, 'Centro-Africana (República)', NULL, NULL, NULL, NULL),
	(58, 'Chade', NULL, NULL, NULL, NULL),
	(59, 'Chile', NULL, NULL, NULL, NULL),
	(60, 'China', NULL, NULL, NULL, NULL),
	(61, 'Chipre', NULL, NULL, NULL, NULL),
	(62, 'Cidade do Vaticano ver Santa Sé', NULL, NULL, NULL, NULL),
	(63, 'Colômbia', NULL, NULL, NULL, NULL),
	(64, 'Comores', NULL, NULL, NULL, NULL),
	(65, 'Congo', NULL, NULL, NULL, NULL),
	(66, 'Congo (República Democrática do)', NULL, NULL, NULL, NULL),
	(67, 'Coreia (República da) ', NULL, NULL, NULL, NULL),
	(68, 'Coreia (República Popular Democrática da) ', NULL, NULL, NULL, NULL),
	(69, 'Costa do Marfim', NULL, NULL, NULL, NULL),
	(70, 'Croácia', NULL, NULL, NULL, NULL),
	(71, 'Cuba', NULL, NULL, NULL, NULL),
	(72, 'Dinamarca', NULL, NULL, NULL, NULL),
	(73, 'Domínica', NULL, NULL, NULL, NULL),
	(74, 'Egipto', NULL, NULL, NULL, NULL),
	(75, 'El Salvador', NULL, NULL, NULL, NULL),
	(76, 'Emiratos Árabes Unidos', NULL, NULL, NULL, NULL),
	(77, 'Equador', NULL, NULL, NULL, NULL),
	(78, 'Eritreia', NULL, NULL, NULL, NULL),
	(79, 'Eslovaca (República)', NULL, NULL, NULL, NULL),
	(80, 'Eslovénia', NULL, NULL, NULL, NULL),
	(81, 'Estados Unidos', NULL, NULL, NULL, NULL),
	(82, 'Estónia', NULL, NULL, NULL, NULL),
	(83, 'Etiópia', NULL, NULL, NULL, NULL),
	(84, 'Filipinas', NULL, NULL, NULL, NULL),
	(85, 'Finlândia', NULL, NULL, NULL, NULL),
	(86, 'Gabão', NULL, NULL, NULL, NULL),
	(87, 'Gâmbia', NULL, NULL, NULL, NULL),
	(88, 'Gana', NULL, NULL, NULL, NULL),
	(89, 'Geórgia', NULL, NULL, NULL, NULL),
	(90, 'Georgia do Sul e Ilhas Sandwich', NULL, NULL, NULL, NULL),
	(91, 'Gibraltar', NULL, NULL, NULL, NULL),
	(92, 'Granada', NULL, NULL, NULL, NULL),
	(93, 'Grécia', NULL, NULL, NULL, NULL),
	(94, 'Gronelândia', NULL, NULL, NULL, NULL),
	(95, 'Guadalupe', NULL, NULL, NULL, NULL),
	(96, 'Guam', NULL, NULL, NULL, NULL),
	(97, 'Guatemala', NULL, NULL, NULL, NULL),
	(98, 'Guiana', NULL, NULL, NULL, NULL),
	(99, 'Guiana Francesa', NULL, NULL, NULL, NULL),
	(100, 'Guiné', NULL, NULL, NULL, NULL),
	(101, 'Guiné Equatorial', NULL, NULL, NULL, NULL),
	(102, 'Guiné-Bissau', NULL, NULL, NULL, NULL),
	(103, 'Haiti', NULL, NULL, NULL, NULL),
	(104, 'Honduras', NULL, NULL, NULL, NULL),
	(105, 'Hong Kong', NULL, NULL, NULL, NULL),
	(106, 'Hungria', NULL, NULL, NULL, NULL),
	(107, 'Iémen', NULL, NULL, NULL, NULL),
	(108, 'Ilhas Bouvet', NULL, NULL, NULL, NULL),
	(109, 'Ilhas Caimão', NULL, NULL, NULL, NULL),
	(110, 'Ilhas Christmas', NULL, NULL, NULL, NULL),
	(111, 'Ilhas Cocos (Keeling)', NULL, NULL, NULL, NULL),
	(112, 'Ilhas Cook', NULL, NULL, NULL, NULL),
	(113, 'Ilhas Falkland (Malvinas)', NULL, NULL, NULL, NULL),
	(114, 'Ilhas Faroé', NULL, NULL, NULL, NULL),
	(115, 'Ilhas Fiji', NULL, NULL, NULL, NULL),
	(116, 'Ilhas Heard e Ilhas McDonald', NULL, NULL, NULL, NULL),
	(117, 'Ilhas Marianas do Norte', NULL, NULL, NULL, NULL),
	(118, 'Ilhas Marshall', NULL, NULL, NULL, NULL),
	(119, 'Ilhas menores distantes dos Estados Unidos', NULL, NULL, NULL, NULL),
	(120, 'Ilhas Norfolk', NULL, NULL, NULL, NULL),
	(121, 'Ilhas Salomão', NULL, NULL, NULL, NULL),
	(122, 'Ilhas Virgens (britânicas)', NULL, NULL, NULL, NULL),
	(123, 'Ilhas Virgens (Estados Unidos)', NULL, NULL, NULL, NULL),
	(124, 'Índia', NULL, NULL, NULL, NULL),
	(125, 'Indonésia', NULL, NULL, NULL, NULL),
	(126, 'Irão (República Islâmica)', NULL, NULL, NULL, NULL),
	(127, 'Iraque', NULL, NULL, NULL, NULL),
	(128, 'Islândia', NULL, NULL, NULL, NULL),
	(129, 'Israel', NULL, NULL, NULL, NULL),
	(130, 'Jamaica', NULL, NULL, NULL, NULL),
	(131, 'Japão', NULL, NULL, NULL, NULL),
	(132, 'Jibuti', NULL, NULL, NULL, NULL),
	(133, 'Jordânia', NULL, NULL, NULL, NULL),
	(134, 'Jugoslávia', NULL, NULL, NULL, NULL),
	(135, 'Kenya', NULL, NULL, NULL, NULL),
	(136, 'Kiribati', NULL, NULL, NULL, NULL),
	(137, 'Kuwait', NULL, NULL, NULL, NULL),
	(138, 'Laos (República Popular Democrática do)', NULL, NULL, NULL, NULL),
	(139, 'Lesoto', NULL, NULL, NULL, NULL),
	(140, 'Letónia', NULL, NULL, NULL, NULL),
	(141, 'Líbano', NULL, NULL, NULL, NULL),
	(142, 'Libéria', NULL, NULL, NULL, NULL),
	(143, 'Líbia (Jamahiriya Árabe da)', NULL, NULL, NULL, NULL),
	(144, 'Liechtenstein', NULL, NULL, NULL, NULL),
	(145, 'Lituânia', NULL, NULL, NULL, NULL),
	(146, 'Luxemburgo', NULL, NULL, NULL, NULL),
	(147, 'Macau', NULL, NULL, NULL, NULL),
	(148, 'Macedónia (antiga república jugoslava da)', NULL, NULL, NULL, NULL),
	(149, 'Madagáscar', NULL, NULL, NULL, NULL),
	(150, 'Malásia', NULL, NULL, NULL, NULL),
	(151, 'Malawi', NULL, NULL, NULL, NULL),
	(152, 'Maldivas', NULL, NULL, NULL, NULL),
	(153, 'Mali', NULL, NULL, NULL, NULL),
	(154, 'Malta', NULL, NULL, NULL, NULL),
	(155, 'Martinica', NULL, NULL, NULL, NULL),
	(156, 'Maurícias', NULL, NULL, NULL, NULL),
	(157, 'Mauritânia', NULL, NULL, NULL, NULL),
	(158, 'Mayotte', NULL, NULL, NULL, NULL),
	(159, 'México', NULL, NULL, NULL, NULL),
	(160, 'Micronésia (Estados Federados da)', NULL, NULL, NULL, NULL),
	(161, 'Moldova (República de)', NULL, NULL, NULL, NULL),
	(162, 'Mónaco', NULL, NULL, NULL, NULL),
	(163, 'Mongólia', NULL, NULL, NULL, NULL),
	(164, 'Monserrate', NULL, NULL, NULL, NULL),
	(165, 'Myanmar', NULL, NULL, NULL, NULL),
	(166, 'Namíbia', NULL, NULL, NULL, NULL),
	(167, 'Nauru', NULL, NULL, NULL, NULL),
	(168, 'Nepal', NULL, NULL, NULL, NULL),
	(169, 'Nicarágua', NULL, NULL, NULL, NULL),
	(170, 'Niger', NULL, NULL, NULL, NULL),
	(171, 'Nigéria', NULL, NULL, NULL, NULL),
	(172, 'Niue', NULL, NULL, NULL, NULL),
	(173, 'Noruega', NULL, NULL, NULL, NULL),
	(174, 'Nova Caledónia', NULL, NULL, NULL, NULL),
	(175, 'Nova Zelândia', NULL, NULL, NULL, NULL),
	(176, 'Omã', NULL, NULL, NULL, NULL),
	(177, 'Países Baixos', NULL, NULL, NULL, NULL),
	(178, 'Palau', NULL, NULL, NULL, NULL),
	(179, 'Panamá', NULL, NULL, NULL, NULL),
	(180, 'Papuásia-Nova Guiné', NULL, NULL, NULL, NULL),
	(181, 'Paquistão', NULL, NULL, NULL, NULL),
	(182, 'Paraguai', NULL, NULL, NULL, NULL),
	(183, 'Peru', NULL, NULL, NULL, NULL),
	(184, 'Pitcairn', NULL, NULL, NULL, NULL),
	(185, 'Polinésia Francesa', NULL, NULL, NULL, NULL),
	(186, 'Polónia', NULL, NULL, NULL, NULL),
	(187, 'Porto Rico', NULL, NULL, NULL, NULL),
	(188, 'Portugal', NULL, NULL, NULL, NULL),
	(189, 'Quirguizistão', NULL, NULL, NULL, NULL),
	(190, 'Reino Unido', NULL, NULL, NULL, NULL),
	(191, 'República Checa', NULL, NULL, NULL, NULL),
	(192, 'República Dominicana', NULL, NULL, NULL, NULL),
	(193, 'Reunião', NULL, NULL, NULL, NULL),
	(194, 'Roménia', NULL, NULL, NULL, NULL),
	(195, 'Ruanda', NULL, NULL, NULL, NULL),
	(196, 'Rússia (Federação da)', NULL, NULL, NULL, NULL),
	(197, 'Samoa', NULL, NULL, NULL, NULL),
	(198, 'Samoa Americana', NULL, NULL, NULL, NULL),
	(199, 'Santa Helena', NULL, NULL, NULL, NULL),
	(200, 'Santa Lúcia', NULL, NULL, NULL, NULL),
	(201, 'Santa Sé (Cidade Estado do Vaticano)*', NULL, NULL, NULL, NULL),
	(202, 'São Cristóvão e Nevis', NULL, NULL, NULL, NULL),
	(203, 'São Marino', NULL, NULL, NULL, NULL),
	(204, 'São Pedro e Miquelon', NULL, NULL, NULL, NULL),
	(205, 'São Tomé e Príncipe', NULL, NULL, NULL, NULL),
	(206, 'São Vicente e Granadinas', NULL, NULL, NULL, NULL),
	(207, 'Sara Ocidental', NULL, NULL, NULL, NULL),
	(208, 'Senegal', NULL, NULL, NULL, NULL),
	(209, 'Serra Leoa', NULL, NULL, NULL, NULL),
	(210, 'Seychelles', NULL, NULL, NULL, NULL),
	(211, 'Singapura', NULL, NULL, NULL, NULL),
	(212, 'Síria (República Árabe da)', NULL, NULL, NULL, NULL),
	(213, 'Somália', NULL, NULL, NULL, NULL),
	(214, 'Sri Lanka', NULL, NULL, NULL, NULL),
	(215, 'Suazilândia', NULL, NULL, NULL, NULL),
	(216, 'Sudão', NULL, NULL, NULL, NULL),
	(217, 'Suécia', NULL, NULL, NULL, NULL),
	(218, 'Suiça', NULL, NULL, NULL, NULL),
	(219, 'Suriname', NULL, NULL, NULL, NULL),
	(220, 'Svålbard e a Ilha de Jan Mayen', NULL, NULL, NULL, NULL),
	(221, 'Tailândia', NULL, NULL, NULL, NULL),
	(222, 'Taiwan (Província da China)', NULL, NULL, NULL, NULL),
	(223, 'Tajiquistão', NULL, NULL, NULL, NULL),
	(224, 'Tanzânia, República Unida da', NULL, NULL, NULL, NULL),
	(225, 'Território Britânico do Oceano Índico', NULL, NULL, NULL, NULL),
	(226, 'Território Palestiniano Ocupado', NULL, NULL, NULL, NULL),
	(227, 'Territórios Franceses do Sul', NULL, NULL, NULL, NULL),
	(228, 'Timor Leste', NULL, NULL, NULL, NULL),
	(229, 'Togo', NULL, NULL, NULL, NULL),
	(230, 'Tokelau', NULL, NULL, NULL, NULL),
	(231, 'Tonga', NULL, NULL, NULL, NULL),
	(232, 'Trindade e Tobago', NULL, NULL, NULL, NULL),
	(233, 'Tunísia', NULL, NULL, NULL, NULL),
	(234, 'Turcos e Caicos (Ilhas)', NULL, NULL, NULL, NULL),
	(235, 'Turquemenistão', NULL, NULL, NULL, NULL),
	(236, 'Turquia', NULL, NULL, NULL, NULL),
	(237, 'Tuvalu', NULL, NULL, NULL, NULL),
	(238, 'Ucrânia', NULL, NULL, NULL, NULL),
	(239, 'Uganda', NULL, NULL, NULL, NULL),
	(240, 'Uruguai', NULL, NULL, NULL, NULL),
	(241, 'Usbequistão', NULL, NULL, NULL, NULL),
	(242, 'Vanuatu', NULL, NULL, NULL, NULL),
	(243, 'Vietname', NULL, NULL, NULL, NULL),
	(244, 'Wallis e Futuna (Ilha)', NULL, NULL, NULL, NULL),
	(245, 'Zaire, ver Congo (República Democrática do)', NULL, NULL, NULL, NULL),
	(246, 'Zâmbia', NULL, NULL, NULL, NULL),
	(247, 'Zimbabwe', NULL, NULL, NULL, NULL);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.parametros
CREATE TABLE IF NOT EXISTS `parametros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  `valor` varchar(45) DEFAULT NULL,
  `vida` int(10) unsigned DEFAULT NULL,
  `createde_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `canal_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_parametros_canal` (`canal_id`),
  CONSTRAINT `FK_parametros_canal` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.parametros: ~22 rows (aproximadamente)
INSERT INTO `parametros` (`id`, `designacao`, `valor`, `vida`, `createde_at`, `updated_at`, `canal_id`) VALUES
	(1, 'N.º de Dias Gratis', '30', NULL, '2020-04-22 16:18:57', '2020-04-22 16:18:57', 1),
	(2, 'N.º de Dias Aviso', '10', NULL, '2020-04-22 16:18:57', '2020-04-22 16:18:57', 1),
	(8, 'IPC', '0.05', 0, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(9, 'cambio', '190', 0, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(10, 'N.º Mes Paragem Vendas de Produto', NULL, 9, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(11, 'N.º Mes Alerta Vendas de Produto', NULL, 5, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(12, 'Nº Minimo de Alerta Existencia dos Produtos', NULL, 21, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(13, 'Valor Desconto', '100', 100, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(14, 'Retencao na fonte', '6.5', 7, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(15, 'hora', '22:00:00', 22, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(16, 'TipoImpreensao', 'A4', 1, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(17, 'NotaEntrega', 'SIM', 1, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(18, 'CartaRecompesa', 'SIM', 1, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(19, 'LayoutVenda', 'Classico', 1, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 1),
	(20, 'Nº Dias Vencimento Factura', NULL, 15, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 3),
	(21, 'IVA', NULL, 1, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 3),
	(22, 'Deposito de valor', NULL, 1, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 3),
	(23, 'Nº Dias Vencimento Factura Proforma', NULL, 15, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 3),
	(24, 'Sigla da empresa', 'AGT', 0, '2020-07-09 15:05:56', '2020-07-09 15:05:56', 3),
	(25, 'Licença Premium', 'Mensal', 31, '2020-07-16 15:38:01', '2020-07-16 15:38:01', 3),
	(26, 'Licença Platina', 'Anual', 365, '2020-07-16 15:40:57', '2020-07-16 15:40:57', 3),
	(27, 'Licença Definitiva', 'Definitiva', NULL, '2020-07-16 15:48:18', '2020-07-16 15:48:18', 3);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.password_resets: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.perfils
CREATE TABLE IF NOT EXISTS `perfils` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(255) NOT NULL,
  `status_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uuid` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.perfils: ~3 rows (aproximadamente)
INSERT INTO `perfils` (`id`, `designacao`, `status_id`, `user_id`, `created_at`, `updated_at`, `uuid`) VALUES
	(1, 'Super Administrador ', 1, 1, '2023-01-07 13:57:30', '2023-11-18 03:51:57', '57723dce-88b3-4a23-85e9-83d0fade77eb'),
	(2, 'Suporte Linha 1', 1, 1, '2023-01-13 10:56:29', '2023-01-13 10:56:29', 'd32e91c2-e502-464d-a786-11290f0edbd5'),
	(26, 'Apoio MUTUE', 1, 1, '2023-11-22 14:58:35', '2023-11-22 15:06:27', '58b2a821-ca74-41bc-80a1-7691c5853611');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.periodo_testes
CREATE TABLE IF NOT EXISTS `periodo_testes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `periodo` date NOT NULL,
  `dias_restante` int(10) unsigned NOT NULL,
  `empresa_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_periodo_teste_empresas` (`empresa_id`),
  CONSTRAINT `FK_periodo_teste_empresas` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.periodo_testes: ~4 rows (aproximadamente)
INSERT INTO `periodo_testes` (`id`, `periodo`, `dias_restante`, `empresa_id`) VALUES
	(16, '2020-08-16', 30, 25),
	(19, '2020-09-04', 30, 27),
	(20, '2020-09-06', 5, 21),
	(21, '2020-12-06', 30, 28);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `label` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empresa_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.permissions: ~10 rows (aproximadamente)
INSERT INTO `permissions` (`id`, `name`, `status_id`, `guard_name`, `created_at`, `updated_at`, `label`, `empresa_id`) VALUES
	(1, 'gerir utilizadores', 1, 'admin', '2020-05-04 16:07:01', '2020-05-04 16:07:02', 'Gerir utilizadores', 1),
	(2, 'Gerir permissoes', 1, 'admin', '2020-05-18 01:08:13', '2020-05-18 01:08:15', 'Gerir permissoes', 1),
	(3, 'Gerir pagamento de licenças dos clientes', 1, 'admin', '2020-05-18 01:09:05', '2020-05-18 01:09:06', 'Gerir ativação de licenças', 1),
	(4, 'gerir bancos', 1, 'admin', '2020-05-18 01:09:05', '2020-05-18 01:09:06', 'Gerir bancos', 1),
	(6, 'Gerir pedidos ativacao de licenca', 1, 'admin', '2020-05-18 01:09:05', '2020-05-18 01:09:06', 'Gerir pedidos ativacao de licenca', 1),
	(7, 'Resetar a senha dos clientes', 1, 'admin', '2020-05-18 01:09:05', '2020-05-18 01:09:06', 'Resetar a senha dos clientes', 1),
	(8, 'Atualizar dados da empresa', 1, 'admin', '2020-05-18 01:09:05', '2020-05-18 01:09:06', 'Atualizar dados da empresa', 1),
	(9, 'Efetuar backup do banco de dados', 1, 'admin', '2020-05-18 01:09:05', '2020-05-18 01:09:06', 'Efetuar backup do banco de dados', 1),
	(10, 'Gerir activação de utilizador dos clientes', 1, 'admin', '2020-05-18 01:09:05', '2020-05-18 01:09:06', 'Gerir activação de utilizador dos clientes', 1),
	(11, 'Gerir clientes', 1, 'admin', '2023-11-20 12:10:43', '2023-11-20 12:10:44', 'Gerir clientes', 1);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.personal_access_tokens: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.producaos
CREATE TABLE IF NOT EXISTS `producaos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `materia_prima_id` int(10) unsigned DEFAULT NULL,
  `num_toro_producao` int(11) NOT NULL,
  `nitems` int(10) DEFAULT NULL,
  `nproducao` int(10) NOT NULL,
  `disperdicio` double(8,2) DEFAULT NULL,
  `data` date NOT NULL,
  `responsavel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_sistema_id` int(10) unsigned DEFAULT NULL,
  `maquina_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `producaos_estado_sistema_id_foreign` (`estado_sistema_id`),
  KEY `producaos_maquina_id_foreign` (`maquina_id`),
  KEY `FK_producaos_materia_primas` (`materia_prima_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.producaos: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.producao_items
CREATE TABLE IF NOT EXISTS `producao_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `produto_id` int(10) unsigned DEFAULT NULL,
  `na` double(8,2) NOT NULL,
  `nb` double(8,2) NOT NULL,
  `nc` double(8,2) NOT NULL,
  `nd` double(8,2) NOT NULL,
  `medio` double(8,2) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `comprimento` double(8,2) NOT NULL,
  `volume` double(8,2) NOT NULL,
  `producao_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `armazem_id` int(10) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `producao_items_producao_id_foreign` (`producao_id`),
  KEY `FK_producao_items_armazems` (`armazem_id`),
  KEY `FK_producao_items_produtos` (`produto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.producao_items: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.roles: ~3 rows (aproximadamente)
INSERT INTO `roles` (`id`, `label`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Super Admin', 'Super-Admin', 'web', '2020-05-18 00:05:05', '2020-05-18 00:05:06'),
	(2, 'Admin', 'Admin', 'web', '2020-05-18 00:05:41', '2020-05-18 00:05:42'),
	(3, 'Funcionario', 'Funcionario', 'web', '2020-06-09 20:22:21', '2020-06-09 20:22:23');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.role_has_permissions: ~15 rows (aproximadamente)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(3, 2),
	(5, 2),
	(6, 2),
	(7, 2),
	(10, 2);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.status_gerais
CREATE TABLE IF NOT EXISTS `status_gerais` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.status_gerais: ~2 rows (aproximadamente)
INSERT INTO `status_gerais` (`id`, `designacao`) VALUES
	(1, 'Activo'),
	(2, 'Inativo');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.status_licencas
CREATE TABLE IF NOT EXISTS `status_licencas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.status_licencas: ~3 rows (aproximadamente)
INSERT INTO `status_licencas` (`id`, `designacao`) VALUES
	(1, 'Activo'),
	(2, 'Rejeitado'),
	(3, 'Pendente');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.status_senha
CREATE TABLE IF NOT EXISTS `status_senha` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.status_senha: ~2 rows (aproximadamente)
INSERT INTO `status_senha` (`id`, `designacao`) VALUES
	(1, 'Vulnerável'),
	(2, 'Segura');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.tb_preinscricao
CREATE TABLE IF NOT EXISTS `tb_preinscricao` (
  `id` int(10) NOT NULL,
  `Nome_Completo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_identificacao` int(10) DEFAULT NULL,
  `Bilhete_Identidade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Sexo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Data_Nascimento` date DEFAULT NULL,
  `Codigo_pais_habilitacao_anterior` int(10) DEFAULT NULL,
  `Contactos_Telefonicos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Nome_Pessoa_Contacto_Telefone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Curso_Candidatura` int(10) DEFAULT NULL,
  `Morada_Completa` varchar(455) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Codigo_Habilitacao_Anterior` int(10) DEFAULT NULL,
  `Codigo_Profissao` int(10) DEFAULT NULL,
  `necessidade_especial_id` int(10) DEFAULT NULL,
  `polo_id` int(10) DEFAULT NULL,
  `Codigo_Turno` int(10) DEFAULT NULL,
  `anoLectivo` int(10) DEFAULT NULL,
  `canal` int(10) DEFAULT NULL,
  `codigo_tipo_candidatura` int(10) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `data_preescrincao` datetime DEFAULT NULL,
  `data_ultima_actualizacao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.tb_preinscricao: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.tipos_clientes
CREATE TABLE IF NOT EXISTS `tipos_clientes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.tipos_clientes: ~6 rows (aproximadamente)
INSERT INTO `tipos_clientes` (`id`, `designacao`) VALUES
	(1, 'Singular'),
	(2, 'Instituição Privada'),
	(3, 'Instituição Publica'),
	(4, 'Sociedade Anónima'),
	(5, 'ONG'),
	(6, 'Diversos');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.tipos_contactos
CREATE TABLE IF NOT EXISTS `tipos_contactos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.tipos_contactos: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.tipos_licencas
CREATE TABLE IF NOT EXISTS `tipos_licencas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.tipos_licencas: ~5 rows (aproximadamente)
INSERT INTO `tipos_licencas` (`id`, `designacao`) VALUES
	(1, 'Grátis'),
	(2, 'Mensal'),
	(3, 'Anual'),
	(4, 'Definitivo'),
	(5, 'Semestral');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.tipos_regimes
CREATE TABLE IF NOT EXISTS `tipos_regimes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Designacao` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.tipos_regimes: ~3 rows (aproximadamente)
INSERT INTO `tipos_regimes` (`id`, `Designacao`) VALUES
	(1, 'Regime Geral'),
	(2, 'Regime Simplificado'),
	(3, ' Regime de Exclusão');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.tipotaxa
CREATE TABLE IF NOT EXISTS `tipotaxa` (
  `codigo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `taxa` int(11) NOT NULL,
  `codigostatus` int(10) unsigned NOT NULL,
  `descricao` varchar(45) DEFAULT NULL,
  `codigoMotivo` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `empresa_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  KEY `FK_tipotaxa_2` (`codigostatus`),
  KEY `FK_tipotaxa_empresas` (`empresa_id`),
  CONSTRAINT `FK_tipotaxa_2` FOREIGN KEY (`codigostatus`) REFERENCES `status_gerais` (`id`),
  CONSTRAINT `FK_tipotaxa_empresas` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.tipotaxa: ~3 rows (aproximadamente)
INSERT INTO `tipotaxa` (`codigo`, `taxa`, `codigostatus`, `descricao`, `codigoMotivo`, `created_at`, `updated_at`, `empresa_id`) VALUES
	(1, 0, 1, 'IVA(0,00%)', 12, '2020-09-28 13:10:30', '2020-09-28 13:10:30', 1),
	(2, 14, 1, 'IVA(14,00%)', 9, '2020-09-28 13:10:30', '2020-09-28 13:10:30', 1),
	(19, 2, 1, 'IVA(2,00%)', 8, '2020-12-09 23:13:48', '2020-12-09 23:41:22', 1);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.tipo_users
CREATE TABLE IF NOT EXISTS `tipo_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designacao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.tipo_users: ~3 rows (aproximadamente)
INSERT INTO `tipo_users` (`id`, `designacao`) VALUES
	(1, 'Admin'),
	(2, 'Empresa'),
	(3, 'Cliente');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.users_admin
CREATE TABLE IF NOT EXISTS `users_admin` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `canal_id` int(10) unsigned DEFAULT NULL,
  `tipo_user_id` int(10) unsigned DEFAULT NULL,
  `status_id` int(10) unsigned DEFAULT NULL,
  `status_senha_id` int(10) unsigned DEFAULT '1',
  `username` varchar(450) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foto` varchar(145) COLLATE utf8_unicode_ci DEFAULT NULL,
  `guard` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'web' COMMENT 'guard usado para verficar as permissões no serviço AuthServiceProvider',
  `notificarAtivacaoLicenca` enum('Y','N') COLLATE utf8_unicode_ci DEFAULT 'N',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `telefone` (`telefone`),
  KEY `FK_users_canal` (`canal_id`),
  KEY `Fk_tipo_user_id` (`tipo_user_id`),
  KEY `Fk_status_id` (`status_id`),
  KEY `FK_users_status_senha` (`status_senha_id`),
  CONSTRAINT `FK_users_canal` FOREIGN KEY (`canal_id`) REFERENCES `canais_comunicacoes` (`id`),
  CONSTRAINT `FK_users_status` FOREIGN KEY (`status_id`) REFERENCES `status_gerais` (`id`),
  CONSTRAINT `FK_users_status_senha` FOREIGN KEY (`status_senha_id`) REFERENCES `status_senha` (`id`),
  CONSTRAINT `FK_users_tipo` FOREIGN KEY (`tipo_user_id`) REFERENCES `tipo_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.users_admin: ~10 rows (aproximadamente)
INSERT INTO `users_admin` (`id`, `name`, `uuid`, `telefone`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `canal_id`, `tipo_user_id`, `status_id`, `status_senha_id`, `username`, `foto`, `guard`, `notificarAtivacaoLicenca`) VALUES
	(1, 'MUTUE SOLUÇÕES TECNOLÓGICAS INTELIGENTES LDA', NULL, '922969192', 'geral@mutue.net', '2020-05-08 13:24:57', '$2y$10$baLh/tZe8kXQJ9/Q/WXtueFTOBSq/h6TR6yxMUI7bQmAKbOA2w206', 'iSQUT6TSCBXB9saGXK7u56gqqIUjHfU8S172XSpeTKAPJw5upGUrlDDGYD0t', '2020-05-08 13:25:31', '2023-07-10 09:35:43', NULL, 3, 1, 1, 1, 'MUTUE SOLUÇÕES TECNOLÓGICAS INTELIGENTES LDA', 'admin/UMA.jpg', 'web', 'N'),
	(87, 'Domingos Gaspar', NULL, '926237716', 'domingos.gaspar@mutue.net', '2023-04-24 12:10:27', '$2y$10$baLh/tZe8kXQJ9/Q/WXtueFTOBSq/h6TR6yxMUI7bQmAKbOA2w206', NULL, '2023-04-24 13:10:27', '2023-11-22 14:57:15', NULL, 3, 1, 2, 1, 'Domingos Gaspar', 'utilizadores/cliente/avatarEmpresa.png', 'web', 'N'),
	(88, 'Zenilda Fila', NULL, '915108899', 'zenilda.fila@mutue.net', '2023-04-24 17:11:03', '$2y$10$Ev7u/fS4lZ9.16Z/VHbx/uFYm7ySvsRlVRbmEZwiXOW1qo1XinX..', NULL, '2023-04-24 18:11:03', '2023-11-22 14:58:44', NULL, 3, 1, 1, 1, 'Zenilda Fila', 'utilizadores/cliente/avatarEmpresa.png', 'web', 'Y'),
	(89, 'Osvaldo Ramos', NULL, '928277927', 'info.ramossoft@gmail.com', '2023-04-24 17:12:26', '$2y$10$baLh/tZe8kXQJ9/Q/WXtueFTOBSq/h6TR6yxMUI7bQmAKbOA2w206', NULL, '2023-04-24 18:12:26', '2023-11-22 14:58:58', NULL, 3, 1, 1, 1, 'Osvaldo Ramos', 'utilizadores/cliente/avatarEmpresa.png', 'web', 'Y'),
	(90, 'Gilberto', NULL, '999999999', 'socrates.gilberto@mutue.net', '2023-04-24 17:13:22', '$2y$10$baLh/tZe8kXQJ9/Q/WXtueFTOBSq/h6TR6yxMUI7bQmAKbOA2w206', NULL, '2023-04-24 18:13:22', '2023-11-22 14:59:06', NULL, 3, 1, 1, 1, 'Gilberto', 'utilizadores/cliente/avatarEmpresa.png', 'web', 'Y'),
	(91, 'Fernanda', NULL, '925445556', 'luzia.coma@mutue.net', '2023-04-24 17:14:58', '$2y$10$lFZKfSvr8qjZZEZMqZMeUOEr8hKdaPC1IumsRZ3Et5ltulCz85HSi', NULL, '2023-04-24 18:14:58', '2023-11-22 14:59:14', NULL, 3, 1, 2, 1, 'Luzia', 'utilizadores/cliente/avatarEmpresa.png', 'web', 'N'),
	(92, 'Osvaldo Duzentos', NULL, '925522789', 'osvaldo.duzentos@mutue.net', '2023-04-24 17:16:26', '$2y$10$usd2RN2ZmWQDKHt1fOxkLeQQfrspOEp.CJAGjHcbq8EZp238n8v06', NULL, '2023-04-24 18:16:26', '2023-11-22 14:59:22', NULL, 3, 1, 1, 1, 'Osvaldo Duzentos', 'utilizadores/cliente/avatarEmpresa.png', 'web', 'N'),
	(93, 'João Lourenço', NULL, '936037664', 'joaolourenco@gmail.com', '2023-11-17 19:15:49', '$2y$10$8s7lxTRySQrgjkQNZIjZJOpA6iOy0VOBfijkPlEDPTwbd.TXLtjGK', NULL, '2023-11-17 20:15:49', '2023-11-22 14:59:49', NULL, 3, 1, 1, 1, 'João Lourenço', 'utilizadores/cliente/avatarEmpresa.png', 'web', 'N'),
	(94, 'Emerson', NULL, '927326170', 'emersontixeira@gmail.com', '2023-11-17 19:18:34', '$2y$10$M9f5T4FvOkPhmoaPec0CKeuClg3Y/bWtbKFAhH5Nje2y.l6eprFTq', NULL, '2023-11-17 20:18:34', '2023-11-22 14:59:39', NULL, 3, 1, 1, 1, 'Emerson', 'utilizadores/cliente/avatarEmpresa.png', 'web', 'N'),
	(95, 'Mauro', NULL, '923298581', 'maurofrancisco@gmail.com', '2023-11-17 19:19:33', '$2y$10$hiAIOT/4pAvhElG6.7GP.eMfwoghAo7Dd/W4psjBDbsyfITvwAi82', NULL, '2023-11-17 20:19:33', '2023-11-22 14:59:31', NULL, 3, 1, 1, 1, 'Mauro', 'utilizadores/cliente/avatarEmpresa.png', 'web', 'N');

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.user_perfil
CREATE TABLE IF NOT EXISTS `user_perfil` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `perfil_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `plan_profile_plan_id_foreign` (`user_id`),
  KEY `plan_profile_profile_id_foreign` (`perfil_id`)
) ENGINE=InnoDB AUTO_INCREMENT=783 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.user_perfil: ~10 rows (aproximadamente)
INSERT INTO `user_perfil` (`id`, `user_id`, `perfil_id`) VALUES
	(1, 1, 1),
	(774, 87, 1),
	(775, 88, 26),
	(776, 89, 1),
	(777, 90, 26),
	(778, 91, 26),
	(779, 92, 26),
	(780, 95, 2),
	(781, 94, 2),
	(782, 93, 2);

-- A despejar estrutura para tabela mutue_negocios_aeroporto_admin.validacao_empresa
CREATE TABLE IF NOT EXISTS `validacao_empresa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `pais_id` int(10) unsigned NOT NULL,
  `expirado_em` datetime DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `nif` varchar(45) NOT NULL,
  `tipo_cliente_id` int(10) unsigned NOT NULL,
  `tipo_regime_id` int(10) unsigned DEFAULT NULL,
  `gestor_cliente_id` int(10) unsigned DEFAULT NULL,
  `canal_comunicacao_id` int(10) unsigned DEFAULT NULL,
  `logotipo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `email` varchar(145) DEFAULT NULL,
  `pessoal_Contacto` varchar(145) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `file_alvara` varchar(255) DEFAULT NULL,
  `file_nif` varchar(255) DEFAULT NULL,
  `used` int(11) NOT NULL DEFAULT '0' COMMENT '0=>Não usado, 1=>usado',
  PRIMARY KEY (`id`),
  KEY `FK_empresas_pais` (`pais_id`),
  KEY `FK_empresas_tipo` (`tipo_cliente_id`),
  KEY `FK_empresas_tipos_regimes` (`tipo_regime_id`),
  KEY `FK_validacao_empresa_gestor_clientes` (`gestor_cliente_id`),
  KEY `FK_validacao_empresa_canais_comunicacoes` (`canal_comunicacao_id`),
  CONSTRAINT `FK_validacao_empresa_canais_comunicacoes` FOREIGN KEY (`canal_comunicacao_id`) REFERENCES `canais_comunicacoes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_validacao_empresa_gestor_clientes` FOREIGN KEY (`gestor_cliente_id`) REFERENCES `gestor_clientes` (`id`),
  CONSTRAINT `FK_validacao_empresa_paises` FOREIGN KEY (`pais_id`) REFERENCES `paises` (`id`),
  CONSTRAINT `FK_validacao_empresa_tipos_clientes` FOREIGN KEY (`tipo_cliente_id`) REFERENCES `tipos_clientes` (`id`),
  CONSTRAINT `FK_validacao_empresa_tipos_regimes` FOREIGN KEY (`tipo_regime_id`) REFERENCES `tipos_regimes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- A despejar dados para tabela mutue_negocios_aeroporto_admin.validacao_empresa: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
