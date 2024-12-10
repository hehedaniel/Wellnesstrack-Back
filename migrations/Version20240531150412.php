<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531150412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alimento (id INT AUTO_INCREMENT NOT NULL, id_usuario_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, marca VARCHAR(255) NOT NULL, cantidad DOUBLE PRECISION NOT NULL, proteinas DOUBLE PRECISION NOT NULL, grasas DOUBLE PRECISION NOT NULL, carbohidratos DOUBLE PRECISION NOT NULL, azucares DOUBLE PRECISION NOT NULL, vitaminas DOUBLE PRECISION NOT NULL, minerales DOUBLE PRECISION NOT NULL, imagen LONGTEXT NOT NULL, INDEX IDX_A3C395937EB2C349 (id_usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alimento ADD CONSTRAINT FK_A3C395937EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE ejercicio CHANGE valor_met valor_met INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alimento DROP FOREIGN KEY FK_A3C395937EB2C349');
        $this->addSql('DROP TABLE alimento');
        $this->addSql('ALTER TABLE ejercicio CHANGE valor_met valor_met DOUBLE PRECISION NOT NULL');
    }
}
