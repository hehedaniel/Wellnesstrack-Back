<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531183926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE peso (id INT AUTO_INCREMENT NOT NULL, id_usuario_id INT NOT NULL, fecha DATE NOT NULL, hora TIME NOT NULL, peso DOUBLE PRECISION NOT NULL, imc DOUBLE PRECISION NOT NULL, INDEX IDX_DD7820B77EB2C349 (id_usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE peso ADD CONSTRAINT FK_DD7820B77EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE peso DROP FOREIGN KEY FK_DD7820B77EB2C349');
        $this->addSql('DROP TABLE peso');
    }
}
