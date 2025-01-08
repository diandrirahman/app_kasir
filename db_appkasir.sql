-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_appkasir
CREATE DATABASE IF NOT EXISTS `db_appkasir` /*!40100 DEFAULT CHARACTER SET armscii8 COLLATE armscii8_bin */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_appkasir`;

-- Dumping structure for table db_appkasir.tb_barang
CREATE TABLE IF NOT EXISTS `tb_barang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(5) COLLATE armscii8_bin DEFAULT NULL,
  `nama_barang` varchar(20) COLLATE armscii8_bin DEFAULT NULL,
  `harga` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table db_appkasir.tb_barang: ~2 rows (approximately)
INSERT INTO `tb_barang` (`id`, `kode_barang`, `nama_barang`, `harga`) VALUES
	(1, 'X-001', 'Sabun', 1000),
	(3, 'X-002', 'Sikat Gigi', 15000);

-- Dumping structure for table db_appkasir.tb_level
CREATE TABLE IF NOT EXISTS `tb_level` (
  `id` int NOT NULL AUTO_INCREMENT,
  `level_name` varchar(10) COLLATE armscii8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table db_appkasir.tb_level: ~2 rows (approximately)
INSERT INTO `tb_level` (`id`, `level_name`) VALUES
	(1, 'Admin'),
	(2, 'Kasir');

-- Dumping structure for table db_appkasir.tb_transaksi
CREATE TABLE IF NOT EXISTS `tb_transaksi` (
  `id_transaksi` varchar(50) CHARACTER SET armscii8 COLLATE armscii8_bin NOT NULL DEFAULT 'AUTO_INCREMENT',
  `tanggal` datetime DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `bayar` decimal(10,2) DEFAULT NULL,
  `kembali` decimal(10,2) DEFAULT NULL,
  `kasir` varchar(50) CHARACTER SET armscii8 COLLATE armscii8_bin DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table db_appkasir.tb_transaksi: ~0 rows (approximately)
INSERT INTO `tb_transaksi` (`id_transaksi`, `tanggal`, `total`, `bayar`, `kembali`, `kasir`) VALUES
	('TRX-20250108073708', '2025-01-08 07:37:08', 34000.00, 50000.00, 16000.00, 'Rahman');

-- Dumping structure for table db_appkasir.tb_transaksi_detail
CREATE TABLE IF NOT EXISTS `tb_transaksi_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` varchar(20) COLLATE armscii8_bin DEFAULT NULL,
  `kode_barang` varchar(20) COLLATE armscii8_bin DEFAULT NULL,
  `nama_barang` varchar(100) COLLATE armscii8_bin DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table db_appkasir.tb_transaksi_detail: ~0 rows (approximately)
INSERT INTO `tb_transaksi_detail` (`id`, `id_transaksi`, `kode_barang`, `nama_barang`, `harga`, `jumlah`, `subtotal`) VALUES
	(20, 'TRX-20250108073708', 'X-001', 'Sabun', 1000.00, 4, 4000.00),
	(21, 'TRX-20250108073708', 'X-002', 'Sikat Gigi', 15000.00, 2, 30000.00);

-- Dumping structure for table db_appkasir.tb_user
CREATE TABLE IF NOT EXISTS `tb_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE armscii8_bin DEFAULT NULL,
  `password` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `nama` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `id_level` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table db_appkasir.tb_user: ~2 rows (approximately)
INSERT INTO `tb_user` (`id`, `username`, `password`, `nama`, `id_level`) VALUES
	(1, 'Admin', 'b272b732416895667749bcf8194b09593a0577bc', 'Admin', 1),
	(6, 'rahman123', '15540b124cfaa055e2e267dcfb4a3d983f7a2422', 'Rahman', 2);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
