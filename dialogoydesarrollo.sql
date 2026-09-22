CREATE DATABASE IF NOT EXISTS revista_digital
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE revista_digital;

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Tabla: usuarios
-- ------------------------------------------------------------
CREATE TABLE usuarios (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombres         VARCHAR(100) NOT NULL,
    ap_paterno      VARCHAR(100) NOT NULL,
    ap_materno      VARCHAR(100) NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    rol             VARCHAR(20) NOT NULL DEFAULT 'redactor',
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: autores
-- ------------------------------------------------------------
CREATE TABLE autores (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombres         VARCHAR(100) NOT NULL,
    ap_paterno      VARCHAR(100) NULL,
    ap_materno      VARCHAR(100) NULL,
    nickname        VARCHAR(100) NULL,
    es_nickname     TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: reportajes
-- ------------------------------------------------------------
CREATE TABLE reportajes (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    titulo              VARCHAR(255) NOT NULL,
    resumen_corto       VARCHAR(500) NULL,
    desarrollo          LONGTEXT NOT NULL,
    foto_principal      VARCHAR(255) NULL,
    pdf_adjunto         VARCHAR(255) NULL,
    fecha_publicacion   DATE NOT NULL,
    es_destacado        TINYINT(1) NOT NULL DEFAULT 0,
    autor_id            INT NOT NULL,
    usuario_id          INT NOT NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_reportajes_autor FOREIGN KEY (autor_id) REFERENCES autores(id),
    CONSTRAINT fk_reportajes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: reportajes_fotos
-- ------------------------------------------------------------
CREATE TABLE reportajes_fotos (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    reportaje_id    INT NOT NULL,
    url_foto        VARCHAR(255) NOT NULL,
    orden           SMALLINT NOT NULL DEFAULT 0,
    descripcion     VARCHAR(255) NULL,
    CONSTRAINT fk_reportajes_fotos_reportaje FOREIGN KEY (reportaje_id) REFERENCES reportajes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: noticias
-- ------------------------------------------------------------
CREATE TABLE noticias (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    titulo              VARCHAR(255) NOT NULL,
    foto                VARCHAR(255) NULL,
    link_externo        VARCHAR(500) NULL,
    fecha_publicacion   DATE NOT NULL,
    usuario_id          INT NOT NULL,
    CONSTRAINT fk_noticias_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: boletines
-- ------------------------------------------------------------
CREATE TABLE boletines (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    numero_boletin      VARCHAR(50) NOT NULL UNIQUE,
    resumen             VARCHAR(500) NULL,
    foto_portada        VARCHAR(255) NULL,
    archivo_pdf         VARCHAR(255) NOT NULL,
    fecha_publicacion   DATE NOT NULL,
    usuario_id          INT NOT NULL,
    CONSTRAINT fk_boletines_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: podcasts
-- ------------------------------------------------------------
CREATE TABLE podcasts (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    titulo              VARCHAR(255) NOT NULL,
    url_embed           VARCHAR(500) NOT NULL,
    fecha_publicacion   DATE NOT NULL,
    usuario_id          INT NOT NULL,
    CONSTRAINT fk_podcasts_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabla: videos
-- ------------------------------------------------------------
CREATE TABLE videos (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    titulo              VARCHAR(255) NOT NULL,
    url_embed           VARCHAR(500) NOT NULL,
    fecha_publicacion   DATE NOT NULL,
    usuario_id          INT NOT NULL,
    CONSTRAINT fk_videos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Índices
-- ------------------------------------------------------------
CREATE INDEX idx_reportajes_fecha ON reportajes(fecha_publicacion);
CREATE INDEX idx_reportajes_destacado ON reportajes(es_destacado);
CREATE INDEX idx_reportajes_fotos_reportaje ON reportajes_fotos(reportaje_id);
CREATE INDEX idx_noticias_fecha ON noticias(fecha_publicacion);
CREATE INDEX idx_boletines_fecha ON boletines(fecha_publicacion);
CREATE INDEX idx_podcasts_fecha ON podcasts(fecha_publicacion);
CREATE INDEX idx_videos_fecha ON videos(fecha_publicacion);

SET FOREIGN_KEY_CHECKS = 1;