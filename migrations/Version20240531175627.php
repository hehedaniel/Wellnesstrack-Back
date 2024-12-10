<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240531175627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recetas (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, instrucciones LONGTEXT NOT NULL, cantidad_final DOUBLE PRECISION NOT NULL, proteinas DOUBLE PRECISION NOT NULL, grasas DOUBLE PRECISION NOT NULL, carbohidratos DOUBLE PRECISION NOT NULL, azucares DOUBLE PRECISION NOT NULL, vitaminas DOUBLE PRECISION NOT NULL, minerales DOUBLE PRECISION NOT NULL, imagen LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE recetas');
    }
}
