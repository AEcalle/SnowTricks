<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211115145554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE registration_token registration_token BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE new_password_token new_password_token BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE registration_token registration_token BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE new_password_token new_password_token BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
    }
}
