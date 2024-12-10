<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531160954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE consumo_dia (id INT AUTO_INCREMENT NOT NULL, id_usuario_id INT NOT NULL, fecha DATETIME NOT NULL, comida VARCHAR(255) NOT NULL, cantidad DOUBLE PRECISION NOT NULL, momento VARCHAR(255) NOT NULL, INDEX IDX_77412CD17EB2C349 (id_usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consumo_dia ADD CONSTRAINT FK_77412CD17EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consumo_dia DROP FOREIGN KEY FK_77412CD17EB2C349');
        $this->addSql('DROP TABLE consumo_dia');
    }
}
