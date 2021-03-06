<?php

declare(strict_types=1);

namespace MatriceMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200315162635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create skillmatrix table.';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE skillmatrix (id CHAR(36) NOT NULL COMMENT \'(DC2Type:skillmatrix_id)\', persons JSON NOT NULL COMMENT \'(DC2Type:person_collection)\', skills JSON NOT NULL COMMENT \'(DC2Type:skill_collection)\', ratings JSON DEFAULT NULL COMMENT \'(DC2Type:rating_collection)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE skillmatrix');
    }
}
