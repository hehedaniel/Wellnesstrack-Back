<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531175758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recetas ADD id_usuario_id INT NOT NULL');
        $this->addSql('ALTER TABLE recetas ADD CONSTRAINT FK_8ADA30D57EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_8ADA30D57EB2C349 ON recetas (id_usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recetas DROP FOREIGN KEY FK_8ADA30D57EB2C349');
        $this->addSql('DROP INDEX IDX_8ADA30D57EB2C349 ON recetas');
        $this->addSql('ALTER TABLE recetas DROP id_usuario_id');
    }
}
