<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530152406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ejercicio (id INT AUTO_INCREMENT NOT NULL, id_usuario_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, grupo_muscular VARCHAR(255) NOT NULL, dificultad VARCHAR(255) NOT NULL, instrucciones LONGTEXT NOT NULL, valor_met INT NOT NULL, INDEX IDX_95ADCFF47EB2C349 (id_usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enlace (id INT AUTO_INCREMENT NOT NULL, id_ejercicio_id INT NOT NULL, enlace LONGTEXT NOT NULL, INDEX IDX_8414B27913487F0F (id_ejercicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, correo VARCHAR(255) NOT NULL, correo_v VARCHAR(255) NOT NULL, contrasena VARCHAR(255) NOT NULL, rol TINYINT(1) NOT NULL, edad INT NOT NULL, altura DOUBLE PRECISION NOT NULL, objetivo_opt VARCHAR(255) NOT NULL, objetivo_num DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ejercicio ADD CONSTRAINT FK_95ADCFF47EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE enlace ADD CONSTRAINT FK_8414B27913487F0F FOREIGN KEY (id_ejercicio_id) REFERENCES ejercicio (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ejercicio DROP FOREIGN KEY FK_95ADCFF47EB2C349');
        $this->addSql('ALTER TABLE enlace DROP FOREIGN KEY FK_8414B27913487F0F');
        $this->addSql('DROP TABLE ejercicio');
        $this->addSql('DROP TABLE enlace');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
