<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240601110044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usuario_realiza_ejercicio (id INT AUTO_INCREMENT NOT NULL, id_ejercicio_id INT NOT NULL, id_usuario_id INT NOT NULL, fecha DATE NOT NULL, hora TIME NOT NULL, calorias DOUBLE PRECISION NOT NULL, tiempo DOUBLE PRECISION NOT NULL, INDEX IDX_C16D635113487F0F (id_ejercicio_id), INDEX IDX_C16D63517EB2C349 (id_usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usuario_realiza_ejercicio ADD CONSTRAINT FK_C16D635113487F0F FOREIGN KEY (id_ejercicio_id) REFERENCES ejercicio (id)');
        $this->addSql('ALTER TABLE usuario_realiza_ejercicio ADD CONSTRAINT FK_C16D63517EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario_realiza_ejercicio DROP FOREIGN KEY FK_C16D635113487F0F');
        $this->addSql('ALTER TABLE usuario_realiza_ejercicio DROP FOREIGN KEY FK_C16D63517EB2C349');
        $this->addSql('DROP TABLE usuario_realiza_ejercicio');
    }
}
